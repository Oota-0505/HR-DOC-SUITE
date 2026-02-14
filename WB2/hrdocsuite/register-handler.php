<?php
/**
 * 登録処理専用ハンドラー
 * テーマのルートディレクトリに配置
 */

// WordPressの機能をロード（パスは環境に合わせて調整）
require_once('../../../wp-load.php');

// POSTメソッド以外は拒否
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    wp_redirect(home_url('/register'));
    exit;
}

// nonce検証
if (!isset($_POST['hrdoc_register_nonce']) || !wp_verify_nonce($_POST['hrdoc_register_nonce'], 'hrdoc_register_action')) {
    wp_die('不正なリクエストです。');
}

$register_error = '';

// 入力値のサニタイズ
$name = sanitize_text_field($_POST['name']);
$email = sanitize_email($_POST['email']);
$phone = sanitize_text_field($_POST['phone']);
$address = sanitize_text_field($_POST['address']);
$company_name = sanitize_text_field($_POST['company_name']);
$password = $_POST['password'];
$password_confirm = $_POST['password_confirm'];

// バリデーション
if (empty($name)) {
    $register_error = 'お名前を入力してください。';
} elseif (empty($email) || !is_email($email)) {
    $register_error = '有効なメールアドレスを入力してください。';
} elseif (email_exists($email)) {
    $register_error = 'このメールアドレスは既に登録されています。';
} elseif ($password !== $password_confirm) {
    $register_error = 'パスワードが一致しません。';
}

// エラーがある場合、クエリパラメータ付きで登録画面に戻す
if ($register_error) {
    $url = add_query_arg('register_error', urlencode($register_error), home_url('/register'));
    wp_redirect($url);
    exit;
}

// ユーザー作成処理
$base_login = sanitize_user(preg_replace('/@.*/', '', $email), true);
if (empty($base_login)) {
    $base_login = 'user';
}
$user_login = $base_login;
$i = 1;
while (username_exists($user_login)) {
    $user_login = $base_login . $i;
    $i++;
}

$user_id = wp_insert_user(array(
    'user_login'   => $user_login,
    'user_pass'    => $password,
    'user_email'   => $email,
    'display_name' => $name,
    'first_name'   => $name,
    'role'         => 'subscriber',
));

if (is_wp_error($user_id)) {
    $url = add_query_arg('register_error', urlencode($user_id->get_error_message()), home_url('/register'));
    wp_redirect($url);
    exit;
}

// ユーザー情報更新
wp_update_user(array(
    'ID' => $user_id,
    'display_name' => $name,
    'first_name' => $name,
));

// カスタムテーブル登録
global $wpdb;
$table_name = $wpdb->prefix . 'custom_users';

// テーブル存在確認
if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name) {
    if (function_exists('hrdoc_create_custom_users_table')) {
        hrdoc_create_custom_users_table();
    }
}

$now = current_time('mysql');
$insert_result = $wpdb->insert(
    $table_name,
    array(
        'wp_user_id' => $user_id,
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'company_name' => $company_name,
        'role_type' => 0,
        'created_at' => $now,
        'updated_at' => $now
    ),
    array('%d', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s')
);

if ($insert_result === false) {
    wp_delete_user($user_id);
    $db_error = $wpdb->last_error;
    $url = add_query_arg('register_error', urlencode('DBエラー: ' . $db_error), home_url('/register'));
    wp_redirect($url);
    exit;
}

// 自動ログイン
$creds = array(
    'user_login'    => $user_login,
    'user_password' => $password,
    'remember'      => true,
);
$signon = wp_signon($creds, false);

if (is_wp_error($signon)) {
    // ログイン失敗でも登録はできているので、ログイン画面へ
    $url = add_query_arg('login_message', urlencode('登録が完了しました。ログインしてください。'), home_url('/login'));
    wp_redirect($url);
    exit;
}

// マイページへ
wp_safe_redirect(home_url('/mypage/'));
exit;
