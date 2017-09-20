<?php
/**
 * BP progenitor Activity
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Activity Loader class
 *
 * @since 1.0.0
 */
class BP_Progenitor_Activity {
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
		require $this->dir . 'functions.php';
		require $this->dir . 'template-tags.php';
		require $this->dir . 'widgets.php';

		// Test suite requires the AJAX functions early.
		if ( function_exists( 'tests_add_filter' ) ) {
			require $this->dir . 'ajax.php';

		// Load AJAX code only on AJAX requests.
		} else {
			add_action( 'admin_init', function() {
				// AJAX condtion.
				if ( defined( 'DOING_AJAX' ) && true === DOING_AJAX &&
					// Check to see if action is activity-specific.
					( false !== strpos( $_REQUEST['action'], 'activity' ) || ( 'post_update' === $_REQUEST['action'] ) )
				) {
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
		add_action( 'bp_progenitor_enqueue_scripts', 'bp_progenitor_activity_enqueue_scripts' );
		add_action( 'bp_widgets_init', array( 'BP_Latest_Activities', 'register_widget' ) );
		add_action( 'bp_progenitor_notifications_init_filters', 'bp_progenitor_activity_notification_filters' );

		/*
		 * Avoid BuddyPress to trigger a forsaken action notice.
		 * We'll generate the button inside our bp_progenitor_get_activity_entry_buttons()
		 * function.
		 */
		$bp = buddypress();

		if ( bp_is_akismet_active() && isset( $bp->activity->akismet ) ) {
			remove_action( 'bp_activity_entry_meta',      array( $bp->activity->akismet, 'add_activity_spam_button'         ) );
			remove_action( 'bp_activity_comment_options', array( $bp->activity->akismet, 'add_activity_comment_spam_button' ) );
		}
	}

	/**
	 * Register add_filter() hooks
	 *
	 * @since 1.0.0
	 */
	protected function setup_filters() {

		// Register activity scripts
		add_filter( 'bp_progenitor_register_scripts', 'bp_progenitor_activity_register_scripts', 10, 1 );

		// Localize Scripts
		add_filter( 'bp_core_get_js_strings', 'bp_progenitor_activity_localize_scripts', 10, 1 );

		add_filter( 'bp_get_activity_action_pre_meta', 'bp_progenitor_activity_secondary_avatars',  10, 2 );
		add_filter( 'bp_get_activity_css_class',       'bp_progenitor_activity_scope_newest_class', 10, 1 );
		add_filter( 'bp_activity_time_since',          'bp_progenitor_activity_time_since',         10, 2 );
		add_filter( 'bp_activity_allowed_tags',        'bp_progenitor_activity_allowed_tags',       10, 1 );
	}
}

/**
 * Launch the Activity loader class.
 *
 * @since 1.0.0
 */
function bp_progenitor_activity( $bp_progenitor = null ) {
	if ( is_null( $bp_progenitor ) ) {
		return;
	}

	$bp_progenitor->activity = new BP_Progenitor_Activity();
}
add_action( 'bp_progenitor_includes', 'bp_progenitor_activity', 10, 1 );
