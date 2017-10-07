<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package BP_Progenitor
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if( is_category() || is_archive() || is_tag() || is_front_page() ) : ?>
		<div class="featured-image"><?php the_post_thumbnail( 'lists-featured-thumb' ); ?></div>
	<?php endif; ?>


		<?php
		// Show title for archive/cat lists only: see header.php for post/page title markup
		// See header.php for post/page titles & meta
		if ( ! is_singular() ) : ?>

		<header class="entry-header">
			<?php	the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		</header><!-- .entry-header -->

		<?php endif; ?>

	<div class="entry-content">

		<?php if( is_category() || is_archive() || is_tag() || is_front_page() ) :

		the_excerpt();

		else:

			the_content( sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'bp-progenitor' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'bp-progenitor' ),
				'after'  => '</div>',
			) );

	 endif; ?>

	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php bp_progenitor_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
