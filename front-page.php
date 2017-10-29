<?php
/**
 * The main home page template - front-page.php
 * This template supercedes the index.php file.
 * This page may be selected as a static page or simply
 * run as the default template hierarchy
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package BP_Progenitor
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php if ( is_user_logged_in() ) : ?>

			<div class="block-1">

				<?php //get_template_part('/template-parts/home-page/home-page-activity-loop'); ?>

				<?php if ( is_active_sidebar('homepage-block-1-loggedin') ) {
						dynamic_sidebar( 'homepage-block-1-loggedin' );
				}; ?>
			</div>

			<div class="block-2">

				<?php if ( is_active_sidebar('homepage-block-2-loggedin') ) {
						dynamic_sidebar( 'homepage-block-2-loggedin' );
				}; ?>

				<?php get_template_part('/template-parts/home-page/home-page-post-loop'); ?>

			</div>

			<?php ################# Loggedout frontpage elements ##################### ?>

		<?php else: ?>

			<div class="block-1">
				<?php if ( is_active_sidebar('homepage-block-1-loggedout') ) {
						dynamic_sidebar( 'homepage-block-1-loggedout' );
				}; ?>
			</div>

			<div class="block-2">
				<?php if ( is_active_sidebar('homepage-block-2-loggedout') ) {
						dynamic_sidebar( 'homepage-block-2-loggedout' );
				}; ?>
			</div>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
