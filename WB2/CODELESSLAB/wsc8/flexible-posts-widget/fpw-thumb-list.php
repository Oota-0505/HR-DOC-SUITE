<?php

if ( !defined('ABSPATH') )
	die('-1');

echo $before_widget;

if ( !empty($title) )
	echo $before_title . $title . $after_title;

if( $flexible_posts->have_posts() ):
?>
	<div class="fpw-thumb-list row">

	<?php while( $flexible_posts->have_posts() ) : $flexible_posts->the_post(); global $post; ?>

		<div class="col-md-6 col-sm-6 thumbnail-box">
			<div class="blog-thumbnail">
				<a href="<?php the_permalink(); ?>">
					<?php if( has_post_thumbnail() ) {
						the_post_thumbnail('blog-thumbnail');
					} else { ?>
					<img src="<?php echo get_template_directory_uri(); ?>/img/noimage.png">
					<?php } ?>
				</a>
			</div>
			<div class="blog-sumamry">
				<span class="blog-date"><?php the_time('Y年n月j日'); ?></span>
				<span class="blog-title"><a href="<?php the_permalink(); ?>"><?php if (strlen($post->post_title) > 30) {echo mb_substr(the_title($before = '', $after = '', FALSE), 0, 30) . '…'; } else {the_title();} ?></a></span>
			</div>
		</div>

	<?php endwhile; ?>
	</div>
<?php else: ?>
	<div class="dpe-flexible-posts no-posts">
	</div>
<?php
endif;

echo $after_widget;
