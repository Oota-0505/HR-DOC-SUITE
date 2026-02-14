<form role="search" method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="searchform">
		<label class="screen-reader-text" for="s"><?php _x( 'Search for:', 'label' ); ?></label>
	<div class="searchform-txt">
		<input type="text" value="<?php echo get_search_query(); ?>" name="s" id="s" />
	</div>
	<div class="searchform-btn">
		<input type="submit" id="searchsubmit" value="<?php echo esc_attr_x( 'Search', 'submit button' ); ?>" />
	</div>
	</div>
	</div>
</form>