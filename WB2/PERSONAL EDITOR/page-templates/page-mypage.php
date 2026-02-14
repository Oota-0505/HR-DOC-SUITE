<?php
/**
 * Template Name: マイページ
 * Description: PERSONAL EDITOR 会員マイページ
 *
 * @package PERSONAL_EDITOR
 * @since 1.0.0
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

// 管理者判定: WordPress管理者 または custom_users の role_type === 1
$is_admin = current_user_can('manage_options')
    || ($loggedin_user !== null && (int) $loggedin_user->role_type === 1);

// 管理者でない場合、他人のページは見れない
if (!$is_admin && $user_id != $current_user->ID) {
    wp_redirect(home_url('/mypage'));
    exit;
}

// 表示用ユーザー情報（$custom_user が null の場合も対応）
$display_user = get_userdata($user_id);
$user_name = ($custom_user ? ($custom_user->name ?? '') : '') ?: ($display_user ? ($display_user->display_name ?? $display_user->user_login ?? '') : '');
$user_email = ($custom_user ? ($custom_user->email ?? '') : '') ?: ($display_user ? ($display_user->user_email ?? '') : '');
$user_phone = $custom_user ? ($custom_user->phone ?? '') : '';
$user_address = $custom_user ? ($custom_user->address ?? '') : '';

// ステータス情報
$user_status = $custom_user ? ($custom_user->status ?? '受付済') : '受付済';

// 申込履歴情報（管理者入力）
$order_date = $custom_user ? ($custom_user->order_date ?? '') : '';
$order_plan_name = $custom_user ? ($custom_user->order_plan_name ?? '') : '';
$order_amount = $custom_user ? ($custom_user->order_amount ?? '') : '';
$order_payment_method = $custom_user ? ($custom_user->order_payment_method ?? '') : '';
$order_status = $custom_user ? ($custom_user->order_status ?? '') : '';

// 請求情報（管理者入力）
$billing_current = $custom_user ? ($custom_user->billing_current ?? '') : '';
$billing_history = $custom_user ? ($custom_user->billing_history ?? '') : '';
$billing_next_date = $custom_user ? ($custom_user->billing_next_date ?? '') : '';

// プラン情報（ダウンロード制御用）
$user_plan = $custom_user ? ($custom_user->plan_type ?? 'basic') : 'basic';

// プラン名の日本語表記定義
$plan_names_jp = array(
    'basic'    => 'ベーシック',
    'standard' => 'スタンダード',
    'pro'      => 'プロ',
    'premium'  => 'プロ'
);
$current_plan_name = $plan_names_jp[$user_plan] ?? 'ベーシック';

// 価格設定 (section-plan.php トップページ準拠)
$prices = array(
    'basic'    => 2178,   // 税込 2,178円
    'standard' => 4378,   // 税込 4,378円
    'pro'      => 8778,   // 税込 8,778円
    '1on1'         => 2200,  // 追加添削（1本）
    'customize'    => 5500,  // プロフィール文章のブラッシュアップ
    'note_check'   => 5500,  // note記事の骨子作成サポート
    'meeting'      => 8800,  // 世界観整理セッション 60分
    'flow'         => 550,   // フォント変更・テキスト整形
    'review'       => 1100,  // タイトルのブラッシュアップ（3案）
    'tag'          => 1100,  // 導入文リライト（200~300字）
    'theme'        => 1650,  // キャッチコピー作成（3案）
    'improve'      => 3300,  // 見出しデザイン作成
    'long'         => 2200,  // 箇条書きから本文化（200~300字）
);

$option_keys = array('1on1', 'customize', 'note_check', 'meeting', 'flow', 'review', 'tag', 'theme', 'improve', 'long');
$col_map = array('1on1' => 'option_1on1');
// interview, rewrite は PERSONAL EDITOR では未使用だが DB 互換のため残す
foreach ($option_keys as $k) {
    if (!isset($col_map[$k])) $col_map[$k] = 'option_' . $k;
}
$option_values = array();
foreach ($option_keys as $k) {
    $col = $col_map[$k];
    $option_values[$k] = ($custom_user && isset($custom_user->$col)) ? (int)$custom_user->$col : 0;
}

$initial_total = $prices[$user_plan] ?? 2178;
foreach ($option_keys as $k) {
    $initial_total += $option_values[$k] * ($prices[$k] ?? 0);
}

// 保存処理
$save_message = '';
$save_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['personaleditor_mypage_nonce']) || !wp_verify_nonce($_POST['personaleditor_mypage_nonce'], 'personaleditor_mypage_action')) {
        $save_error = '不正なリクエストです。';
    } else {
        // アカウント情報保存
        if (isset($_POST['personaleditor_account_save'])) {
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
        if ($is_admin && isset($_POST['personaleditor_admin_save'])) {
            $data = array(
                'status' => sanitize_text_field($_POST['user_status'] ?? '受付済'),
                'plan_type' => sanitize_text_field($_POST['plan_type'] ?? 'basic'),
                'option_customize' => intval($_POST['option_customize'] ?? 0),
                'option_review' => intval($_POST['option_review'] ?? 0),
                'option_flow' => intval($_POST['option_flow'] ?? 0),
                'option_note_check' => intval($_POST['option_note_check'] ?? 0),
                'option_tag' => intval($_POST['option_tag'] ?? 0),
                'option_theme' => intval($_POST['option_theme'] ?? 0),
                'option_improve' => intval($_POST['option_improve'] ?? 0),
                'option_1on1' => intval($_POST['option_1on1'] ?? 0),
                'option_long' => intval($_POST['option_long'] ?? 0),
                'option_interview' => intval($_POST['option_interview'] ?? 0),
                'option_meeting' => intval($_POST['option_meeting'] ?? 0),
                'option_rewrite' => intval($_POST['option_rewrite'] ?? 0),
                'order_date' => sanitize_text_field($_POST['order_date'] ?? ''),
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
                $user_plan = $data['plan_type'];
                foreach ($option_keys as $k) {
                    $col = $col_map[$k];
                    $option_values[$k] = isset($data[$col]) ? (int)$data[$col] : 0;
                }
                
                // 表示用プラン名を即座に更新
                $current_plan_name = $plan_names_jp[$user_plan] ?? 'ベーシック';
                
                $order_date = $data['order_date'];
                $order_amount = $data['order_amount'];
                $order_payment_method = $data['order_payment_method'];
                $order_status = $data['order_status'];
                $billing_current = $data['billing_current'];
                $billing_history = $data['billing_history'];
                $billing_next_date = $data['billing_next_date'];

                // 合計金額を最新の状態に再計算
                $initial_total = $prices[$user_plan] ?? 2178;
                foreach ($option_keys as $k) {
                    $initial_total += $option_values[$k] * ($prices[$k] ?? 0);
                }
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
  <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;700&family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    /* ============================================
       マイページ専用CSS - Scoped Styles
       プレフィックス: .mypage- を使用
       詳細度を高めて競合を防止
    ============================================ */
    
    /* === HTML & Body Reset for Mypage（サイト統一：ダーク＋オレンジ） === */
    html.mypage-html,
    body.page-mypage {
      margin: 0 !important;
      padding: 0 !important;
      width: 100% !important;
      min-width: 100% !important;
      overflow-x: clip !important;
      background: #1F2933 !important;
      scroll-behavior: smooth;
    }
    
    body.page-mypage {
      position: relative !important;
      padding-bottom: 0 !important;
    }
    
    /* === CSS Variables === */
    .mypage-container {
      width: 100%;
      min-width: 100%;
      --mp-primary: #F97316;
      --mp-primary-light: #ea580c;
      --mp-primary-dark: #c2410c;
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
      background: #1F2933;
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
      background: #F97316;
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
    
    /* === Layout（サイト l-inner 準拠） === */
    .mypage-wrapper {
      max-width: 1225px;
      margin: 0 auto;
      padding: 0 25px;
    }
    @media (max-width: 767px) {
      .mypage-wrapper { padding: 0 20px; }
    }
    
    /* === 案2: ヒーロー＋カードグリッド型 === */
    
    /* ヒーローエリア */
    .mypage-hero {
      background: linear-gradient(135deg, rgba(249, 115, 22, 0.15) 0%, rgba(249, 115, 22, 0.05) 100%);
      border: 1px solid rgba(249, 115, 22, 0.25);
      border-radius: 0.75rem;
      padding: 2rem 2.5rem;
      margin-top: 2rem;
      margin-bottom: 2.5rem;
    }
    
    .mypage-hero-inner {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 1.5rem 2rem;
    }
    
    .mypage-hero-info {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 1rem 1.5rem;
    }
    
    .mypage-hero-name {
      font-size: 1.5rem;
      font-weight: 700;
      color: #F3F4F6;
      margin: 0;
    }
    
    .mypage-hero-divider {
      width: 1px;
      height: 1.5rem;
      background: rgba(249, 115, 22, 0.5);
      display: none;
    }
    @media (min-width: 640px) {
      .mypage-hero-divider { display: block; }
    }
    
    .mypage-hero-plan {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 1.125rem;
      font-weight: 600;
      color: #F97316;
    }
    
    .mypage-hero-status {
      display: inline-flex;
      align-items: center;
      gap: 0.375rem;
      padding: 0.375rem 0.875rem;
      border-radius: 100px;
      font-size: 0.875rem;
      font-weight: 600;
      color: #fff;
    }
    
    .mypage-hero-cta {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: 0.75rem;
    }
    
    .mypage-hero-total {
      font-size: 0.8125rem;
      color: rgba(243, 244, 246, 0.8);
    }
    
    .mypage-hero-total .js-total-amount {
      display: block;
      font-size: 1.75rem;
      font-weight: 800;
      color: #F97316;
      margin-top: 0.25rem;
    }
    
    .mypage-hero-payment-btn {
      padding: 0.75rem 1.75rem;
      font-size: 1rem;
      font-weight: 600;
      border-radius: 0.5rem;
      border: none;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      background: #F97316;
      color: #fff;
      transition: all 0.2s;
      font-family: inherit;
    }
    
    .mypage-hero-payment-btn:hover {
      background: #ea580c;
      transform: translateY(-1px);
    }
    
    /* 3カラムカードグリッド */
    .mypage-card-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1.5rem;
      margin-bottom: 2.5rem;
    }
    @media (max-width: 991px) {
      .mypage-card-grid {
        grid-template-columns: 1fr;
      }
    }
    
    .mypage-grid-card {
      background: #fff;
      border-radius: 0.5rem;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      border: 1px solid rgba(0, 0, 0, 0.06);
    }
    
    .mypage-grid-card-header {
      padding: 1.25rem 1.5rem;
      border-bottom: 1px solid #e5e7eb;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }
    
    .mypage-grid-card-icon {
      width: 44px;
      height: 44px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #fff7ed;
      color: #F97316;
      border-radius: 0.5rem;
      font-size: 1.25rem;
    }
    
    .mypage-grid-card-title {
      font-size: 1rem;
      font-weight: 700;
      color: var(--mp-text);
      margin: 0;
    }
    
    .mypage-grid-card-body {
      padding: 1.5rem;
      flex: 1;
      min-height: 0;
    }
    
    .mypage-grid-card.mypage-card-download .mypage-grid-card-body,
    .mypage-grid-card.mypage-card-account .mypage-grid-card-body {
      overflow-y: auto;
      max-height: 380px;
    }
    
    .mypage-card-download .mypage-download-item {
      padding: 0.75rem 1rem;
    }
    
    @media (max-width: 767px) {
      .mypage-hero {
        padding: 1.5rem 1.25rem;
      }
      .mypage-hero-inner {
        flex-direction: column;
        align-items: flex-start;
      }
      .mypage-hero-name {
        font-size: 1.25rem;
      }
      .mypage-hero-cta {
        width: 100%;
        align-items: stretch;
      }
      .mypage-hero-payment-btn {
        width: 100%;
      }
    }
    
    /* === Header（ダーク・サイト統一） === */
    .mypage-header {
      background: rgba(31, 41, 51, 0.98);
      border-bottom: 1px solid rgba(249, 115, 22, 0.2);
      padding: 1rem 0;
      position: sticky;
      top: 0;
      z-index: 100;
      backdrop-filter: blur(10px);
    }
    
    .mypage-header-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      flex-wrap: wrap;
    }
    
    .mypage-header-logo a {
      display: inline-block;
      text-decoration: none;
      cursor: pointer;
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
      border: 1px solid rgba(255,255,255,0.15);
      border-radius: 100px;
      font-size: 0.875rem;
      background: rgba(255,255,255,0.08);
      color: #F3F4F6;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    
    .mypage-container .mypage-search-input::placeholder {
      color: rgba(243, 244, 246, 0.6);
    }
    
    .mypage-container .mypage-search-input:focus {
      outline: none;
      border-color: #F97316;
      box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
    }
    
    .mypage-header-search i {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: rgba(243, 244, 246, 0.6);
      font-size: 0.875rem;
    }
    
    .mypage-header-nav {
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    
    .mypage-header-user {
      font-size: 0.875rem;
      color: #F3F4F6;
      font-weight: 500;
    }
    
    .mypage-container .mypage-admin-badge {
      display: inline-flex;
      align-items: center;
      background: #F97316;
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
      color: #F3F4F6;
      text-decoration: none;
      padding: 0.5rem 0.75rem;
      border-radius: 6px;
      transition: background 0.2s, color 0.2s;
    }
    
    .mypage-container .mypage-header-link:hover,
    .mypage-container a.mypage-header-link:hover {
      background: rgba(249, 115, 22, 0.2);
      color: #F97316;
    }
    
    /* === Main Content（p-contact 風ヒーロー） === */
    .mypage-main {
      padding: 4rem 0 5rem;
    }
    
    .mypage-title-area {
      text-align: center;
      margin-bottom: 3rem;
    }
    
    .mypage-container .mypage-title-area .mypage-h1 {
      margin: 0 0 0.75rem 0;
      font-size: 2rem;
      font-weight: 700;
      letter-spacing: 0.08em;
      color: #F97316 !important; /* メインカラー（オレンジ） */
    }
    
    .mypage-title-desc {
      margin: 0;
      font-size: 0.9375rem;
      color: rgba(243, 244, 246, 0.8);
      line-height: 1.6;
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
    
    /* === Section Card（白カード・サイト統一） === */
    .mypage-section {
      background: #fff;
      border-radius: 0.5rem;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
      margin-bottom: 2rem;
      overflow: hidden;
    }
    
    .mypage-section-header {
      padding: 1.5rem 2rem;
      border-bottom: 1px solid #e5e7eb;
      background: #fff;
    }
    
    .mypage-section-body {
      padding: 2rem;
    }
    
    /* === Status Display === */
    .mypage-status-display {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.75rem;
      padding: 1.5rem;
      background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
      border-radius: var(--mp-radius);
      margin-bottom: 1rem;
      border: 1px solid rgba(249, 115, 22, 0.2);
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
      background: #F97316;
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
      border-color: #F97316;
      box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.12);
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
      background: #F97316;
      color: #fff;
      box-shadow: 0 2px 8px rgba(249, 115, 22, 0.3);
    }
    
    .mypage-container .mypage-btn-primary:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(249, 115, 22, 0.4);
      background: #ea580c;
    }
    
    .mypage-container .mypage-btn-secondary {
      background: #fff;
      color: #F97316;
      border: 1px solid #F97316;
    }
    
    .mypage-container .mypage-btn-secondary:hover {
      background: #fff7ed;
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
      border-color: #F97316;
      box-shadow: 0 8px 24px rgba(249, 115, 22, 0.12);
      transform: translateY(-2px);
    }
    
    .mypage-download-icon {
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #fff7ed;
      color: #F97316;
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
    .mypage-support-grid:has(.mypage-support-card:only-child) {
      grid-template-columns: 1fr;
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
      background: #F97316;
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
      border-color: #F97316;
    }
    
    .mypage-container .mypage-checkbox-label input[type="checkbox"] {
      width: 18px;
      height: 18px;
      accent-color: #F97316;
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
      color: rgba(243, 244, 246, 0.7);
      background: rgba(0,0,0,0.15);
      border-radius: var(--mp-radius);
      margin-top: 2rem;
    }
    
    .mypage-footer-notice p {
      margin: 0;
    }
    
    /* === フローティング合計バー（プランセクション表示時に下部固定） === */
    .mypage-floating-total {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      z-index: 999;
      background: rgba(31, 41, 51, 0.98);
      border-top: 1px solid rgba(249, 115, 22, 0.3);
      padding: 1rem 1.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 1.5rem;
      flex-wrap: wrap;
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      box-shadow: 0 -4px 24px rgba(0, 0, 0, 0.2);
      transform: translateY(100%);
      opacity: 0;
      visibility: hidden;
      pointer-events: none;
      transition: transform 0.3s ease, opacity 0.3s ease, visibility 0.3s;
    }
    
    .mypage-floating-total.is-visible {
      transform: translateY(0);
      opacity: 1;
      visibility: visible;
      pointer-events: auto;
    }
    
    .mypage-floating-total-label {
      font-size: 0.875rem;
      color: rgba(243, 244, 246, 0.9);
    }
    
    .mypage-floating-total .js-total-amount {
      font-size: 1.5rem;
      font-weight: 800;
      color: #F97316;
    }
    
    .mypage-floating-total .mypage-floating-payment-btn {
      padding: 0.75rem 1.75rem;
      font-size: 1rem;
      font-weight: 600;
      border-radius: 0.5rem;
      border: none;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      background: #F97316;
      color: #fff;
      transition: all 0.2s;
      font-family: inherit;
    }
    
    .mypage-floating-total .mypage-floating-payment-btn:hover {
      background: #ea580c;
      transform: translateY(-1px);
    }
    
    body.mypage-floating-bar-visible {
      padding-bottom: 80px !important;
    }
    
    @media (max-width: 640px) {
      .mypage-floating-total {
        padding: 0.875rem 1rem;
        gap: 1rem;
      }
      .mypage-floating-total .js-total-amount {
        font-size: 1.25rem;
      }
      body.mypage-floating-bar-visible {
        padding-bottom: 72px !important;
      }
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
      color: #F97316 !important;
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

    /* === プラン・オプション選択テーブル (追加) === */
    .mp-selection-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 1.5rem;
    }
    .mp-selection-table th, .mp-selection-table td {
      padding: 1rem;
      border-bottom: 1px solid var(--mp-border-light);
      font-size: 0.9375rem;
    }
    .mp-selection-table th {
      background: var(--mp-bg);
      text-align: left;
      font-weight: 600;
      color: var(--mp-secondary);
    }
    .mp-selection-table .price-cell {
      text-align: right;
      font-weight: 700;
      color: var(--mp-primary);
      width: 140px;
    }
    .mp-selection-table .select-cell {
      text-align: center;
      width: 80px;
    }
    .mp-selection-table .qty-input {
      width: 70px;
      padding: 0.5rem;
      border: 1px solid var(--mp-border);
      border-radius: var(--mp-radius-sm);
      text-align: center;
      font-family: inherit;
    }
    .mp-total-display {
      background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
      padding: 2rem;
      border-radius: var(--mp-radius);
      text-align: center;
      margin-top: 1.5rem;
      border: 1px solid var(--mp-border);
    }
    .mp-total-amount {
      display: block;
      font-size: 2.5rem;
      font-weight: 800;
      color: var(--mp-secondary);
      margin: 0.5rem 0;
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
        <div class="mypage-header-logo">
          <a href="<?php echo esc_url(home_url('/')); ?>">
            <img src="<?php img_path(); ?>/common/header-logo.webp" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
          </a>
        </div>
        
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
            <i class="fas fa-users"></i> ユーザー選択
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
        <p class="mypage-title-desc">ご契約状況と各種サービスをご確認いただけます</p>
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
      
      <!-- ヒーローエリア -->
      <div class="mypage-hero">
        <div class="mypage-hero-inner">
          <div class="mypage-hero-info">
            <h2 class="mypage-hero-name"><?php echo esc_html($user_name); ?> 様</h2>
            <span class="mypage-hero-divider" aria-hidden="true"></span>
            <span class="mypage-hero-plan">
              <i class="fas fa-crown"></i>
              <span id="displayPlanName"><?php echo esc_html($current_plan_name); ?></span>プラン
            </span>
            <?php $hero_status = $status_list[$user_status] ?? $status_list['受付済']; ?>
            <span class="mypage-hero-status" style="background-color: <?php echo esc_attr($hero_status['color']); ?>">
              <i class="fas fa-<?php echo esc_attr($hero_status['icon']); ?>"></i>
              <?php echo esc_html($user_status); ?>
            </span>
          </div>
          <div class="mypage-hero-cta">
            <div class="mypage-hero-total">
              月額合計（税込）
              <span class="js-total-amount">¥<?php echo number_format($initial_total); ?></span>
            </div>
            <button type="button" class="mypage-hero-payment-btn js-payment-btn" id="paymentBtn">
              <i class="fas fa-credit-card"></i> 決済する
            </button>
          </div>
        </div>
      </div>
      
      <!-- 3カラムカードグリッド -->
      <div class="mypage-card-grid">
        
        <!-- アカウント情報カード -->
        <div class="mypage-grid-card mypage-card-account">
          <div class="mypage-grid-card-header">
            <div class="mypage-grid-card-icon"><i class="fas fa-user"></i></div>
            <h3 class="mypage-grid-card-title">アカウント情報</h3>
          </div>
          <div class="mypage-grid-card-body">
            <form action="" method="post">
              <?php wp_nonce_field('personaleditor_mypage_action', 'personaleditor_mypage_nonce'); ?>
              <input type="hidden" name="personaleditor_account_save" value="1">
              <div class="mypage-form-group">
                <label class="mypage-form-label">氏名</label>
                <input type="text" name="user_name" class="mypage-form-input" value="<?php echo esc_attr($user_name); ?>">
              </div>
              <div class="mypage-form-group">
                <label class="mypage-form-label">メールアドレス</label>
                <input type="email" name="user_email" class="mypage-form-input" value="<?php echo esc_attr($user_email); ?>">
              </div>
              <div class="mypage-form-group">
                <label class="mypage-form-label">電話番号</label>
                <input type="tel" name="user_phone" class="mypage-form-input" value="<?php echo esc_attr($user_phone); ?>" placeholder="090-1234-5678">
              </div>
              <div class="mypage-form-group">
                <label class="mypage-form-label">住所</label>
                <input type="text" name="user_address" class="mypage-form-input" value="<?php echo esc_attr($user_address); ?>" placeholder="都道府県から入力">
              </div>
              <div class="mypage-form-group">
                <label class="mypage-form-label">パスワード</label>
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                  <input type="password" class="mypage-form-input" value="********" readonly style="flex: 1;">
                  <button type="button" class="mypage-btn mypage-btn-secondary" onclick="openPasswordModal()">
                    <i class="fas fa-key"></i> 変更
                  </button>
                </div>
              </div>
              <button type="submit" class="mypage-btn mypage-btn-primary" style="width: 100%; margin-top: 0.5rem;">
                <i class="fas fa-save"></i> 保存
              </button>
            </form>
          </div>
        </div>
        
        <!-- ダウンロードカード -->
        <div class="mypage-grid-card mypage-card-download" id="downloadSection">
          <div class="mypage-grid-card-header">
            <div class="mypage-grid-card-icon"><i class="fas fa-download"></i></div>
            <h3 class="mypage-grid-card-title">ダウンロード資料</h3>
          </div>
          <div class="mypage-grid-card-body">
            <div class="mypage-download-list">
            <?php
            $plan_hierarchy = array('basic' => 0, 'standard' => 1, 'pro' => 2, 'premium' => 2);
            $plan_name_katakana = array('basic' => 'ベーシック', 'standard' => 'スタンダード', 'pro' => 'プロ', 'premium' => 'プロ');
            function get_plan_name_katakana($plan_key) {
                global $plan_name_katakana;
                return isset($plan_name_katakana[$plan_key]) ? $plan_name_katakana[$plan_key] : $plan_key;
            }
            $current_user_plan_weight = isset($plan_hierarchy[$user_plan]) ? $plan_hierarchy[$user_plan] : 0;
            $downloads = array(
                array('name' => '利用ガイドPDF', 'meta' => 'サービスの使い方をまとめたガイドです', 'file' => 'guide.pdf', 'icon' => 'fas fa-file-pdf', 'plan' => 'basic'),
                array('name' => '特典PDF', 'meta' => 'ご契約特典の特別資料', 'file' => 'bonus.pdf', 'icon' => 'fas fa-gift', 'plan' => 'basic'),
                array('name' => '編集成果物ファイル（必要時）', 'meta' => '添削・編集の成果物をダウンロードできます', 'file' => 'works.pdf', 'icon' => 'fas fa-file-lines', 'plan' => 'basic'),
                array('name' => 'その他（運営が追加する資料）', 'meta' => '運営から追加で共有される資料', 'file' => 'other.pdf', 'icon' => 'fas fa-folder-open', 'plan' => 'basic'),
            );
            foreach ($downloads as $item) :
                $required_plan = $item['plan'];
                $required_weight = isset($plan_hierarchy[$required_plan]) ? $plan_hierarchy[$required_plan] : 0;
                $is_locked = ($current_user_plan_weight < $required_weight);
                $file_url = get_template_directory_uri() . '/pdfs/' . $item['file'];
                $file_path = get_template_directory() . '/pdfs/' . $item['file'];
                $exists = file_exists($file_path);
            ?>
              <a href="<?php echo $is_locked ? '#' : esc_url($file_url); ?>" 
                 class="mypage-download-item <?php echo $is_locked ? 'is-locked' : ''; ?>" 
                 <?php if (!$is_locked) : ?>
                   target="_blank" download="<?php echo esc_attr($item['name']); ?>.pdf"
                   <?php if (!$exists) : ?> onclick="alert('PDFファイルを準備中です。'); return false;" <?php endif; ?>
                 <?php else : ?>
                   onclick="alert('この資料は<?php echo esc_js(get_plan_name_katakana($required_plan)); ?>プラン以上でご利用いただけます。'); return false;"
                 <?php endif; ?>
                 data-name="<?php echo esc_attr($item['name']); ?>">
                <div class="mypage-download-icon" <?php echo $is_locked ? 'style="background: #f3f4f6; color: #9ca3af;"' : ''; ?>>
                  <i class="<?php echo esc_attr($item['icon']); ?>"></i>
                </div>
                <div class="mypage-download-info">
                  <div class="mypage-download-name">
                    <?php echo esc_html($item['name']); ?>
                    <?php if ($required_weight > 0) : ?>
                      <span class="mypage-download-premium plan-<?php echo esc_attr($required_plan); ?>">
                        <i class="fas fa-crown"></i> <?php echo esc_html(get_plan_name_katakana($required_plan)); ?>
                      </span>
                    <?php endif; ?>
                  </div>
                  <div class="mypage-download-meta">
                    <?php if ($is_locked) : ?>
                      <i class="fas fa-lock"></i> <?php echo esc_html(get_plan_name_katakana($required_plan)); ?>プランへの加入が必要です
                    <?php else : ?>
                      <?php echo esc_html($item['meta']); ?>
                    <?php endif; ?>
                  </div>
                </div>
                <span class="mypage-download-arrow">
                  <?php if ($is_locked) : ?><i class="fas fa-lock"></i><?php else : ?><i class="fas fa-download"></i><?php endif; ?>
                </span>
              </a>
            <?php endforeach; ?>
            </div>
          </div>
        </div>
        
        <!-- サポートカード -->
        <div class="mypage-grid-card mypage-card-support">
          <div class="mypage-grid-card-header">
            <div class="mypage-grid-card-icon"><i class="fas fa-envelope"></i></div>
            <h3 class="mypage-grid-card-title">サポート</h3>
          </div>
          <div class="mypage-grid-card-body">
            <p style="font-size: 0.9375rem; color: var(--mp-text-muted); margin: 0 0 1.25rem 0; line-height: 1.6;">ご質問・ご相談はお気軽にどうぞ</p>
            <a href="<?php echo esc_url(home_url('/#contact')); ?>" target="_blank" rel="noopener noreferrer" class="mypage-btn mypage-btn-primary" style="width: 100%; justify-content: center;">
              <i class="fas fa-paper-plane"></i> お問い合わせフォームへ
            </a>
          </div>
        </div>
      </div><!-- .mypage-card-grid -->
      
      <!-- プラン・オプション選択 -->
      <section class="mypage-section" id="planSection">
        <div class="mypage-section-header">
          <h2 class="mypage-h2">プラン・オプション選択</h2>
        </div>
        <div class="mypage-section-body">
          <h3 class="mypage-h3">1. プランを選択</h3>
          <div style="overflow-x: auto;">
            <table class="mp-selection-table">
              <thead>
                <tr>
                  <th>プラン名</th>
                  <th class="price-cell">月額（税込）</th>
                  <th class="select-cell">選択</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>ベーシックプラン</td>
                  <td class="price-cell">¥2,178</td>
                  <td class="select-cell"><input type="radio" name="plan_select" value="basic" <?php checked($user_plan, 'basic'); ?>></td>
                </tr>
                <tr>
                  <td>スタンダードプラン</td>
                  <td class="price-cell">¥4,378</td>
                  <td class="select-cell"><input type="radio" name="plan_select" value="standard" <?php checked($user_plan, 'standard'); ?>></td>
                </tr>
                <tr>
                  <td>プロプラン</td>
                  <td class="price-cell">¥8,778</td>
                  <td class="select-cell"><input type="radio" name="plan_select" value="pro" <?php echo ($user_plan === 'pro' || $user_plan === 'premium') ? 'checked' : ''; ?>></td>
                </tr>
              </tbody>
            </table>
          </div>
          <h3 class="mypage-h3" style="margin-top: 2rem;">2. オプションを追加</h3>
          <div style="overflow-x: auto;">
            <table class="mp-selection-table">
              <thead>
                <tr>
                  <th>オプション名</th>
                  <th class="price-cell">単価（税込）</th>
                  <th class="select-cell">数量</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $option_rows = array(
                    '1on1' => array('追加添削（1本）', 2200),
                    'customize' => array('プロフィール文章のブラッシュアップ（1アカウント）', 5500),
                    'note_check' => array('note記事の骨子作成サポート（1本）', 5500),
                    'meeting' => array('世界観整理セッション 60分（1回）', 8800),
                    'flow' => array('フォント変更・テキスト整形（1回）', 550),
                    'review' => array('タイトルのブラッシュアップ（1回／3案）', 1100),
                    'tag' => array('導入文リライト（1か所／200~300字）', 1100),
                    'theme' => array('キャッチコピー作成（1回／3案）', 1650),
                    'improve' => array('見出しデザイン作成（1回）', 3300),
                    'long' => array('箇条書きから本文化（1か所／200~300字）', 2200),
                );
                foreach ($option_rows as $key => $row) :
                    $input_name = $key === '1on1' ? 'option_1on1' : 'option_' . $key;
                ?>
                <tr>
                  <td><?php echo esc_html($row[0]); ?></td>
                  <td class="price-cell">¥<?php echo number_format($row[1]); ?></td>
                  <td class="select-cell"><input type="number" name="<?php echo esc_attr($input_name); ?>" class="qty-input" value="<?php echo esc_attr($option_values[$key]); ?>" min="0" max="99"></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </section>
      
      <!-- 申込履歴・請求情報 -->
      <section class="mypage-section">
        <div class="mypage-section-header">
          <h2 class="mypage-h2">申込履歴・請求情報</h2>
        </div>
        <div class="mypage-section-body">
          <div class="mypage-form-row">
            <div>
              <h3 class="mypage-h3">申込履歴</h3>
              <table class="mypage-info-table">
                <tr><th>申込日</th><td><?php echo esc_html($order_date ?: '—'); ?></td></tr>
                <tr><th>プラン名</th><td><span id="historyPlanName"><?php echo esc_html($current_plan_name); ?></span></td></tr>
                <tr><th>決済金額</th><td><?php echo $order_amount ? '¥' . esc_html(number_format((int)$order_amount)) : '—'; ?></td></tr>
                <tr><th>決済方法</th><td><?php echo esc_html($order_payment_method ?: '—'); ?></td></tr>
                <tr><th>ステータス</th><td><?php echo esc_html($order_status ?: '—'); ?></td></tr>
              </table>
            </div>
            <div>
              <h3 class="mypage-h3">請求情報</h3>
              <table class="mypage-info-table">
                <tr><th>今月の請求額</th><td><?php echo $billing_current ? '¥' . esc_html(number_format((int)$billing_current)) : '—'; ?></td></tr>
                <tr><th>支払い履歴</th><td><?php echo esc_html($billing_history ?: '—'); ?></td></tr>
                <tr><th>次回更新日</th><td><?php echo esc_html($billing_next_date ?: '—'); ?></td></tr>
              </table>
            </div>
          </div>
        </div>
      </section>
      
      <!-- 退会・アカウント削除 -->
      <section class="mypage-section" id="withdrawalSection">
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
      <!-- ■ 管理者専用：ユーザー情報編集 (復元) -->
      <section class="mypage-section mypage-admin-section">
        <div class="mypage-section-header">
          <h2 class="mypage-h2">
            管理者専用：ユーザー情報編集
            <span class="mypage-admin-badge-large"><i class="fas fa-lock"></i> Admin Only</span>
          </h2>
        </div>
        <div class="mypage-section-body">
          <form action="" method="post">
            <?php wp_nonce_field('personaleditor_mypage_action', 'personaleditor_mypage_nonce'); ?>
            <input type="hidden" name="personaleditor_admin_save" value="1">
            
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
            
            <h3 class="mypage-h3" style="margin-top: 1.5rem;">申込履歴・オプション設定</h3>
            <div class="mypage-form-row">
              <div class="mypage-form-group">
                <label class="mypage-form-label">申込日</label>
                <input type="text" name="order_date" class="mypage-form-input" value="<?php echo esc_attr($order_date); ?>" placeholder="2026/01/01">
              </div>
              <div class="mypage-form-group">
                <label class="mypage-form-label" style="color: var(--mp-primary); font-weight: 700;">ご契約プランの設定</label>
                <select name="plan_type" class="mypage-form-input" style="border: 2px solid var(--mp-primary);">
                  <option value="basic" <?php selected($user_plan, 'basic'); ?>>ベーシック</option>
                  <option value="standard" <?php selected($user_plan, 'standard'); ?>>スタンダード</option>
                  <option value="pro" <?php selected($user_plan, 'pro'); ?>>プロ</option>
                </select>
                <small style="color: var(--mp-text-muted);">※上部表示と資料ロックに連動します</small>
              </div>
            </div>

            <?php
            $opts_arr = array_values($option_rows);
            $opts_keys = array_keys($option_rows);
            for ($i = 0; $i < count($opts_arr); $i += 2) :
              $k1 = $opts_keys[$i]; $r1 = $opts_arr[$i];
              $n1 = $k1 === '1on1' ? 'option_1on1' : 'option_' . $k1;
              $k2 = isset($opts_keys[$i+1]) ? $opts_keys[$i+1] : null;
              $r2 = isset($opts_arr[$i+1]) ? $opts_arr[$i+1] : null;
              $n2 = $k2 ? ($k2 === '1on1' ? 'option_1on1' : 'option_' . $k2) : '';
            ?>
            <div class="mypage-form-row">
              <div class="mypage-form-group">
                <label class="mypage-form-label"><?php echo esc_html($r1[0]); ?> (数量)</label>
                <input type="number" name="<?php echo esc_attr($n1); ?>" class="mypage-form-input" value="<?php echo esc_attr($option_values[$k1]); ?>" min="0">
              </div>
              <?php if ($k2) : ?>
              <div class="mypage-form-group">
                <label class="mypage-form-label"><?php echo esc_html($r2[0]); ?> (数量)</label>
                <input type="number" name="<?php echo esc_attr($n2); ?>" class="mypage-form-input" value="<?php echo esc_attr($option_values[$k2]); ?>" min="0">
              </div>
              <?php endif; ?>
            </div>
            <?php endfor; ?>
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
              <button type="submit" class="mypage-btn mypage-btn-success" style="padding-left: 3rem; padding-right: 3rem;">
                <i class="fas fa-save"></i> 管理者情報を保存
              </button>
            </div>
          </form>
        </div>
      </section>
      <?php endif; ?>
      
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
      <a href="mailto:info@freeplancompany.com?subject=退会申請&body=退会を希望します。%0A%0Aユーザー名: <?php echo esc_attr($user_name); ?>%0Aメールアドレス: <?php echo esc_attr($user_email); ?>" class="mypage-btn mypage-btn-danger">
        <i class="fas fa-trash-alt"></i> 退会申請を送信
      </a>
    </div>
  </div>
</div>

<!-- フローティング合計バー（プランセクション表示時に表示） -->
<div class="mypage-floating-total" id="floatingTotalBar" aria-hidden="true">
  <span class="mypage-floating-total-label">月額合計（税込）</span>
  <span class="js-total-amount">¥<?php echo number_format($initial_total); ?></span>
  <button type="button" class="mypage-floating-payment-btn js-payment-btn">
    <i class="fas fa-credit-card"></i> 決済する
  </button>
</div>

<script>
// モーダル制御
function openPasswordModal() {
  document.getElementById('passwordModal').classList.add('is-active');
}
function closePasswordModal() {
  document.getElementById('passwordModal').classList.remove('is-active');
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
  const searchInput = document.getElementById('searchInput');
  if (searchInput) {
    searchInput.addEventListener('input', function(e) {
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
  }

  // 金額計算ロジック (12オプション対応)
  const planPrices = {
    basic: <?php echo $prices['basic']; ?>,
    standard: <?php echo $prices['standard']; ?>,
    pro: <?php echo $prices['pro']; ?>,
    premium: <?php echo $prices['pro']; ?>
  };
  const optionPrices = <?php echo json_encode($prices); ?>;

  const planRadios = document.querySelectorAll('input[name="plan_select"]');
  const qtyInputs = document.querySelectorAll('.qty-input');
  const totalDisplays = document.querySelectorAll('.js-total-amount');

  const optionInputNames = ['option_1on1','option_customize','option_note_check','option_meeting','option_flow','option_review','option_tag','option_theme','option_improve','option_long'];
  const optionPriceKeys = ['1on1','customize','note_check','meeting','flow','review','tag','theme','improve','long'];

  function calculateTotal() {
    let total = 0;
    
    const selectedPlan = document.querySelector('input[name="plan_select"]:checked');
    if (selectedPlan) {
      total += planPrices[selectedPlan.value] || 0;
      const displayPlanName = document.getElementById('displayPlanName');
      const historyPlanName = document.getElementById('historyPlanName');
      if (displayPlanName) {
        const planNames = { basic: 'ベーシック', standard: 'スタンダード', pro: 'プロ' };
        displayPlanName.textContent = planNames[selectedPlan.value];
        if (historyPlanName) historyPlanName.textContent = planNames[selectedPlan.value];
      }
    }

    for (let i = 0; i < optionInputNames.length; i++) {
      const inp = document.querySelector('input[name="' + optionInputNames[i] + '"]');
      const qty = inp ? (parseInt(inp.value) || 0) : 0;
      const key = optionPriceKeys[i];
      total += qty * (optionPrices[key] || 0);
    }
    
    totalDisplays.forEach(function(el) {
      el.textContent = '¥' + total.toLocaleString();
    });
  }

  // 初期表示時に計算を実行
  calculateTotal();

  // イベントリスナーの登録
  planRadios.forEach(radio => radio.addEventListener('change', calculateTotal));
  qtyInputs.forEach(input => {
    input.addEventListener('input', calculateTotal);
    input.addEventListener('change', calculateTotal);
  });

  // 決済ボタンクリック（ヒーロー＋フローティングバー）→ 支払い情報入力ページへ遷移
  document.querySelectorAll('.js-payment-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      const selectedPlan = document.querySelector('input[name="plan_select"]:checked');
      if (!selectedPlan) {
        alert('プランを選択してください。');
        return;
      }
      const plan = selectedPlan.value;
      const params = new URLSearchParams({ plan: plan });
      var optParamMap = {'option_1on1':'opt_1on1','option_customize':'opt_customize','option_note_check':'opt_note_check','option_meeting':'opt_meeting','option_flow':'opt_flow','option_review':'opt_review','option_tag':'opt_tag','option_theme':'opt_theme','option_improve':'opt_improve','option_long':'opt_long'};
      optionInputNames.forEach(function(name) {
        const inp = document.querySelector('input[name="' + name + '"]');
        const key = optParamMap[name] || ('opt_' + name.replace('option_',''));
        params.set(key, (inp && inp.value) ? inp.value : '0');
      });
      window.location.href = '<?php echo esc_url(home_url("/payment")); ?>?' + params.toString();
    });
  });

  // フローティング合計バー：プランセクションが画面内に入ったら表示
  const planSection = document.getElementById('planSection');
  const floatingBar = document.getElementById('floatingTotalBar');
  if (planSection && floatingBar) {
    const observer = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          floatingBar.classList.add('is-visible');
          floatingBar.setAttribute('aria-hidden', 'false');
          document.body.classList.add('mypage-floating-bar-visible');
        } else {
          floatingBar.classList.remove('is-visible');
          floatingBar.setAttribute('aria-hidden', 'true');
          document.body.classList.remove('mypage-floating-bar-visible');
        }
      });
    }, {
      threshold: 0.02,
      rootMargin: '0px'
    });
    observer.observe(planSection);
  }

  // 決済確認・完了モーダルは廃止（支払いページで完結するため）。閉じる関数は他で参照されないが残しておく
  window.closeConfirmModal = function() {
    var m = document.getElementById('paymentConfirmModal');
    if (m) m.classList.remove('is-active');
  };
  window.closePaymentModal = function() {
    var m = document.getElementById('paymentCompleteModal');
    if (m) m.classList.remove('is-active');
  };
</script>

<?php wp_footer(); ?>

</body>
</html>
