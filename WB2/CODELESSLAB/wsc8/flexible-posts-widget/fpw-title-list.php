<?php

if ( !defined('ABSPATH') )
	die('-1');

echo $before_widget;

if ( !empty($title) )
	echo $before_title . $title . $after_title;

if( $flexible_posts->have_posts() ):
?>
	<div class="fpw-title-list">
	<?php while( $flexible_posts->have_posts() ) : $flexible_posts->the_post(); global $post; ?>

	<div class="blog-sumamry">
		<span class="blog-date"><?php the_time('Y年n月j日'); ?></span>
		<span class="blog-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span>
	</div>

	<?php endwhile; ?>
	</div>
<?php else: ?>
	<div class="dpe-flexible-posts no-posts">
	</div>
<?php
endif;

echo $after_widget;
