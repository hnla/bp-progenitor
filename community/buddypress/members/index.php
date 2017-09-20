<?php
/**
 * BuddyPress Members Directory
 */

?>

	<?php bp_progenitor_before_members_directory_content() ?>

	<?php if ( ! bp_progenitor_is_object_nav_in_sidebar() ) : ?>

		<?php bp_get_template_part( 'common/nav/directory-nav' ); ?>

	<?php endif; ?>

	<div class="screen-content">

	<?php bp_get_template_part( 'common/search-and-filters-bar' ); ?>

		<div id="members-dir-list" class="members dir-list" data-bp-list="members">
			<div id="bp-ajax-loader"><?php bp_progenitor_user_feedback( 'directory-members-loading' ); ?></div>
		</div><!-- #members-dir-list -->

		<?php bp_progenitor_after_members_directory_content() ?>
	</div><!-- // .screen-content -->
