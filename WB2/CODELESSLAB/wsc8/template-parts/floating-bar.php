<?php if (!defined('ABSPATH')) return; ?>
<div class="cl-floating-bar">
  <?php if (is_user_logged_in()): ?>
    <a href="<?php echo esc_url(home_url('/mypage')); ?>" class="cl-fb-link">マイページ</a>
  <?php else: ?>
    <a href="<?php echo esc_url(home_url('/login')); ?>" class="cl-fb-link">ログイン</a>
  <?php endif; ?>
</div>
<style>
.cl-floating-bar{position:fixed;bottom:24px;right:24px;z-index:100;display:flex;flex-direction:column;gap:8px;align-items:flex-end;}
.cl-fb-link{display:inline-block;padding:12px 24px;background:linear-gradient(180deg,#51C3D5 0%,#2B5AF4 100%);color:#fff;font-weight:bold;font-size:14px;border-radius:30px;box-shadow:3px 3px 8px rgba(0,0,0,0.2);transition:transform .2s,box-shadow .2s;}
.cl-fb-link:hover{color:#fff;transform:translateY(-2px);box-shadow:4px 4px 12px rgba(0,0,0,0.25);}
body.page-payment-complete .cl-floating-bar{display:none;}
</style>
