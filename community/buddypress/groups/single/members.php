<?php
/**
 * BuddyPress - Groups Members
 *
 * @since 1.0.0
 */
?>


	<div class="subnav-filters filters clearfix no-subnav">

			<?php bp_progenitor_search_form(); ?>

		<?php bp_get_template_part( 'common/filters/groups-screens-filters' ); ?>

	</div>


<div id="members-group-list" class="group_members dir-list" data-bp-list="group_members">

	<div id="bp-ajax-loader"><?php bp_progenitor_user_feedback( 'group-members-loading' ); ?></div>

</div><!-- .group_members.dir-list -->
