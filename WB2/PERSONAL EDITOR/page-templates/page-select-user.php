<?php
/**
 * Template Name: ユーザー選択ページ
 * PERSONAL EDITOR 管理者ユーザー選択
 *
 * @package PERSONAL_EDITOR
 * @since 1.0.0
 */

if (!defined('ABSPATH')) exit;

if (!is_user_logged_in()) { wp_redirect(home_url('/login')); exit; }

$current_user = wp_get_current_user();
global $wpdb;
$current_custom_user = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}custom_users WHERE wp_user_id = %d", $current_user->ID));
$is_wp_admin = current_user_can('manage_options');
$is_custom_admin = ($current_custom_user !== null && (int)$current_custom_user->role_type === 1);
if (!$is_wp_admin && !$is_custom_admin) { wp_redirect(home_url('/mypage')); exit; }

$wp_users = get_users(array('orderby' => 'display_name', 'order' => 'ASC'));
$custom_users_by_wp = array();
if ($wp_users) {
    $cu_all = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}custom_users");
    if ($cu_all) { foreach ($cu_all as $cu) { $custom_users_by_wp[(int)$cu->wp_user_id] = $cu; } }
}

$select_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_user'])) {
    if (!isset($_POST['personaleditor_select_user_nonce']) || !wp_verify_nonce($_POST['personaleditor_select_user_nonce'], 'personaleditor_select_user_action')) {
        $select_error = '不正なリクエストです。再度お試しください。';
    } else {
        $selected_wp_user_id = intval($_POST['selected_user']);
        $selected_wp_user = get_userdata($selected_wp_user_id);
        if ($selected_wp_user) {
            if (!session_id()) session_start();
            $cu = isset($custom_users_by_wp[$selected_wp_user_id]) ? $custom_users_by_wp[$selected_wp_user_id] : null;
            $_SESSION['managed_user_id'] = $cu ? (int)$cu->id : 0;
            $cu_name = ($cu && isset($cu->name)) ? trim((string)$cu->name) : '';
            $_SESSION['managed_user_name'] = $cu_name !== '' ? $cu_name : $selected_wp_user->display_name;
            if (trim((string)$_SESSION['managed_user_name']) === '') $_SESSION['managed_user_name'] = $selected_wp_user->user_login;
            wp_redirect(home_url('/mypage?user_id=' . $selected_wp_user_id)); exit;
        } else { $select_error = '選択されたユーザーが見つかりません。'; }
    }
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ユーザー選択 | <?php bloginfo('name'); ?></title>
  <?php wp_head(); ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    .auth-select-container{font-family:'Noto Sans JP',sans-serif;min-height:100vh;position:relative;margin:0;padding:0;box-sizing:border-box}
    .auth-select-background{position:fixed;inset:0;background:#1F2933;z-index:0}
    .auth-select-content{position:relative;z-index:1;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:100px 20px 20px}
    .auth-select-header{position:fixed;top:0;left:0;right:0;display:flex;justify-content:space-between;align-items:center;padding:16px 24px;background:rgba(31,41,51,.9);backdrop-filter:blur(10px);z-index:100}
    .auth-select-header-logo a{display:inline-block;text-decoration:none;line-height:0}
    .auth-select-header-logo img{height:32px;width:auto;max-width:160px;object-fit:contain;display:block}
    .auth-select-header-back{display:flex;align-items:center;gap:8px;color:#fff;text-decoration:none;font-size:.9rem;opacity:.8}
    .auth-select-header-back:hover{opacity:1}
    .auth-select-card{width:100%;max-width:480px;background:#fff;border-radius:24px;padding:40px;box-shadow:0 20px 40px rgba(0,0,0,.2);z-index:1}
    .auth-select-logo{text-align:center;margin-bottom:30px}
    .auth-select-logo a{display:inline-block;text-decoration:none}
    .auth-select-logo img{height:45px;width:auto;max-width:100%;object-fit:contain;display:block}
    .auth-select-title{text-align:center;margin-bottom:30px;color:#1F2933;font-weight:800;font-size:1.5rem}
    .auth-select-admin-badge{display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#F97316,#1F2933);color:#fff;font-size:.75rem;font-weight:600;padding:6px 12px;border-radius:100px;margin-bottom:20px}
    .auth-select-error{color:#dc2626;background:#fef2f2;padding:12px 16px;border-radius:8px;margin-bottom:20px;font-size:.9rem;text-align:center;border:1px solid #fecaca}
    .auth-select-user-count{display:flex;align-items:center;justify-content:center;gap:8px;padding:12px;background:linear-gradient(135deg,#fff7ed,#ffedd5);border-radius:12px;margin-bottom:20px;font-size:.875rem;color:#c2410c;font-weight:500}
    .auth-select-form-group{margin-bottom:20px}
    .auth-select-form-label{font-size:.9rem;color:#6b7280;margin-bottom:8px;display:block}
    .auth-select-input-field{width:100%;background:#f3f4f6;height:50px;border-radius:50px;display:grid;grid-template-columns:15% 1fr auto;gap:.5rem;align-items:center;padding:0 1rem;color:#1f2937}
    .auth-select-input-field i{text-align:center;color:#acacac}
    .auth-select-dropdown-label{font-size:1rem;color:#1f2937;font-weight:500}
    .auth-select-custom-dropdown{position:relative;width:100%}
    .auth-select-custom-dropdown.is-open .auth-select-dropdown-arrow{transform:rotate(180deg)}
    .auth-select-dropdown-arrow{transition:transform .2s}
    .auth-select-dropdown-list{position:absolute;top:100%;left:0;right:0;margin-top:4px;background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,.15);max-height:240px;overflow-y:auto;z-index:200;display:none;color:#1f2937}
    .auth-select-custom-dropdown.is-open .auth-select-dropdown-list{display:block}
    .auth-select-dropdown-option{padding:12px 16px;font-size:1rem;font-weight:500;cursor:pointer;border-bottom:1px solid #f3f4f6;color:#1f2937;transition:background .15s}
    .auth-select-dropdown-option:hover{background:#fff7ed!important;color:#c2410c!important}
    .auth-select-dropdown-option--empty{cursor:default;color:#6b7280!important}
    .auth-select-btn-solid{width:100%;background:linear-gradient(135deg,#F97316,#1F2933);color:#fff;padding:18px 32px;border-radius:12px;font-weight:700;font-size:1.05rem;display:flex;align-items:center;justify-content:center;gap:12px;border:none;cursor:pointer;font-family:inherit;margin:10px 0}
    .auth-select-btn-solid:hover{background:linear-gradient(135deg,#ea580c,#F97316)}
    .auth-select-footer-links{text-align:center;margin-top:30px}
    .auth-select-footer-link{color:#6b7280;text-decoration:none;font-size:.9rem;display:inline-flex;align-items:center;gap:8px}
    .auth-select-footer-link:hover{color:#F97316}
    .auth-select-footer-divider{height:16px;width:1px;background:#e2e8f0;display:inline-block;margin:0 16px;vertical-align:middle}
  </style>
</head>
<body>
<?php wp_body_open(); ?>
<div class="auth-select-container">
  <div class="auth-select-background"></div>
  <div class="auth-select-content">
    <header class="auth-select-header">
      <div class="auth-select-header-logo">
        <a href="<?php echo esc_url(home_url('/')); ?>">
          <img src="<?php img_path(); ?>/common/header-logo.webp" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
        </a>
      </div>
      <a href="<?php echo esc_url(home_url('/')); ?>" class="auth-select-header-back"><i class="fas fa-arrow-left"></i> トップに戻る</a>
    </header>
    <div class="auth-select-card">
      <div class="auth-select-logo">
        <a href="<?php echo esc_url(home_url('/')); ?>">
          <img src="<?php img_path(); ?>/common/header-logo.webp" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
        </a>
      </div>
      <div style="text-align:center"><span class="auth-select-admin-badge"><i class="fas fa-crown"></i> 管理者モード</span></div>
      <h2 class="auth-select-title">ユーザー選択</h2>
      <?php if ($select_error) : ?><div class="auth-select-error"><i class="fas fa-exclamation-circle"></i> <?php echo esc_html($select_error); ?></div><?php endif; ?>
      <?php if ($wp_users && count($wp_users) > 0) : ?><div class="auth-select-user-count"><i class="fas fa-users"></i> 登録ユーザー: <?php echo count($wp_users); ?>名</div><?php endif; ?>
      <form action="" method="post">
        <?php wp_nonce_field('personaleditor_select_user_action', 'personaleditor_select_user_nonce'); ?>
        <div class="auth-select-form-group">
          <label class="auth-select-form-label">管理対象ユーザーを選択してください</label>
          <div class="auth-select-custom-dropdown">
            <input type="hidden" name="selected_user" id="selected_user_input" value="" required>
            <div class="auth-select-input-field auth-select-dropdown-trigger" id="userDropdownTrigger" tabindex="0" role="button" aria-haspopup="listbox" aria-expanded="false">
              <i class="fas fa-user-circle"></i>
              <span class="auth-select-dropdown-label">選択してください</span>
              <svg class="auth-select-dropdown-arrow" viewBox="0 0 20 20" width="20" height="20"><path stroke="#6b7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none" d="m6 8 4 4 4-4"/></svg>
            </div>
            <div class="auth-select-dropdown-list" id="userDropdownList" role="listbox">
              <?php if ($wp_users) : foreach ($wp_users as $wp_user) :
                $cu = isset($custom_users_by_wp[$wp_user->ID]) ? $custom_users_by_wp[$wp_user->ID] : null;
                $cu_name = ($cu && isset($cu->name)) ? trim((string)$cu->name) : '';
                $display_name = $cu_name !== '' ? $cu_name : $wp_user->display_name;
                if (trim((string)$display_name) === '') $display_name = $wp_user->user_login;
                $company = ($cu && isset($cu->company_name) && trim((string)$cu->company_name) !== '') ? trim($cu->company_name) : '';
                $label = $company !== '' ? esc_html($display_name) . '（' . esc_html($company) . '）' : esc_html($display_name);
              ?>
              <div class="auth-select-dropdown-option" role="option" data-value="<?php echo esc_attr($wp_user->ID); ?>" data-label="<?php echo esc_attr($display_name . ($company !== '' ? '（' . $company . '）' : '')); ?>"><?php echo $label; ?></div>
              <?php endforeach; else : ?>
              <div class="auth-select-dropdown-option auth-select-dropdown-option--empty">登録ユーザーがいません</div>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <button type="submit" class="auth-select-btn-solid"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14M22 4L12 14.01l-3-3"/></svg> 選択して管理画面へ</button>
      </form>
      <div class="auth-select-footer-links">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="auth-select-footer-link"><i class="fas fa-home"></i> トップページに戻る</a>
        <span class="auth-select-footer-divider"></span>
        <a href="<?php echo wp_logout_url(home_url('/login')); ?>" class="auth-select-footer-link"><i class="fas fa-sign-out-alt"></i> ログアウト</a>
      </div>
    </div>
  </div>
</div>
<script>
(function(){var t=document.getElementById('userDropdownTrigger'),l=document.getElementById('userDropdownList'),i=document.getElementById('selected_user_input'),lb=document.querySelector('.auth-select-dropdown-label'),w=t?t.closest('.auth-select-custom-dropdown'):null,f=i?i.closest('form'):null;if(!t||!l||!i||!w)return;function close(){w.classList.remove('is-open');t.setAttribute('aria-expanded','false')}t.addEventListener('click',function(e){e.preventDefault();e.stopPropagation();w.classList.toggle('is-open');t.setAttribute('aria-expanded',w.classList.contains('is-open'))});l.querySelectorAll('.auth-select-dropdown-option:not(.auth-select-dropdown-option--empty)').forEach(function(o){o.addEventListener('click',function(e){e.stopPropagation();i.value=o.getAttribute('data-value');lb.textContent=o.getAttribute('data-label');close()})});document.addEventListener('click',function(e){if(!w.contains(e.target))close()});if(f)f.addEventListener('submit',function(e){if(!i.value){e.preventDefault();alert('ユーザーを選択してください。');w.classList.add('is-open');t.focus()}})}());
</script>
<?php wp_footer(); ?>
</body>
</html>
