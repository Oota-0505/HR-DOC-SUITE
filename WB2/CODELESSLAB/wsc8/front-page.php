<?php /* WordPress CMS Theme WSC Project. */ get_header(); ?>
<main>
	<?php if(is_home()): ?>
	<div class="content">
		<p><a href="./wp-admin/options-reading.php">表示設定</a>から、いずれかの固定ページをフロントページに指定してください。</p>
	</div>
	<?php elseif(is_front_page()): ?>
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; endif; ?>
	<?php endif; ?>
</main>
<?php get_footer(); ?>
