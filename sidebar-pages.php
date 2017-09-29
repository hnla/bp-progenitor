<?php
/**
* Sidebar Pages
*
* @package bp-progenitor
*/
if ( ! is_active_sidebar( 'sidebar-pages' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area">
	<?php dynamic_sidebar( 'sidebar-pages' ); ?>
</aside><!-- #secondary -->
