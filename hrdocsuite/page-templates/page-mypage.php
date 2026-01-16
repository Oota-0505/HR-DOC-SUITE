<?php
/**
 * Template Name: マイページ
 * Description: 会員マイページ（簡易確認用UI - 手動管理前提）
 *
 * @package HR_DOC_SUITE
 * @since 2.0.0
 * 
 * セクション構成:
 * 1. アカウント情報（ユーザー編集可能）
 * 2. ステータス表示
 * 3. 申込履歴（閲覧のみ）
 * 4. 請求情報（閲覧のみ）
 * 5. ダウンロード資料
 * 6. サポート機能
 * 7. 退会・アカウント削除
 */

// 直接アクセス禁止
if (!defined('ABSPATH')) {
    exit;
}

// ログインチェック
if (!is_user_logged_in()) {
    $login_page = get_page_by_path('login');
    $login_url = $login_page ? get_permalink($login_page) : wp_login_url(home_url('/mypage/'));
    wp_safe_redirect($login_url);
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

// ステータス情報
$user_status = $custom_user->status ?? '受付済';

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

// プラン情報（ダウンロード制御用）
$user_plan = $custom_user->plan_type ?? 'basic';

// 保存処理
$save_message = '';
$save_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['hrdoc_mypage_nonce']) || !wp_verify_nonce($_POST['hrdoc_mypage_nonce'], 'hrdoc_mypage_action')) {
        $save_error = '不正なリクエストです。';
    } else {
        // アカウント情報保存
        if (isset($_POST['hrdoc_account_save'])) {
            $data = array(
                'name' => sanitize_text_field($_POST['user_name'] ?? ''),
                'email' => sanitize_email($_POST['user_email'] ?? ''),
                'phone' => sanitize_text_field($_POST['user_phone'] ?? ''),
                'address' => sanitize_text_field($_POST['user_address'] ?? ''),
                'updated_at' => current_time('mysql'),
            );
            
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
                $save_message = 'アカウント情報を保存しました。';
                $user_name = $data['name'];
                $user_email = $data['email'];
                $user_phone = $data['phone'];
                $user_address = $data['address'];
            } else {
                $save_error = '保存に失敗しました（DBエラー: ' . $wpdb->last_error . '）';
            }
        }
        
        // 管理者専用：申込履歴・請求情報・ステータス保存
        if ($is_admin && isset($_POST['hrdoc_admin_save'])) {
            $data = array(
                'status' => sanitize_text_field($_POST['user_status'] ?? '受付済'),
                'order_date' => sanitize_text_field($_POST['order_date'] ?? ''),
                'order_plan_name' => sanitize_text_field($_POST['order_plan_name'] ?? ''),
                'order_amount' => sanitize_text_field($_POST['order_amount'] ?? ''),
                'order_payment_method' => sanitize_text_field($_POST['order_payment_method'] ?? ''),
                'order_status' => sanitize_text_field($_POST['order_status'] ?? ''),
                'billing_current' => sanitize_text_field($_POST['billing_current'] ?? ''),
                'billing_history' => sanitize_text_field($_POST['billing_history'] ?? ''),
                'billing_next_date' => sanitize_text_field($_POST['billing_next_date'] ?? ''),
                'updated_at' => current_time('mysql'),
            );
            
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
                $save_message = '管理者情報を保存しました。';
                $user_status = $data['status'];
                $order_date = $data['order_date'];
                $order_plan_name = $data['order_plan_name'];
                $order_amount = $data['order_amount'];
                $order_payment_method = $data['order_payment_method'];
                $order_status = $data['order_status'];
                $billing_current = $data['billing_current'];
                $billing_history = $data['billing_history'];
                $billing_next_date = $data['billing_next_date'];
            } else {
                $save_error = '保存に失敗しました（DBエラー: ' . $wpdb->last_error . '）';
            }
        }
    }
}

