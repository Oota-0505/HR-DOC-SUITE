<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
  <header class="site-header" id="siteHeader">
    <div class="container header-inner">
      <div class="logo" aria-label="<?php bloginfo('name'); ?>">
        <a href="<?php echo esc_url(home_url('/')); ?>">
          <span class="logo-text">HR DOC SUITE</span>
        </a>
      </div>
      <nav class="nav">
        <a href="<?php echo esc_url(home_url('/#reasons')); ?>" class="nav-link">REASON</a>
        <a href="<?php echo esc_url(home_url('/#services')); ?>" class="nav-link">SERVICE</a>
        <a href="<?php echo esc_url(home_url('/#faq')); ?>" class="nav-link">FAQ</a>
        <a href="<?php echo esc_url(home_url('/#pricing')); ?>" class="nav-link">PLAN</a>
        <a href="<?php echo esc_url(home_url('/#company')); ?>" class="nav-link">COMPANY</a>
        <a href="<?php echo esc_url(home_url('/#contact')); ?>" class="nav-link">CONTACT</a>
      </nav>

      <button class="menu-toggle" aria-label="メニューを開く">
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
      </button>
    </div>

    <!-- Mobile Navigation Overlay -->
    <div class="mobile-nav">
      <nav class="mobile-nav-inner">
        <a href="<?php echo esc_url(home_url('/#reasons')); ?>" class="mobile-nav-link">REASON</a>
        <a href="<?php echo esc_url(home_url('/#services')); ?>" class="mobile-nav-link">SERVICE</a>
        <a href="<?php echo esc_url(home_url('/#faq')); ?>" class="mobile-nav-link">FAQ</a>
        <a href="<?php echo esc_url(home_url('/#pricing')); ?>" class="mobile-nav-link">PLAN</a>
        <a href="<?php echo esc_url(home_url('/#company')); ?>" class="mobile-nav-link">COMPANY</a>
        <a href="<?php echo esc_url(home_url('/#contact')); ?>" class="mobile-nav-link">CONTACT</a>
        <div class="mobile-nav-footer">
          <?php if (is_user_logged_in()) : ?>
            <a href="<?php echo esc_url(home_url('/mypage')); ?>" class="btn btn-entry-hero mobile-menu-btn">マイページ →</a>
          <?php else : ?>
            <a href="<?php echo esc_url(home_url('/#contact')); ?>" class="btn btn-entry-hero mobile-menu-btn">お問い合わせ →</a>
          <?php endif; ?>
        </div>
      </nav>
    </div>
  </header>
