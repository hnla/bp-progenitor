<?php
/**
 * BP Progenitor temptate notices template.
 *
 * @since 1.0.0
 */
?>
<aside class="<?php bp_progenitor_template_message_classes(); ?>">
	<span class="bp-icon" aria-hidden="true"></span>
	<?php bp_progenitor_template_message(); ?>

	<?php if ( bp_progenitor_has_dismiss_button() ) : ?>

		<button type="button" class="bp-tooltip" data-bp-tooltip="<?php esc_attr_e( 'Close', 'buddypress' ); ?>" aria-label="<?php esc_attr_e( 'Close this notice', 'buddypress' ); ?>" data-bp-close="<?php bp_progenitor_dismiss_button_type(); ?>"><span class="dashicons dashicons-dismiss" aria-hidden="true"></span></button>

	<?php endif ; ?>
</aside>
