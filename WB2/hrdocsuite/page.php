<?php
/**
 * 固定ページテンプレート
 *
 * @package HR_DOC_SUITE
 * @since 1.0.0
 */

get_header();
?>

<main class="site-main">
  <div class="container">
    <?php while (have_posts()) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?>>
        <header class="page-header">
          <h1 class="page-title"><?php the_title(); ?></h1>
        </header>

        <div class="page-body entry-content">
          <?php the_content(); ?>
        </div>

        <?php
        wp_link_pages(array(
          'before' => '<div class="page-links">ページ:',
          'after'  => '</div>',
        ));
        ?>
      </article>
    <?php endwhile; ?>
  </div>
</main>

<?php get_footer(); ?>
