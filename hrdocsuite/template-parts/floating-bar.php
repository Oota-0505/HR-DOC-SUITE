<?php
/**
 * フローティングバー テンプレートパーツ
 * 
 * 画面下部に追随するバー
 * ログイン状態によって表示を切り替え
 *
 * @package HR_DOC_SUITE
 * @since 1.0.0
 * 
 * 表示ロジック:
 * - 未ログイン → 「ログイン」＋「新規登録」ボタン
 * - ログイン済み → 「マイページ」ボタン
 */

// 直接アクセス禁止
if (!defined('ABSPATH')) {
    exit;
}
?>

<!-- Floating Bar -->
<div class="floating-bar" id="floatingBar">
  <div class="container floating-inner">
    <div class="floating-links">
      <?php if (is_user_logged_in()) : ?>
        <!-- ログイン済み：マイページボタン -->
        <a href="<?php echo esc_url(home_url('/mypage')); ?>" class="floating-btn btn-login">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
          </svg>
          マイページ
        </a>
      <?php else : ?>
        <!-- 未ログイン：ログイン＋新規登録ボタン -->
        <a href="<?php echo esc_url(home_url('/login')); ?>" class="floating-btn btn-login">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
          </svg>
          ログイン
        </a>
        <a href="<?php echo esc_url(home_url('/register')); ?>" class="floating-btn btn-register">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <line x1="19" y1="8" x2="19" y2="14"></line>
            <line x1="22" y1="11" x2="16" y2="11"></line>
          </svg>
          新規登録
        </a>
      <?php endif; ?>
    </div>
  </div>
</div>
