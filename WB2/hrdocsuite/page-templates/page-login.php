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
 *   - 管理者(WordPress manage_options または custom_users role_type == 1) → /select-user
 *   - それ以外（一般ユーザー・未登録含む） → /mypage
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

    // 管理者: WordPress管理者 または custom_users の role_type === 1
    $is_custom_admin = ($custom_user !== null && (int) $custom_user->role_type === 1);
    if ($is_wp_admin || $is_custom_admin) {
        wp_redirect(home_url('/select-user'));
    } else {
        wp_redirect(home_url('/mypage'));
    }
    exit;
}

// ログイン処理（POSTの場合）
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hrdoc_login'])) {
    
    // nonce検証（セキュリティ強化）
    if (!isset($_POST['hrdoc_login_nonce']) || !wp_verify_nonce($_POST['hrdoc_login_nonce'], 'hrdoc_login_action')) {
        $login_error = '不正なリクエストです。再度お試しください。';
    } else {
        $login = isset($_POST['login']) ? sanitize_text_field($_POST['login']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $remember = isset($_POST['remember']) ? true : false;

        // メールアドレスまたはユーザー名でログイン（WordPress管理画面と同様）
        $user = get_user_by('email', $login);
        if (!$user) {
            $user = get_user_by('login', $login);
        }

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

                // 管理者: WordPress管理者 または custom_users の role_type === 1
                $is_wp_admin_after = user_can($login_result, 'manage_options');
                $is_custom_admin_after = ($custom_user !== null && (int) $custom_user->role_type === 1);
                if ($is_wp_admin_after || $is_custom_admin_after) {
                    wp_redirect(home_url('/select-user'));
                } else {
                    wp_redirect(home_url('/mypage'));
                }
                exit;
            } else {
                $login_error = 'ユーザー名・メールアドレスまたはパスワードが正しくありません。';
            }
        } else {
            $login_error = 'ユーザー名・メールアドレスまたはパスワードが正しくありません。';
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
    
    .auth-login-container .auth-login-header-logo a {
      text-decoration: none;
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
    
    /* === Main Card Wrapper（上下レイアウト） === */
    .auth-login-container .auth-login-wrapper {
      width: 100%;
      max-width: 480px;
      min-height: 560px;
      background: #ffffff;
      border-radius: 24px;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      box-shadow: 0 20px 60px -15px rgba(13, 27, 62, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.08) inset;
      position: relative;
      z-index: 1;
      border: 1px solid rgba(255, 255, 255, 0.25);
    }
    
    /* === 上段：案内エリア === */
    .auth-login-container .auth-login-side-info {
      flex: 0 0 auto;
      background: var(--al-bg-gradient);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: var(--al-white);
      padding: 36px 40px 32px;
      text-align: center;
      position: relative;
      z-index: 2;
      border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .auth-login-container .auth-login-side-info h2 {
      font-size: 1.75rem;
      font-weight: 800;
      margin-bottom: 12px;
      line-height: 1.3;
      color: var(--al-white);
      letter-spacing: 0.02em;
    }
    
    /* === 案内エリア 段落（1行で表示・変な改行を防止） === */
    .auth-login-container .auth-login-side-info p,
    .auth-login-container .auth-login-side-info p * {
      font-size: 0.95rem !important;
      line-height: 1.6 !important;
      margin-bottom: 20px !important;
      max-width: 320px !important;
      opacity: 0.9 !important;
      color: #ffffff !important;
      color: var(--al-white) !important;
      white-space: nowrap !important;
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
    
    /* === 下段：ログインフォーム === */
    .auth-login-container .auth-login-side-form {
      flex: 1;
      background: linear-gradient(180deg, #fafbfc 0%, #ffffff 24px);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 44px 32px 50px;
    }
    
    .auth-login-container .auth-login-side-form h2 {
      font-size: 1.875rem;
      font-weight: 800;
      color: var(--al-navy);
      margin-bottom: 28px;
      letter-spacing: 0.02em;
    }
    
    .auth-login-container .auth-login-logo-top {
      margin-bottom: 20px;
    }
    
    .auth-login-container .auth-login-logo-top a {
      text-decoration: none;
    }
    
    /* ログイン上ロゴ：ヘッダーと同じテキストスタイル（フォームは白背景のためネイビー系で表示） */
    .auth-login-container .auth-login-logo-top .logo-text {
      font-family: "Plus Jakarta Sans", "Roboto", sans-serif;
      font-weight: 700;
      font-size: 1.25rem;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      background: linear-gradient(135deg, #0d1b3e 0%, #1e40af 50%, #3b82f6 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      display: inline-block;
      transition: opacity 0.2s;
    }
    
    .auth-login-container .auth-login-logo-top a:hover .logo-text {
      opacity: 0.85;
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
      margin-bottom: 14px;
      height: 48px;
      border-radius: 12px;
      display: grid;
      grid-template-columns: 44px 1fr;
      padding: 0 1rem 0 0;
      align-items: center;
      border: 1px solid transparent;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    
    .auth-login-container .auth-login-input-field:focus-within {
      border-color: var(--al-primary);
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
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
      padding: 16px 32px;
      border-radius: 14px;
      font-weight: 700;
      font-size: 1.05rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      box-shadow: 0 8px 24px rgba(37, 99, 235, 0.28);
      transition: all 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      border: none;
      cursor: pointer;
      letter-spacing: 0.05em;
      text-decoration: none;
      margin: 12px 0 0;
      font-family: inherit;
    }
    
    .auth-login-container .auth-login-btn-solid:hover,
    .auth-login-container button.auth-login-btn-solid:hover {
      transform: translateY(-4px) scale(1.02);
      box-shadow: 0 20px 40px rgba(37, 99, 235, 0.4);
      background: linear-gradient(135deg, #3b82f6 0%, var(--al-primary) 100%);
    }
    
    /* === Responsive === */
    @media (max-width: 520px) {
      .auth-login-container .auth-login-wrapper {
        max-width: 100%;
        border-radius: 20px;
        min-height: auto;
      }
      
      .auth-login-container .auth-login-side-info {
        padding: 28px 20px;
      }
      
      .auth-login-container .auth-login-side-info h2 {
        font-size: 1.5rem;
      }
      
      .auth-login-container .auth-login-side-info p,
      .auth-login-container .auth-login-side-info p * {
        white-space: normal !important;
      }
      
      .auth-login-container .auth-login-side-form {
        padding: 32px 20px 40px;
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
      <div class="auth-login-header-logo">
        <?php get_template_part('template-parts/logo-text'); ?>
      </div>
      <a href="<?php echo esc_url(home_url('/')); ?>" class="auth-login-header-back">
        <i class="fas fa-arrow-left"></i> トップに戻る
      </a>
    </header>

    <div class="auth-login-wrapper">
      <!-- 左側：案内 -->
      <div class="auth-login-side-info">
        <h2>お問い合わせ</h2>
        <p>採用と評価のドキュメント作成を効率化しましょう。</p>
        <a href="<?php echo esc_url(home_url('/#contact')); ?>" class="auth-login-btn-outline">入力フォームへ</a>
      </div>

      <!-- 右側：ログインフォーム -->
      <div class="auth-login-side-form">
        <div class="auth-login-logo-top">
          <?php get_template_part('template-parts/logo-text'); ?>
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
              <i class="fas fa-user"></i>
              <input type="text" name="login" placeholder="メールアドレスまたはユーザー名" required autocomplete="username">
            </div>
            <div class="auth-login-input-field">
              <i class="fas fa-lock"></i>
              <input type="password" name="password" placeholder="パスワード" required autocomplete="current-password">
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
