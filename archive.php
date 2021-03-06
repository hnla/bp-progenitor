<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package BP_Progenitor
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		if ( have_posts() ) : ?>

		<?php /* See header.php for cat/archive title & description */ ?>

			<div class="<?php post_loops_wrap_class(); ?>">

			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */ ?>

					<div class="box">
						<?php get_template_part( 'template-parts/content-parts/content', get_post_format() ); ?>
					</div>

			<?php	endwhile; ?>

			</div>

			<?php

			progenitor_postloop_paginate();

		else :

			get_template_part( 'template-parts/content-parts/content', 'none' );

		endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
