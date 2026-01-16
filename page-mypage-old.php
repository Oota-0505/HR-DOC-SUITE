<?php
/**
 * Template Name: マイページ
 * Description: ユーザーのマイページ（プラン・オプション・アカウント・申込履歴・請求情報）
 *
 * @package HR_DOC_SUITE
 * @since 1.0.0
 * 
 * セクション構成:
 * 1. プラン選択（基本/プレミアム）
 * 2. オプション（セット数入力）
 * 3. 合計金額＋決済ボタン（ポップアップ）
 * 4. アカウント情報（新規登録時の情報を表示）
 * 5. 申込履歴（管理者が手入力）
 * 6. 請求情報（管理者が手入力）
 * 7. ダウンロード資料
 * 8. 写真講評（メール送信リンク）
 */

// 直接アクセス禁止
if (!defined('ABSPATH')) {
    exit;
}

// ログインチェック
if (!is_user_logged_in()) {
    wp_redirect(home_url('/login'));
    exit;
}

// ユーザー情報取得
$current_user = wp_get_current_user();
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : $current_user->ID;

global $wpdb;

// 表示対象ユーザー
$custom_user = $wpdb->get_row(
    $wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}custom_users WHERE wp_user_id = %d",
        $user_id
    )
);

// ログイン中ユーザー（権限チェック用）
$loggedin_user = $wpdb->get_row(
    $wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}custom_users WHERE wp_user_id = %d",
        $current_user->ID
    )
);

// 管理者判定
$is_admin = false;
if (($loggedin_user == null) || ($loggedin_user->role_type == 1)) {
    $is_admin = true;
}

// 管理者でない場合、他人のページは見れない
if (!$is_admin && $user_id != $current_user->ID) {
    wp_redirect(home_url('/mypage'));
    exit;
}

// 表示用ユーザー情報
$display_user = get_userdata($user_id);
$user_name = $custom_user->name ?? ($display_user->display_name ?? '');
$user_email = $custom_user->email ?? ($display_user->user_email ?? '');
$user_phone = $custom_user->phone ?? '';
$user_address = $custom_user->address ?? '';

// プラン・オプション情報
$current_plan = $custom_user->plan_type ?? 'basic';
$option_guide = $custom_user->option_guide ?? 0;
$option_remake = $custom_user->option_remake ?? 0;
$option_extra = $custom_user->option_extra ?? 0;

// 申込履歴情報（管理者入力）
$order_date = $custom_user->order_date ?? '';
$order_plan_name = $custom_user->order_plan_name ?? '';
$order_amount = $custom_user->order_amount ?? '';
$order_payment_method = $custom_user->order_payment_method ?? '';
$order_status = $custom_user->order_status ?? '';

// 請求情報（管理者入力）
$billing_current = $custom_user->billing_current ?? '';
$billing_history = $custom_user->billing_history ?? '';
$billing_next_date = $custom_user->billing_next_date ?? '';
$billing_method = $custom_user->billing_method ?? '';

// 保存処理
$save_message = '';
$save_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hrdoc_mypage_save'])) {
    if (!isset($_POST['hrdoc_mypage_nonce']) || !wp_verify_nonce($_POST['hrdoc_mypage_nonce'], 'hrdoc_mypage_action')) {
        $save_error = '不正なリクエストです。';
    } else {
        $data = array(
            'plan_type' => sanitize_text_field($_POST['plan_select'] ?? 'basic'),
            'option_guide' => intval($_POST['option_guide'] ?? 0),
            'option_remake' => intval($_POST['option_remake'] ?? 0),
            'option_extra' => intval($_POST['option_extra'] ?? 0),
            'updated_at' => current_time('mysql'),
        );
        
        // 管理者のみ編集可能なフィールド
        if ($is_admin) {
            $data['order_date'] = sanitize_text_field($_POST['order_date'] ?? '');
            $data['order_plan_name'] = sanitize_text_field($_POST['order_plan_name'] ?? '');
            $data['order_amount'] = sanitize_text_field($_POST['order_amount'] ?? '');
            $data['order_payment_method'] = sanitize_text_field($_POST['order_payment_method'] ?? '');
            $data['order_status'] = sanitize_text_field($_POST['order_status'] ?? '');
            $data['billing_current'] = sanitize_text_field($_POST['billing_current'] ?? '');
            $data['billing_history'] = sanitize_text_field($_POST['billing_history'] ?? '');
            $data['billing_next_date'] = sanitize_text_field($_POST['billing_next_date'] ?? '');
            $data['billing_method'] = sanitize_text_field($_POST['billing_method'] ?? '');
        }
        
        if ($custom_user != null) {
            $result = $wpdb->update(
                $wpdb->prefix . 'custom_users',
                $data,
                array('wp_user_id' => $user_id)
            );
        } else {
            $data['wp_user_id'] = $user_id;
            $result = $wpdb->insert($wpdb->prefix . 'custom_users', $data);
        }
        
        if ($result !== false) {
            $save_message = '保存しました。';
            // 値を更新
            $current_plan = $data['plan_type'];
            $option_guide = $data['option_guide'];
            $option_remake = $data['option_remake'];
            $option_extra = $data['option_extra'];
            if ($is_admin) {
                $order_date = $data['order_date'];
                $order_plan_name = $data['order_plan_name'];
                $order_amount = $data['order_amount'];
                $order_payment_method = $data['order_payment_method'];
                $order_status = $data['order_status'];
                $billing_current = $data['billing_current'];
                $billing_history = $data['billing_history'];
                $billing_next_date = $data['billing_next_date'];
                $billing_method = $data['billing_method'];
            }
        } else {
            $save_error = '保存に失敗しました。';
        }
    }
}

