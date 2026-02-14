<?php
/* Template Name: ユーザー選択 */
if (!defined('ABSPATH')) exit;
if (!is_user_logged_in()) { wp_safe_redirect(home_url('/login')); exit; }

$cu = wp_get_current_user();
global $wpdb;
$loggedin_cu = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}custom_users WHERE wp_user_id = %d", $cu->ID));
$is_admin = user_can($cu, 'manage_options') || ($loggedin_cu !== null && isset($loggedin_cu->role_type) && (int)$loggedin_cu->role_type === 1);
if (!$is_admin) { wp_safe_redirect(home_url('/mypage')); exit; }

$wp_users = get_users(array('orderby' => 'login', 'order' => 'ASC'));
get_header();
?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/mypage.css">
<main id="join">
  <div class="wrap">
    <div class="box" style="max-width:480px;margin:60px auto;">
      <h1 class="tac">ユーザー選択</h1>
      <p class="tac muted" style="margin-bottom:20px;">管理対象のユーザーを選択してください</p>
      <form id="selectForm">
        <label>ユーザー</label>
        <select id="userSelect" style="width:100%;padding:12px;border-radius:8px;border:1px solid var(--border_color);">
          <?php if ($wp_users): foreach ($wp_users as $u): ?>
            <option value="<?php echo esc_attr($u->ID); ?>"><?php echo esc_html($u->user_login); ?></option>
          <?php endforeach; endif; ?>
        </select>
        <button class="btn" type="button" id="btnSelect" style="margin-top:20px;">選択してマイページへ</button>
      </form>
      <p class="tac" style="margin-top:20px;">
        <a href="<?php echo esc_url(home_url('/mypage')); ?>">← マイページへ</a>
      </p>
      <p class="tac" style="margin-top:8px;">
        <a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>">ログアウト</a>
      </p>
    </div>
  </div>
</main>
<script>
document.getElementById('btnSelect').addEventListener('click', function() {
  var v = document.getElementById('userSelect').value;
  if (v) location.href = '<?php echo esc_url(home_url('/mypage')); ?>?user_id=' + v;
});
</script>
<?php get_footer(); ?>
