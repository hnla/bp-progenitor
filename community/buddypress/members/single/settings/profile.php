<?php
/**
 * BuddyPress - Progenitor Members Settings ( Profile )
 *
 * @since 1.0.0
 */

bp_progenitor_member_hook( 'before', 'settings_template' ); ?>

<h2 class="screen-heading profile-settings-screen">
	<?php _e( 'Profile Visibility Settings', 'buddypress' ); ?>
</h2>

<p class="bp-help-text profile-visibility-info">
	<?php _e( 'Select who may see your profile details.', 'buddypress' ); ?>
</p>

<form action="<?php echo esc_url( bp_displayed_user_domain() . bp_get_settings_slug() . '/profile/' ); ?>" method="post" class="standard-form" id="settings-form">

	<?php if ( bp_xprofile_get_settings_fields() ) : ?>

		<?php while ( bp_profile_groups() ) : bp_the_profile_group(); ?>

			<?php if ( bp_profile_fields() ) : ?>

				<table class="profile-settings bp-tables-user" id="<?php echo esc_attr( 'xprofile-settings-' . bp_get_the_profile_group_slug() ); ?>">
					<thead>
						<tr>
							<th class="title field-group-name"><?php bp_the_profile_group_name(); ?></th>
							<th class="title"><?php _e( 'Visibility', 'buddypress' ); ?></th>
						</tr>
					</thead>

					<tbody>

						<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

							<tr <?php bp_field_css_class(); ?>>
								<td class="field-name"><?php bp_the_profile_field_name(); ?></td>
								<td class="field-visibility"><?php bp_profile_settings_visibility_select(); ?></td>
							</tr>

						<?php endwhile; ?>

					</tbody>
				</table>

			<?php endif; ?>

		<?php endwhile; ?>

	<?php endif; ?>

	<input type="hidden" name="field_ids" id="field_ids" value="<?php bp_the_profile_field_ids(); ?>" />

	<?php bp_progenitor_submit_button( 'members-profile-settings' ); ?>

</form>

<?php bp_progenitor_member_hook( 'after', 'settings_template' );
