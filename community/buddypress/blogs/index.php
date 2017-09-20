<?php
/**
 * BuddyPress - Progenitor Blogs Directory
 *
 * @since 1.0.0
 */
?>

	<?php bp_progenitor_before_blogs_directory_content(); ?>

	<?php if ( ! bp_progenitor_is_object_nav_in_sidebar() ) : ?>

		<?php bp_get_template_part( 'common/nav/directory-nav' ); ?>

	<?php endif; ?>

	<div class="screen-content">

	<?php bp_get_template_part( 'common/search-and-filters-bar' ); ?>

		<div id="blogs-dir-list" class="blogs dir-list" data-bp-list="blogs">
			<div id="bp-ajax-loader"><?php bp_progenitor_user_feedback( 'directory-blogs-loading' ); ?></div>
		</div><!-- #blogs-dir-list -->

		<?php bp_progenitor_after_blogs_directory_content(); ?>
	</div><!-- // .screen-content -->

