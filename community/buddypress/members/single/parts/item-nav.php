<?php
/**
 * BuddyPress - Progenitor Single Members item Navigation
 *
 * @since 1.0.0
 */
?>

	<nav class="<?php bp_progenitor_single_item_nav_classes(); ?>" id="object-nav" role="navigation" aria-label="<?php esc_attr_e( 'User menu', 'buddypress' ); ?>">

		<?php if ( bp_progenitor_has_nav( array( 'type' => 'primary' ) ) ) : ?>

			<ul>

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

				<?php bp_progenitor_member_hook( '', 'options_nav' ); ?>

			</ul>

		<?php endif ; ?>

	</nav>
