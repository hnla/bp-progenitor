<?php
/**
 * BuddyPress Progenitor Activity templates
 *
 * @since 1.0.0
 */
?>

	<?php bp_progenitor_before_activity_directory_content(); ?>


	<?php bp_progenitor_template_notices(); ?>

	<?php if ( ! bp_progenitor_is_object_nav_in_sidebar() ) : ?>

		<?php bp_get_template_part( 'common/nav/directory-nav' ); ?>

	<?php endif; ?>

	<div class="screen-content">

		<?php if ( is_user_logged_in() ) : ?>

			<?php bp_get_template_part( 'activity/post-form' ); ?>

		<?php endif; ?>
		<?php bp_get_template_part( 'common/search-and-filters-bar' ); ?>

		<?php bp_progenitor_activity_hook( 'before_directory', 'list' ); ?>

		<div id="activity-stream" class="activity" data-bp-list="activity">

			<div id="bp-ajax-loader"><?php bp_progenitor_user_feedback( 'directory-activity-loading' ); ?></div>

		</div><!-- .activity -->

		<?php bp_progenitor_after_activity_directory_content(); ?>
	</div><!-- // .screen-content -->

