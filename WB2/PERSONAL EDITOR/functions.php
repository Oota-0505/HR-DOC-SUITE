<?php
/**
 * PERSONAL EDITOR テーマ functions.php
 *
 * @package PERSONAL_EDITOR
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 初期ユーザー定義（テーマ有効化時に自動作成）
 * ※既存ユーザー（PE, PersonalEditor）は変更しません
 */
define('PERSONALEDITOR_SEED_USERS', array(
    array(
        'user_login'   => 'demo',
        'user_email'   => 'yamada0000tarou@gmail.com',
        'user_pass'    => 'Taro0000',
        'display_name' => 'デモユーザー',
        'role'         => 'subscriber',
        'role_type'    => 0, // 一般
    ),
    array(
        'user_login'   => 'test',
        'user_email'   => 'ryunosuke.mako@check-raise.jp',
        'user_pass'    => '@testtest0',
        'display_name' => '管理者',
        'role'         => 'subscriber',
        'role_type'    => 1, // 管理者
    ),
));

/**
 * カスタムユーザーテーブル用ヘルパー
 */
function personaleditor_get_custom_user($user_id) {
    global $wpdb;
    $table = $wpdb->prefix . 'custom_users';
    return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table} WHERE id = %d", $user_id));
}

function personaleditor_get_all_custom_users() {
    global $wpdb;
    $table = $wpdb->prefix . 'custom_users';
    return $wpdb->get_results("SELECT * FROM {$table}");
}

/**
 * カスタムユーザーテーブル作成
 */
function personaleditor_create_custom_users_table() {
    global $wpdb;
    $table = $wpdb->prefix . 'custom_users';
    $charset = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE {$table} (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        wp_user_id bigint(20) UNSIGNED NOT NULL,
        name varchar(100) DEFAULT '',
        email varchar(100) DEFAULT '',
        phone varchar(50) DEFAULT '',
        address varchar(255) DEFAULT '',
        company_name varchar(100) DEFAULT '',
        status varchar(50) DEFAULT '受付済',
        role_type tinyint(1) DEFAULT 0 COMMENT '0:一般, 1:管理者',
        plan_type varchar(20) DEFAULT 'basic',
        option_customize int(1) DEFAULT 0,
        option_review int(1) DEFAULT 0,
        option_flow int(1) DEFAULT 0,
        option_1on1 int(1) DEFAULT 0,
        order_date varchar(50) DEFAULT '',
        order_plan_name varchar(100) DEFAULT '',
        order_amount varchar(50) DEFAULT '',
        order_payment_method varchar(100) DEFAULT '',
        order_status varchar(100) DEFAULT '',
        billing_current varchar(50) DEFAULT '',
        billing_history text,
        billing_next_date varchar(50) DEFAULT '',
        billing_method varchar(100) DEFAULT '',
        created_at datetime DEFAULT '0000-00-00 00:00:00',
        updated_at datetime DEFAULT '0000-00-00 00:00:00',
        PRIMARY KEY (id),
        UNIQUE KEY unique_wp_user (wp_user_id),
        KEY idx_email (email),
        KEY idx_role_type (role_type)
    ) {$charset};";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    $option_cols = array(
        'option_customize', 'option_review', 'option_flow', 'option_1on1',
        'option_note_check', 'option_tag', 'option_theme', 'option_improve',
        'option_long', 'option_interview', 'option_meeting', 'option_rewrite'
    );
    foreach ($option_cols as $col) {
        $check = $wpdb->get_results("SHOW COLUMNS FROM `{$table}` LIKE '{$col}'");
        if (empty($check)) {
            $wpdb->query("ALTER TABLE `{$table}` ADD `{$col}` int(1) DEFAULT 0 AFTER `plan_type`");
        }
    }
}

/**
 * 初期ユーザー作成（demo, test のみ。既存ユーザーは変更しない）
 */
function personaleditor_seed_users() {
    global $wpdb;
    $table = $wpdb->prefix . 'custom_users';
    $now = current_time('mysql');

    foreach (PERSONALEDITOR_SEED_USERS as $user_data) {
        $role_type = (int) $user_data['role_type'];
        unset($user_data['role_type']);

        if (username_exists($user_data['user_login'])) {
            continue;
        }

        $user_id = wp_insert_user($user_data);
        if (is_wp_error($user_id)) continue;

        $exists = $wpdb->get_var($wpdb->prepare("SELECT 1 FROM {$table} WHERE wp_user_id = %d", $user_id));
        if (!$exists) {
            $wpdb->insert($table, array(
                'wp_user_id' => $user_id,
                'name' => $user_data['display_name'] ?? '',
                'email' => $user_data['user_email'] ?? '',
                'role_type' => $role_type,
                'created_at' => $now,
                'updated_at' => $now,
            ), array('%d', '%s', '%s', '%d', '%s', '%s'));
        }
    }
}

