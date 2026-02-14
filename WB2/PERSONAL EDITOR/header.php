<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="format-detection" content="telephone=no">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PERSONAL EDITOR</title>
  <meta
    name="description"
    content="" />
  <meta name="keywords" content="キーワード" />

  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>
  <div id="page" class="l-site">
    <header class="l-header p-header" id="header">
      <div class="p-header__inner">
        <?php $logo_tag = is_front_page() ? 'h1' : 'div'; ?>
        <<?php echo $logo_tag; ?> class="p-header__logo">
          <a href="<?php echo esc_url(home_url('/')); ?>">
            <img src="<?php img_path(); ?>/common/header-logo.webp" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
          </a>
        </<?php echo $logo_tag; ?>>
        <nav class="p-header__nav" aria-label="メインメニュー">
          <ul class="p-header__nav-list">
            <li class="p-header__nav-item">
              <a href="<?php echo esc_url(home_url('/') . '#'); ?>">ホーム</a>
            </li>
            <li class="p-header__nav-item">
              <a href="<?php echo esc_url(home_url('/') . '#feature'); ?>">特長</a>
            </li>
            <li class="p-header__nav-item">
              <a href="<?php echo esc_url(home_url('/') . '#tool'); ?>">サービス</a>
            </li>
            <li class="p-header__nav-item">
              <a href="<?php echo esc_url(home_url('/') . '#plan'); ?>">料金プラン</a>
            </li>
            <li class="p-header__nav-item">
              <a href="<?php echo esc_url(home_url('/') . '#company'); ?>">会社概要</a>
            </li>
          </ul>
          <div class="p-header__contact">
            <p class="p-header__tel">
              <a href="tel:03-6825-6831">03-6825-6831</a>
            </p>
            <p class="p-header__hours">平日 10:00~18:00</p>
          </div>
          <div class="p-header__cta">
            <a href="<?php echo esc_url(home_url('/') . '#contact'); ?>" class="p-header__cta-button c-button">お問合せはこちら</a>
          </div>
        </nav>
        <button class="p-header__hamburger js-hamburger">
          <span></span>
          <span></span>
          <span></span>
        </button>
        <div class="p-header__drawer js-drawer">
          <nav class="p-header__drawer-nav" aria-label="メインメニュー">
            <ul class="p-header__drawer-list">
              <li class="p-header__drawer-item">
                <a href="<?php echo esc_url(home_url('/') . '#'); ?>">ホーム</a>
              </li>
              <li class="p-header__drawer-item">
                <a href="<?php echo esc_url(home_url('/') . '#feature'); ?>">特長</a>
              </li>
              <li class="p-header__drawer-item">
                <a href="<?php echo esc_url(home_url('/') . '#tool'); ?>">サービス</a>
              </li>
              <li class="p-header__drawer-item">
                <a href="<?php echo esc_url(home_url('/') . '#plan'); ?>">料金プラン</a>
              </li>
              <li class="p-header__drawer-item">
                <a href="<?php echo esc_url(home_url('/') . '#company'); ?>">会社概要</a>
              </li>
              <li class="p-header__drawer-item p-header__drawer-item--contact">
                <a href="<?php echo esc_url(home_url('/') . '#contact'); ?>">お問合せはこちら</a>
              </li>
            </ul>
          </nav>
          <div class="p-header__drawer-contact">
            <p class="p-header__drawer-tel">
              <a href="tel:03-6825-6831">TEL：03-6825-6831</a>
            </p>
            <p class="p-header__drawer-hours">平日 10:00~18:00</p>
          </div>
        </div>
      </div>
    </header>