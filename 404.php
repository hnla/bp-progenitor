<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package BP_Progenitor
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<div class="error-404 not-found">

				<div class="page-content">
					<p><?php esc_html_e( 'This may habve been due to a typo or we have removed the page?. Maybe try one of the links below or a search?', 'bp-progenitor' ); ?></p>

					<?php  progenitor_site_search( array( 'parent_class' => array( 'wide') ) ); ?>
					<?php get_template_part('template-parts/content-parts/no-results-suggested-content'); ?>

				</div><!-- .page-content -->
			</div><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar('pages');
get_footer();
