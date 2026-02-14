<?php /* Template Name: full-width */ ?>
<?php /* WordPress CMS Theme WSC Project. */ get_header(); ?>
<div class="container">
	<h1 class="entry-title"><?php the_title(); ?></h1>
</div>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php the_content(); ?>
		<?php comments_template(); ?>
	</div>
<?php endwhile; endif; ?>
<?php get_footer(); ?>