<?php
/**
* The site search form
*
* @package bp-progenitor
*/
?>
<div class="site-search bp-search">
<form role="search" method="get" class="site-search-form" action="<?php echo home_url( '/' ); ?>">

	<label>
		<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ) ?></span>
	</label>

	<input type="search" class="search-field"
			placeholder="<?php echo esc_attr_x( 'Search …', 'placeholder' ) ?>"
			value="<?php echo get_search_query() ?>" name="s" />


		<button type="submit" class="search-submit" name="search">
			<span class="fa fa-search" aria-hidden="true"></span>
			<span id="button-text" class="screen-reader-text"><?php _e( 'Search', 'bp-progenitor' ); ?></span>
		</button>
</form>
</div>
