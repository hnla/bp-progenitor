<?php
/**
 * BuddyPress - Progenitor Members Home
 *
 * @since   1.0.0
 */
?>

	<?php bp_progenitor_member_hook( 'before', 'home_content' ); ?>

	<div id="item-header" role="complementary" data-bp-item-id="<?php echo esc_attr( bp_displayed_user_id() ); ?>" data-bp-item-component="members" class="users-header single-headers">

		<?php bp_progenitor_member_header_template_part(); ?>

	</div><!-- #item-header -->

	<div class="bp-wrap">
		<?php if ( ! bp_progenitor_is_object_nav_in_sidebar() ) : ?>

			<?php bp_get_template_part( 'members/single/parts/item-nav' ); ?>

		<?php endif; ?>

		<div id="item-body" class="item-body">

			<?php bp_progenitor_member_template_part(); ?>

		</div><!-- #item-body -->
	</div><!-- // .bp-wrap -->

	<?php bp_progenitor_member_hook( 'after', 'home_content' ); ?>
