<?php
/**
 * BuddyPress Single Groups Admin Navigation
 *
 * @since 1.0.0
 */
?>

<nav class="<?php bp_progenitor_single_item_subnav_classes(); ?>" id="subnav" role="navigation" aria-label="<?php esc_attr_e( 'Group administration menu', 'buddypress' ); ?>">

	<?php if ( bp_progenitor_has_nav( array( 'object' => 'group_manage' ) ) ) : ?>

		<ul class="subnav">

			<?php while ( bp_progenitor_nav_items() ) : bp_progenitor_nav_item(); ?>

				<li id="<?php bp_progenitor_nav_id(); ?>" class="<?php bp_progenitor_nav_classes(); ?>">
					<a href="<?php bp_progenitor_nav_link(); ?>" id="<?php bp_progenitor_nav_link_id(); ?>">
						<?php bp_progenitor_nav_link_text(); ?>

						<?php if ( bp_progenitor_nav_has_count() ) : ?>
							<span><?php bp_progenitor_nav_count(); ?></span>
						<?php endif; ?>
					</a>
				</li>

			<?php endwhile; ?>

		</ul>

	<?php endif ; ?>

</nav><!-- #isubnav -->
