<?php
/*
YARPP Template: Thumbnails
*/ ?>
<p class="widget-title">関連記事</p>
<?php if (have_posts()):?>
<div class="yarpp-template-thumbnail row">

<?php while (have_posts()) : the_post(); ?>

	<div class="col-md-3 col-sm-4 col-ms-6 thumbnail-box">
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
<p>関連記事はありません</p>
<?php endif; ?>
