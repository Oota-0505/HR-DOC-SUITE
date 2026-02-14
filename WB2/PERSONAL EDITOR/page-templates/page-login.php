<?php
/**
 * Template Name: ログインページ
 * PERSONAL EDITOR ログイン
 *
 * @package PERSONAL_EDITOR
 * @since 1.0.0
 */

if (!defined('ABSPATH')) exit;

if (is_user_logged_in()) {
    $current_user = wp_get_current_user();
    $is_wp_admin = user_can($current_user, 'manage_options');
    global $wpdb;
    $custom_user = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}custom_users WHERE wp_user_id = %d", $current_user->ID));
    $is_custom_admin = ($custom_user !== null && (int)$custom_user->role_type === 1);
    if ($is_wp_admin || $is_custom_admin) wp_redirect(home_url('/select-user'));
    else wp_redirect(home_url('/mypage'));
    exit;
}

$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['personaleditor_login'])) {
    if (!isset($_POST['personaleditor_login_nonce']) || !wp_verify_nonce($_POST['personaleditor_login_nonce'], 'personaleditor_login_action')) {
        $login_error = '不正なリクエストです。再度お試しください。';
    } else {
        $login = isset($_POST['login']) ? sanitize_text_field($_POST['login']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $remember = isset($_POST['remember']);
        $user = get_user_by('email', $login) ?: get_user_by('login', $login);
        if ($user) {
            $creds = array('user_login' => $user->user_login, 'user_password' => $password, 'remember' => $remember);
            $result = wp_signon($creds, false);
            if (!is_wp_error($result)) {
                wp_set_current_user($result->ID);
                $custom_user = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}custom_users WHERE wp_user_id = %d", $result->ID));
                $is_ca = ($custom_user !== null && (int)$custom_user->role_type === 1);
                wp_redirect(user_can($result, 'manage_options') || $is_ca ? home_url('/select-user') : home_url('/mypage'));
                exit;
            }
        }
        $login_error = 'ユーザー名・メールアドレスまたはパスワードが正しくありません。';
    }
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン | <?php bloginfo('name'); ?></title>
  <?php wp_head(); ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
body.page-login{padding-bottom:0 !important}
.pe-login{font-family:"Noto Sans JP",sans-serif;min-height:100vh;background-color:#1F2933;background-image:radial-gradient(ellipse 80% 50% at 50% 0%,rgba(249,115,22,.12) 0%,transparent 50%)}
.pe-login__header{position:fixed;top:0;left:0;right:0;padding:1rem 1.5rem;background:rgba(31,41,51,.95);backdrop-filter:blur(10px);z-index:100}
.pe-login__header-inner{max-width:1225px;margin:0 auto;display:flex;justify-content:space-between;align-items:center}
.pe-login__logo{display:inline-block;line-height:0;text-decoration:none}
.pe-login__logo img{height:32px;width:auto;max-width:160px;object-fit:contain;display:block}
.pe-login__back{color:#F3F4F6;text-decoration:none;font-size:.875rem;display:inline-flex;align-items:center;gap:.5rem;transition:opacity .3s}
.pe-login__back:hover{opacity:.8}
.pe-login__main{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:6rem 1.5rem 4rem}
.pe-login__card{width:100%;max-width:440px;padding:3rem 2.5rem;background:#fff;border-radius:.5rem;box-shadow:0 25px 50px -12px rgba(0,0,0,.25)}
.pe-login__subtitle{font-family:"Heebo",sans-serif;font-weight:700;font-size:1.25rem;letter-spacing:.4em;color:#F97316;text-align:center;text-transform:uppercase;margin:0 0 .5rem}
.pe-login__title{font-weight:700;font-size:2rem;line-height:1.5;letter-spacing:.08em;color:#1F2933;text-align:center;margin:0 0 2rem}
.pe-login__error{color:#dc2626;background:#fef2f2;padding:.75rem 1rem;border-radius:.5rem;margin-bottom:1.5rem;font-size:.875rem;border:1px solid #fecaca;display:flex;align-items:center;gap:.5rem}
.pe-login__form-wrap{margin-bottom:1.5rem}
.pe-login__field{display:grid;grid-template-columns:2.5rem 1fr;align-items:center;background:#f3f4f6;border:1px solid transparent;border-radius:.5rem;margin-bottom:1rem;padding:0 1rem;height:3.125rem;transition:border-color .2s,box-shadow .2s}
.pe-login__field:focus-within{border-color:#F97316;box-shadow:0 0 0 3px rgba(249,115,22,.12)}
.pe-login__field i{color:#9ca3af;font-size:1rem;text-align:center}
.pe-login__field input{background:none;border:none;outline:none;font-size:1rem;font-weight:500;color:#1F2933;width:100%}
.pe-login__field input::placeholder{color:#9ca3af}
.pe-login__remember{display:flex;align-items:center;gap:.75rem;cursor:pointer;margin-bottom:1.5rem;user-select:none}
.pe-login__remember input{display:none}
.pe-login__check{width:1.125rem;height:1.125rem;background:#f3f4f6;border:1px solid #e5e7eb;border-radius:.25rem;flex-shrink:0;position:relative;transition:background .2s,border-color .2s}
.pe-login__remember input:checked+.pe-login__check{background:#F97316;border-color:#F97316}
.pe-login__remember input:checked+.pe-login__check::after{content:"";position:absolute;left:.35rem;top:.1rem;width:.35rem;height:.6rem;border:solid #fff;border-width:0 2px 2px 0;transform:rotate(45deg)}
.pe-login__label{font-size:.875rem;color:#6b7280}
.pe-login__submit{width:100%;padding:1rem 1.5rem;background:#F97316;color:#fff;border:none;border-radius:0;font-weight:700;font-size:1.05rem;cursor:pointer;font-family:inherit;transition:opacity .3s,transform .2s}
.pe-login__submit:hover{opacity:.9;transform:translateY(-1px)}
.pe-login__cta{text-align:center;margin:0;padding-top:1.5rem;border-top:1px solid #e5e7eb}
.pe-login__cta a{color:#F97316;text-decoration:none;font-size:.875rem;font-weight:600;transition:opacity .3s}
.pe-login__cta a:hover{opacity:.8}
@media screen and (max-width:767px){.pe-login__main{padding:5rem 1.25rem 3rem}.pe-login__card{padding:2rem 1.5rem}.pe-login__subtitle{font-size:.875rem;letter-spacing:.3em}.pe-login__title{font-size:1.5rem;margin-bottom:1.5rem}}
  </style>
</head>
<body class="page-login">
<?php wp_body_open(); ?>
<div class="pe-login">
  <header class="pe-login__header">
    <div class="pe-login__header-inner">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="pe-login__logo">
        <img src="<?php img_path(); ?>/common/header-logo.webp" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
      </a>
      <a href="<?php echo esc_url(home_url('/')); ?>" class="pe-login__back"><i class="fas fa-arrow-left"></i> トップに戻る</a>
    </div>
  </header>

  <main class="pe-login__main">
    <div class="pe-login__card">
      <p class="pe-login__subtitle">LOGIN</p>
      <h1 class="pe-login__title">ログイン</h1>

      <?php if ($login_error) : ?>
        <div class="pe-login__error"><i class="fas fa-exclamation-circle"></i> <?php echo esc_html($login_error); ?></div>
      <?php endif; ?>

      <div class="pe-login__form-wrap">
        <form action="" method="post" class="pe-login__form">
          <?php wp_nonce_field('personaleditor_login_action', 'personaleditor_login_nonce'); ?>
          <input type="hidden" name="personaleditor_login" value="1">
          <div class="pe-login__field">
            <i class="fas fa-user"></i>
            <input type="text" name="login" placeholder="メールアドレスまたはユーザー名" required autocomplete="username">
          </div>
          <div class="pe-login__field">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="パスワード" required autocomplete="current-password">
          </div>
          <label class="pe-login__remember">
            <input type="checkbox" name="remember">
            <span class="pe-login__check"></span>
            <span class="pe-login__label">次回から自動的にログイン</span>
          </label>
          <button type="submit" class="pe-login__submit c-button">ログインする</button>
        </form>
      </div>

      <p class="pe-login__cta">
        <a href="<?php echo esc_url(home_url('/#contact')); ?>">お問い合わせはこちら</a>
      </p>
    </div>
  </main>
</div>
<?php wp_footer(); ?>
</body>
</html>
