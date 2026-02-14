<?php /* WordPress CMS Theme WSC Project. */ get_header(); ?>

<div class="container">
	<div class="content">
		<?php $post = $posts[0]; ?>
		<?php if (is_category()) { ?>
		<div class="category-header">
			<h1 class="archive-title"><?php single_cat_title(); ?></h1>
			<?php echo category_description(); ?>
		</div>
		<?php } elseif( is_tag() ) { ?>
		<h1 class="archive-title">タグ &#8216;<?php single_tag_title(); ?>&#8217;</h1>
		<?php } elseif (is_day()) { ?>
		<h1 class="archive-title"><?php the_time('Y年n月j日'); ?></h1>
		<?php } elseif (is_month()) { ?>
		<h1 class="archive-title"><?php the_time('Y年n月'); ?></h1>
		<?php } elseif (is_year()) { ?>
		<h1 class="archive-title"><?php the_time('Y年'); ?></h1>
		<?php } elseif (is_author()) { ?>
		<h1 class="archive-title">Author Archive</h1>
		<?php } elseif (is_search()) { ?>
		<h1 class="archive-title">検索結果</h1>
		<p>キーワード「<?php the_search_query(); ?>」での検索結果は次の通りです。</p>
		<?php } elseif (is_404()) { ?>
		<h1 class="archive-title">ページが見つかりません</h1>
		<p>お探しのページは削除されたか、すでに存在していません。<br>
			ナビゲーションメニューからクリックしてお進みいただくか、<br>
			下の検索フォームでキーワード検索をお試しください。</p>
		<?php get_search_form(); ?>
		<?php } elseif (is_front_page()) { ?>
		<?php } else { ?>
		<h1 class="archive-title">Blog</h1>
		<?php } ?>

		<div class="row">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<div class="col-md-3 col-sm-4 col-ms-6 thumbnail-box">
				<div <?php post_class(); ?>>
					<?php if( has_post_thumbnail() ) { ?>
					<div class="blog-thumbnail">
						<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail('blog-thumbnail'); ?>
					<?php } else { ?>
					<div class="blog-excerpt">
						<a href="<?php the_permalink(); ?>">
					<?php echo get_the_excerpt(); ?>
					<?php } ?>
						</a>
					</div>
					<div class="blog-sumamry">
						<span class="blog-date"><?php the_time('Y年n月j日'); ?></span>
						<span class="blog-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span>
					</div>
				</div>
			</div>

		<?php endwhile; ?>

		<div class="navigation">
			<?php if(function_exists('wp_pagenavi')): ?>
			<?php wp_pagenavi(); ?>
			<?php else : ?>
			<div class="alignleft"><?php previous_posts_link('前のページ') ?></div>
			<div class="alignright"><?php next_posts_link('次のページ') ?></div>
			<?php endif; ?>
		</div>

	<?php endif; ?>
		</div>

	</div>

<div class="archive-widget">
	<?php if ( ! dynamic_sidebar( 'archive-widget-area' ) ) : ?>
	<?php endif; ?>
</div>

</div>


<?php get_footer(); ?>