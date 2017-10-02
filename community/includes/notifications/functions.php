<?php
/**
 * Notifications functions
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Register Scripts for the Notifications component
 *
 * @since 1.0.0
 *
 * @param  array  $scripts  The array of scripts to register
 * @return array  The same array with the specific notifications scripts.
 */
function bp_progenitor_notifications_register_scripts( $scripts = array() ) {

	if ( ! isset( $scripts['bp-progenitor'] ) ) {
		return $scripts;
	}

	return array_merge( $scripts, array(
		'bp-progenitor-notifications' => array(
			'file'         => 'js/buddypress-notifications%s.js',
			'dependencies' => array( 'bp-progenitor' ),
			'footer'       => true,
		),
	) );
}

/**
 * Enqueue the notifications scripts
 *
 * @since 1.0.0
 */
function bp_progenitor_notifications_enqueue_scripts() {

	if ( ! bp_is_user_notifications() ) {
		return;
	}

	wp_enqueue_script( 'bp-progenitor-notifications' );
}

/**
 * Init Notifications filters and fire a hook to let
 * plugins/components register their filters.
 *
 * @since 1.0.O
 */
function bp_progenitor_notifications_init_filters() {
	if ( ! bp_is_user_notifications() ) {
		return;
	}

	bp_progenitor()->notifications->filters = array();

	/**
	 * Hook here to register your custom notification filters
	 *
	 * @since 1.0.0
	 */
	do_action( 'bp_progenitor_notifications_init_filters' );
}

/**
 * Register new filters for the notifications screens.
 *
 * @since 1.0.0
 *
 * @param  array  $args {
 *     Array of arguments.
 *
 *     @type string      $id         The unique string to identify your "component action". Required.
 *     @type string      $label      The human readable notification type. Required.
 *     @type int         $position   The position to output your filter. Optional.
 * }
 * @return bool True if the filter has been successfully registered. False otherwise.
 */
function bp_progenitor_notifications_register_filter( $args = array() ) {
	$bp_progenitor = bp_progenitor();

	$r = wp_parse_args( $args, array(
		'id'       => '',
		'label'    => '',
		'position' => 99,
	) );

	if ( empty( $r['id'] ) || empty( $r['label'] ) ) {
		return false;
	}

	if ( isset( $bp_progenitor->notifications->filters[ $r['id'] ] ) ) {
		return false;
	}

	$bp_progenitor->notifications->filters[ $r['id'] ] = $r;
	return true;
}

/**
 * Get one or all notifications filters.
 *
 * @since 1.0.0
 *
 * @param  string $id  The notificication component action to get the filter of.
 *                     Leave empty to get all notifications filters.
 * @return array|false All or a specific notifications parameters. False if no match are found.
 */
function bp_progenitor_notifications_get_filters( $id = '' ) {
	$bp_progenitor = bp_progenitor();

	// Get all filters
	if ( empty( $id ) ) {
		return $bp_progenitor->notifications->filters;

	// Get a specific filter
	} elseif ( ! empty( $id ) && isset( $bp_progenitor->notifications->filters[ $id ] ) ) {
		return $bp_progenitor->notifications->filters[ $id ];

	// Oops nothing found!
	} else {
		return false;
	}
}

/**
 * Sort Notifications according to their position arguments.
 *
 * @since 1.0.0
 *
 * @param  array  $filters The notifications filters to order.
 * @return array  The sorted filters.
 */
function bp_progenitor_notifications_sort( $filters = array() ) {
	$sorted = array();

	if ( empty( $filters ) || ! is_array( $filters ) ) {
		return $sorted;
	}

	foreach ( $filters as $filter ) {
		// Default position
		$position = 99;

		if ( isset( $filter['position'] ) ) {
			$position = (int) $filter['position'];
		}

		// If position is already taken, move to the first next available
		if ( isset( $sorted[ $position ] ) ) {
			$sorted_keys = array_keys( $sorted );

			do {
				$position += 1;
			} while ( in_array( $position, $sorted_keys ) );
		}

		$sorted[ $position ] = $filter;
	}

	ksort( $sorted );
	return $sorted;
}

/**
 * Add a dashicon to Notifications action links
 *
 * @since 1.0.0
 *
 * @param  string $link        The action link.
 * @param  string $bp_tooltip  The data-bp-attribute of the link.
 * @param  string $aria_label  The aria-label attribute of the link.
 * @param  string $dashicon    The dashicon class.
 * @return string              Link Output.
 */
function bp_progenitor_notifications_dashiconified_link( $link = '', $bp_tooltip = '', $dashicon = '' ) {
	preg_match( '/<a\s[^>]*>(.*)<\/a>/siU', $link, $match );

	if ( ! empty( $match[0] ) && ! empty( $match[1] ) && ! empty( $dashicon ) && ! empty( $bp_tooltip ) ) {
		$link = str_replace(
			'>' . $match[1] . '<',
			sprintf(
				' class="bp-tooltip" data-bp-tooltip="%1$s"><span class="dashicons %2$s" aria-hidden="true"></span><span class="bp-screen-reader-text">%3$s</span><',
				esc_attr( $bp_tooltip ),
				sanitize_html_class( $dashicon ),
				$match[1]
			),
			$match[0]
		);
	}

	return $link;
}

/**
 * Edit the Mark Unread action link to include a dashicon
 *
 * @since 1.0.0
 *
 * @param  string $link The Mark Unread action link.
 * @return string       Link Output.
 */
function bp_progenitor_notifications_mark_unread_link( $link = '' ) {
	return bp_progenitor_notifications_dashiconified_link( $link, __( 'Mark Unread', 'buddypress' ), 'dashicons-hidden' );
}

/**
 * Edit the Mark Read action link to include a dashicon
 *
 * @since 1.0.0
 *
 * @param  string $link The Mark Read action link.
 * @return string       Link Output.
 */
function bp_progenitor_notifications_mark_read_link( $link = '' ) {
	return bp_progenitor_notifications_dashiconified_link( $link, __( 'Mark Read', 'buddypress' ), 'dashicons-visibility' );
}

/**
 * Edit the Delete action link to include a dashicon
 *
 * @since 1.0.0
 *
 * @param  string $link The Delete action link.
 * @return string       Link Output.
 */
function bp_progenitor_notifications_delete_link( $link = '' ) {
	return bp_progenitor_notifications_dashiconified_link( $link, __( 'Delete', 'buddypress' ), 'dashicons-dismiss' );
}