/**
 * 固定ページ定義（テーマ有効化時に自動作成）
 */
define('PERSONALEDITOR_SEED_PAGES', array(
    array('post_name' => 'login',           'post_title' => 'ログイン',           'template' => 'page-templates/page-login.php'),
    array('post_name' => 'mypage',          'post_title' => 'マイページ',         'template' => 'page-templates/page-mypage.php'),
    array('post_name' => 'select-user',     'post_title' => 'ユーザー選択',       'template' => 'page-templates/page-select-user.php'),
    array('post_name' => 'payment',         'post_title' => '支払い情報入力',     'template' => 'page-templates/page-payment.php'),
    array('post_name' => 'payment-complete','post_title' => '決済完了',           'template' => 'page-templates/page-payment-complete.php'),
    array('post_name' => 'terms',           'post_title' => '利用規約',           'template' => 'page-templates/page-terms.php'),
    array('post_name' => 'privacy',         'post_title' => 'プライバシーポリシー','template' => 'page-templates/page-privacy.php'),
    array('post_name' => 'tokushoho',       'post_title' => '特定商取引法に基づく表記','template' => 'page-templates/page-tokushoho.php'),
));

/**
 * 固定ページ自動作成
 */
function personaleditor_seed_pages() {
    $author_id = 1;
    if (!get_user_by('ID', 1)) {
        $admins = get_users(array('role' => 'administrator', 'number' => 1));
        $author_id = !empty($admins) ? $admins[0]->ID : get_current_user_id();
        if (!$author_id) return;
    }

    foreach (PERSONALEDITOR_SEED_PAGES as $page_data) {
        $existing = get_page_by_path($page_data['post_name']);
        if ($existing) continue;

        $page_id = wp_insert_post(array(
            'post_type'    => 'page',
            'post_name'    => $page_data['post_name'],
            'post_title'   => $page_data['post_title'],
            'post_status'  => 'publish',
            'post_content' => '',
            'post_author'  => $author_id,
            'menu_order'   => 0,
        ));

        if (!is_wp_error($page_id) && isset($page_data['template'])) {
            update_post_meta($page_id, '_wp_page_template', $page_data['template']);
        }
    }
}

/**
 * テーマ有効化時
 */
function personaleditor_theme_activation() {
    personaleditor_create_custom_users_table();
    personaleditor_seed_users();
    personaleditor_seed_pages();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'personaleditor_theme_activation');

// ファビコン（wp_head で全テンプレートに出力）
add_action('wp_head', function () {
    echo '<link rel="icon" type="image/png" href="' . esc_url(get_template_directory_uri() . '/assets/images/favicon.png') . '">' . "\n";
}, 1);
// ファビコン（管理画面）
add_action('admin_head', function () {
    echo '<link rel="icon" type="image/png" href="' . esc_url(get_template_directory_uri() . '/assets/images/favicon.png') . '">' . "\n";
}, 1);

function personaleditor_check_custom_table() {
    personaleditor_create_custom_users_table();
}
add_action('init', 'personaleditor_check_custom_table');

/**
 * 管理画面アクセス時に固定ページが存在しない場合に自動作成
 * （テーマ有効化時に実行されなかった場合のフォールバック）
 */
function personaleditor_maybe_seed_pages() {
    if (!current_user_can('manage_options')) {
        return;
    }
    $login_page = get_page_by_path('login');
    if ($login_page) {
        return; // すでに作成済み
    }
    personaleditor_create_custom_users_table();
    personaleditor_seed_users();
    personaleditor_seed_pages();
    flush_rewrite_rules();
}
add_action('admin_init', 'personaleditor_maybe_seed_pages');

function personaleditor_theme_deactivation() {
    flush_rewrite_rules();
}
add_action('switch_theme', 'personaleditor_theme_deactivation');

