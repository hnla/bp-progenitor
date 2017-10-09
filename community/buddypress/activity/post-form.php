<?php
/**
 * BuddyPress - Progentor Activity Post Form
 */

?>

<?php
/**
 * Template tag to prepare the activity post form
 * checks capability and enqueue needed scripts.
 */
bp_progenitor_before_activity_post_form(); ?>

<div id="bp-progenitor-activity-form" class="activity-update-form clearfix"></div>

<?php
/**
 * Template tag to load the Javascript
 * templates of the Post form UI
 */
bp_progenitor_after_activity_post_form(); ?>
