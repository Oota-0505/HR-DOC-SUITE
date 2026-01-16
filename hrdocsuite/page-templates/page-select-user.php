<?php
/**
 * Template Name: ユーザー選択ページ
 * Description: 管理者が管理対象ユーザーを選択するページ
 *
 * @package HR_DOC_SUITE
 * @since 1.0.0
 * 
 * 画面フロー:
 * 1. DBからユーザー情報を取得
 * 2. ユーザー情報をドロップダウンリストに表示
 * 3. 選択後 → マイページへ遷移
 * 
 * アクセス条件:
 * - ログイン済み
 * - 管理者（role_type == 1）または未登録ユーザー
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

$current_user = wp_get_current_user();
global $wpdb;

// 管理者チェック（role_type == 1 のみアクセス可能）
$current_custom_user = $wpdb->get_row(
    $wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}custom_users WHERE wp_user_id = %d",
        $current_user->ID
    )
);

// 管理者でない場合はマイページへリダイレクト
if ($current_custom_user !== null && $current_custom_user->role_type != 1) {
    wp_redirect(home_url('/mypage'));
    exit;
}

/**
 * DBからユーザー情報を取得（仕様書）
 * 
 * global $wpdb;
 * $custom_user = $wpdb->get_results(
 *     $wpdb->prepare(
 *         "SELECT * FROM {$wpdb->prefix}custom_users"
 *     )
 * );
 */
$custom_users = $wpdb->get_results(
    "SELECT * FROM {$wpdb->prefix}custom_users ORDER BY name ASC"
);

