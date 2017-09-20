<?php
/**
 * BuddyPress - Progenitor Single Members item Sub Navigation
 *
 * @since 1.0.0
 */
?>

<?php if ( bp_progenitor_has_nav( array( 'type' => 'secondary' ) ) ) : ?>

	<?php while ( bp_progenitor_nav_items() ) : bp_progenitor_nav_item(); ?>

		<li id="<?php bp_progenitor_nav_id(); ?>" class="<?php bp_progenitor_nav_classes(); ?>" <?php bp_progenitor_nav_scope(); ?>>
			<a href="<?php bp_progenitor_nav_link(); ?>" id="<?php bp_progenitor_nav_link_id(); ?>">
				<?php bp_progenitor_nav_link_text(); ?>

				<?php if ( bp_progenitor_nav_has_count() ) : ?>
					<span><?php bp_progenitor_nav_count(); ?></span>
				<?php endif; ?>
			</a>
		</li>

	<?php endwhile; ?>

<?php endif ; ?>
