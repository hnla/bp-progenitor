<?php
/**
 * BuddyPress - Progenitor Activity Home
 */

?>

	<?php bp_progenitor_template_notices(); ?>

	<div class="activity single-activity" data-bp-single="<?php echo esc_attr( bp_current_action() ); ?>">

		<ul id="activity-stream" class="activity-list item-list bp-list" data-bp-list="activity">

			<li id="bp-ajax-loader"><?php bp_progenitor_user_feedback( 'single-activity-loading' ); ?></li>

		</ul>

	</div>
