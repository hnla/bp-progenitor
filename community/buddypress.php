<?php
/**
 * The template for displaying BuddyPress screens (based on page.php).
 *
 *
 * @link https://codex.buddypress.org/themes/theme-compatibility-1-7/
 *
 * @package BP_Progenitor
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">

		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content-parts/content', 'page' );

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php

if ( ! bp_is_user() ) {
	if ( ! bp_is_group() ) {
		if ( bp_is_directory() && show_sbar_for_bp_dir() ) {
			get_sidebar('pages');
		}
	}
}

get_footer();
