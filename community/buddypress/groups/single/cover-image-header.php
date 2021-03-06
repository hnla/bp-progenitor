<?php
/**
 * BuddyPress - Groups Cover Image Header.
 *
 * @since 1.0.0
 */
?>

<div id="cover-image-container">
	<a id="header-cover-image" href="<?php echo esc_url( bp_get_group_permalink() ); ?>"></a>

	<div id="item-header-cover-image">
		<?php if ( ! bp_disable_group_avatar_uploads() ) : ?>
			<div id="item-header-avatar">
				<a href="<?php echo esc_url( bp_get_group_permalink() ); ?>" title="<?php echo esc_attr( bp_get_group_name() ); ?>">

					<?php bp_group_avatar(); ?>

				</a>
			</div><!-- #item-header-avatar -->
		<?php endif; ?>

	</div><!-- #item-header-cover-image -->

</div><!-- #cover-image-container -->

	<?php	if ( ! bp_progenitor_groups_front_page_description() ) : ?>
		<div id="item-header-content">

			<div class="meta-info">

				<?php bp_progenitor_group_hook( 'before', 'header_meta' ); ?>

				<p class="activity" data-livestamp="<?php bp_core_iso8601_date( bp_get_group_last_active( 0, array( 'relative' => false ) ) ); ?>"><?php printf( __( 'Active %s', 'buddypress' ), bp_get_group_last_active() ); ?></p>
				<?php echo bp_progenitor_group_type(); ?>

				<?php if ( bp_progenitor_group_has_meta_extra() ): ?>
					<div class="item-meta">

						<?php echo bp_progenitor_group_extra(); ?>

					</div><!-- .item-meta -->
				<?php endif; ?>

				<?php bp_progenitor_group_header_buttons( array( 'button_element' => 'button' ) ); ?>

			</div>

			<?php bp_get_template_part( 'groups/single/parts/header-item-actions' ); ?>

		</div><!-- #item-header-content -->
	<?php endif; ?>

	<?php	if ( ! bp_progenitor_groups_front_page_description() ) : ?>
		<?php if ( bp_progenitor_group_description() ) { ?>
		<div class="desc-wrap">
			<div class="group-description">
				<h3 class="single-group-desc-title"><?php _e('Group Summary', 'bp-progenitor'); ?></h3>
			<?php echo bp_progenitor_group_description(); ?>
			</div><!-- //.group_description -->
		</div>
		<?php	} ?>
	<?php endif; ?>
