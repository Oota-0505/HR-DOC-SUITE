<?php
/**
 * Template Name: ログインページ
 * Description: ユーザーログイン用のカスタムページテンプレート
 *
 * @package HR_DOC_SUITE
 * @since 1.0.0
 * 
 * 画面フロー:
 * - GET  → ログイン画面を表示
 * - POST → DBにリクエスト → 管理者チェック → リダイレクト
 *   - 管理者(role_type == 1) or 未登録 → /select-user
 *   - 一般ユーザー → /mypage
 */

// 直接アクセス禁止
if (!defined('ABSPATH')) {
    exit;
}

// ログイン済みならリダイレクト
if (is_user_logged_in()) {
    $current_user = wp_get_current_user();

    // WP管理者（wp-admin用ログイン）と、サービス利用者（custom_users）を混同しない
    $is_wp_admin = user_can($current_user, 'manage_options');

    // カスタムユーザーテーブルからrole_typeを確認
    global $wpdb;
    $custom_user = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}custom_users WHERE wp_user_id = %d",
            $current_user->ID
        )
    );

    /**
     * ✅ 重要
     * - WP管理者で、custom_users に未登録（=運営アカウントとしては未設定）の場合は、
     *   このページを表示できるようにする（ここでリダイレクトしない）
     * - それ以外は従来通り：
     *   - 管理者(role_type==1) or 未登録 → /select-user
     *   - 一般ユーザー → /mypage
     */
    if (!($is_wp_admin && $custom_user === null)) {
        if ($custom_user === null || intval($custom_user->role_type) === 1) {
            wp_redirect(home_url('/select-user'));
        } else {
            wp_redirect(home_url('/mypage'));
        }
        exit;
    }
}

