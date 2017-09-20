<?php
/**
 * BuddyPress - Groups Create
 */

bp_progenitor_groups_create_hook( 'before', 'page' ); ?>

	<h2 class="bp-subhead"><?php _e( 'Create A New Group','buddypress' ); ?></h2>

	<?php bp_progenitor_groups_create_hook( 'before', 'content_template' ); ?>

	<form action="<?php bp_group_creation_form_action(); ?>" method="post" id="create-group-form" class="standard-form" enctype="multipart/form-data">

		<?php bp_progenitor_groups_create_hook( 'before' ); ?>

		<?php bp_progenitor_template_notices(); ?>

		<div class="item-body" id="group-create-body">

			<nav class="<?php bp_progenitor_groups_create_steps_classes(); ?>" id="group-create-tabs" role="navigation" aria-label="<?php esc_attr_e( 'Group creation menu', 'buddypress' ); ?>">
				<ul class="group-create-buttons button-tabs">

					<?php bp_progenitor_group_creation_tabs(); ?>

				</ul>
			</nav>

			<?php bp_progenitor_group_creation_screen(); ?>

		</div><!-- .item-body -->

		<?php bp_progenitor_groups_create_hook( 'after' ); ?>

	</form>

	<?php bp_progenitor_groups_create_hook( 'after', 'content_template' ); ?>

<?php bp_progenitor_groups_create_hook( 'after', 'page' );
