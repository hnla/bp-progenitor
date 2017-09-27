<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package BP_Progenitor
 */

?>

			</div><!-- // #content -->

		<?php if ( is_active_sidebar( 'first-footer-widget-area' )
		           || is_active_sidebar( 'second-footer-widget-area' )
		           || is_active_sidebar( 'third-footer-widget-area' )
		           || is_active_sidebar( 'fourth-footer-widget-area' ) ) : ?>

			<div id="container-widget-footer" class="footer-widgets-wrap" role="complementary">

				<div id="footer-widgets" class="<?php  esc_attr( progenitor_foot_widgets_count() ); ?> widgets-parent">
					<?php get_template_part( 'template-parts/footer-widgets') ?>
				</div>

			</div>
		<?php endif; ?>

		</div><!-- // #page -->
	</div><!-- // #site-wrap -->

	<footer id="colophon" class="site-footer primary-color">
		<div class="site-info">
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'bp-progenitor' ) ); ?>"><?php
				/* translators: %s: CMS name, i.e. WordPress. */
				printf( esc_html__( 'Proudly powered by %s', 'bp-progenitor' ), 'WordPress' );
			?></a>
			<span class="sep"> | </span>
			<?php
				/* translators: 1: Theme name, 2: Theme author. */
				printf( esc_html__( 'Theme: %1$s by %2$s.', 'bp-progenitor' ), 'bp-progenitor', '<a href="https://github.com/hnla/bp-progenitor">Hugo Ashmore & BP core members</a>' );
			?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->


<?php wp_footer(); ?>

</body>
</html>