// 価格設定
$prices = array(
    'basic' => 2178,
    'premium' => 5478,
    'guide' => 550,
    'remake' => 1100,
    'extra' => 330
);

$initial_total = $prices[$current_plan] 
    + ($option_guide * $prices['guide'])
    + ($option_remake * $prices['remake'])
    + ($option_extra * $prices['extra']);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>マイページ | <?php bloginfo('name'); ?></title>
  <?php wp_head(); ?>
  <style>
    :root {
      --primary: #2563eb;
      --navy: #0f172a;
      --text-muted: #64748b;
      --bg-cream: #fefce8;
      --bg-cream-dark: #fef9c3;
      --border-cream: #fde047;
    }
    
    * { box-sizing: border-box; }
    
    /* マイページ専用の背景スタイル */
    body {
      font-family: 'Noto Sans JP', sans-serif;
      margin: 0;
      padding: 0;
      min-height: 100vh;
    }
    
    .mypage-container {
      max-width: 800px;
      margin: 0 auto;
      background: linear-gradient(180deg, #fefce8 0%, #fef9c3 100%);
      min-height: 100vh;
      padding: 20px;
    }
    
    .mypage-title {
      text-align: center;
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--primary);
      margin-bottom: 30px;
    }
    
    .section {
      margin-bottom: 40px;
    }
    
    .section-header {
      display: flex;
      align-items: center;
      margin-bottom: 16px;
    }
    
    .section-header h3 {
      font-size: 1rem;
      font-weight: 700;
      color: var(--navy);
      margin: 0;
    }
    
    .section-header h3::before {
      content: '■';
      color: var(--navy);
      margin-right: 8px;
    }
    
    .section-note {
      font-size: 0.75rem;
      color: #dc2626;
      margin-left: 12px;
    }
    
    .subsection-title {
      text-align: center;
      font-size: 0.9rem;
      color: var(--text-muted);
      margin-bottom: 12px;
      position: relative;
    }
    
    .subsection-title::before,
    .subsection-title::after {
      content: '';
      position: absolute;
      top: 50%;
      width: 30%;
      height: 1px;
      background: #d1d5db;
    }
    
    .subsection-title::before { left: 0; }
    .subsection-title::after { right: 0; }
    
    /* テーブルスタイル */
    .mypage-table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      border: 1px solid #e5e7eb;
      margin-bottom: 16px;
    }
    
    .mypage-table th {
      background: #f9fafb;
      padding: 10px 12px;
      text-align: left;
      font-size: 0.8rem;
      font-weight: 600;
      color: var(--navy);
      border-bottom: 1px solid #e5e7eb;
    }
    
    .mypage-table td {
      padding: 12px;
      font-size: 0.85rem;
      color: #374151;
      border-bottom: 1px solid #f3f4f6;
      vertical-align: middle;
    }
    
    .mypage-table tbody tr:last-child td {
      border-bottom: none;
    }
    
    .plan-name {
      font-weight: 600;
    }
    
    .plan-content {
      font-size: 0.75rem;
      line-height: 1.5;
      color: var(--text-muted);
    }
    
    .price {
      font-weight: 600;
      text-align: center;
    }
    
    .selection {
      text-align: center;
    }
    
    .selection input[type="radio"] {
      width: 16px;
      height: 16px;
      accent-color: var(--primary);
    }
    
    .qty-input {
      width: 60px;
      padding: 6px 8px;
      border: 1px solid #d1d5db;
      border-radius: 4px;
      text-align: center;
      font-size: 0.85rem;
    }
    
    .qty-input:read-only {
      background: #f3f4f6;
    }
    
    /* 合計金額セクション */
    .total-section {
      text-align: center;
      padding: 20px;
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      margin-bottom: 40px;
    }
    
    .total-label {
      font-size: 1.2rem;
      font-weight: 700;
      color: var(--navy);
      margin-bottom: 8px;
    }
    
    .total-note {
      font-size: 0.75rem;
      color: #dc2626;
      margin-bottom: 16px;
    }
    
    .btn-payment {
      display: inline-block;
      padding: 12px 40px;
      background: #22c55e;
      color: #fff;
      font-size: 1rem;
      font-weight: 600;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background 0.2s;
    }
    
    .btn-payment:hover {
      background: #16a34a;
    }
    
    .payment-note {
      font-size: 0.75rem;
      color: #dc2626;
      margin-top: 8px;
    }
    
    /* フォームフィールド */
    .form-table {
      width: 100%;
      border-collapse: collapse;
    }
    
    .form-table tr {
      border-bottom: 1px solid #f3f4f6;
    }
    
    .form-table tr:last-child {
      border-bottom: none;
    }
    
    .form-table th {
      width: 140px;
      padding: 12px 0;
      text-align: left;
      font-size: 0.85rem;
      font-weight: 500;
      color: var(--navy);
      vertical-align: middle;
    }
    
    .form-table td {
      padding: 8px 0;
    }
    
    .form-input {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #d1d5db;
      border-radius: 4px;
      font-size: 0.9rem;
      background: #fff;
    }
    
    .form-input:read-only {
      background: #f9fafb;
      color: var(--text-muted);
    }
    
    .form-input:focus {
      outline: none;
      border-color: var(--primary);
    }
    
    /* ダウンロードリンク */
    .download-list {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      overflow: hidden;
    }
    
    .download-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 16px 20px;
      border-bottom: 1px solid #f3f4f6;
      text-decoration: none;
      color: var(--navy);
      transition: background 0.2s;
    }
    
    .download-item:last-child {
      border-bottom: none;
    }
    
    .download-item:hover {
      background: #f9fafb;
    }
    
    .download-item i {
      color: #dc2626;
      margin-right: 12px;
    }
    
    .download-item span {
      flex: 1;
      font-size: 0.9rem;
    }
    
    .download-item .arrow {
      color: var(--text-muted);
    }
    
    /* 写真講評 */
    .photo-section {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      padding: 20px;
    }
    
    .photo-note {
      font-size: 0.85rem;
      color: var(--text-muted);
      margin-bottom: 16px;
    }
    
    .photo-link {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 16px 20px;
      background: #f9fafb;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      text-decoration: none;
      color: var(--navy);
      transition: background 0.2s;
    }
    
    .photo-link:hover {
      background: #f3f4f6;
    }
    
    .photo-link i {
      color: var(--primary);
      margin-right: 12px;
    }
    
    /* メッセージ */
    .message {
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 0.9rem;
    }
    
    .message-success {
      background: #dcfce7;
      border: 1px solid #22c55e;
      color: #166534;
    }
    
    .message-error {
      background: #fef2f2;
      border: 1px solid #ef4444;
      color: #dc2626;
    }
    
    /* ヘッダー */
    .mypage-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 16px;
      border-bottom: 1px solid #e5e7eb;
    }
    
    .mypage-header-logo img {
      height: 30px;
    }
    
    .mypage-header-links a {
      font-size: 0.85rem;
      color: var(--text-muted);
      text-decoration: none;
      margin-left: 16px;
    }
    
    .mypage-header-links a:hover {
      color: var(--primary);
    }
    
    .admin-badge {
      display: inline-block;
      background: var(--primary);
      color: #fff;
      font-size: 0.7rem;
      padding: 2px 8px;
      border-radius: 10px;
      margin-left: 8px;
    }
    
    /* ポップアップ */
    .popup-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      z-index: 1000;
      justify-content: center;
      align-items: center;
    }
    
    .popup-overlay.active {
      display: flex;
    }
    
    .popup-content {
      background: #fff;
      padding: 40px 60px;
      border-radius: 12px;
      text-align: center;
      box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }
    
    .popup-content h4 {
      font-size: 1.2rem;
      color: var(--navy);
      margin-bottom: 20px;
    }
    
    .popup-content .btn-close {
      padding: 10px 30px;
      background: var(--primary);
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 0.9rem;
    }
    
    /* レスポンシブ */
    @media (max-width: 768px) {
      .mypage-table th,
      .mypage-table td {
        padding: 8px 6px;
        font-size: 0.75rem;
      }
      
      .form-table th {
        width: 100px;
      }
      
      .mypage-header {
        flex-direction: column;
        gap: 12px;
      }
    }
  </style>
