<?php
/**
* The home page primary post loop
*
* @package bp-progenitor
* @since 0.1.0
*/

if ( have_posts() ) : ?>

	<div class="<?php post_loops_wrap_class(); ?>">

	<?php
	while ( have_posts() ) : the_post(); ?>
		<div class="box <?php echo ( is_sticky() )? ' sticky-post' : ''; ?>">
			<?php get_template_part( 'template-parts/content-parts/content' ); ?>
		</div>

	<?php endwhile; ?>

	</div>


	<?php		progenitor_postloop_paginate();


else :

	get_template_part( 'template-parts/content', 'none' );

endif;
