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

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php

if ( ! bp_is_user() ) {
	if ( ! bp_is_group() ) {
	get_sidebar('pages');
	}
}

get_footer();
