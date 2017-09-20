<?php
/**
 * BP Progenitor Component's directory nav template.
 *
 * @since 1.0.0
 */
?>

<nav class="<?php bp_progenitor_directory_type_navs_class(); ?>" role="navigation" aria-label="<?php esc_attr_e( 'Directory menu', 'buddypress' ); ?>">

	<?php if ( bp_progenitor_has_nav( array( 'object' => 'directory' ) ) ) : ?>

		<ul type="list" class="component-navigation <?php bp_progenitor_directory_list_class(); ?>">

			<?php while ( bp_progenitor_nav_items() ) : bp_progenitor_nav_item(); ?>

				<li id="<?php bp_progenitor_nav_id(); ?>" class="<?php bp_progenitor_nav_classes(); ?>" <?php bp_progenitor_nav_scope(); ?> data-bp-object="<?php bp_progenitor_directory_nav_object(); ?>">
					<a href="<?php bp_progenitor_nav_link(); ?>">
						<?php bp_progenitor_nav_link_text(); ?>

						<?php if ( bp_progenitor_nav_has_count() ) : ?>
							<span><?php bp_progenitor_nav_count(); ?></span>
						<?php endif; ?>
					</a>
				</li>

			<?php endwhile; ?>

		</ul><!-- .component-navigation -->

	<?php endif ; ?>

</nav><!-- .bp-navs -->
