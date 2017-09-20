<?php
/**
 * BuddyPress - Progenitor Members Single Profile WP
 *
 * @since 1.0.0
 */

bp_progenitor_wp_profile_hooks( 'before' ); ?>

<div class="bp-widget wp-profile">

	<h2 class="screen-heading">
		<?php
		if ( bp_is_my_profile() ) {
			_e( 'My Profile', 'buddypress' );
		} else {
			printf(
				/* Translators: a member's profile, e.g. "Paul's profile". */
				__( "%s's Profile", 'buddypress' ),
				bp_get_displayed_user_fullname()
			);
		}
		?>
	</h2>

	<?php if ( bp_progenitor_has_wp_profile_fields() ) : ?>

		<table class="wp-profile-fields">

			<?php while ( bp_progenitor_wp_profile_fields() ) : bp_progenitor_wp_profile_field(); ?>

				<tr id="<?php bp_progenitor_wp_profile_field_id();?>">
					<td class="label"><?php bp_progenitor_wp_profile_field_label();?></td>
					<td class="data"><?php bp_progenitor_wp_profile_field_data();?></td>
				</tr>

			<?php endwhile ;?>

		</table>

	<?php else :

		bp_progenitor_user_feedback( 'member-wp-profile-none' );

	endif; ?>

</div>

<?php bp_progenitor_wp_profile_hooks( 'after' ); ?>