// ログイン処理（POSTの場合）
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hrdoc_login'])) {
    
    // nonce検証（セキュリティ強化）
    if (!isset($_POST['hrdoc_login_nonce']) || !wp_verify_nonce($_POST['hrdoc_login_nonce'], 'hrdoc_login_action')) {
        $login_error = '不正なリクエストです。再度お試しください。';
    } else {
        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];
        $remember = isset($_POST['remember']) ? true : false;
        
        // メールアドレスからユーザーを取得（仕様書: $user = get_user_by('email', $login);）
        $user = get_user_by('email', $email);
        
        if ($user) {
            $creds = array(
                'user_login'    => $user->user_login,
                'user_password' => $password,
                'remember'      => $remember
            );
            
            $login_result = wp_signon($creds, false);
            
            if (!is_wp_error($login_result)) {
                // ログイン成功：セッションを確実にセット
                wp_set_current_user($login_result->ID);

                // カスタムユーザーテーブルからrole_typeを確認
                global $wpdb;
                $custom_user = $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT * FROM {$wpdb->prefix}custom_users WHERE wp_user_id = %d",
                        $login_result->ID
                    )
                );

                /**
                 * 管理者かチェック（仕様書）
                 * if (($custom_user == null) || ($custom_user->role_type == 1)) {
                 *     wp_redirect('/wordpress/select-user');
                 * } else {
                 *     wp_redirect('/wordpress/my-page');
                 * }
                 */
                if ($custom_user === null || $custom_user->role_type == 1) {
                    wp_redirect(home_url('/select-user'));
                } else {
                    wp_redirect(home_url('/mypage'));
                }
                exit;
            } else {
                $login_error = 'メールアドレスまたはパスワードが正しくありません。';
            }
        } else {
            $login_error = 'メールアドレスまたはパスワードが正しくありません。';
        }
    }
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン | <?php bloginfo('name'); ?></title>
  <?php wp_head(); ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    /* ============================================
       ログインページ専用CSS - Scoped Styles
       プレフィックス: .auth-login- を使用
       詳細度を高めて競合を防止
    ============================================ */
    
    /* === CSS Variables === */
    .auth-login-container {
      --al-primary: #2563eb;
      --al-primary-hover: #1d4ed8;
      --al-navy: #0d1b3e;
      --al-bg-gradient: linear-gradient(135deg, #0d1b3e 0%, #1e40af 50%, #3b82f6 100%);
      --al-text-main: #1f2937;
      --al-text-muted: #6b7280;
      --al-input-bg: #f3f4f6;
      --al-white: #ffffff;
      --al-border: #e2e8f0;
    }
    
    /* === Reset === */
    .auth-login-container,
    .auth-login-container *,
    .auth-login-container *::before,
    .auth-login-container *::after {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    /* === Base Body Styles === */
    .auth-login-container {
      font-family: 'Inter', 'Noto Sans JP', sans-serif;
      min-height: 100vh;
      position: relative;
    }
    
    /* === Background === */
    .auth-login-container .auth-login-background {
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
    
    .auth-login-container .auth-login-background::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle at center, rgba(37, 99, 235, 0.03) 0%, transparent 40%);
      animation: auth-login-rotate 60s linear infinite;
      pointer-events: none;
    }
    
    @keyframes auth-login-rotate {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
    
    /* === Content Wrapper === */
    .auth-login-container .auth-login-content {
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
    .auth-login-container .auth-login-header {
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
    
    .auth-login-container .auth-login-header-logo img {
      height: 32px;
      filter: brightness(0) invert(1);
    }
    
    .auth-login-container .auth-login-header-back,
    .auth-login-container a.auth-login-header-back {
      display: flex;
      align-items: center;
      gap: 8px;
      color: #fff;
      text-decoration: none;
      font-size: 0.9rem;
      opacity: 0.8;
      transition: opacity 0.2s;
    }
    
    .auth-login-container .auth-login-header-back:hover,
    .auth-login-container a.auth-login-header-back:hover {
      opacity: 1;
    }
    
    /* === Main Card Wrapper === */
    .auth-login-container .auth-login-wrapper {
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
    
    /* === Info Side (Left) === */
    .auth-login-container .auth-login-side-info {
      flex: 1;
      background: var(--al-bg-gradient);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: var(--al-white);
      padding: 40px;
      text-align: center;
      position: relative;
      z-index: 2;
      clip-path: ellipse(100% 100% at 0% 50%);
    }
    
    .auth-login-container .auth-login-side-info h2 {
      font-size: 2.2rem;
      font-weight: 800;
      margin-bottom: 20px;
      line-height: 1.3;
      color: var(--al-white);
    }
    
    /* === Side Info Paragraph - 色を白に強制指定（詳細度を高めて競合を防止） === */
    .auth-login-container .auth-login-side-info p,
    .auth-login-container .auth-login-side-info p * {
      font-size: 1rem !important;
      line-height: 1.6 !important;
      margin-bottom: 40px !important;
      max-width: 300px !important;
      opacity: 0.9 !important;
      color: #ffffff !important;
      color: var(--al-white) !important;
    }
    
    .auth-login-container .auth-login-btn-outline,
    .auth-login-container a.auth-login-btn-outline {
      padding: 12px 40px;
      border: 2px solid var(--al-white);
      background: transparent;
      color: var(--al-white);
      border-radius: 12px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
    }
    
    .auth-login-container .auth-login-btn-outline:hover,
    .auth-login-container a.auth-login-btn-outline:hover {
      background: var(--al-white);
      color: var(--al-navy);
    }
    
    /* === Form Side (Right) === */
    .auth-login-container .auth-login-side-form {
      flex: 1.2;
      background: #ffffff;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 50px;
    }
    
    .auth-login-container .auth-login-side-form h2 {
      font-size: 2rem;
      font-weight: 800;
      color: var(--al-navy);
      margin-bottom: 30px;
    }
    
    .auth-login-container .auth-login-logo-top {
      margin-bottom: 20px;
    }
    
    .auth-login-container .auth-login-logo-top img {
      height: 40px;
    }
    
    /* === Form Container === */
    .auth-login-container .auth-login-form-container {
      width: 100%;
      max-width: 360px;
    }
    
    /* === Error Message === */
    .auth-login-container .auth-login-error {
      color: #dc2626;
      background: #fef2f2;
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 0.9rem;
      border: 1px solid #fecaca;
    }
    
    /* === Input Field === */
    .auth-login-container .auth-login-input-field {
      width: 100%;
      background: var(--al-input-bg);
      margin-bottom: 15px;
      height: 50px;
      border-radius: 50px;
      display: grid;
      grid-template-columns: 15% 85%;
      padding: 0 1rem;
      align-items: center;
    }
    
    .auth-login-container .auth-login-input-field i {
      text-align: center;
      color: #acacac;
      font-size: 1rem;
    }
    
    .auth-login-container .auth-login-input-field input {
      background: none;
      outline: none;
      border: none;
      font-weight: 500;
      font-size: 1rem;
      color: var(--al-navy);
      width: 100%;
      font-family: inherit;
    }
    
    .auth-login-container .auth-login-input-field input::placeholder {
      color: #9ca3af;
    }
    
    /* === Form Options === */
    .auth-login-container .auth-login-form-options {
      margin: 15px 0 20px 10px;
    }
    
    .auth-login-container .auth-login-checkbox-container {
      display: flex;
      align-items: center;
      cursor: pointer;
      user-select: none;
      gap: 10px;
    }
    
    .auth-login-container .auth-login-checkbox-container input {
      display: none;
    }
    
    .auth-login-container .auth-login-checkmark {
      width: 18px;
      height: 18px;
      background-color: var(--al-input-bg);
      border: 1px solid var(--al-border);
      border-radius: 4px;
      display: inline-block;
      position: relative;
      transition: 0.2s;
      flex-shrink: 0;
    }
    
    .auth-login-container .auth-login-checkbox-container input:checked ~ .auth-login-checkmark {
      background-color: var(--al-primary);
      border-color: var(--al-primary);
    }
    
    .auth-login-container .auth-login-checkmark::after {
      content: "";
      position: absolute;
      display: none;
      left: 6px;
      top: 2px;
      width: 4px;
      height: 8px;
      border: solid white;
      border-width: 0 2px 2px 0;
      transform: rotate(45deg);
    }
    
    .auth-login-container .auth-login-checkbox-container input:checked ~ .auth-login-checkmark::after {
      display: block;
    }
    
    .auth-login-container .auth-login-label-text {
      font-size: 0.85rem;
      color: var(--al-text-muted);
      font-weight: 500;
    }
    
    /* === Submit Button === */
    .auth-login-container .auth-login-btn-solid,
    .auth-login-container button.auth-login-btn-solid {
      width: 100%;
      background: linear-gradient(135deg, var(--al-primary) 0%, var(--al-navy) 100%);
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
    
    .auth-login-container .auth-login-btn-solid:hover,
    .auth-login-container button.auth-login-btn-solid:hover {
      transform: translateY(-4px) scale(1.02);
      box-shadow: 0 20px 40px rgba(37, 99, 235, 0.4);
      background: linear-gradient(135deg, #3b82f6 0%, var(--al-primary) 100%);
    }
    
    /* === Responsive === */
    @media (max-width: 870px) {
      .auth-login-container .auth-login-wrapper {
        flex-direction: column;
        height: auto;
        max-width: 500px;
        border-radius: 20px;
      }
      
      .auth-login-container .auth-login-side-info {
        clip-path: none;
        padding: 40px 20px;
      }
      
      .auth-login-container .auth-login-side-form {
        padding: 40px 20px;
      }
    }
  </style>
</head>
<body>
<?php wp_body_open(); ?>

<div class="auth-login-container">
  <!-- 背景ラッパー -->
  <div class="auth-login-background"></div>
  
  <!-- コンテンツラッパー -->
  <div class="auth-login-content">
    <!-- トップページへ戻るヘッダー -->
    <header class="auth-login-header">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="auth-login-header-logo">
        <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="<?php bloginfo('name'); ?>">
      </a>
      <a href="<?php echo esc_url(home_url('/')); ?>" class="auth-login-header-back">
        <i class="fas fa-arrow-left"></i> トップに戻る
      </a>
    </header>

    <div class="auth-login-wrapper">
      <!-- 左側：新規登録案内（曲線デザイン） -->
      <div class="auth-login-side-info">
        <h2>はじめての方はこちら</h2>
        <p>アカウントを作成して、採用と評価のドキュメント作成を効率化しましょう。</p>
        <a href="<?php echo esc_url(home_url('/register')); ?>" class="auth-login-btn-outline">新規登録</a>
      </div>

      <!-- 右側：ログインフォーム -->
      <div class="auth-login-side-form">
        <div class="auth-login-logo-top">
          <a href="<?php echo esc_url(home_url('/')); ?>">
            <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="<?php bloginfo('name'); ?>">
          </a>
        </div>
        <h2>ログイン</h2>
        
        <?php if ($login_error) : ?>
          <div class="auth-login-error">
            <i class="fas fa-exclamation-circle"></i> <?php echo esc_html($login_error); ?>
          </div>
        <?php endif; ?>
        
        <div class="auth-login-form-container">
          <form action="" method="post">
            <?php wp_nonce_field('hrdoc_login_action', 'hrdoc_login_nonce'); ?>
            <input type="hidden" name="hrdoc_login" value="1">
            
            <div class="auth-login-input-field">
              <i class="fas fa-envelope"></i>
              <input type="email" name="email" placeholder="メールアドレス" required>
            </div>
            <div class="auth-login-input-field">
              <i class="fas fa-lock"></i>
              <input type="password" name="password" placeholder="パスワード" required>
            </div>
            <div class="auth-login-form-options">
              <label class="auth-login-checkbox-container">
                <input type="checkbox" name="remember">
                <span class="auth-login-checkmark"></span>
                <span class="auth-login-label-text">次回から自動的にログイン</span>
              </label>
            </div>
            <button type="submit" class="auth-login-btn-solid">
              ログインする
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
