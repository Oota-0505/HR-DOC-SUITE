<?php
/**
 * Template Name: 新規登録ページ
 * Description: ユーザー登録用のカスタムページテンプレート
 *
 * @package HR_DOC_SUITE
 * @since 1.0.0
 * 
 * 画面フロー:
 * 1. ユーザー情報を入力
 * 2. ユーザー情報をDBに保存
 * 3. 自動ログイン → マイページへ遷移
 * 
 * 入力フィールド（仕様書）:
 * - name (text, required)
 * - email (email, required)
 * - phone (tel, optional)
 * - address (text, optional)
 * - password (password, required)
 */

// 直接アクセス禁止
if (!defined('ABSPATH')) {
    exit;
}

// デバッグログ：POSTリクエストが届いているか確認
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log('[HRDOC Register] POST request received. Action: ' . ($_POST['hrdoc_register_action'] ?? 'none'));
}

// ログイン済みならマイページへリダイレクト
if (is_user_logged_in()) {
    wp_safe_redirect(home_url('/mypage/'));
    exit;
}

// 登録処理（register-handler.php で処理するため、ここではエラー表示のみ）
$register_error = '';
if (isset($_GET['register_error'])) {
    $register_error = urldecode($_GET['register_error']);
}
$register_success = false;

// 以前のPOST処理をコメントアウトまたは削除
/*
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hrdoc_register'])) {
    // ... (以前のコード) ...
}
*/
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>新規登録 | <?php bloginfo('name'); ?></title>
  <?php wp_head(); ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    /* ============================================
       新規登録ページ専用CSS - Scoped Styles
       プレフィックス: .auth-register- を使用
       詳細度を高めて競合を防止
    ============================================ */
    
    /* === CSS Variables === */
    .auth-register-container {
      --ar-primary: #2563eb;
      --ar-primary-hover: #1d4ed8;
      --ar-navy: #0d1b3e;
      --ar-bg-gradient: linear-gradient(135deg, #0d1b3e 0%, #1e40af 50%, #3b82f6 100%);
      --ar-text-main: #1f2937;
      --ar-text-muted: #6b7280;
      --ar-input-bg: #f3f4f6;
      --ar-white: #ffffff;
      --ar-border: #e2e8f0;
    }
    
    /* === Reset === */
    .auth-register-container,
    .auth-register-container *,
    .auth-register-container *::before,
    .auth-register-container *::after {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    /* === Base Body Styles === */
    .auth-register-container {
      font-family: 'Inter', 'Noto Sans JP', sans-serif;
      min-height: 100vh;
      position: relative;
    }
    
    /* === Background === */
    .auth-register-container .auth-register-background {
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
    
    .auth-register-container .auth-register-background::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle at center, rgba(37, 99, 235, 0.03) 0%, transparent 40%);
      animation: auth-register-rotate 60s linear infinite;
      pointer-events: none;
    }
    
    @keyframes auth-register-rotate {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
    
    /* === Content Wrapper === */
    .auth-register-container .auth-register-content {
      position: relative;
      z-index: 1;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      padding-top: 100px;
      padding-bottom: 40px;
    }
    
    /* === Header === */
    .auth-register-container .auth-register-header {
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
    
    .auth-register-container .auth-register-header-logo img {
      height: 32px;
      filter: brightness(0) invert(1);
    }
    
    .auth-register-container .auth-register-header-back,
    .auth-register-container a.auth-register-header-back {
      display: flex;
      align-items: center;
      gap: 8px;
      color: #fff;
      text-decoration: none;
      font-size: 0.9rem;
      opacity: 0.8;
      transition: opacity 0.2s;
    }
    
    .auth-register-container .auth-register-header-back:hover,
    .auth-register-container a.auth-register-header-back:hover {
      opacity: 1;
    }
    
    /* === Main Card Wrapper === */
    .auth-register-container .auth-register-wrapper {
      width: 100%;
      max-width: 1000px;
      min-height: 600px;
      background: #ffffff;
      border-radius: 30px;
      display: flex;
      overflow: hidden;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
      position: relative;
      z-index: 1;
      border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    /* === Info Side (Left - reversed position) === */
    .auth-register-container .auth-register-side-info {
      flex: 1;
      background: var(--ar-bg-gradient);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: var(--ar-white);
      padding: 40px;
      text-align: center;
      position: relative;
      z-index: 2;
      clip-path: ellipse(100% 100% at 100% 50%);
    }
    
    .auth-register-container .auth-register-side-info h2 {
      font-size: 2.2rem;
      font-weight: 800;
      margin-bottom: 20px;
      line-height: 1.3;
      color: var(--ar-white);
    }
    
    /* === Side Info Paragraph - 色を白に強制指定（詳細度を高めて競合を防止） === */
    .auth-register-container .auth-register-side-info p,
    .auth-register-container .auth-register-side-info p * {
      font-size: 1rem !important;
      line-height: 1.6 !important;
      margin-bottom: 40px !important;
      max-width: 300px !important;
      opacity: 0.9 !important;
      color: #ffffff !important;
      color: var(--ar-white) !important;
    }
    
    .auth-register-container .auth-register-btn-outline,
    .auth-register-container a.auth-register-btn-outline {
      padding: 12px 40px;
      border: 2px solid var(--ar-white);
      background: transparent;
      color: var(--ar-white);
      border-radius: 12px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
    }
    
    .auth-register-container .auth-register-btn-outline:hover,
    .auth-register-container a.auth-register-btn-outline:hover {
      background: var(--ar-white);
      color: var(--ar-navy);
    }
    
    /* === Form Side (Right - reversed position) === */
    .auth-register-container .auth-register-side-form {
      flex: 1.2;
      background: #ffffff;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 50px;
      order: -1;
    }
    
    .auth-register-container .auth-register-side-form h2 {
      font-size: 2rem;
      font-weight: 800;
      color: var(--ar-navy);
      margin-bottom: 30px;
    }
    
    .auth-register-container .auth-register-logo-top {
      margin-bottom: 20px;
    }
    
    .auth-register-container .auth-register-logo-top img {
      height: 40px;
    }
    
    /* === Form Container === */
    .auth-register-container .auth-register-form-container {
      width: 100%;
      max-width: 400px;
    }
    
    /* === Error Message === */
    .auth-register-container .auth-register-error {
      color: #dc2626;
      background: #fef2f2;
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 0.9rem;
      border: 1px solid #fecaca;
    }
    
    /* === Input Field === */
    .auth-register-container .auth-register-input-field {
      width: 100%;
      background: var(--ar-input-bg);
      margin-bottom: 15px;
      height: 50px;
      border-radius: 50px;
      display: grid;
      grid-template-columns: 15% 85%;
      padding: 0 1rem;
      align-items: center;
    }
    
    .auth-register-container .auth-register-input-field i {
      text-align: center;
      color: #acacac;
      font-size: 1rem;
    }
    
    .auth-register-container .auth-register-input-field input {
      background: none;
      outline: none;
      border: none;
      font-weight: 500;
      font-size: 1rem;
      color: var(--ar-navy);
      width: 100%;
      font-family: inherit;
    }
    
    .auth-register-container .auth-register-input-field input::placeholder {
      color: #9ca3af;
    }
    
    /* === Submit Button === */
    .auth-register-container .auth-register-btn-solid,
    .auth-register-container button.auth-register-btn-solid {
      width: 100%;
      background: linear-gradient(135deg, var(--ar-primary) 0%, var(--ar-navy) 100%);
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
    
    .auth-register-container .auth-register-btn-solid:hover,
    .auth-register-container button.auth-register-btn-solid:hover {
      transform: translateY(-4px) scale(1.02);
      box-shadow: 0 20px 40px rgba(37, 99, 235, 0.4);
      background: linear-gradient(135deg, #3b82f6 0%, var(--ar-primary) 100%);
    }
    
    .auth-register-container .auth-register-btn-solid svg {
      width: 18px;
      height: 18px;
    }
    
    /* === Responsive === */
    @media (max-width: 870px) {
      .auth-register-container .auth-register-wrapper {
        flex-direction: column;
        height: auto;
        max-width: 500px;
        border-radius: 20px;
      }
      
      .auth-register-container .auth-register-side-info {
        clip-path: none;
        padding: 40px 20px;
        order: -1;
      }
      
      .auth-register-container .auth-register-side-form {
        padding: 40px 20px;
        order: 0;
      }
    }
  </style>
</head>
<body>
<?php wp_body_open(); ?>

<div class="auth-register-container">
  <!-- 背景ラッパー -->
  <div class="auth-register-background"></div>
  
  <!-- コンテンツラッパー -->
  <div class="auth-register-content">
    <!-- トップページへ戻るヘッダー -->
    <header class="auth-register-header">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="auth-register-header-logo">
        <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="<?php bloginfo('name'); ?>">
      </a>
      <a href="<?php echo esc_url(home_url('/')); ?>" class="auth-register-header-back">
        <i class="fas fa-arrow-left"></i> トップに戻る
      </a>
    </header>

    <div class="auth-register-wrapper">
      <!-- 左側：ログイン案内（曲線デザイン） -->
      <div class="auth-register-side-info">
        <h2>おかえりなさい！</h2>
        <p>既にアカウントをお持ちの方は、こちらからログインしてください。</p>
        <a href="<?php echo esc_url(home_url('/login')); ?>" class="auth-register-btn-outline">ログイン</a>
      </div>

      <!-- 右側：新規登録フォーム -->
      <div class="auth-register-side-form">
        <div class="auth-register-logo-top">
          <a href="<?php echo esc_url(home_url('/')); ?>">
            <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="<?php bloginfo('name'); ?>">
          </a>
        </div>
        <h2>新規登録</h2>
        
        <?php if ($register_error) : ?>
          <div class="auth-register-error">
            <i class="fas fa-exclamation-circle"></i> <?php echo esc_html($register_error); ?>
          </div>
        <?php endif; ?>
        
        <div class="auth-register-form-container">
          <!-- 独立したハンドラーへ送信 -->
          <form action="<?php echo get_template_directory_uri(); ?>/register-handler.php" method="post">
            <?php wp_nonce_field('hrdoc_register_action', 'hrdoc_register_nonce'); ?>
            <input type="hidden" name="hrdoc_register" value="1">
            
            <!-- 仕様書: name (text, required) -->
            <div class="auth-register-input-field">
              <i class="fas fa-user"></i>
              <input type="text" name="name" id="fullName" placeholder="お名前" required 
                     value="<?php echo isset($_POST['name']) ? esc_attr($_POST['name']) : ''; ?>">
            </div>
            
            <!-- 仕様書: email (email, required) -->
            <div class="auth-register-input-field">
              <i class="fas fa-envelope"></i>
              <input type="email" name="email" id="email" placeholder="メールアドレス" required
                     value="<?php echo isset($_POST['email']) ? esc_attr($_POST['email']) : ''; ?>">
            </div>
            
            <!-- 仕様書: phone (tel, optional) -->
            <div class="auth-register-input-field">
              <i class="fas fa-phone"></i>
              <input type="tel" name="phone" id="phone" placeholder="電話番号（任意）"
                     value="<?php echo isset($_POST['phone']) ? esc_attr($_POST['phone']) : ''; ?>">
            </div>
            
            <!-- 仕様書: address (text, optional) -->
            <div class="auth-register-input-field">
              <i class="fas fa-map-marker-alt"></i>
              <input type="text" name="address" id="address" placeholder="住所（任意）"
                     value="<?php echo isset($_POST['address']) ? esc_attr($_POST['address']) : ''; ?>">
            </div>
            
            <!-- 追加: company_name (ビジネス向け) -->
            <div class="auth-register-input-field">
              <i class="fas fa-building"></i>
              <input type="text" name="company_name" id="company_name" placeholder="会社名（任意）"
                     value="<?php echo isset($_POST['company_name']) ? esc_attr($_POST['company_name']) : ''; ?>">
            </div>
            
            <!-- 仕様書: password (password, required) -->
            <div class="auth-register-input-field">
              <i class="fas fa-lock"></i>
              <input type="password" name="password" id="password" placeholder="パスワード" required>
            </div>
            
            <!-- 追加: password_confirm (セキュリティ強化) -->
            <div class="auth-register-input-field">
              <i class="fas fa-lock"></i>
              <input type="password" name="password_confirm" id="password_confirm" placeholder="パスワード（確認）" required>
            </div>
            
            <button type="submit" class="auth-register-btn-solid">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 7a4 4 0 1 0 0-8 4 4 0 0 0 0 8M19 8v6M22 11h-6"/></svg>
              登録する
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
