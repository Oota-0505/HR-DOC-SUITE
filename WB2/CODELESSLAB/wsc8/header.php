<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title><?php global $page, $paged; wp_title( '|', true, 'right' ); bloginfo( 'name' ); ?></title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="keywords" content="ノーコード,Webflow,Glide,Bubble,STUDIO,サブスク,eラーニング">
	<meta name="description" content="月額1,980円（税込2,178円）〜で、ノーコード（Webflow / Glide / Bubble / STUDIO）を“課題ベース”で学び、毎月アウトプットが完成する構築サブスク">
	<link rel="preload" as="image" href="/images/kvimg.webp" fetchpriority="high">
  <?php if(is_front_page()): ?>
  <meta property='og:title' content='CODELESSLAB | ノーコード構築課題サブスク' >
  <meta property='og:type' content='website' >
  <meta property='og:url' content='/' >
  <meta property='og:image' content='https://codeless-lab.com/wp-content/themes/wsc8/images/ogimage.png' >
  <meta property='og:site_name' content='CODELESSLAB | ノーコード構築課題サブスク' >
  <meta property='og:description' content='月額1,980円（税込2,178円）〜で、ノーコード（Webflow / Glide / Bubble / STUDIO）を“課題ベース”で学び、毎月アウトプットが完成する構築サブスク' >
  <?php endif; ?>
  <!-- <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> -->
  <!-- <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&family=Zen+Kaku+Gothic+New:wght@500;700;900&display=swap" rel="stylesheet"> -->
  <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.ico" sizes="any">
  <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/images/ti.png">
<?php if(is_front_page()): ?><link rel="preload" as="image" href="https://codeless-lab.com/wp-content/themes/wsc8/images/kvimg.webp" fetchpriority="high"><?php endif; ?>
  <?php include("style.php")?>
  <?php include("wow.php"); ?>
  <!-- <?php include("style-font.php")?> -->
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
  <!-- ヘッダー -->
  <header class="cl-header">
    <div class="cl-header-inner">
      <!-- ロゴ -->
      <a class="cl-logo" href="<?php echo home_url( '/' );?>">
        <div class="cl-logo-mark">
        <?php include("headerlogo.php")?>
        </div>
        <div>
          <div class="cl-logo-text-main">CODELESS LAB</div>
          <div class="cl-logo-text-sub">コードレスラボ</div>
        </div>
      </a>


      <!-- ナビ -->
      <nav class="cl-nav" aria-label="グローバルナビゲーション">
        <a class="cl-nav-item" href="<?php echo home_url( '/' );?>#sec1">
          <div class="cl-nav-label-ja">選ばれる理由</div>
          <div class="cl-nav-label-en">REASON</div>
        </a>
        <a class="cl-nav-item" href="<?php echo home_url( '/' );?>#sec2">
          <div class="cl-nav-label-ja">利用者の声</div>
          <div class="cl-nav-label-en">VOICE</div>
        </a>
        <a class="cl-nav-item" href="<?php echo home_url( '/' );?>#sec3">
          <div class="cl-nav-label-ja">サービス内容</div>
          <div class="cl-nav-label-en">SERVICE</div>
        </a>
        <a class="cl-nav-item" href="<?php echo home_url( '/' );?>#sec4">
          <div class="cl-nav-label-ja">ご利用の流れ</div>
          <div class="cl-nav-label-en">FLOW</div>
        </a>
        <a class="cl-nav-item" href="<?php echo home_url( '/' );?>#sec5">
          <div class="cl-nav-label-ja">料金プラン</div>
          <div class="cl-nav-label-en">PLAN</div>
        </a>
      </nav>
    </div>
  </header>
<div id="spMenu"><a href="#" class="menu-trigger" aria-label="smartphone menu"><p></p><p></p></a></div>
<div class="spnavi"></div>
