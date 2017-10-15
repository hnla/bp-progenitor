<?php
/**
 * BuddyPress - Progenitor Users Cover Image Header
 *
 * @since 1.0.0
 */
?>

<div id="cover-image-container">
	<a id="header-cover-image" href="<?php bp_displayed_user_link(); ?>"></a>

	<div id="item-header-cover-image">
		<div id="item-header-avatar">
			<a href="<?php bp_displayed_user_link(); ?>">

				<?php bp_displayed_user_avatar( 'type=full' ); ?>

			</a>

		</div><!-- #item-header-avatar -->

	</div><!-- #item-header-cover-image -->
</div><!-- #cover-image-container -->

<div id="item-header-content">

	<?php bp_progenitor_member_header_buttons( array( 'container' => 'div', 'button_element' => 'button', 'container_classes' => array( 'member-header-actions' ) ) ); ?>

	<?php bp_progenitor_member_hook( 'before', 'header_meta' ); ?>

	<?php if ( bp_progenitor_member_has_meta() ) : ?>
		<div class="item-meta">

			<?php bp_progenitor_member_meta(); ?>

		</div><!-- #item-meta -->
	<?php endif ; ?>

</div><!-- #item-header-content -->
