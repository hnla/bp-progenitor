<?php
/**
 * BP Nouveau - Groups Directory
 *
 * @since 1.0.0
 */
?>

	<?php bp_progenitor_before_groups_directory_content(); ?>

	<?php bp_progenitor_template_notices(); ?>

	<?php if ( ! bp_progenitor_is_object_nav_in_sidebar() ) : ?>

		<?php bp_get_template_part( 'common/nav/directory-nav' ); ?>

	<?php endif; ?>

	<div class="screen-content">

	<?php bp_get_template_part( 'common/search-and-filters-bar' ); ?>

		<div id="groups-dir-list" class="groups dir-list" data-bp-list="groups">
			<div id="bp-ajax-loader"><?php bp_progenitor_user_feedback( 'directory-groups-loading' ); ?></div>
		</div><!-- #groups-dir-list -->

	<?php bp_progenitor_after_groups_directory_content(); ?>
	</div><!-- // .screen-content -->

