<?php
/**
 * 404エラーページテンプレート
 *
 * @package HR_DOC_SUITE
 * @since 1.0.0
 */

get_header();
?>

<main class="site-main error-404">
  <div class="container">
    <div class="error-content" style="text-align: center; padding: 100px 20px; min-height: 60vh; display: flex; flex-direction: column; justify-content: center; align-items: center;">
      <h1 style="font-size: 8rem; font-weight: 900; color: var(--navy); line-height: 1; margin-bottom: 16px; background: linear-gradient(135deg, var(--navy) 0%, var(--blue) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">404</h1>
      <h2 style="font-size: 1.5rem; font-weight: 600; color: var(--text); margin-bottom: 24px;">ページが見つかりません</h2>
      
      <p style="color: var(--muted); line-height: 1.8; margin-bottom: 40px; max-width: 500px;">
        お探しのページは存在しないか、移動した可能性があります。<br>
        URLをご確認いただくか、下記のリンクからお探しください。
      </p>
      
      <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-entry-hero">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
          <polyline points="9 22 9 12 15 12 15 22"></polyline>
        </svg>
        トップページへ戻る
      </a>
    </div>
  </div>
</main>

<?php get_footer(); ?>
