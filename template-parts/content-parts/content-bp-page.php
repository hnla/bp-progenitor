<?php
/**
 * Template part for displaying BP screen content in buddypress.php
 *
 * @package BP_Progenitor
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-content<?php echo entry_content_class(); ?>">

		<?php the_content();	?>

	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
