<?php
/**
 * BuddyPress - Groups Header
 *
 * @since 1.0.0
 */
?>

<?php bp_get_template_part( 'groups/single/parts/header-item-actions' ); ?>

<?php if ( ! bp_disable_group_avatar_uploads() ) : ?>
	<div id="item-header-avatar">
		<a href="<?php echo esc_url( bp_get_group_permalink() ); ?>" class="bp-tooltip" data-bp-tooltip="<?php echo esc_attr( bp_get_group_name() ); ?>">

			<?php bp_group_avatar(); ?>

		</a>
	</div><!-- #item-header-avatar -->
<?php endif; ?>

<div id="item-header-content">

	<div class="meta-info">

		<p class="activity" data-livestamp="<?php bp_core_iso8601_date( bp_get_group_last_active( 0, array( 'relative' => false ) ) ); ?>">
			<?php printf( __( 'active %s', 'buddypress' ), bp_get_group_last_active() ); ?>
		</p>

	</div>
	<?php bp_progenitor_group_hook( 'before', 'header_meta' ); ?>

	<?php if ( bp_progenitor_group_has_meta_extra() ): ?>
		<div class="item-meta">

			<?php echo bp_progenitor_group_extra() ?>

		</div><!-- .item-meta -->
	<?php endif; ?>

	<?php if ( ! bp_progenitor_groups_front_page_description() ) { ?>
		<?php if ( bp_progenitor_group_description() ) { ?>
			<div class="group-description">
				<?php echo bp_progenitor_group_description() ?>
			</div><!-- //.group_description -->
		<?php	} ?>
	<?php } ?>

</div><!-- #item-header-content -->

<?php bp_progenitor_group_header_buttons(); ?>