</head>
<body>
<?php wp_body_open(); ?>

<div class="mypage-container">
  
  <!-- ヘッダー -->
  <div class="mypage-header">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="mypage-header-logo">
      <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="<?php bloginfo('name'); ?>">
    </a>
    <div class="mypage-header-links">
      <span><?php echo esc_html($current_user->display_name); ?> 様</span>
      <?php if ($is_admin) : ?>
        <span class="admin-badge">管理者</span>
        <a href="<?php echo esc_url(home_url('/select-user')); ?>"><i class="fas fa-users"></i> ユーザー切替</a>
      <?php endif; ?>
      <a href="<?php echo wp_logout_url(home_url('/')); ?>"><i class="fas fa-sign-out-alt"></i> ログアウト</a>
    </div>
  </div>

  <h1 class="mypage-title">マイページ</h1>
  
  <?php if ($save_message) : ?>
    <div class="message message-success"><?php echo esc_html($save_message); ?></div>
  <?php endif; ?>
  <?php if ($save_error) : ?>
    <div class="message message-error"><?php echo esc_html($save_error); ?></div>
  <?php endif; ?>

  <form action="" method="post" id="mypageForm">
    <?php wp_nonce_field('hrdoc_mypage_action', 'hrdoc_mypage_nonce'); ?>
    <input type="hidden" name="hrdoc_mypage_save" value="1">

    <!-- ■ プラン選択 -->
    <div class="section">
      <div class="section-header">
        <h3>プラン選択</h3>
      </div>
      
      <div class="subsection-title">プラン</div>
      
      <table class="mypage-table">
        <thead>
          <tr>
            <th>プラン名</th>
            <th>プラン名</th>
            <th style="text-align:center;">価格（税込）</th>
            <th style="text-align:center;">選択</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="plan-name">基本プラン</td>
            <td class="plan-content">
              ・月1 PDF（10〜15ページ）<br>
              ・写真講評 3枚<br>
              （改善ポイント3つ）
            </td>
            <td class="price">¥2,178</td>
            <td class="selection">
              <input type="radio" name="plan_select" value="basic" <?php checked($current_plan, 'basic'); ?>>
            </td>
          </tr>
          <tr>
            <td class="plan-name">プレミアムプラン</td>
            <td class="plan-content">
              ・月1 PDF（10〜15ページ）<br>
              ・写真講評 10枚（改善ポイント3つ）<br>
              ・PDFファイルアーカイブ見放題
            </td>
            <td class="price">¥5,478</td>
            <td class="selection">
              <input type="radio" name="plan_select" value="premium" <?php checked($current_plan, 'premium'); ?>>
            </td>
          </tr>
        </tbody>
      </table>
      
      <div class="subsection-title">オプション</div>
      <p style="text-align:center; font-size:0.75rem; color:var(--text-muted); margin-top:-8px; margin-bottom:12px;">必要なときだけ追加できる "軽い課金"</p>
      
      <table class="mypage-table">
        <thead>
          <tr>
            <th>オプション名</th>
            <th>内容</th>
            <th style="text-align:center;">価格（税込）</th>
            <th style="text-align:center;">セット数</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="plan-name">見える化ガイド（赤線）</td>
            <td class="plan-content">構図・余白・焦点を赤線で示す</td>
            <td class="price">¥550</td>
            <td class="selection">
              <input type="number" name="option_guide" class="qty-input" value="<?php echo esc_attr($option_guide); ?>" min="0" max="99">
            </td>
          </tr>
          <tr>
            <td class="plan-name">構図リメイク<br>（トリミング2案）</td>
            <td class="plan-content">あなたの写真を"プロ構図"に組み直し</td>
            <td class="price">¥1,100</td>
            <td class="selection">
              <input type="number" name="option_remake" class="qty-input" value="<?php echo esc_attr($option_remake); ?>" min="0" max="99">
            </td>
          </tr>
          <tr>
            <td class="plan-name">追加講評（1枚）</td>
            <td class="plan-content">もっと見てほしい人向け</td>
            <td class="price">¥330</td>
            <td class="selection">
              <input type="number" name="option_extra" class="qty-input" value="<?php echo esc_attr($option_extra); ?>" min="0" max="99">
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- 合計金額 -->
    <div class="total-section">
      <div class="total-label">合計金額（税込）<span id="totalAmount">¥<?php echo number_format($initial_total); ?></span></div>
  
      <button type="button" class="btn-payment" id="paymentBtn">決済</button>

    </div>

    <!-- ■ アカウント情報 -->
    <div class="section">
      <div class="section-header">
        <h3>アカウント情報</h3>
    
      </div>
      
      <table class="form-table">
        <tr>
          <th>氏名</th>
          <td><input type="text" class="form-input" value="<?php echo esc_attr($user_name); ?>" readonly></td>
        </tr>
        <tr>
          <th>メールアドレス（ID）</th>
          <td><input type="email" class="form-input" value="<?php echo esc_attr($user_email); ?>" readonly></td>
        </tr>
        <tr>
          <th>電話番号（任意）</th>
          <td><input type="tel" class="form-input" value="<?php echo esc_attr($user_phone); ?>" readonly></td>
        </tr>
        <tr>
          <th>住所（任意）</th>
          <td><input type="text" class="form-input" value="<?php echo esc_attr($user_address); ?>" readonly></td>
        </tr>
        <tr>
          <th>パスワード</th>
          <td><input type="password" class="form-input" value="********" readonly></td>
        </tr>
      </table>
    </div>

    <!-- ■ 申込履歴 -->
    <div class="section">
      <div class="section-header">
        <h3>申込履歴（24時間以内に反映）</h3>
      </div>
      
      <table class="form-table">
        <tr>
          <th>申込日</th>
          <td><input type="text" name="order_date" class="form-input" value="<?php echo esc_attr($order_date); ?>" <?php echo !$is_admin ? 'readonly' : ''; ?>></td>
        </tr>
        <tr>
          <th>プラン名</th>
          <td><input type="text" name="order_plan_name" class="form-input" value="<?php echo esc_attr($order_plan_name); ?>" <?php echo !$is_admin ? 'readonly' : ''; ?>></td>
        </tr>
        <tr>
          <th>決済金額</th>
          <td><input type="text" name="order_amount" class="form-input" value="<?php echo esc_attr($order_amount); ?>" <?php echo !$is_admin ? 'readonly' : ''; ?>></td>
        </tr>
        <tr>
          <th>決済方法</th>
          <td><input type="text" name="order_payment_method" class="form-input" value="<?php echo esc_attr($order_payment_method); ?>" <?php echo !$is_admin ? 'readonly' : ''; ?>></td>
        </tr>
        <tr>
          <th>ステータス仕様</th>
          <td><input type="text" name="order_status" class="form-input" value="<?php echo esc_attr($order_status); ?>" <?php echo !$is_admin ? 'readonly' : ''; ?>></td>
        </tr>
      </table>
    </div>

    <!-- ■ 請求情報 -->
    <div class="section">
      <div class="section-header">
        <h3>請求情報</h3>
        
      </div>
      
      <table class="form-table">
        <tr>
          <th>今月の請求額</th>
          <td><input type="text" name="billing_current" class="form-input" value="<?php echo esc_attr($billing_current); ?>" <?php echo !$is_admin ? 'readonly' : ''; ?>></td>
        </tr>
        <tr>
          <th>支払い履歴</th>
          <td><input type="text" name="billing_history" class="form-input" value="<?php echo esc_attr($billing_history); ?>" <?php echo !$is_admin ? 'readonly' : ''; ?>></td>
        </tr>
        <tr>
          <th>次回更新日使用</th>
          <td><input type="text" name="billing_next_date" class="form-input" value="<?php echo esc_attr($billing_next_date); ?>" <?php echo !$is_admin ? 'readonly' : ''; ?>></td>
        </tr>
        <tr>
          <th>決済方法</th>
          <td><input type="text" name="billing_method" class="form-input" value="<?php echo esc_attr($billing_method); ?>" <?php echo !$is_admin ? 'readonly' : ''; ?>></td>
        </tr>
      </table>
    </div>

    <?php if ($is_admin) : ?>
      <div style="text-align: center; margin-bottom: 40px;">
        <button type="submit" style="padding: 12px 40px; background: var(--primary); color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem;">
          <i class="fas fa-save"></i> 保存する
        </button>
      </div>
    <?php endif; ?>

  </form>

  <!-- ■ ダウンロード資料 -->
  <div class="section">
    <div class="section-header">
      <h3>ダウンロード資料</h3>
    </div>
    
    <div class="download-list">
      <?php
      // PDFファイルのパス
      $guide_pdf = get_template_directory_uri() . '/pdfs/guide.pdf';
      $bonus_pdf = get_template_directory_uri() . '/pdfs/bonus.pdf';
      
      // ファイルが存在するかチェック（サーバー側のパス）
      $guide_pdf_path = get_template_directory() . '/pdfs/guide.pdf';
      $bonus_pdf_path = get_template_directory() . '/pdfs/bonus.pdf';
      ?>
      <a href="<?php echo esc_url($guide_pdf); ?>" class="download-item" 
         target="_blank" 
         download="利用ガイド.pdf"
         <?php if (!file_exists($guide_pdf_path)) : ?>
         onclick="alert('PDFファイルを準備中です。\nファイル: guide.pdf\n配置先: wp-content/themes/hrdocsuite/pdfs/'); return false;"
         <?php endif; ?>>
        <i class="fas fa-file-pdf"></i>
        <span>利用ガイドPDFダウンロード</span>
        <span class="arrow">→</span>
      </a>
      <a href="<?php echo esc_url($bonus_pdf); ?>" class="download-item" 
         target="_blank" 
         download="特典.pdf"
         <?php if (!file_exists($bonus_pdf_path)) : ?>
         onclick="alert('PDFファイルを準備中です。\nファイル: bonus.pdf\n配置先: wp-content/themes/hrdocsuite/pdfs/'); return false;"
         <?php endif; ?>>
        <i class="fas fa-file-pdf"></i>
        <span>特典PDFダウンロード</span>
        <span class="arrow">→</span>
      </a>
    </div>
  </div>

  <!-- ■ 写真講評 -->
  <div class="section">
    <div class="section-header">
      <h3>写真講評</h3>
    </div>
    
    <div class="photo-section">
      <p class="photo-note">（下記のリンクからメールにて写真の転送お願いします）</p>
      <a href="mailto:photo@example.com?subject=写真講評依頼" class="photo-link">
        <span><i class="fas fa-camera"></i> 写真を送る</span>
        <span class="arrow">→</span>
      </a>

    </div>
  </div>

