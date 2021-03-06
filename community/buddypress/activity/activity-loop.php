<?php
/**
 * BuddyPress - Activity Loop
 */

bp_progenitor_before_loop(); ?>

<?php if ( bp_has_activities( bp_ajax_querystring( 'activity' ) . '&per_page= 12') ) : ?>

	<ul  class="activity-list item-list bp-list">

	<?php while ( bp_activities() ) : bp_the_activity(); ?>

		<?php bp_get_template_part( 'activity/entry' ); ?>

	<?php endwhile; ?>

	<?php if ( bp_activity_has_more_items() ) : ?>

		<li class="load-more">
			<a href="<?php bp_activity_load_more_link() ?>"><?php _e( 'Load More', 'buddypress' ); ?></a>
		</li>

	<?php endif; ?>

	</ul>
<?php else : ?>

		<?php bp_progenitor_user_feedback( 'activity-loop-none' ); ?>

<?php endif; ?>

<?php bp_progenitor_after_loop(); ?>
