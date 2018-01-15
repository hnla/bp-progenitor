<?php
/**
 * BuddyPress - Progenitor Members Activate
 */

?>

	<?php bp_progenitor_activation_hook( 'before', 'page' ); ?>

	<div class="page" id="activate-page">

		<?php bp_progenitor_template_notices(); ?>

		<?php bp_progenitor_activation_hook( 'before', 'content' ); ?>

		<?php if ( !bp_account_was_activated() ) : ?>

			<?php if ( isset( $_GET['e'] ) ) : ?>
				<p><?php _e( 'Your account was activated successfully! Your account details have been sent to you in a separate email.', 'buddypress' ); ?></p>
			<?php else : ?>

			<div class="bp-message bp-feedback success">
				<span class="bp-icon" aria-hidden="true"></span>
				<p><?php _e( 'Your account was activated successfully! You can now login with the username and password you provided when you signed up.', 'buddypress' ); ?></p>
			</div>

				<?php progenitor_login_form( array( 'redirect' => bp_get_root_domain() ) );  ?>

			<?php endif; ?>

		<?php else : ?>

			<div class="bp-message bp-feedback info">
				<span class="bp-icon fa fa-info" aria-hidden="true"></span>
				<p><?php _e( 'Please provide a valid activation key.', 'buddypress' ); ?></p>
			</div>

			<form action="" method="get" class="standard-form" id="activation-form">

				<label for="key"><?php _e( 'Activation Key:', 'buddypress' ); ?></label>
				<input type="text" name="key" id="key" value="" />

				<p class="submit">
					<input type="submit" name="submit" value="<?php esc_attr_e( 'Activate', 'buddypress' ); ?>" />
				</p>

			</form>

		<?php endif; ?>

		<?php bp_progenitor_activation_hook( 'after', 'content' ); ?>

	</div><!-- .page -->

	<?php bp_progenitor_activation_hook( 'after', 'page' ); ?>

