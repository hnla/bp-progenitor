<?php
/*
Plugin Name: BP Progenitor Theme registration - Hugo Ashmore (hnla)

	Plugin runs the necessary BP hook to register the theme compat package details.

 This file needs to be run in the mu folder  'mu-plugins' under 'wp-content'
 If 'mu-plugins' doesn't exist it will need creating manually.

 This a temporary approach to hopefully be superceded by being able to
 run the hook from the theme setup functions file.

 Version: 0.1.0

 Author: Hugo Ashmore

*/


/**
* Register BP Progenitor
*
*/
function register_bp_progenitor_details() {
	add_action( 'bp_register_theme_packages', function() {
		bp_register_theme_package( array(
			'id'      => 'progenitor',
			'name'    => __( 'BuddyPress Progenitor', 'buddypress' ),
			'version' => bp_get_version(),
			'dir'     => trailingslashit( get_template_directory() . '/community' ),
			'url'     => trailingslashit( get_template_directory_uri() . '/community' ),
		) );
	} );
}
add_action('bp_loaded', 'register_bp_progenitor_details');
