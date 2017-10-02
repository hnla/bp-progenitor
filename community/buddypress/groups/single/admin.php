<?php
/**
 * BuddyPress - Groups Admin
 *
 * @since 1.0.0
 */

bp_get_template_part( 'groups/single/parts/admin-subnav' ); ?>

<form action="<?php bp_group_admin_form_action(); ?>" name="group-settings-form" id="group-settings-form" class="standard-form" method="post" enctype="multipart/form-data">

	<?php bp_progenitor_group_manage_screen(); ?>

</form><!-- #group-settings-form -->

