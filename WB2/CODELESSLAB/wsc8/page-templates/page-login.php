<?php
/* Template Name: ログイン */
if (!defined('ABSPATH')) exit;

if (is_user_logged_in()) {
  wp_safe_redirect(home_url('/mypage'));
  exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'login_nonce')) {
    $error = '不正なリクエストです。';
  } else {
    $login = sanitize_text_field($_POST['login'] ?? '');
    if (is_email($login)) {
      $u = get_user_by('email', $login);
      if ($u) $login = $u->user_login;
    }
    $creds = ['user_login' => $login, 'user_password' => $_POST['password'] ?? '', 'remember' => !empty($_POST['remember'])];
    if (class_exists('SiteGuard')) remove_filter('authenticate', array('SiteGuard', 'captcha_authenticate'), 30);
    $user = wp_signon($creds, false);
    if (is_wp_error($user)) {
      $error = 'ログインに失敗しました。';
    } else {
      global $wpdb;
      $cu = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}custom_users WHERE wp_user_id = %d", $user->ID));
      $is_admin = user_can($user, 'manage_options') || ($cu !== null && isset($cu->role_type) && (int)$cu->role_type === 1);
      $redirect = !empty($_GET['redirect_to']) ? esc_url_raw($_GET['redirect_to']) : ($is_admin ? home_url('/select-user') : home_url('/mypage'));
      wp_safe_redirect($redirect);
      exit;
    }
  }
}
get_header();
?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/mypage.css">
<main id="join">
  <div class="wrap">
    <div class="box" style="max-width:400px;margin:60px auto;">
      <h1 class="tac">ログイン</h1>
      <?php if ($error): ?><p class="tac" style="color:#dc2626;margin-bottom:16px;"><?php echo esc_html($error); ?></p><?php endif; ?>
      <form method="post">
        <?php wp_nonce_field('login_nonce'); ?>
        <label>ログインID（メールまたはユーザー名）</label>
        <input type="text" name="login" required placeholder="メールアドレスまたはユーザー名">
        <label>パスワード</label>
        <input type="password" name="password" required>
        <label style="display:flex;align-items:center;gap:8px;margin-top:12px;">
          <input type="checkbox" name="remember" value="1"> 次回から自動的にログイン
        </label>
        <button class="btn" type="submit" style="margin-top:20px;">ログイン</button>
      </form>
      <?php /* 新規登録は終了したため非表示
      <p class="tac muted" style="margin-top:20px;">
        <a href="<?php echo esc_url(home_url('/register')); ?>">新規登録はこちら</a>
      </p>
      */ ?>
      <p class="tac" style="margin-top:12px;">
        <a href="<?php echo esc_url(home_url('/')); ?>">← トップへ戻る</a>
      </p>
    </div>
  </div>
</main>
<?php get_footer(); ?>
