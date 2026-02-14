<?php
/**
 * メインテンプレート（フォールバック用）
 *
 * @package HR_DOC_SUITE
 * @since 1.0.0
 */

get_header();
?>

<main class="site-main">
  <div class="container">
    <?php if (have_posts()) : ?>
      <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <header class="entry-header">
            <?php if (is_singular()) : ?>
              <h1 class="entry-title"><?php the_title(); ?></h1>
            <?php else : ?>
              <h2 class="entry-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </h2>
            <?php endif; ?>
          </header>

          <div class="entry-content">
            <?php
            if (is_singular()) {
              the_content();
            } else {
              the_excerpt();
            }
            ?>
          </div>
        </article>
      <?php endwhile; ?>

      <?php
      the_posts_pagination(array(
        'mid_size'  => 2,
        'prev_text' => '&laquo; 前へ',
        'next_text' => '次へ &raquo;',
      ));
      ?>

    <?php else : ?>
      <article class="no-results">
        <header class="entry-header">
          <h1 class="entry-title">コンテンツが見つかりません</h1>
        </header>
        <div class="entry-content">
          <p>お探しのページは存在しないか、移動した可能性があります。</p>
          <p><a href="<?php echo esc_url(home_url('/')); ?>">トップページに戻る</a></p>
        </div>
      </article>
    <?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>
