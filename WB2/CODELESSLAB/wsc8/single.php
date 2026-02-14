<?php /* WordPress CMS Theme WSC Project. */ get_header(); ?>
<div class="container">
	<div class="cf">
		<div class="main-column">
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<div class="content">
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="entry-header">
						<span class="blog-date">公開日：<?php the_time('Y年m月d日'); ?></span>
						<span class="blog-category">カテゴリー：<?php the_category(', '); ?></span>
						<span class="blog-tags">タグ：<?php the_tags('', ', '); ?></span>
						<div><?php do_action( 'fudou_share_buttons_do' ); ?></div>
					</div>
					<?php the_content(); ?>
					<?php do_action( 'fudou_share_buttons_do' ); ?>
					<?php comments_template(); ?>
					<div class="navigation">
						<div class="alignleft"><?php previous_post_link('%link', '前のページ' ,'TRUE') ?></div>
						<div class="alignright"><?php next_post_link('%link', '次のページ' ,'TRUE') ?></div>
					</div>
				</div>
				<?php endwhile; endif; ?>
			</div>
			<div class="single-widget">
				<?php if ( ! dynamic_sidebar( 'single-widget-area' ) ) : ?>
				<?php endif; ?>
			</div>
		</div>
		<div class="side-column">
			<div class="content">
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>