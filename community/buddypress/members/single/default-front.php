<?php
/**
 * BP Progenitor Default user's front template.
 * This template is called if admin selects to use the users default front page.
 * The home.php template calls `bp_progenitor_member_template_part()` which will
 * locate this file to use.
 *
 * @since 0.1.0
 */
?>

<div class="member-front-page single-custom-front-page">

	<?php if ( ! is_customize_preview() && bp_current_user_can( 'bp_moderate' ) && ! is_active_sidebar( 'sidebar-buddypress-members' ) ) : ?>

		<div class="bp-feedback custom-homepage-info info">
			<strong><?php esc_html_e( 'Manage the members default front page', 'buddypress' ); ?></strong>
			<button type="button" class="bp-tooltip" data-bp-tooltip="<?php esc_attr_e( 'Close', 'buddypress' ); ?>" aria-label="<?php esc_attr_e( 'Close this notice', 'buddypress' ); ?>" data-bp-close="remove"><span class="dashicons dashicons-dismiss" aria-hidden="true"></span></button><br/>
			<?php printf(
				esc_html__( 'You can set the preferences of the %s or add %s to it.', 'buddypress' ),
				bp_progenitor_members_get_customizer_option_link(),
				bp_progenitor_members_get_customizer_widgets_link()
			); ?>
		</div>

	<?php endif; ?>

	<?php if ( bp_progenitor_members_wp_bio_info() ) : ?>

		<div class="member-description">

			<?php if ( user_has_wp_author_description() ) : ?>
				<blockquote class="member-bio">
					<?php bp_progenitor_member_description( bp_displayed_user_id() ); ?>
				</blockquote><!-- .member-bio -->
			<?php endif ; ?>

			<?php if ( bp_is_my_profile() || bp_current_user_can( 'bp_moderate' ) ) :

				if ( ! user_has_wp_author_description() && bp_progenitor_members_wp_bio_info() ) { ?>
					<div class="info bp-feedback">
						<span class="bp-icon" aria-hidden="true"></span>
						<p class="text"><?php _e('You may display your WordPress author description here, you will need to add your description by visiting the link below.', 'progenitor'); ?></p>
					</div>
			<?php	};

				bp_progenitor_member_description_edit_link();

			endif ; ?>

		</div><!-- .member-description -->

	<?php endif ; ?>

	<?php if ( is_active_sidebar( 'sidebar-buddypress-members' ) ) : ?>

		<div id="member-front-widgets" class="bp-sidebar bp-widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-buddypress-members' ); ?>
		</div><!-- .bp-sidebar.bp-widget-area -->

	<?php endif ; ?>

</div>
