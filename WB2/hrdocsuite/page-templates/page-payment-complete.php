<?php
/**
 * Template Name: 決済完了
 * Description: 申込確定後の完了画面（POSTで受信→DB保存→PRGで表示）
 *
 * @package HR_DOC_SUITE
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// 未ログインはログインへ
if (!is_user_logged_in()) {
    wp_safe_redirect(home_url('/login'));
    exit;
}

$plan_names_jp = array(
    'basic'    => 'ベーシックプラン',
    'standard' => 'スタンダードプラン',
    'pro'      => 'プロプラン'
);

// POST: 申込確定処理（PRGパターン）
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['hrdoc_execute_nonce']) || !wp_verify_nonce($_POST['hrdoc_execute_nonce'], 'hrdoc_payment_execute')) {
        wp_safe_redirect(home_url('/mypage'));
        exit;
    }

    $plan        = isset($_POST['plan']) ? sanitize_text_field($_POST['plan']) : 'basic';
    $total       = isset($_POST['total']) ? max(0, intval($_POST['total'])) : 0;
    $opt_customize = isset($_POST['opt_customize']) ? max(0, intval($_POST['opt_customize'])) : 0;
    $opt_review  = isset($_POST['opt_review']) ? max(0, intval($_POST['opt_review'])) : 0;
    $opt_flow    = isset($_POST['opt_flow']) ? max(0, intval($_POST['opt_flow'])) : 0;
    $opt_1on1    = isset($_POST['opt_1on1']) ? max(0, intval($_POST['opt_1on1'])) : 0;

    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    global $wpdb;
    $table = $wpdb->prefix . 'custom_users';
    $plan_label = isset($plan_names_jp[$plan]) ? $plan_names_jp[$plan] : 'ベーシックプラン';

    $order_date = current_time('Y/n/j');
    $data = array(
        'plan_type'           => in_array($plan, array('basic', 'standard', 'pro')) ? $plan : 'basic',
        'option_customize'    => $opt_customize,
        'option_review'       => $opt_review,
        'option_flow'         => $opt_flow,
        'option_1on1'          => $opt_1on1,
        'order_date'          => $order_date,
        'order_plan_name'     => $plan_label,
        'order_amount'        => (string) $total,
        'order_payment_method'=> 'クレジットカード',
        'order_status'        => '決済完了',
        'status'              => '決済確認済',
        'updated_at'          => current_time('mysql'),
    );

    $exists = $wpdb->get_var($wpdb->prepare(
        "SELECT 1 FROM {$table} WHERE wp_user_id = %d",
        $user_id
    ));

    if ($exists) {
        $wpdb->update($table, $data, array('wp_user_id' => $user_id));
    } else {
        $data['wp_user_id'] = $user_id;
        $wpdb->insert($table, $data);
    }

    $done_page = get_page_by_path('payment-complete');
    $redirect_url = $done_page ? add_query_arg('done', '1', get_permalink($done_page)) : add_query_arg('done', '1', home_url('/payment-complete'));
    wp_safe_redirect($redirect_url);
    exit;
}

// GET: done=1 のときだけ完了表示、それ以外はマイページへ
if (!isset($_GET['done']) || $_GET['done'] !== '1') {
    wp_safe_redirect(home_url('/mypage'));
    exit;
}

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
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <style>
    /* 決済完了ページ専用 - body.payment-complete-page でスコープし他ページと競合しない */
    html.paycomplete-html,
    body.payment-complete-page { margin: 0 !important; padding: 0 !important; box-sizing: border-box !important; }
    body.payment-complete-page { font-family: 'Noto Sans JP', sans-serif !important; min-height: 100vh !important; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #f0fdfa 100%) !important; display: flex !important; align-items: center !important; justify-content: center !important; padding: 2rem 1rem !important; color: #1f2937 !important; }
    body.payment-complete-page .paycomplete-box { max-width: 480px; width: 100%; background: #fff; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); padding: 2.5rem; text-align: center; border: 1px solid #e5e7eb; }
    body.payment-complete-page .paycomplete-box .paycomplete-icon { width: 80px; height: 80px; margin: 0 auto 1.5rem; background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; }
    body.payment-complete-page .paycomplete-box .paycomplete-h1 { font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0 0 0.5rem; }
    body.payment-complete-page .paycomplete-box .paycomplete-lead { font-size: 0.9375rem; color: #64748b; margin-bottom: 2rem; line-height: 1.6; }
    body.payment-complete-page .paycomplete-box .paycomplete-btn { display: inline-block; padding: 0.875rem 2rem; border-radius: 10px; font-weight: 600; text-decoration: none; font-size: 1rem; transition: transform 0.2s, box-shadow 0.2s; font-family: inherit; }
    body.payment-complete-page .paycomplete-box .paycomplete-btn-primary { background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: #fff; box-shadow: 0 2px 8px rgba(30, 64, 175, 0.3); }
    body.payment-complete-page .paycomplete-box .paycomplete-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(30, 64, 175, 0.4); }
    body.payment-complete-page .paycomplete-box .paycomplete-btn-secondary { background: #f1f5f9; color: #475569; margin-left: 0.75rem; border: 1px solid #e2e8f0; }
    body.payment-complete-page .paycomplete-box .paycomplete-btn-secondary:hover { background: #e2e8f0; }
    body.payment-complete-page .paycomplete-box .paycomplete-actions { margin-top: 2rem; }
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
