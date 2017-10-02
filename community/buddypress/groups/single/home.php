<?php
/**
 * BuddyPress - Groups Home
 *
 * @since 1.0.0
 */

?>

	<?php if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); ?>

		<?php bp_progenitor_group_hook( 'before', 'home_content' ); ?>

		<div id="item-header" role="complementary" data-bp-item-id="<?php bp_group_id(); ?>" data-bp-item-component="groups" class="groups-header single-headers">

			<?php bp_progenitor_group_header_template_part(); ?>

		</div><!-- #item-header -->

		<div class="bp-wrap">

			<?php if ( ! bp_progenitor_is_object_nav_in_sidebar() ) : ?>

				<?php bp_get_template_part( 'groups/single/parts/item-nav' ); ?>

			<?php endif; ?>

			<div id="item-body" class="item-body">

				<?php bp_progenitor_group_template_part(); ?>

			</div><!-- #item-body -->

		</div><!-- // .bp-wrap -->

		<?php bp_progenitor_group_hook( 'after', 'home_content' ); ?>

	<?php endwhile; endif; ?>