// ステータス一覧
$status_list = array(
    '受付済' => array('color' => '#6b7280', 'icon' => 'clock'),
    '決済確認済' => array('color' => '#3b82f6', 'icon' => 'check-circle'),
    '提供中' => array('color' => '#22c55e', 'icon' => 'play-circle'),
    '解約申請中' => array('color' => '#f59e0b', 'icon' => 'exclamation-circle'),
    '解約済' => array('color' => '#ef4444', 'icon' => 'times-circle'),
);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="mypage-html">
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>マイページ | <?php bloginfo('name'); ?></title>
  <?php wp_head(); ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    /* ============================================
       マイページ専用CSS - Scoped Styles
       プレフィックス: .mypage- を使用
       詳細度を高めて競合を防止
    ============================================ */
    
    /* === HTML & Body Reset for Mypage === */
    html.mypage-html,
    body.page-mypage {
      margin: 0 !important;
      padding: 0 !important;
      width: 100% !important;
      min-width: 100% !important;
      overflow-x: hidden !important;
      background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #f0fdfa 100%) !important;
    }
    
    body.page-mypage {
      position: relative !important;
    }
    
    /* === CSS Variables === */
    .mypage-container {
      width: 100%;
      min-width: 100%;
      --mp-primary: #1e40af;
      --mp-primary-light: #3b82f6;
      --mp-primary-dark: #1e3a8a;
      --mp-secondary: #0f172a;
      --mp-accent: #06b6d4;
      --mp-success: #22c55e;
      --mp-warning: #f59e0b;
      --mp-danger: #ef4444;
      --mp-text: #1f2937;
      --mp-text-muted: #6b7280;
      --mp-text-light: #9ca3af;
      --mp-bg: #f8fafc;
      --mp-bg-card: #ffffff;
      --mp-border: #e5e7eb;
      --mp-border-light: #f3f4f6;
      --mp-shadow: 0 1px 3px rgba(0,0,0,0.1);
      --mp-shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
      --mp-shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
      --mp-radius: 12px;
      --mp-radius-sm: 8px;
      --mp-radius-lg: 16px;
    }
    
    /* === Reset & Base === */
    .mypage-container,
    .mypage-container *,
    .mypage-container *::before,
    .mypage-container *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    
    .mypage-container {
      font-family: 'Noto Sans JP', -apple-system, BlinkMacSystemFont, sans-serif;
      background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #f0fdfa 100%);
      min-height: 100vh;
      color: var(--mp-text);
      line-height: 1.7;
    }
    
    /* === Typography - 強化セレクタ === */
    .mypage-container .mypage-h1,
    .mypage-container h1.mypage-h1 {
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--mp-secondary);
      margin: 0;
      padding: 0;
    }
    
    .mypage-container .mypage-h2,
    .mypage-container h2.mypage-h2 {
      font-size: 1.125rem;
      font-weight: 700;
      color: var(--mp-secondary);
      margin: 0 0 1rem 0;
      padding: 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .mypage-container .mypage-h2::before {
      content: '';
      display: inline-block;
      width: 4px;
      height: 1.25rem;
      background: linear-gradient(180deg, var(--mp-primary) 0%, var(--mp-accent) 100%);
      border-radius: 2px;
    }
    
    .mypage-container .mypage-h3,
    .mypage-container h3.mypage-h3 {
      font-size: 0.9375rem;
      font-weight: 600;
      color: var(--mp-text);
      margin: 0 0 0.75rem 0;
      padding: 0;
    }
    
    /* === Links - 強化セレクタ === */
    .mypage-container .mypage-link,
    .mypage-container a.mypage-link {
      color: var(--mp-primary);
      text-decoration: none;
      transition: color 0.2s ease;
    }
    
    .mypage-container .mypage-link:hover,
    .mypage-container a.mypage-link:hover {
      color: var(--mp-primary-light);
      text-decoration: underline;
    }
    
    /* === Layout === */
    .mypage-wrapper {
      max-width: 900px;
      margin: 0 auto;
      padding: 0 1rem;
    }
    
    /* === Header === */
    .mypage-header {
      background: var(--mp-bg-card);
      border-bottom: 1px solid var(--mp-border);
      padding: 1rem 0;
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: var(--mp-shadow);
    }
    
    .mypage-header-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      flex-wrap: wrap;
    }
    
    .mypage-header-logo img {
      height: 32px;
      width: auto;
    }
    
    .mypage-header-search {
      flex: 1;
      max-width: 300px;
      position: relative;
    }
    
    .mypage-container .mypage-search-input {
      width: 100%;
      padding: 0.625rem 1rem 0.625rem 2.5rem;
      border: 1px solid var(--mp-border);
      border-radius: 100px;
      font-size: 0.875rem;
      background: var(--mp-bg);
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    
    .mypage-container .mypage-search-input:focus {
      outline: none;
      border-color: var(--mp-primary-light);
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .mypage-header-search i {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--mp-text-muted);
      font-size: 0.875rem;
    }
    
    .mypage-header-nav {
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    
    .mypage-header-user {
      font-size: 0.875rem;
      color: var(--mp-text);
      font-weight: 500;
    }
    
    .mypage-container .mypage-admin-badge {
      display: inline-flex;
      align-items: center;
      background: linear-gradient(135deg, var(--mp-primary) 0%, var(--mp-accent) 100%);
      color: #fff;
      font-size: 0.6875rem;
      font-weight: 600;
      padding: 0.25rem 0.625rem;
      border-radius: 100px;
      margin-left: 0.5rem;
    }
    
    .mypage-container .mypage-header-link,
    .mypage-container a.mypage-header-link {
      display: inline-flex;
      align-items: center;
      gap: 0.375rem;
      font-size: 0.8125rem;
      color: var(--mp-text-muted);
      text-decoration: none;
      padding: 0.5rem 0.75rem;
      border-radius: var(--mp-radius-sm);
      transition: background 0.2s, color 0.2s;
    }
    
    .mypage-container .mypage-header-link:hover,
    .mypage-container a.mypage-header-link:hover {
      background: var(--mp-bg);
      color: var(--mp-primary);
    }
    
    /* === Main Content === */
    .mypage-main {
      padding: 2rem 0 4rem;
    }
    
    .mypage-title-area {
      text-align: center;
      margin-bottom: 2rem;
    }
    
    .mypage-title-area .mypage-h1 {
      margin-bottom: 0.5rem;
    }
    
    .mypage-title-subtitle {
      font-size: 0.875rem;
      color: var(--mp-text-muted);
    }
    
    /* === Messages === */
    .mypage-container .mypage-message {
      padding: 1rem 1.25rem;
      border-radius: var(--mp-radius-sm);
      margin-bottom: 1.5rem;
      font-size: 0.875rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }
    
    .mypage-container .mypage-message-success {
      background: #dcfce7;
      border: 1px solid #86efac;
      color: #166534;
    }
    
    .mypage-container .mypage-message-error {
      background: #fef2f2;
      border: 1px solid #fecaca;
      color: #dc2626;
    }
    
    /* === Section Card === */
    .mypage-section {
      background: var(--mp-bg-card);
      border-radius: var(--mp-radius-lg);
      box-shadow: var(--mp-shadow);
      margin-bottom: 1.5rem;
      overflow: hidden;
    }
    
    .mypage-section-header {
      padding: 1.25rem 1.5rem;
      border-bottom: 1px solid var(--mp-border-light);
      background: linear-gradient(180deg, #fafafa 0%, #ffffff 100%);
    }
    
    .mypage-section-body {
      padding: 1.5rem;
    }
    
    /* === Status Display === */
    .mypage-status-display {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.75rem;
      padding: 1.5rem;
      background: linear-gradient(135deg, #f0f9ff 0%, #ecfeff 100%);
      border-radius: var(--mp-radius);
      margin-bottom: 1rem;
    }
    
    .mypage-status-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.75rem 1.5rem;
      border-radius: 100px;
      font-size: 1rem;
      font-weight: 600;
      color: #fff;
    }
    
    .mypage-status-list {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 0.5rem;
      margin-top: 1rem;
    }
    
    .mypage-status-item {
      display: inline-flex;
      align-items: center;
      gap: 0.375rem;
      font-size: 0.75rem;
      color: var(--mp-text-muted);
      padding: 0.375rem 0.75rem;
      background: var(--mp-bg);
      border-radius: 100px;
    }
    
    .mypage-status-item.is-active {
      background: var(--mp-primary);
      color: #fff;
    }
    
    /* === Form Elements === */
    .mypage-form-group {
      margin-bottom: 1.25rem;
    }
    
    .mypage-form-group:last-child {
      margin-bottom: 0;
    }
    
    .mypage-container .mypage-form-label {
      display: block;
      font-size: 0.8125rem;
      font-weight: 600;
      color: var(--mp-text);
      margin-bottom: 0.5rem;
    }
    
    .mypage-form-label .mypage-label-optional {
      font-weight: 400;
      color: var(--mp-text-muted);
      font-size: 0.75rem;
      margin-left: 0.5rem;
    }
    
    .mypage-container .mypage-form-input,
    .mypage-container input.mypage-form-input,
    .mypage-container textarea.mypage-form-input,
    .mypage-container select.mypage-form-input {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 1px solid var(--mp-border);
      border-radius: var(--mp-radius-sm);
      font-size: 0.9375rem;
      font-family: inherit;
      background: var(--mp-bg-card);
      transition: border-color 0.2s, box-shadow 0.2s;
      color: var(--mp-text);
    }
    
    .mypage-container .mypage-form-input:focus {
      outline: none;
      border-color: var(--mp-primary-light);
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .mypage-container .mypage-form-input:read-only {
      background: var(--mp-bg);
      color: var(--mp-text-muted);
      cursor: not-allowed;
    }
    
    .mypage-container textarea.mypage-form-input {
      min-height: 120px;
      resize: vertical;
    }
    
    .mypage-container select.mypage-form-input {
      cursor: pointer;
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E");
      background-position: right 0.75rem center;
      background-repeat: no-repeat;
      background-size: 1.25rem;
      padding-right: 2.5rem;
    }
    
    /* === Form Row (2 columns) === */
    .mypage-form-row {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem;
    }
    
    @media (max-width: 640px) {
      .mypage-form-row {
        grid-template-columns: 1fr;
      }
    }
    
    /* === Buttons === */
    .mypage-container .mypage-btn,
    .mypage-container button.mypage-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      padding: 0.75rem 1.5rem;
      border: none;
      border-radius: var(--mp-radius-sm);
      font-size: 0.9375rem;
      font-weight: 600;
      font-family: inherit;
      cursor: pointer;
      transition: all 0.2s ease;
      text-decoration: none;
    }
    
    .mypage-container .mypage-btn-primary,
    .mypage-container button.mypage-btn-primary {
      background: linear-gradient(135deg, var(--mp-primary) 0%, var(--mp-primary-light) 100%);
      color: #fff;
      box-shadow: 0 2px 4px rgba(30, 64, 175, 0.3);
    }
    
    .mypage-container .mypage-btn-primary:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 8px rgba(30, 64, 175, 0.4);
    }
    
    .mypage-container .mypage-btn-secondary {
      background: var(--mp-bg);
      color: var(--mp-text);
      border: 1px solid var(--mp-border);
    }
    
    .mypage-container .mypage-btn-secondary:hover {
      background: var(--mp-border-light);
    }
    
    .mypage-container .mypage-btn-success {
      background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
      color: #fff;
    }
    
    .mypage-container .mypage-btn-danger {
      background: #fff;
      color: var(--mp-danger);
      border: 1px solid var(--mp-danger);
    }
    
    .mypage-container .mypage-btn-danger:hover {
      background: #fef2f2;
    }
    
    .mypage-container .mypage-btn-line {
      background: #06c755;
      color: #fff;
    }
    
    .mypage-container .mypage-btn-line:hover {
      background: #05b04a;
    }
    
    .mypage-btn-group {
      display: flex;
      gap: 0.75rem;
      margin-top: 1.5rem;
    }
    
    /* === Info Table === */
    .mypage-info-table {
      width: 100%;
      border-collapse: collapse;
    }
    
    .mypage-info-table tr {
      border-bottom: 1px solid var(--mp-border-light);
    }
    
    .mypage-info-table tr:last-child {
      border-bottom: none;
    }
    
    .mypage-container .mypage-info-table th {
      width: 140px;
      padding: 1rem 0;
      text-align: left;
      font-size: 0.8125rem;
      font-weight: 600;
      color: var(--mp-text-muted);
      vertical-align: middle;
    }
    
    .mypage-container .mypage-info-table td {
      padding: 1rem 0;
      font-size: 0.9375rem;
      color: var(--mp-text);
    }
    
    /* === Download List === */
    .mypage-download-list {
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
    }
    
    .mypage-container .mypage-download-item,
    .mypage-container a.mypage-download-item {
      display: flex;
      align-items: center;
      padding: 1rem 1.25rem;
      background: var(--mp-bg);
      border: 1px solid var(--mp-border);
      border-radius: var(--mp-radius-sm);
      text-decoration: none;
      color: var(--mp-text);
      transition: all 0.2s ease;
    }
    
    .mypage-container .mypage-download-item:hover,
    .mypage-container a.mypage-download-item:hover {
      background: #fff;
      border-color: var(--mp-primary-light);
      box-shadow: var(--mp-shadow-md);
      transform: translateY(-2px);
    }
    
    .mypage-download-icon {
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #fee2e2;
      color: #dc2626;
      border-radius: var(--mp-radius-sm);
      margin-right: 1rem;
      font-size: 1.25rem;
    }
    
    .mypage-download-info {
      flex: 1;
    }
    
    .mypage-download-name {
      font-weight: 600;
      font-size: 0.9375rem;
      margin-bottom: 0.25rem;
    }
    
    .mypage-download-meta {
      font-size: 0.75rem;
      color: var(--mp-text-muted);
    }
    
    .mypage-download-arrow {
      color: var(--mp-text-muted);
      font-size: 1rem;
    }
    
    .mypage-download-premium {
      display: inline-flex;
      align-items: center;
      gap: 0.25rem;
      font-size: 0.6875rem;
      color: #f59e0b;
      background: #fef3c7;
      padding: 0.125rem 0.5rem;
      border-radius: 100px;
      margin-left: 0.5rem;
    }
    
    /* ロックされたアイテムのスタイル */
    .mypage-download-item.is-locked {
      cursor: not-allowed;
      border-color: #f3f4f6 !important;
      background: #fafafa !important;
      opacity: 0.8;
    }
    
    .mypage-download-item.is-locked .mypage-download-name {
      color: #9ca3af;
    }
    
    .mypage-download-item.is-locked .mypage-download-meta {
      color: #d1d5db;
    }
    
    .mypage-download-item.is-locked .mypage-download-arrow {
      color: #d1d5db;
    }
    
    /* === Support Section === */
    .mypage-support-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1.5rem;
    }
    
    @media (max-width: 640px) {
      .mypage-support-grid {
        grid-template-columns: 1fr;
      }
    }
    
    .mypage-support-card {
      padding: 1.5rem;
      background: var(--mp-bg);
      border-radius: var(--mp-radius);
      text-align: center;
    }
    
    .mypage-support-icon {
      width: 60px;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, var(--mp-primary) 0%, var(--mp-accent) 100%);
      color: #fff;
      border-radius: 50%;
      margin: 0 auto 1rem;
      font-size: 1.5rem;
    }
    
    .mypage-support-card .mypage-h3 {
      margin-bottom: 0.5rem;
    }
    
    .mypage-support-desc {
      font-size: 0.8125rem;
      color: var(--mp-text-muted);
      margin-bottom: 1rem;
    }
    
    /* === Checkbox Group === */
    .mypage-checkbox-group {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      margin-bottom: 1rem;
    }
    
    .mypage-container .mypage-checkbox-label {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.75rem 1rem;
      background: var(--mp-bg);
      border: 1px solid var(--mp-border);
      border-radius: var(--mp-radius-sm);
      cursor: pointer;
      transition: border-color 0.2s;
    }
    
    .mypage-container .mypage-checkbox-label:hover {
      border-color: var(--mp-primary-light);
    }
    
    .mypage-container .mypage-checkbox-label input[type="checkbox"] {
      width: 18px;
      height: 18px;
      accent-color: var(--mp-primary);
    }
    
    /* === Delete Section === */
    .mypage-delete-section {
      background: #fef2f2;
      border: 1px solid #fecaca;
      border-radius: var(--mp-radius);
      padding: 1.5rem;
    }
    
    .mypage-delete-warning {
      display: flex;
      align-items: flex-start;
      gap: 1rem;
      margin-bottom: 1rem;
    }
    
    .mypage-delete-warning i {
      color: var(--mp-danger);
      font-size: 1.25rem;
      margin-top: 0.125rem;
    }
    
    .mypage-delete-warning-text {
      font-size: 0.875rem;
      color: #991b1b;
      line-height: 1.6;
    }
    
    /* === Footer Notice === */
    .mypage-footer-notice {
      text-align: center;
      padding: 2rem;
      font-size: 0.8125rem;
      color: var(--mp-text-muted);
      background: rgba(255,255,255,0.5);
      border-radius: var(--mp-radius);
      margin-top: 2rem;
    }
    
    .mypage-footer-notice p {
      margin: 0;
    }
    
    /* === Responsive === */
    @media (max-width: 768px) {
      .mypage-header-inner {
        flex-direction: column;
        align-items: stretch;
      }
      
      .mypage-header-search {
        max-width: none;
        order: 3;
      }
      
      .mypage-header-nav {
        justify-content: space-between;
      }
      
      .mypage-section-body {
        padding: 1rem;
      }
      
      .mypage-container .mypage-info-table th {
        width: 100px;
        font-size: 0.75rem;
      }
      
      .mypage-container .mypage-info-table td {
        font-size: 0.875rem;
      }
    }
    
    /* === Password Change Modal === */
    /* === Modal Overlay - 詳細度を高めて競合を防止 === */
    .mypage-container .mypage-modal-overlay,
    body.page-mypage .mypage-modal-overlay {
      display: none !important;
      position: fixed !important;
      top: 0 !important;
      left: 0 !important;
      width: 100% !important;
      height: 100% !important;
      background: rgba(0, 0, 0, 0.6) !important;
      background-color: rgba(0, 0, 0, 0.6) !important;
      z-index: 10000 !important;
      justify-content: center !important;
      align-items: center !important;
      backdrop-filter: blur(4px) !important;
      -webkit-backdrop-filter: blur(4px) !important;
    }
    
    .mypage-container .mypage-modal-overlay.is-active,
    body.page-mypage .mypage-modal-overlay.is-active {
      display: flex !important;
    }
    
    /* === Modal - 詳細度を高めて競合を防止 === */
    .mypage-container .mypage-modal,
    body.page-mypage .mypage-modal {
      background: var(--mp-bg-card) !important;
      background-color: #ffffff !important;
      border-radius: var(--mp-radius-lg) !important;
      padding: 2rem !important;
      max-width: 400px !important;
      width: 90% !important;
      box-shadow: var(--mp-shadow-lg) !important;
      position: relative !important;
      z-index: 10001 !important;
    }
    
    /* === Modal Header & Close - 詳細度を高めて競合を防止 === */
    .mypage-container .mypage-modal-header,
    body.page-mypage .mypage-modal-header {
      display: flex !important;
      align-items: center !important;
      justify-content: space-between !important;
      margin-bottom: 1.5rem !important;
    }
    
    .mypage-container .mypage-modal-close,
    body.page-mypage .mypage-modal-close {
      background: none !important;
      border: none !important;
      font-size: 1.25rem !important;
      color: var(--mp-text-muted) !important;
      cursor: pointer !important;
      padding: 0.25rem !important;
    }
    
    .mypage-container .mypage-modal-close:hover,
    body.page-mypage .mypage-modal-close:hover {
      color: var(--mp-text) !important;
    }
    
    /* === Admin Section === */
    .mypage-admin-section {
      background: linear-gradient(135deg, #fef3c7 0%, #fef9c3 100%);
      border: 2px dashed #f59e0b;
    }
    
    .mypage-admin-badge-large {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      background: #f59e0b;
      color: #fff;
      font-size: 0.75rem;
      font-weight: 600;
      padding: 0.375rem 0.75rem;
      border-radius: 100px;
      margin-left: 0.75rem;
    }
  </style>
</head>
<body class="page-mypage">
<?php wp_body_open(); ?>

<div class="mypage-container">
  
  <!-- ヘッダー -->
  <header class="mypage-header">
    <div class="mypage-wrapper">
      <div class="mypage-header-inner">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="mypage-header-logo">
          <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="<?php bloginfo('name'); ?>">
        </a>
        
        <div class="mypage-header-search">
          <i class="fas fa-search"></i>
          <input type="text" class="mypage-search-input" placeholder="資料を検索..." id="searchInput">
        </div>
        
        <nav class="mypage-header-nav">
          <span class="mypage-header-user">
            <?php echo esc_html($current_user->display_name); ?> 様
            <?php if ($is_admin) : ?>
              <span class="mypage-admin-badge"><i class="fas fa-crown"></i> 管理者</span>
            <?php endif; ?>
          </span>
          <?php if ($is_admin) : ?>
            <a href="<?php echo esc_url(home_url('/select-user')); ?>" class="mypage-header-link">
              <i class="fas fa-users"></i> ユーザー切替
            </a>
          <?php endif; ?>
          <a href="<?php echo wp_logout_url(home_url('/')); ?>" class="mypage-header-link">
            <i class="fas fa-sign-out-alt"></i> ログアウト
          </a>
        </nav>
      </div>
    </div>
  </header>

  <!-- メインコンテンツ -->
  <main class="mypage-main">
    <div class="mypage-wrapper">
      
      <!-- タイトル -->
      <div class="mypage-title-area">
        <h1 class="mypage-h1">マイページ</h1>
        <p class="mypage-title-subtitle">ご契約状況と各種サービスをご確認いただけます</p>
      </div>
      
      <!-- メッセージ表示 -->
      <?php if ($save_message) : ?>
        <div class="mypage-message mypage-message-success">
          <i class="fas fa-check-circle"></i>
          <?php echo esc_html($save_message); ?>
        </div>
      <?php endif; ?>
      <?php if ($save_error) : ?>
        <div class="mypage-message mypage-message-error">
          <i class="fas fa-exclamation-circle"></i>
          <?php echo esc_html($save_error); ?>
        </div>
      <?php endif; ?>
      
      <!-- 1. ステータス表示 -->
      <section class="mypage-section">
        <div class="mypage-section-header">
          <h2 class="mypage-h2">ご契約ステータス</h2>
        </div>
        <div class="mypage-section-body">
          <div class="mypage-status-display">
            <?php 
            $current_status_info = $status_list[$user_status] ?? $status_list['受付済'];
            ?>
            <span class="mypage-status-badge" style="background-color: <?php echo esc_attr($current_status_info['color']); ?>">
              <i class="fas fa-<?php echo esc_attr($current_status_info['icon']); ?>"></i>
              <?php echo esc_html($user_status); ?>
            </span>
          </div>
          <div class="mypage-status-list">
            <?php foreach ($status_list as $status_name => $status_info) : ?>
              <span class="mypage-status-item <?php echo ($status_name === $user_status) ? 'is-active' : ''; ?>">
                <i class="fas fa-<?php echo esc_attr($status_info['icon']); ?>"></i>
                <?php echo esc_html($status_name); ?>
              </span>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
      
      <!-- 2. アカウント情報 -->
      <section class="mypage-section">
        <div class="mypage-section-header">
          <h2 class="mypage-h2">アカウント情報</h2>
        </div>
        <div class="mypage-section-body">
          <form action="" method="post">
            <?php wp_nonce_field('hrdoc_mypage_action', 'hrdoc_mypage_nonce'); ?>
            <input type="hidden" name="hrdoc_account_save" value="1">
            
            <div class="mypage-form-row">
              <div class="mypage-form-group">
                <label class="mypage-form-label">氏名</label>
                <input type="text" name="user_name" class="mypage-form-input" value="<?php echo esc_attr($user_name); ?>">
              </div>
              <div class="mypage-form-group">
                <label class="mypage-form-label">メールアドレス（ログインID）</label>
                <input type="email" name="user_email" class="mypage-form-input" value="<?php echo esc_attr($user_email); ?>">
              </div>
            </div>
            
            <div class="mypage-form-row">
              <div class="mypage-form-group">
                <label class="mypage-form-label">電話番号 <span class="mypage-label-optional">任意</span></label>
                <input type="tel" name="user_phone" class="mypage-form-input" value="<?php echo esc_attr($user_phone); ?>" placeholder="090-1234-5678">
              </div>
              <div class="mypage-form-group">
                <label class="mypage-form-label">住所 <span class="mypage-label-optional">任意</span></label>
                <input type="text" name="user_address" class="mypage-form-input" value="<?php echo esc_attr($user_address); ?>" placeholder="都道府県から入力">
              </div>
            </div>
            
            <div class="mypage-form-group">
              <label class="mypage-form-label">パスワード</label>
              <div style="display: flex; gap: 0.75rem; align-items: center;">
                <input type="password" class="mypage-form-input" value="********" readonly style="flex: 1;">
                <button type="button" class="mypage-btn mypage-btn-secondary" onclick="openPasswordModal()">
                  <i class="fas fa-key"></i> 変更
                </button>
              </div>
            </div>
            
            <div class="mypage-btn-group">
              <button type="submit" class="mypage-btn mypage-btn-primary">
                <i class="fas fa-save"></i> アカウント情報を保存
              </button>
            </div>
          </form>
        </div>
      </section>
      
      <!-- 3. 申込履歴 -->
      <section class="mypage-section">
        <div class="mypage-section-header">
          <h2 class="mypage-h2">申込履歴</h2>
        </div>
        <div class="mypage-section-body">
          <?php if ($order_date || $order_plan_name) : ?>
            <table class="mypage-info-table">
              <tr>
                <th>申込日</th>
                <td><?php echo esc_html($order_date ?: '—'); ?></td>
              </tr>
              <tr>
                <th>プラン名</th>
                <td><?php echo esc_html($order_plan_name ?: '—'); ?></td>
              </tr>
              <tr>
                <th>決済金額</th>
                <td><?php echo $order_amount ? '¥' . esc_html(number_format((int)$order_amount)) : '—'; ?></td>
              </tr>
              <tr>
                <th>決済方法</th>
                <td><?php echo esc_html($order_payment_method ?: '—'); ?></td>
              </tr>
              <tr>
                <th>ステータス</th>
                <td><?php echo esc_html($order_status ?: '—'); ?></td>
              </tr>
            </table>
          <?php else : ?>
            <p style="text-align: center; color: var(--mp-text-muted); padding: 2rem;">
              <i class="fas fa-inbox" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
              申込履歴はありません
            </p>
          <?php endif; ?>
        </div>
      </section>
      
      <!-- 4. 請求情報 -->
      <section class="mypage-section">
        <div class="mypage-section-header">
          <h2 class="mypage-h2">請求情報</h2>
        </div>
        <div class="mypage-section-body">
          <?php if ($billing_current || $billing_next_date) : ?>
            <table class="mypage-info-table">
              <tr>
                <th>今月の請求額</th>
                <td><?php echo $billing_current ? '¥' . esc_html(number_format((int)$billing_current)) : '—'; ?></td>
              </tr>
              <tr>
                <th>支払い履歴</th>
                <td><?php echo esc_html($billing_history ?: '—'); ?></td>
              </tr>
              <tr>
                <th>次回更新日</th>
                <td><?php echo esc_html($billing_next_date ?: '—'); ?></td>
              </tr>
            </table>
          <?php else : ?>
            <p style="text-align: center; color: var(--mp-text-muted); padding: 2rem;">
              <i class="fas fa-file-invoice" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
              請求情報はありません
            </p>
          <?php endif; ?>
          <p style="font-size: 0.75rem; color: var(--mp-text-muted); margin-top: 1rem; text-align: center;">
            ※ 決済情報（カード番号等）はサイト側で保持しておりません
          </p>
        </div>
      </section>
      
      <!-- 5. ダウンロード資料 -->
      <section class="mypage-section" id="downloadSection">
        <div class="mypage-section-header">
          <h2 class="mypage-h2">ダウンロード資料</h2>
        </div>
        <div class="mypage-section-body">
          <div class="mypage-download-list">
            <?php
            // ダウンロード資料リストの定義
            $downloads = array(
                array(
                    'name' => '利用ガイドPDF',
                    'meta' => 'サービスの使い方をまとめたガイドです',
                    'file' => 'guide.pdf',
                    'icon' => 'fas fa-file-pdf',
                    'plan' => 'basic' // 誰でも見れる
                ),
                array(
                    'name' => '採用テンプレート',
                    'meta' => '採用実務で使えるドキュメントセット',
                    'file' => 'template_recruit.pdf',
                    'icon' => 'fas fa-user-plus',
                    'plan' => 'basic'
                ),
                array(
                    'name' => '評価テンプレート',
                    'meta' => '人事評価・目標設定用シート',
                    'file' => 'template_evaluate.pdf',
                    'icon' => 'fas fa-chart-line',
                    'plan' => 'premium' // プレミアム限定
                ),
                array(
                    'name' => '1on1／面談関連',
                    'meta' => '定期面談やフィードバック用ガイド',
                    'file' => 'template_1on1.pdf',
                    'icon' => 'fas fa-comments',
                    'plan' => 'premium' // プレミアム限定
                ),
                array(
                    'name' => '特典PDF',
                    'meta' => 'ご契約特典の特別資料',
                    'file' => 'bonus.pdf',
                    'icon' => 'fas fa-gift',
                    'plan' => 'basic'
                ),
            );

            foreach ($downloads as $item) :
                $is_locked = ($item['plan'] === 'premium' && $user_plan !== 'premium');
                $file_url = get_template_directory_uri() . '/pdfs/' . $item['file'];
                $file_path = get_template_directory() . '/pdfs/' . $item['file'];
                $exists = file_exists($file_path);
            ?>
              <a href="<?php echo $is_locked ? '#' : esc_url($file_url); ?>" 
                 class="mypage-download-item <?php echo $is_locked ? 'is-locked' : ''; ?>" 
                 <?php if (!$is_locked) : ?>
                   target="_blank" 
                   download="<?php echo esc_attr($item['name']); ?>.pdf"
                   <?php if (!$exists) : ?>
                     onclick="alert('PDFファイルを準備中です。'); return false;"
                   <?php endif; ?>
                 <?php else : ?>
                   onclick="alert('この資料はプレミアムプラン限定です。プランのアップグレードをご検討ください。'); return false;"
                 <?php endif; ?>
                 data-name="<?php echo esc_attr($item['name']); ?>">
                
                <div class="mypage-download-icon" <?php echo $is_locked ? 'style="background: #f3f4f6; color: #9ca3af;"' : ''; ?>>
                  <i class="<?php echo esc_attr($item['icon']); ?>"></i>
                </div>
                
                <div class="mypage-download-info">
                  <div class="mypage-download-name">
                    <?php echo esc_html($item['name']); ?>
                    <?php if ($item['plan'] === 'premium') : ?>
                      <span class="mypage-download-premium"><i class="fas fa-crown"></i> Premium</span>
                    <?php endif; ?>
                  </div>
                  <div class="mypage-download-meta">
                    <?php if ($is_locked) : ?>
                      <i class="fas fa-lock"></i> プレミアムプランへの加入が必要です
                    <?php else : ?>
                      <?php echo esc_html($item['meta']); ?>
                    <?php endif; ?>
                  </div>
                </div>
                
                <span class="mypage-download-arrow">
                  <?php if ($is_locked) : ?>
                    <i class="fas fa-lock"></i>
                  <?php else : ?>
                    <i class="fas fa-download"></i>
                  <?php endif; ?>
                </span>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
      
      <!-- 6. サポート -->
      <section class="mypage-section">
        <div class="mypage-section-header">
          <h2 class="mypage-h2">サポート</h2>
        </div>
        <div class="mypage-section-body">
          <div class="mypage-support-grid">
            <!-- お問い合わせフォーム -->
            <div class="mypage-support-card">
              <div class="mypage-support-icon">
                <i class="fas fa-envelope"></i>
              </div>
              <h3 class="mypage-h3">お問い合わせ</h3>
              <p class="mypage-support-desc">ご質問・ご相談はこちらから</p>
              <button type="button" class="mypage-btn mypage-btn-primary" onclick="openContactModal()">
                <i class="fas fa-paper-plane"></i> フォームを開く
              </button>
            </div>
            
            <!-- LINEサポート -->
            <div class="mypage-support-card">
              <div class="mypage-support-icon" style="background: #06c755;">
                <i class="fab fa-line"></i>
              </div>
              <h3 class="mypage-h3">LINEチャットサポート</h3>
              <p class="mypage-support-desc">LINEでお気軽にご相談ください</p>
              <a href="https://line.me/R/ti/p/@flowgram" target="_blank" rel="noopener noreferrer" class="mypage-btn mypage-btn-line">
                <i class="fab fa-line"></i> LINEで相談する
              </a>
            </div>
          </div>
        </div>
      </section>
      
      <!-- 7. 退会・アカウント削除 -->
      <section class="mypage-section">
        <div class="mypage-section-header">
          <h2 class="mypage-h2">退会・アカウント削除</h2>
        </div>
        <div class="mypage-section-body">
          <div class="mypage-delete-section">
            <div class="mypage-delete-warning">
              <i class="fas fa-exclamation-triangle"></i>
              <div class="mypage-delete-warning-text">
                <strong>ご注意ください</strong><br>
                アカウントを削除すると、以下の影響があります：<br>
                ・サービスへのアクセスができなくなります<br>
                ・ダウンロード資料が利用できなくなります<br>
                ・削除後のアカウント復元はできません<br>
                ・サブスクリプションの停止は運営にて手動対応いたします
              </div>
            </div>
            <button type="button" class="mypage-btn mypage-btn-danger" onclick="openDeleteModal()">
              <i class="fas fa-trash-alt"></i> 退会申請へ進む
            </button>
          </div>
        </div>
      </section>
      
      <?php if ($is_admin) : ?>
      <!-- 管理者専用：情報編集セクション -->
      <section class="mypage-section mypage-admin-section">
        <div class="mypage-section-header">
          <h2 class="mypage-h2">
            管理者専用：ユーザー情報編集
            <span class="mypage-admin-badge-large"><i class="fas fa-lock"></i> Admin Only</span>
          </h2>
        </div>
        <div class="mypage-section-body">
          <form action="" method="post">
            <?php wp_nonce_field('hrdoc_mypage_action', 'hrdoc_mypage_nonce'); ?>
            <input type="hidden" name="hrdoc_admin_save" value="1">
            
            <h3 class="mypage-h3" style="margin-top: 0;">ステータス変更</h3>
            <div class="mypage-form-group">
              <label class="mypage-form-label">現在のステータス</label>
              <select name="user_status" class="mypage-form-input">
                <?php foreach ($status_list as $status_name => $status_info) : ?>
                  <option value="<?php echo esc_attr($status_name); ?>" <?php selected($user_status, $status_name); ?>>
                    <?php echo esc_html($status_name); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            
            <h3 class="mypage-h3" style="margin-top: 1.5rem;">申込履歴</h3>
            <div class="mypage-form-row">
              <div class="mypage-form-group">
                <label class="mypage-form-label">申込日</label>
                <input type="text" name="order_date" class="mypage-form-input" value="<?php echo esc_attr($order_date); ?>" placeholder="2026/01/01">
              </div>
              <div class="mypage-form-group">
                <label class="mypage-form-label">プラン名</label>
                <input type="text" name="order_plan_name" class="mypage-form-input" value="<?php echo esc_attr($order_plan_name); ?>">
              </div>
            </div>
            <div class="mypage-form-row">
              <div class="mypage-form-group">
                <label class="mypage-form-label">決済金額</label>
                <input type="text" name="order_amount" class="mypage-form-input" value="<?php echo esc_attr($order_amount); ?>" placeholder="5000">
              </div>
              <div class="mypage-form-group">
                <label class="mypage-form-label">決済方法</label>
                <input type="text" name="order_payment_method" class="mypage-form-input" value="<?php echo esc_attr($order_payment_method); ?>" placeholder="クレジットカード">
              </div>
            </div>
            <div class="mypage-form-group">
              <label class="mypage-form-label">申込ステータス</label>
              <input type="text" name="order_status" class="mypage-form-input" value="<?php echo esc_attr($order_status); ?>" placeholder="決済完了">
            </div>
            
            <h3 class="mypage-h3" style="margin-top: 1.5rem;">請求情報</h3>
            <div class="mypage-form-row">
              <div class="mypage-form-group">
                <label class="mypage-form-label">今月の請求額</label>
                <input type="text" name="billing_current" class="mypage-form-input" value="<?php echo esc_attr($billing_current); ?>" placeholder="5000">
              </div>
              <div class="mypage-form-group">
                <label class="mypage-form-label">次回更新日</label>
                <input type="text" name="billing_next_date" class="mypage-form-input" value="<?php echo esc_attr($billing_next_date); ?>" placeholder="2026/02/01">
              </div>
            </div>
            <div class="mypage-form-group">
              <label class="mypage-form-label">支払い履歴</label>
              <input type="text" name="billing_history" class="mypage-form-input" value="<?php echo esc_attr($billing_history); ?>" placeholder="2026/01/01 ¥5,000">
            </div>
            
            <div class="mypage-btn-group">
              <button type="submit" class="mypage-btn mypage-btn-success">
                <i class="fas fa-save"></i> 管理者情報を保存
              </button>
            </div>
          </form>
        </div>
      </section>
      <?php endif; ?>
      
      <!-- フッター注意書き -->
      <div class="mypage-footer-notice">
        <p>
          本ページは「業務補助・確認用UI」です。<br>
          表示情報と実際の契約・決済状態が異なる場合、<strong>決済サービスからの通知内容を正とします。</strong>
        </p>
      </div>
      
    </div>
  </main>
  
</div>

<!-- パスワード変更モーダル -->
<div class="mypage-modal-overlay" id="passwordModal">
  <div class="mypage-modal">
    <div class="mypage-modal-header">
      <h3 class="mypage-h3" style="margin: 0;">パスワード変更</h3>
      <button type="button" class="mypage-modal-close" onclick="closePasswordModal()">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <form action="<?php echo esc_url(wp_lostpassword_url()); ?>" method="get">
      <p style="font-size: 0.875rem; color: var(--mp-text-muted); margin-bottom: 1.5rem;">
        パスワードの変更は、WordPress標準のパスワードリセット機能を使用します。
        下のボタンをクリックすると、パスワードリセット用のメールが送信されます。
      </p>
      <div class="mypage-btn-group" style="justify-content: flex-end;">
        <button type="button" class="mypage-btn mypage-btn-secondary" onclick="closePasswordModal()">キャンセル</button>
        <a href="<?php echo esc_url(wp_lostpassword_url()); ?>" class="mypage-btn mypage-btn-primary">
          <i class="fas fa-envelope"></i> リセットメールを送信
        </a>
      </div>
    </form>
  </div>
</div>

<!-- お問い合わせモーダル -->
<div class="mypage-modal-overlay" id="contactModal">
  <div class="mypage-modal" style="max-width: 500px;">
    <div class="mypage-modal-header">
      <h3 class="mypage-h3" style="margin: 0;">お問い合わせ</h3>
      <button type="button" class="mypage-modal-close" onclick="closeContactModal()">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <form action="#" method="post" id="contactForm">
      <div class="mypage-form-group">
        <label class="mypage-form-label">相談内容（複数選択可）</label>
        <div class="mypage-checkbox-group">
          <label class="mypage-checkbox-label">
            <input type="checkbox" name="contact_type[]" value="subscription">
            サブスク契約・解約について
          </label>
          <label class="mypage-checkbox-label">
            <input type="checkbox" name="contact_type[]" value="service">
            サービス内容について
          </label>
          <label class="mypage-checkbox-label">
            <input type="checkbox" name="contact_type[]" value="other">
            その他
          </label>
        </div>
      </div>
      <div class="mypage-form-group">
        <label class="mypage-form-label">詳細内容</label>
        <textarea name="contact_message" class="mypage-form-input" placeholder="お問い合わせ内容をご記入ください"></textarea>
      </div>
      <div class="mypage-btn-group" style="justify-content: flex-end;">
        <button type="button" class="mypage-btn mypage-btn-secondary" onclick="closeContactModal()">キャンセル</button>
        <button type="submit" class="mypage-btn mypage-btn-primary">
          <i class="fas fa-paper-plane"></i> 送信する
        </button>
      </div>
    </form>
  </div>
</div>

<!-- 退会確認モーダル -->
<div class="mypage-modal-overlay" id="deleteModal">
  <div class="mypage-modal">
    <div class="mypage-modal-header">
      <h3 class="mypage-h3" style="margin: 0; color: var(--mp-danger);">退会申請</h3>
      <button type="button" class="mypage-modal-close" onclick="closeDeleteModal()">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <div style="text-align: center; padding: 1rem 0;">
      <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: var(--mp-danger); margin-bottom: 1rem;"></i>
      <p style="font-size: 0.9375rem; color: var(--mp-text); margin-bottom: 1rem;">
        本当に退会しますか？<br>
        この操作は取り消せません。
      </p>
      <p style="font-size: 0.8125rem; color: var(--mp-text-muted);">
        退会申請後、運営にて手動でアカウント削除・サブスクリプション停止を行います。
        処理完了までに数営業日かかる場合がございます。
      </p>
    </div>
    <div class="mypage-btn-group" style="justify-content: center;">
      <button type="button" class="mypage-btn mypage-btn-secondary" onclick="closeDeleteModal()">キャンセル</button>
      <a href="mailto:support@example.com?subject=退会申請&body=退会を希望します。%0A%0Aユーザー名: <?php echo esc_attr($user_name); ?>%0Aメールアドレス: <?php echo esc_attr($user_email); ?>" class="mypage-btn mypage-btn-danger">
        <i class="fas fa-trash-alt"></i> 退会申請を送信
      </a>
    </div>
  </div>
</div>

<script>
// モーダル制御
function openPasswordModal() {
  document.getElementById('passwordModal').classList.add('is-active');
}
function closePasswordModal() {
  document.getElementById('passwordModal').classList.remove('is-active');
}
function openContactModal() {
  document.getElementById('contactModal').classList.add('is-active');
}
function closeContactModal() {
  document.getElementById('contactModal').classList.remove('is-active');
}
function openDeleteModal() {
  document.getElementById('deleteModal').classList.add('is-active');
}
function closeDeleteModal() {
  document.getElementById('deleteModal').classList.remove('is-active');
}

// モーダル外クリックで閉じる
document.querySelectorAll('.mypage-modal-overlay').forEach(function(overlay) {
  overlay.addEventListener('click', function(e) {
    if (e.target === this) {
      this.classList.remove('is-active');
    }
  });
});

// ESCキーでモーダルを閉じる
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    document.querySelectorAll('.mypage-modal-overlay.is-active').forEach(function(modal) {
      modal.classList.remove('is-active');
    });
  }
});

