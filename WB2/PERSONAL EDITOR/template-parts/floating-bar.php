<?php
/**
 * フローティングバー テンプレートパーツ
 * hrdocsuite 準拠 / PERSONAL EDITOR サイトカラー対応
 *
 * 画面下部に追随するバー
 * 未ログイン → ログイン / ログイン済み → 管理者はユーザー選択、それ以外はマイページ
 *
 * @package PERSONAL_EDITOR
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$floating_is_admin = false;
if (is_user_logged_in()) {
    $floating_is_admin = current_user_can('manage_options');
    if (! $floating_is_admin) {
        global $wpdb;
        $floating_cu = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT role_type FROM {$wpdb->prefix}custom_users WHERE wp_user_id = %d",
                get_current_user_id()
            )
        );
        $floating_is_admin = ($floating_cu !== null && (int) $floating_cu->role_type === 1);
    }
}
?>

<div class="floating-bar" id="floatingBar">
  <div class="floating-inner">
    <div class="floating-links">
      <?php if (is_user_logged_in()) : ?>
        <?php if ($floating_is_admin) : ?>
        <a href="<?php echo esc_url(home_url('/select-user')); ?>" class="floating-btn btn-login">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
          </svg>
          ユーザー選択
        </a>
        <?php else : ?>
        <a href="<?php echo esc_url(home_url('/mypage')); ?>" class="floating-btn btn-login">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
          </svg>
          マイページ
        </a>
        <?php endif; ?>
      <?php else : ?>
        <a href="<?php echo esc_url(home_url('/login')); ?>" class="floating-btn btn-login">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
          </svg>
          ログイン
        </a>
      <?php endif; ?>
    </div>
  </div>
</div>
