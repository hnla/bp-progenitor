<?php
/**
 * BP progenitor Notifications
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Notifications Loader class
 *
 * @since 1.0.0
 */
class BP_Progenitor_Notifications {
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
		$dir = trailingslashit( $this->dir );

		require "{$dir}functions.php";
		require "{$dir}template-tags.php";
	}

	/**
	 * Register do_action() hooks
	 *
	 * @since 1.0.0
	 */
	protected function setup_actions() {
		add_action( 'bp_init', 'bp_progenitor_notifications_init_filters', 20 );
		add_action( 'bp_progenitor_enqueue_scripts', 'bp_progenitor_notifications_enqueue_scripts' );

		$ajax_actions = array(
			array( 'notifications_filter' => array( 'function' => 'bp_progenitor_ajax_object_template_loader', 'nopriv' => false ) ),
		);

		foreach ( $ajax_actions as $ajax_action ) {
			$action = key( $ajax_action );

			add_action( 'wp_ajax_' . $action, $ajax_action[ $action ]['function'] );

			if ( ! empty( $ajax_action[ $action ]['nopriv'] ) ) {
				add_action( 'wp_ajax_nopriv_' . $action, $ajax_action[ $action ]['function'] );
			}
		}
	}

	/**
	 * Register add_filter() hooks
	 *
	 * @since 1.0.0
	 */
	protected function setup_filters() {
		add_filter( 'bp_progenitor_register_scripts', 'bp_progenitor_notifications_register_scripts', 10, 1 );
		add_filter( 'bp_get_the_notification_mark_unread_link', 'bp_progenitor_notifications_mark_unread_link', 10, 1 );
		add_filter( 'bp_get_the_notification_mark_read_link',   'bp_progenitor_notifications_mark_read_link'  , 10, 1 );
		add_filter( 'bp_get_the_notification_delete_link',      'bp_progenitor_notifications_delete_link'     , 10, 1 );
	}
}

/**
 * Launch the Notifications loader class.
 *
 * @since 1.0.0
 */
function bp_progenitor_notifications( $bp_progenitor = null ) {
	if ( is_null( $bp_progenitor ) ) {
		return;
	}

	$bp_progenitor->notifications = new BP_Progenitor_Notifications();
}
add_action( 'bp_progenitor_includes', 'bp_progenitor_notifications', 10, 1 );