// 資料検索機能
document.getElementById('searchInput').addEventListener('input', function(e) {
  const query = e.target.value.toLowerCase();
  const items = document.querySelectorAll('.mypage-download-item');
  
  items.forEach(function(item) {
    const name = item.getAttribute('data-name') || '';
    const text = item.textContent.toLowerCase();
    if (name.toLowerCase().includes(query) || text.includes(query)) {
      item.style.display = '';
    } else {
      item.style.display = 'none';
    }
  });
});

// お問い合わせフォーム送信
document.getElementById('contactForm').addEventListener('submit', function(e) {
  e.preventDefault();
  
  const checkboxes = document.querySelectorAll('input[name="contact_type[]"]:checked');
  const message = document.querySelector('textarea[name="contact_message"]').value;
  
  if (checkboxes.length === 0) {
    alert('相談内容を1つ以上選択してください。');
    return;
  }
  
  if (!message.trim()) {
    alert('詳細内容を入力してください。');
    return;
  }
  
  // メール送信（実際はサーバーサイド処理が必要）
  let types = [];
  checkboxes.forEach(function(cb) {
    if (cb.value === 'subscription') types.push('サブスク契約・解約');
    if (cb.value === 'service') types.push('サービス内容');
    if (cb.value === 'other') types.push('その他');
  });
  
  const mailtoLink = 'mailto:support@example.com?subject=お問い合わせ: ' + types.join(', ') + 
    '&body=' + encodeURIComponent('【相談内容】\n' + types.join(', ') + '\n\n【詳細】\n' + message + 
    '\n\n【ユーザー情報】\n' + '<?php echo esc_js($user_name); ?>' + ' / ' + '<?php echo esc_js($user_email); ?>');
  
  window.location.href = mailtoLink;
  closeContactModal();
});
</script>

<?php wp_footer(); ?>

</body>
</html>