function my_script_init()
{
  wp_deregister_script('jquery');

  wp_register_script(
    'jquery',
    'https://code.jquery.com/jquery-3.7.1.min.js',
    array(),
    '3.7.1',
    true
  );
  wp_enqueue_script('jquery');

  // Google Fonts
  wp_enqueue_style('google-fonts-inter', 'https://fonts.googleapis.com/css2?family=Heebo:wght@100..900&family=Noto+Sans+JP:wght@100..900&display=swap', array(), null, 'all');

  // CSS読み込み
  wp_enqueue_style('my-style', get_template_directory_uri() . '/assets/css/style.css', array(), filemtime(get_theme_file_path('assets/css/style.css')),  'all');

  // JavaScript読み込み（jQueryに依存）
  wp_enqueue_script('my-script', get_template_directory_uri() . '/assets/js/script.js', array('jquery'), filemtime(get_theme_file_path('assets/js/script.js')), true);

  // 認証系ページ用CSS
  if (is_page_template(array(
    'page-templates/page-login.php',
    'page-templates/page-mypage.php',
    'page-templates/page-select-user.php',
    'page-templates/page-payment.php',
    'page-templates/page-payment-complete.php'
  ))) {
    wp_enqueue_style(
      'personaleditor-auth',
      get_template_directory_uri() . '/assets/css/auth.css',
      array('my-style'),
      filemtime(get_theme_file_path('assets/css/auth.css'))
    );
  }
}
add_action('wp_enqueue_scripts', 'my_script_init');

/**
 * body_class にログイン状態を追加
 */
function personaleditor_body_classes($classes) {
  $classes[] = is_user_logged_in() ? 'is-logged-in' : 'is-logged-out';
  if (is_page_template(array(
    'page-templates/page-login.php',
    'page-templates/page-mypage.php',
    'page-templates/page-select-user.php',
    'page-templates/page-payment.php',
    'page-templates/page-payment-complete.php'
  ))) {
    $classes[] = 'page-auth';
  }
  return $classes;
}
add_filter('body_class', 'personaleditor_body_classes');

/**
 * セキュリティー対策
 */

remove_action('wp_head', 'wp_generator');
add_filter('show_admin_bar', '__return_false');

add_filter('author_rewrite_rules', '__return_empty_array');
function disable_author_archive()
{
  if (preg_match('#/author/.+#', $_SERVER['REQUEST_URI'])) {
    wp_redirect(esc_url(home_url('/404.php')));
    exit;
  }
}
add_action('init', 'disable_author_archive');

if (!is_admin()) {
  if (preg_match('/author=([0-9]*)/i', $_SERVER['QUERY_STRING'])) die();
  add_filter('redirect_canonical', 'my_shapespace_check_enum', 10, 2);
}
function my_shapespace_check_enum($redirect, $request)
{
  if (preg_match('/\?author=([0-9]*)(\/*)/i', $request)) die();
  else return $redirect;
}

add_action('init', 'disable_output');

function disable_output()
{
  remove_filter('the_content', 'wpautop');  // 本文欄
  // remove_filter('the_title', 'wpautop');  // タイトル蘭
  // remove_filter('comment_text', 'wpautop');  // コメント欄
  // remove_filter('the_excerpt', 'wpautop');  // 抜粋欄
}

/*
 * テンプレートパスを返す
 */
function temp_path()
{
  echo esc_url(get_template_directory_uri());
}
/* assetsパスを返す */
function assets_path()
{
  echo esc_url(get_template_directory_uri() . '/assets');
}
/* 画像パスを返す */
function img_path()
{
  echo esc_url(get_template_directory_uri() . '/assets/images');
}

/* 画像パスを返す（戻り値あり・テンプレートパーツ用） */
function get_img_path()
{
  return esc_url(get_template_directory_uri() . '/assets/images');
}
/* mediaフォルダへのURL */
function uploads_path()
{
  echo esc_url(wp_upload_dir()['baseurl']);
}

function page_path($page = "")
{
  $page = $page . '/';
  echo esc_url(home_url($page));
}

// CF7設定
define('PERSONAL_EDITOR_CF7_CONTACT_FORM_ID', 'b75f227');

add_filter('wpcf7_autop_or_not', '__return_false');

/**
 * All in One WP Migration でのエクスポート対象から不要ファイルを除外
 *
 * - 開発用ファイル（gulp / npm 関連）
 * - ソースファイル（src ディレクトリ）
 * - ドキュメント類（coding-md など）
 *
 * ※パスは「wp-content」配下からの相対パスで指定
 */
add_filter('ai1wm_exclude_content_from_export', function ($exclude) {
  $exclude[] = 'themes/PERSONAL EDITOR/node_modules';
  $exclude[] = 'themes/PERSONAL EDITOR/src';
  $exclude[] = 'themes/PERSONAL EDITOR/coding-md';
  $exclude[] = 'themes/PERSONAL EDITOR/gulpfile.js';
  $exclude[] = 'themes/PERSONAL EDITOR/gulpfile.mjs';
  $exclude[] = 'themes/PERSONAL EDITOR/package.json';
  $exclude[] = 'themes/PERSONAL EDITOR/package-lock.json';
  $exclude[] = 'themes/PERSONAL EDITOR/yarn.lock';
  $exclude[] = 'themes/PERSONAL EDITOR/pnpm-lock.yaml';

  return $exclude;
});