// ユーザー選択処理（POST）
$select_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_user'])) {
    
    // nonce検証（セキュリティ強化）
    if (!isset($_POST['hrdoc_select_user_nonce']) || !wp_verify_nonce($_POST['hrdoc_select_user_nonce'], 'hrdoc_select_user_action')) {
        $select_error = '不正なリクエストです。再度お試しください。';
    } else {
        $selected_user_id = intval($_POST['selected_user']);
        
        // 選択されたユーザーが存在するか確認
        $selected_user = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}custom_users WHERE id = %d",
                $selected_user_id
            )
        );
        
        if ($selected_user) {
            // セッションに選択したユーザーIDを保存
            if (!session_id()) {
                session_start();
            }
            $_SESSION['managed_user_id'] = $selected_user_id;
            $_SESSION['managed_user_name'] = $selected_user->name;
            
            // マイページへリダイレクト（wp_user_id をパラメータとして渡す）
            wp_redirect(home_url('/mypage?user_id=' . $selected_user->wp_user_id));
            exit;
        } else {
            $select_error = '選択されたユーザーが見つかりません。';
        }
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
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    /* ============================================
       ユーザー選択ページ専用CSS - Scoped Styles
       プレフィックス: .auth-select- を使用
       詳細度を高めて競合を防止
    ============================================ */
    
    /* === CSS Variables === */
    .auth-select-container {
      --as-primary: #2563eb;
      --as-primary-hover: #1d4ed8;
      --as-navy: #0d1b3e;
      --as-bg-gradient: linear-gradient(135deg, #0d1b3e 0%, #1e40af 50%, #3b82f6 100%);
      --as-text-main: #1f2937;
      --as-text-muted: #6b7280;
      --as-input-bg: #f3f4f6;
      --as-white: #ffffff;
      --as-border: #e2e8f0;
    }
    
    /* === Reset === */
    .auth-select-container,
    .auth-select-container *,
    .auth-select-container *::before,
    .auth-select-container *::after {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    /* === Base Body Styles === */
    .auth-select-container {
      font-family: 'Inter', 'Noto Sans JP', sans-serif;
      min-height: 100vh;
      position: relative;
    }
    
    /* === Background === */
    .auth-select-container .auth-select-background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: #0d1b3e;
      background-image: 
        radial-gradient(at 0% 0%, rgba(37, 99, 235, 0.15) 0px, transparent 50%),
        radial-gradient(at 100% 100%, rgba(30, 58, 138, 0.2) 0px, transparent 50%),
        radial-gradient(at 100% 0%, rgba(59, 130, 246, 0.1) 0px, transparent 50%),
        radial-gradient(at 0% 100%, rgba(29, 78, 216, 0.1) 0px, transparent 50%);
      z-index: 0;
      overflow: hidden;
    }
    
    .auth-select-container .auth-select-background::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle at center, rgba(37, 99, 235, 0.03) 0%, transparent 40%);
      animation: auth-select-rotate 60s linear infinite;
      pointer-events: none;
    }
    
    @keyframes auth-select-rotate {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
    
    /* === Content Wrapper === */
    .auth-select-container .auth-select-content {
      position: relative;
      z-index: 1;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      padding-top: 100px;
    }
    
    /* === Header === */
    .auth-select-container .auth-select-header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 16px 24px;
      background: rgba(13, 27, 62, 0.9);
      backdrop-filter: blur(10px);
      z-index: 100;
    }
    
    .auth-select-container .auth-select-header-logo img {
      height: 32px;
      filter: brightness(0) invert(1);
    }
    
    .auth-select-container .auth-select-header-back,
    .auth-select-container a.auth-select-header-back {
      display: flex;
      align-items: center;
      gap: 8px;
      color: #fff;
      text-decoration: none;
      font-size: 0.9rem;
      opacity: 0.8;
      transition: opacity 0.2s;
    }
    
    .auth-select-container .auth-select-header-back:hover,
    .auth-select-container a.auth-select-header-back:hover {
      opacity: 1;
    }
    
    /* === Single Card Container === */
    .auth-select-container .auth-select-card {
      width: 100%;
      max-width: 480px;
      background: #ffffff;
      border-radius: 24px;
      padding: 40px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
      position: relative;
      z-index: 1;
      border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    /* === Logo === */
    .auth-select-container .auth-select-logo {
      margin-bottom: 30px;
      text-align: center;
    }
    
    .auth-select-container .auth-select-logo img {
      height: 45px;
    }
    
    /* === Title === */
    .auth-select-container .auth-select-title,
    .auth-select-container h2.auth-select-title {
      text-align: center;
      margin-bottom: 30px;
      color: var(--as-navy);
      font-weight: 800;
      font-size: 1.5rem;
    }
    
    /* === Admin Badge === */
    .auth-select-container .auth-select-admin-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: linear-gradient(135deg, var(--as-primary) 0%, #06b6d4 100%);
      color: #fff;
      font-size: 0.75rem;
      font-weight: 600;
      padding: 6px 12px;
      border-radius: 100px;
      margin-bottom: 20px;
    }
    
    .auth-select-container .auth-select-admin-badge i {
      font-size: 0.7rem;
    }
    
    /* === Error Message === */
    .auth-select-container .auth-select-error {
      color: #dc2626;
      background: #fef2f2;
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 0.9rem;
      text-align: center;
      border: 1px solid #fecaca;
    }
    
    /* === Form Container === */
    .auth-select-container .auth-select-form-container {
      width: 100%;
    }
    
    /* === Form Group === */
    .auth-select-container .auth-select-form-group {
      margin-bottom: 20px;
    }
    
    .auth-select-container .auth-select-form-label {
      font-size: 0.9rem;
      color: var(--as-text-muted);
      margin-bottom: 8px;
      display: block;
    }
    
    /* === Input Field === */
    .auth-select-container .auth-select-input-field {
      width: 100%;
      background: var(--as-input-bg);
      height: 50px;
      border-radius: 50px;
      display: grid;
      grid-template-columns: 15% 85%;
      padding: 0 1rem;
      align-items: center;
    }
    
    .auth-select-container .auth-select-input-field i {
      text-align: center;
      color: #acacac;
      font-size: 1rem;
    }
    
    .auth-select-container .auth-select-input-field select {
      background: transparent;
      border: none;
      outline: none;
      width: 100%;
      font-size: 1rem;
      color: var(--as-navy);
      cursor: pointer;
      font-family: inherit;
      appearance: none;
      -webkit-appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E");
      background-position: right 0 center;
      background-repeat: no-repeat;
      background-size: 1.5rem;
      padding-right: 2rem;
    }
    
    .auth-select-container .auth-select-input-field select option {
      padding: 10px;
    }
    
    /* === Submit Button === */
    .auth-select-container .auth-select-btn-solid,
    .auth-select-container button.auth-select-btn-solid {
      width: 100%;
      background: linear-gradient(135deg, var(--as-primary) 0%, var(--as-navy) 100%);
      color: #fff;
      padding: 18px 32px;
      border-radius: 12px;
      font-weight: 700;
      font-size: 1.05rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      box-shadow: 0 10px 30px rgba(37, 99, 235, 0.3);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      border: 1px solid rgba(255, 255, 255, 0.1);
      cursor: pointer;
      letter-spacing: 0.05em;
      text-decoration: none;
      margin: 10px 0;
      font-family: inherit;
    }
    
    .auth-select-container .auth-select-btn-solid:hover,
    .auth-select-container button.auth-select-btn-solid:hover {
      transform: translateY(-4px) scale(1.02);
      box-shadow: 0 20px 40px rgba(37, 99, 235, 0.4);
      background: linear-gradient(135deg, #3b82f6 0%, var(--as-primary) 100%);
    }
    
    .auth-select-container .auth-select-btn-solid svg {
      width: 18px;
      height: 18px;
    }
    
    /* === Footer Links === */
    .auth-select-container .auth-select-footer-links {
      text-align: center;
      margin-top: 30px;
    }
    
    .auth-select-container .auth-select-footer-link,
    .auth-select-container a.auth-select-footer-link {
      color: var(--as-text-muted);
      text-decoration: none;
      font-size: 0.9rem;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: color 0.2s;
    }
    
    .auth-select-container .auth-select-footer-link:hover,
    .auth-select-container a.auth-select-footer-link:hover {
      color: var(--as-primary);
    }
    
    .auth-select-container .auth-select-footer-divider {
      height: 16px;
      width: 1px;
      background: var(--as-border);
      display: inline-block;
      margin: 0 16px;
      vertical-align: middle;
    }
    
    /* === User Count Badge === */
    .auth-select-container .auth-select-user-count {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 12px;
      background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
      border-radius: 12px;
      margin-bottom: 20px;
      font-size: 0.875rem;
      color: var(--as-primary);
      font-weight: 500;
    }
    
    .auth-select-container .auth-select-user-count i {
      font-size: 1rem;
    }
    
    /* === Responsive === */
    @media (max-width: 520px) {
      .auth-select-container .auth-select-card {
        padding: 30px 20px;
        border-radius: 20px;
      }
      
      .auth-select-container .auth-select-footer-divider {
        display: none;
      }
      
      .auth-select-container .auth-select-footer-links {
        display: flex;
        flex-direction: column;
        gap: 12px;
      }
    }
  </style>
</head>
<body>
<?php wp_body_open(); ?>

<div class="auth-select-container">
  <!-- 背景ラッパー -->
  <div class="auth-select-background"></div>
  
  <!-- コンテンツラッパー -->
  <div class="auth-select-content">
    <!-- トップページへ戻るヘッダー -->
    <header class="auth-select-header">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="auth-select-header-logo">
        <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="<?php bloginfo('name'); ?>">
      </a>
      <a href="<?php echo esc_url(home_url('/')); ?>" class="auth-select-header-back">
        <i class="fas fa-arrow-left"></i> トップに戻る
      </a>
    </header>

    <div class="auth-select-card">
      <div class="auth-select-logo">
        <a href="<?php echo esc_url(home_url('/')); ?>">
          <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="<?php bloginfo('name'); ?>">
        </a>
      </div>
      
      <div style="text-align: center;">
        <span class="auth-select-admin-badge">
          <i class="fas fa-crown"></i> 管理者モード
        </span>
      </div>

      <h2 class="auth-select-title">ユーザー選択</h2>
      
      <?php if ($select_error) : ?>
        <div class="auth-select-error">
          <i class="fas fa-exclamation-circle"></i> <?php echo esc_html($select_error); ?>
        </div>
      <?php endif; ?>
      
      <?php if ($custom_users && count($custom_users) > 0) : ?>
        <div class="auth-select-user-count">
          <i class="fas fa-users"></i>
          登録ユーザー: <?php echo count($custom_users); ?>名
        </div>
      <?php endif; ?>
      
      <div class="auth-select-form-container">
        <form action="" method="post">
          <?php wp_nonce_field('hrdoc_select_user_action', 'hrdoc_select_user_nonce'); ?>
          
          <div class="auth-select-form-group">
            <label class="auth-select-form-label">管理対象ユーザーを選択してください</label>
            <div class="auth-select-input-field">
              <i class="fas fa-user-circle"></i>
              <select name="selected_user" required>
                <option value="" disabled selected>選択してください</option>
                <?php if ($custom_users) : ?>
                  <?php foreach ($custom_users as $user) : ?>
                    <option value="<?php echo esc_attr($user->id); ?>">
                      <?php echo esc_html($user->name); ?>
                      <?php if (!empty($user->company_name)) : ?>
                        （<?php echo esc_html($user->company_name); ?>）
                      <?php endif; ?>
                    </option>
                  <?php endforeach; ?>
                <?php else : ?>
                  <option value="" disabled>登録ユーザーがいません</option>
                <?php endif; ?>
              </select>
            </div>
          </div>
          
          <button type="submit" class="auth-select-btn-solid">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14M22 4L12 14.01l-3-3"/></svg>
            選択して管理画面へ
          </button>
        </form>

        <div class="auth-select-footer-links">
          <a href="<?php echo esc_url(home_url('/')); ?>" class="auth-select-footer-link">
            <i class="fas fa-home"></i> トップページに戻る
          </a>
          <span class="auth-select-footer-divider"></span>
          <a href="<?php echo wp_logout_url(home_url('/login')); ?>" class="auth-select-footer-link">
            <i class="fas fa-sign-out-alt"></i> ログアウト
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
