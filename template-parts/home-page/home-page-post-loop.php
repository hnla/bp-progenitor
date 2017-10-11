<?php
/**
* The home page primary post loop
*
* @package bp-progenitor
* @since 0.1.0
*/

if ( have_posts() ) : ?>

	<div class="<?php post_loops_wrap_class(); ?>">

	<?php /* Start the Loop */
	while ( have_posts() ) : the_post();

		/*
		 * Include the Post-Format-specific template for the content.
		 * If you want to override this in a child theme, then include a file
		 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
		 */ ?>
		<div class="box <?php echo ( is_sticky() )? ' sticky-post' : ''; ?>">
			<?php get_template_part( 'template-parts/content-parts/content', get_post_format() ); ?>
		</div>

	<?php endwhile; ?>

	</div>

<?php bp_progenitor_post_navigation( array('prev_text' => 'Older posts', 'next_text' => 'Newer Posts', 'icons' => true) );

else :

	get_template_part( 'template-parts/content', 'none' );

endif;
