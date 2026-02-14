<?php
/**
 * Template Name: 決済完了
 * PERSONAL EDITOR 決済完了
 *
 * @package PERSONAL_EDITOR
 * @since 1.0.0
 */

if (!defined('ABSPATH')) exit;
if (!is_user_logged_in()) { wp_safe_redirect(home_url('/login')); exit; }

$plan_names_jp = array('basic' => 'ベーシックプラン', 'standard' => 'スタンダードプラン', 'pro' => 'プロプラン');
$option_cols = array('customize', 'review', 'flow', 'note_check', 'tag', 'theme', 'improve', '1on1', 'long', 'interview', 'meeting', 'rewrite');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['personaleditor_execute_nonce']) || !wp_verify_nonce($_POST['personaleditor_execute_nonce'], 'personaleditor_payment_execute')) {
        wp_safe_redirect(home_url('/mypage')); exit;
    }
    $plan = isset($_POST['plan']) ? sanitize_text_field($_POST['plan']) : 'basic';
    $total = isset($_POST['total']) ? max(0, intval($_POST['total'])) : 0;
    $opt_data = array();
    foreach ($option_cols as $k) {
        $col = ($k === '1on1') ? 'option_1on1' : 'option_' . $k;
        $opt_data[$col] = isset($_POST['opt_' . $k]) ? max(0, intval($_POST['opt_' . $k])) : 0;
    }
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    global $wpdb;
    $table = $wpdb->prefix . 'custom_users';
    $plan_label = isset($plan_names_jp[$plan]) ? $plan_names_jp[$plan] : 'ベーシックプラン';
    $order_date = current_time('Y/n/j');
    $data = array_merge(
        array(
            'plan_type' => in_array($plan, array('basic', 'standard', 'pro')) ? $plan : 'basic',
            'order_date' => $order_date, 'order_plan_name' => $plan_label, 'order_amount' => (string)$total,
            'order_payment_method' => 'クレジットカード', 'order_status' => '決済完了', 'status' => '決済確認済',
            'updated_at' => current_time('mysql'),
        ),
        $opt_data
    );
    $exists = $wpdb->get_var($wpdb->prepare("SELECT 1 FROM {$table} WHERE wp_user_id = %d", $user_id));
    if ($exists) $wpdb->update($table, $data, array('wp_user_id' => $user_id));
    else { $data['wp_user_id'] = $user_id; $wpdb->insert($table, $data); }
    $done_page = get_page_by_path('payment-complete');
    $redirect_url = $done_page ? add_query_arg('done', '1', get_permalink($done_page)) : add_query_arg('done', '1', home_url('/payment-complete'));
    wp_safe_redirect($redirect_url); exit;
}

if (!isset($_GET['done']) || $_GET['done'] !== '1') { wp_safe_redirect(home_url('/mypage')); exit; }

$mypage_url = home_url('/mypage');
$home_url = home_url('/');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>決済完了 | <?php bloginfo('name'); ?></title>
  <?php wp_head(); ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    body.payment-complete-page{font-family:'Noto Sans JP',sans-serif;min-height:100vh;background:linear-gradient(135deg,#fff7ed,#ffedd5,#fef3c7);display:flex;align-items:center;justify-content:center;padding:2rem 1rem;color:#1f2937;margin:0}
    body.payment-complete-page .paycomplete-box{max-width:480px;width:100%;background:#fff;border-radius:16px;box-shadow:0 10px 40px rgba(0,0,0,.08);padding:2.5rem;text-align:center;border:1px solid #e5e7eb}
    body.payment-complete-page .paycomplete-icon{width:80px;height:80px;margin:0 auto 1.5rem;background:linear-gradient(135deg,#22c55e,#16a34a);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2.5rem}
    body.payment-complete-page .paycomplete-h1{font-size:1.5rem;font-weight:700;color:#1F2933;margin:0 0 .5rem}
    body.payment-complete-page .paycomplete-lead{font-size:.9375rem;color:#64748b;margin-bottom:2rem;line-height:1.6}
    body.payment-complete-page .paycomplete-btn{display:inline-block;padding:.875rem 2rem;border-radius:10px;font-weight:600;text-decoration:none;font-size:1rem;transition:transform .2s,box-shadow .2s;font-family:inherit}
    body.payment-complete-page .paycomplete-btn-primary{background:linear-gradient(135deg,#F97316,#1F2933);color:#fff;box-shadow:0 2px 8px rgba(249,115,22,.3)}
    body.payment-complete-page .paycomplete-btn-primary:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(249,115,22,.4)}
    body.payment-complete-page .paycomplete-btn-secondary{background:#f1f5f9;color:#475569;margin-left:.75rem;border:1px solid #e2e8f0}
    body.payment-complete-page .paycomplete-btn-secondary:hover{background:#e2e8f0}
    body.payment-complete-page .paycomplete-actions{margin-top:2rem}
  </style>
</head>
<body class="payment-complete-page">
  <div class="paycomplete-box">
    <div class="paycomplete-icon"><i class="fas fa-check"></i></div>
    <h1 class="paycomplete-h1">決済が完了しました</h1>
    <p class="paycomplete-lead">お申し込みありがとうございます。<br>24時間以内にマイページに反映されます。</p>
    <div class="paycomplete-actions">
      <a href="<?php echo esc_url($mypage_url); ?>" class="paycomplete-btn paycomplete-btn-primary"><i class="fas fa-user"></i> マイページへ</a>
      <a href="<?php echo esc_url($home_url); ?>" class="paycomplete-btn paycomplete-btn-secondary">トップへ</a>
    </div>
  </div>
<?php wp_footer(); ?>
</body>
</html>
