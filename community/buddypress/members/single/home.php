<?php
/**
 * BuddyPress - Progenitor Members Home
 *
 * @since   1.0.0
 */
?>

<?php if ( 'vertical' == progenitor_menu_style() ) { ?>
	<?php //bp_get_template_part( 'members/single/parts/item-nav' ); ?>
	<div class="flex-wrap">
<?php } ?>

<?php bp_progenitor_member_hook( 'before', 'home_content' ); ?>


<div id="item-header" role="complementary" data-bp-item-id="<?php echo esc_attr( bp_displayed_user_id() ); ?>" data-bp-item-component="members" class="users-header single-headers">

	<?php bp_progenitor_member_header_template_part(); ?>

</div><!-- #item-header -->

<div class="bp-wrap">

	<?php
	/*
	 * If the object selection is not display in main header & not using the widget menu
	 * we display menu here as vert or hori, else we move the nav into the header.php file.
		*/
	?>
	<?php if ( ! bp_progenitor_is_object_nav_in_sidebar() ) : ?>

	<?php if( progenitor_opts( 'object_nav_main_header') ) :
	// This element is controled via scripting to show menu if
	// the script removes the display property
	?>
		<div class="manage-object-nav-visibility" style="display: none">
	<?php endif; ?>

		<?php bp_get_template_part( 'members/single/parts/item-nav' ); ?>

	<?php if( progenitor_opts( 'object_nav_main_header') ) : ?>
		</div>
	<?php endif; ?>

	<?php endif; ?>

	<div id="item-body" class="item-body">

		<?php bp_progenitor_member_template_part(); ?>

	</div><!-- #item-body -->
</div><!-- // .bp-wrap -->

<?php bp_progenitor_member_hook( 'after', 'home_content' ); ?>

<?php if ( 'site-nav-vertical' == progenitor_menu_style() ) { ?>
	</div>
<?php } ?>
