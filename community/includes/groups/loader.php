<?php
/**
 * BP Nouveau Groups
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Groups Loader class
 *
 * @since 1.0.0
 */
class BP_Progenitor_Groups {
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
		$this->dir                   = trailingslashit( dirname( __FILE__ ) );
		$this->is_group_home_sidebar = false;
	}

	/**
	 * Include needed files
	 *
	 * @since 1.0.0
	 */
	protected function includes() {
		require $this->dir . 'functions.php';
		require $this->dir . 'classes.php';
		require $this->dir . 'template-tags.php';
		require $this->dir . 'widgets.php';

		// Test suite requires the AJAX functions early.
		if ( function_exists( 'tests_add_filter' ) ) {
			require $this->dir . 'ajax.php';

		// Load AJAX code only on AJAX requests.
		} else {
			add_action( 'admin_init', function() {
				if ( defined( 'DOING_AJAX' ) && true === DOING_AJAX && 0 === strpos( $_REQUEST['action'], 'groups_' ) ) {
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
		if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			add_action( 'groups_setup_nav', 'bp_progenitor_group_setup_nav' );
		}

		add_action( 'bp_progenitor_enqueue_scripts', 'bp_progenitor_groups_enqueue_scripts' );

		// Avoid Notices for BuddyPress Legacy Backcompat
		remove_action( 'bp_groups_directory_group_filter', 'bp_group_backcompat_create_nav_item', 1000 );

		// Register the Groups Notifications filters
		add_action( 'bp_progenitor_notifications_init_filters', 'bp_progenitor_groups_notification_filters' );

		// Actions to check whether we are in the Group's default front page sidebar
		add_action( 'dynamic_sidebar_before', array( $this, 'group_home_sidebar_set'   ), 10, 1 );
		add_action( 'dynamic_sidebar_after',  array( $this, 'group_home_sidebar_unset' ), 10, 1 );

		// Add a new nav item to settings to let users choose their group invites preferences
		if ( bp_is_active( 'friends' ) && ! bp_progenitor_groups_disallow_all_members_invites() ) {
			add_action( 'bp_settings_setup_nav', 'bp_progenitor_groups_invites_restriction_nav' );
		}
	}

	/**
	 * Register add_filter() hooks
	 *
	 * @since 1.0.0
	 */
	protected function setup_filters() {
		add_filter( 'bp_progenitor_register_scripts', 'bp_progenitor_groups_register_scripts', 10, 1 );
		add_filter( 'bp_core_get_js_strings', 'bp_progenitor_groups_localize_scripts', 10, 1 );
		add_filter( 'groups_create_group_steps', 'bp_progenitor_group_invites_create_steps', 10, 1 );

		$buttons = array(
			'groups_leave_group',
			'groups_join_group',
			'groups_accept_invite',
			'groups_reject_invite',
			'groups_membership_requested',
			'groups_request_membership',
			'groups_group_membership',
		);

		foreach ( $buttons as $button ) {
			add_filter( 'bp_button_' . $button, 'bp_progenitor_ajax_button', 10, 5 );
		}

		// Add sections in the BP Template Pack panel of the customizer.
		add_filter( 'bp_progenitor_customizer_sections', 'bp_progenitor_groups_customizer_sections', 10, 1 );

		// Add settings into the Groups sections of the customizer.
		add_filter( 'bp_progenitor_customizer_settings', 'bp_progenitor_groups_customizer_settings', 10, 1 );

		// Add controls into the Groups sections of the customizer.
		add_filter( 'bp_progenitor_customizer_controls', 'bp_progenitor_groups_customizer_controls', 10, 1 );

		// Add the group's default front template to hieararchy if user enabled it (Enabled by default).
		add_filter( 'bp_groups_get_front_template', 'bp_progenitor_group_reset_front_template', 10, 2 );

		// Add a new nav item to settings to let users choose their group invites preferences
		if ( bp_is_active( 'friends' ) && ! bp_progenitor_groups_disallow_all_members_invites() ) {
			add_filter( 'bp_settings_admin_nav', 'bp_progenitor_groups_invites_restriction_admin_nav', 10, 1 );
		}
	}

	/**
	 * Add filters to be sure the (BuddyPress) widgets display will be consistent
	 * with the current group's default front page.
	 *
	 * @since 1.0.0
	 *
	 * @param string $sidebar_index The Sidebar identifier.
	 */
	public function group_home_sidebar_set( $sidebar_index = '' ) {
		if ( 'sidebar-buddypress-groups' !== $sidebar_index ) {
			return;
		}

		$this->is_group_home_sidebar = true;

		// Add needed filters.
		bp_progenitor_groups_add_home_widget_filters();
	}

	/**
	 * Remove filters to be sure the (BuddyPress) widgets display will no more take
	 * the current group displayed in account.
	 *
	 * @since 1.0.0
	 *
	 * @param string $sidebar_index The Sidebar identifier.
	 */
	public function group_home_sidebar_unset( $sidebar_index = '' ) {
		if ( 'sidebar-buddypress-groups' !== $sidebar_index ) {
			return;
		}

		$this->is_group_home_sidebar = false;

		// Remove no more needed filters.
		bp_progenitor_groups_remove_home_widget_filters();
	}
}

/**
 * Launch the Groups loader class.
 *
 * @since 1.0.0
 */
function bp_progenitor_groups( $bp_progenitor = null ) {
	if ( is_null( $bp_progenitor ) ) {
		return;
	}

	$bp_progenitor->groups = new BP_Progenitor_Groups();
}
add_action( 'bp_progenitor_includes', 'bp_progenitor_groups', 10, 1 );
