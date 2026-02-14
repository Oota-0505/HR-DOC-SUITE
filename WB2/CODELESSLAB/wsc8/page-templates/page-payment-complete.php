<?php
/* Template Name: 決済完了 */
if (!defined('ABSPATH')) exit;
if (!is_user_logged_in()) { wp_safe_redirect(home_url('/login')); exit; }

$PLANS = ['starter' => 'スタータープラン', 'standard' => 'スタンダードプラン', 'pro' => 'プロプラン'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!isset($_POST['execute_nonce']) || !wp_verify_nonce($_POST['execute_nonce'], 'wsc8_payment_execute')) {
    wp_safe_redirect(home_url('/mypage')); exit;
  }
  $plan = sanitize_text_field($_POST['plan'] ?? 'standard');
  $total = max(0, (int)($_POST['total'] ?? 0));
  $plan_label = $PLANS[$plan] ?? 'スタンダードプラン';
  $user_id = wp_get_current_user()->ID;
  global $wpdb;
  $t = $wpdb->prefix . 'custom_users';
  $data = ['plan_name' => $plan_label, 'payment_amount' => $total, 'history_payment_method' => 'クレジットカード', 'status' => '決済完了'];
  $exists = $wpdb->get_var($wpdb->prepare("SELECT 1 FROM {$t} WHERE wp_user_id = %d", $user_id));
  if ($exists) {
    $wpdb->update($t, $data, ['wp_user_id' => $user_id]);
  } else {
    $data['wp_user_id'] = $user_id;
    $wpdb->insert($t, $data);
  }
  wp_safe_redirect(home_url('/payment-complete/?done=1')); exit;
}

if (!isset($_GET['done']) || $_GET['done'] !== '1') { wp_safe_redirect(home_url('/mypage')); exit; }
get_header();
?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/mypage.css">
<main id="join">
  <div class="wrap">
    <div class="box" style="max-width:480px;margin:80px auto;text-align:center;">
      <p style="font-size:3rem;margin-bottom:24px;">✓</p>
      <h1>決済が完了しました</h1>
      <p style="margin:20px 0;">お申し込みありがとうございます。<br>24時間以内にマイページに反映されます。</p>
      <p style="margin-top:32px;"><a class="btn" href="<?php echo esc_url(home_url('/mypage')); ?>">マイページへ</a></p>
      <p style="margin-top:16px;"><a href="<?php echo esc_url(home_url('/')); ?>">トップへ戻る</a></p>
    </div>
  </div>
</main>
<?php get_footer(); ?>
