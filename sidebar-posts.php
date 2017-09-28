<?php
/**
* Sidebar Posts
*
* @package bp-progenitor
*/
if ( ! is_active_sidebar( 'sidebar-posts' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area">
	<?php dynamic_sidebar( 'sidebar-posts' ); ?>
</aside><!-- #secondary -->
