<?php
/**
* BP Activity loop
* A custom short loop for site activity
*
* @since 0.1.0
*/
?>
<div id="buddypress" class="buddypress-wrap">
<div class="homepage-site-activity activity">

<?php if ( bp_has_activities( bp_ajax_querystring( 'activity' ) . '&max= 6' ) ) : ?>

<h2><?php _e('Latest site activity', 'progenitor'); ?></h2>

<ul id="activity-stream" class="activity-list item-list bp-list act-home-loop-list" data-bp-list="activity">

	<?php while ( bp_activities() ) : bp_the_activity(); ?>

	<li class="<?php bp_activity_css_class(); ?>" id="activity-<?php bp_activity_id(); ?>" data-bp-activity-id="<?php bp_activity_id(); ?>">

		<div class="activity-avatar item-avatar">

			<a href="<?php bp_activity_user_link(); ?>">

				<?php bp_activity_avatar( array( 'type' => 'full' ) ); ?>

			</a>

		</div>

		<div class="activity-content">

			<div class="activity-header">

				<?php bp_activity_action(); ?>

			</div>

			<?php if ( bp_progenitor_activity_has_content() ) : ?>

				<div class="activity-inner">

					<?php bp_progenitor_activity_content(); ?>

				</div>

			<?php endif; ?>

			<?php bp_progenitor_activity_entry_buttons(); ?>

		</div>

		<?php bp_progenitor_activity_hook( 'before', 'entry_comments' ); ?>

		<?php if ( bp_activity_get_comment_count() || ( is_user_logged_in() && ( bp_activity_can_comment() || bp_is_single_activity() ) ) ) : ?>

			<div class="activity-comments">

				<?php //bp_activity_comments(); ?>

				<?php //bp_progenitor_activity_comment_form(); ?>

			</div>

		<?php endif; ?>

		<?php bp_progenitor_activity_hook( 'after', 'entry_comments' ); ?>

	</li>

	<?php endwhile; ?>

<?php else : ?>

	<li id="activity-stream-message" class="bp-messages info">
		<?php bp_progenitor_user_feedback( 'activity-loop-none' ); ?>
	</li>

</ul>
<?php endif; ?>
</div>
</div>