</div>

<!-- 決済完了ポップアップ -->
<div class="popup-overlay" id="paymentPopup">
  <div class="popup-content">
    <h4>決済が完了しました</h4>
    <button type="button" class="btn-close" onclick="closePopup()">閉じる</button>
  </div>
</div>

<script>
// 合計金額計算
document.addEventListener('DOMContentLoaded', function() {
  const planRadios = document.querySelectorAll('input[name="plan_select"]');
  const optionInputs = document.querySelectorAll('.qty-input');
  const totalAmount = document.getElementById('totalAmount');
  
  const prices = {
    basic: <?php echo $prices['basic']; ?>,
    premium: <?php echo $prices['premium']; ?>,
    guide: <?php echo $prices['guide']; ?>,
    remake: <?php echo $prices['remake']; ?>,
    extra: <?php echo $prices['extra']; ?>
  };
  
  function calculateTotal() {
    let total = 0;
    planRadios.forEach(radio => {
      if (radio.checked) total += prices[radio.value];
    });
    
    const guide = parseInt(document.querySelector('input[name="option_guide"]').value) || 0;
    const remake = parseInt(document.querySelector('input[name="option_remake"]').value) || 0;
    const extra = parseInt(document.querySelector('input[name="option_extra"]').value) || 0;
    
    total += guide * prices.guide + remake * prices.remake + extra * prices.extra;
    totalAmount.textContent = '¥' + total.toLocaleString();
  }
  
  planRadios.forEach(radio => radio.addEventListener('change', calculateTotal));
  optionInputs.forEach(input => input.addEventListener('input', calculateTotal));
});

// 決済ポップアップ
document.getElementById('paymentBtn').addEventListener('click', function() {
  document.getElementById('paymentPopup').classList.add('active');
});

function closePopup() {
  document.getElementById('paymentPopup').classList.remove('active');
}

document.getElementById('paymentPopup').addEventListener('click', function(e) {
  if (e.target === this) closePopup();
});
</script>

<?php wp_footer(); ?>

</body>
</html>
