<?php
/**
* Site footer widgets block
*
* @package BP_Progenitor
*/
?>

<?php if ( is_active_sidebar( 'first-footer-widget-area' ) ) : ?>
				<div id="first" class="widget-area first">

						<?php dynamic_sidebar( 'first-footer-widget-area' ); ?>

				</div><!-- #first .widget-area -->
<?php endif; ?>

<?php if ( is_active_sidebar( 'second-footer-widget-area' ) ) : ?>
				<div id="second" class="widget-area second">

						<?php dynamic_sidebar( 'second-footer-widget-area' ); ?>

				</div><!-- #second .widget-area -->
<?php endif; ?>

<?php if ( is_active_sidebar( 'third-footer-widget-area' ) ) : ?>
				<div id="third" class="widget-area third">

						<?php dynamic_sidebar( 'third-footer-widget-area' ); ?>

				</div><!-- #third .widget-area -->
<?php endif; ?>

