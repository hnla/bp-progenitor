<?php
/**
 * BP progenitor Messages
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Messages Loader class
 *
 * @since 1.0.0
 */
class BP_progenitor_Messages {
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
		$this->dir = trailingslashit( dirname( __FILE__ ) );
	}

	/**
	 * Include needed files
	 *
	 * @since 1.0.0
	 */
	protected function includes() {
		require $this->dir . 'classes.php';
		require $this->dir . 'functions.php';
		require $this->dir . 'template-tags.php';

		// Test suite requires the AJAX functions early.
		if ( function_exists( 'tests_add_filter' ) ) {
			require $this->dir . 'ajax.php';

		// Load AJAX code only on AJAX requests.
		} else {
			add_action( 'admin_init', function() {
				if ( defined( 'DOING_AJAX' ) && true === DOING_AJAX && 0 === strpos( $_REQUEST['action'], 'messages_' ) ) {
					require $this->dir . 'ajax.php';
				}
			} );
		}
	}

	/**
	 * Register do_action() hooks
	 *
	 * @since 1.0.0
	 */
	protected function setup_actions() {
		// Notices
		add_action( 'widgets_init', 'bp_progenitor_unregister_notices_widget' );
		add_action( 'bp_init',      'bp_progenitor_push_sitewide_notices', 99 );

		// Messages
		add_action( 'bp_messages_setup_nav', 'bp_progenitor_messages_adjust_nav' );

		// Remove deprecated scripts
		remove_action( 'bp_enqueue_scripts', 'messages_add_autocomplete_js' );

		// Enqueue the scripts for the new UI
		add_action( 'bp_progenitor_enqueue_scripts', 'bp_progenitor_messages_enqueue_scripts' );

		// Register the Messages Notifications filters
		add_action( 'bp_progenitor_notifications_init_filters', 'bp_progenitor_messages_notification_filters' );
	}

	/**
	 * Register add_filter() hooks
	 *
	 * @since 1.0.0
	 */
	protected function setup_filters() {
		// Enqueue specific styles
		add_filter( 'bp_progenitor_enqueue_styles', 'bp_progenitor_messages_enqueue_styles', 10, 1 );

		// Register messages scripts
		add_filter( 'bp_progenitor_register_scripts', 'bp_progenitor_messages_register_scripts', 10, 1 );

		// Localize Scripts
		add_filter( 'bp_core_get_js_strings', 'bp_progenitor_messages_localize_scripts', 10, 1 );

		// Notices
		add_filter( 'bp_messages_single_new_message_notification', 'bp_progenitor_format_notice_notification_for_user',  10, 1 );
		add_filter( 'bp_notifications_get_all_notifications_for_user', 'bp_progenitor_add_notice_notification_for_user', 10, 2 );

		// Messages
		add_filter( 'bp_messages_admin_nav', 'bp_progenitor_messages_adjust_admin_nav', 10, 1 );

		remove_filter( 'messages_notice_message_before_save',  'wp_filter_kses', 1 );
		remove_filter( 'messages_message_content_before_save', 'wp_filter_kses', 1 );
		remove_filter( 'bp_get_the_thread_message_content',    'wp_filter_kses', 1 );

		add_filter( 'messages_notice_message_before_save',  'wp_filter_post_kses', 1 );
		add_filter( 'messages_message_content_before_save', 'wp_filter_post_kses', 1 );
		add_filter( 'bp_get_the_thread_message_content',    'wp_filter_post_kses', 1 );
		add_filter( 'bp_get_message_thread_content',        'wp_filter_post_kses', 1 );
		add_filter( 'bp_get_message_thread_content',        'wptexturize'            );
		add_filter( 'bp_get_message_thread_content',        'stripslashes_deep',   1 );
		add_filter( 'bp_get_message_thread_content',        'convert_smilies',     2 );
		add_filter( 'bp_get_message_thread_content',        'convert_chars'          );
		add_filter( 'bp_get_message_thread_content',        'make_clickable',      9 );
		add_filter( 'bp_get_message_thread_content',        'wpautop'                );
	}
}

/**
 * Launch the Messages loader class.
 *
 * @since 1.0.0
 */
function bp_progenitor_messages( $bp_progenitor = null ) {
	if ( is_null( $bp_progenitor ) ) {
		return;
	}

	$bp_progenitor->messages = new BP_progenitor_Messages();
}
add_action( 'bp_progenitor_includes', 'bp_progenitor_messages', 10, 1 );
