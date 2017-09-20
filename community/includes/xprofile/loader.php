<?php
/**
 * BP progenitor xProfile
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * xProfile Loader class
 *
 * @since 1.0.0
 */
class BP_Progenitor_xProfile {
	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->setup_globals();
		$this->includes();
		$this->setup_actions();
		$this->setup_filters();
	}

	/**
	 * Globals
	 *
	 * @since 1.0.0
	 */
	protected function setup_globals() {
		$this->dir = dirname( __FILE__ );
	}

	/**
	 * Include needed files
	 *
	 * @since 1.0.0
	 */
	protected function includes() {
		require( trailingslashit( $this->dir ) . 'functions.php'     );
		require( trailingslashit( $this->dir ) . 'template-tags.php' );
	}

	/**
	 * Register do_action() hooks
	 *
	 * @since 1.0.0
	 */
	protected function setup_actions() {
		// Enqueue the scripts
		add_action( 'bp_progenitor_enqueue_scripts', 'bp_progenitor_xprofile_enqueue_scripts' );
	}

	/**
	 * Register add_filter() hooks
	 *
	 * @since 1.0.0
	 */
	protected function setup_filters() {
		// Register xprofile scripts
		add_filter( 'bp_progenitor_register_scripts', 'bp_progenitor_xprofile_register_scripts', 10, 1 );
	}
}

/**
 * Launch the xProfile loader class.
 *
 * @since 1.0.0
 */
function bp_progenitor_xprofile( $bp_progenitor = null ) {
	if ( is_null( $bp_progenitor ) ) {
		return;
	}

	$bp_progenitor->xprofile = new BP_Progenitor_xProfile();
}
add_action( 'bp_progenitor_includes', 'bp_progenitor_xprofile', 10, 1 );
