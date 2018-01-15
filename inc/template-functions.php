<?php
/**
	* Hooks, filters & general WP site functions
	*
	* @package BP_Progenitor
	*/

/**
	* Adds custom classes to the array of body classes.
	*
	* @param array $classes Classes for the body element.
	* @return array
	*/
function bp_progenitor_main_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'bp_progenitor_main_body_classes', 20 );

/**
	* Add a pingback url auto-discovery header for singularly identifiable articles.
	*/
function bp_progenitor_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'bp_progenitor_pingback_header' );


/**
* Filter the default excerpt length for post loops
*
* @since 0.1.0
*
* @todo Add some checking for  page types & various excerpt lengths.
*/
function progenitor_post_excerpt_length( $length ) {

	$length = 20;

	return $length;
}
add_filter( 'excerpt_length', 'progenitor_post_excerpt_length' );


/**
* Return the post navigation for the haeder region.
*
* @since 0.1.0
*
* @return string
*/
function bp_progenitor_post_navigation( $args = array() ) {

	echo progenitor_get_post_navi( $args );

	return;
}

/**
* The post navi with custom args
* This is a custom build using WP get & next post_link functions
*
* @since 0.1.0
*
*/
function progenitor_get_post_navi( $args ) {

	$args = wp_parse_args( $args, array(
		'prev_text'          => '%title',
		'next_text'          => '%title',
		'icons'              => false,
		'in_same_term'       => false,
		'excluded_terms'     => '',
		'taxonomy'           => 'category',
		'screen_reader_text' => __( 'Post navigation' ),
		'class'              => '',
	) );

	$display_nav = '';
	$screen_reader = sanitize_text_field( $args['screen_reader_text'] );
	$additional_classes = ( ! empty( $args['class'] ) )? ' ' . sanitize_text_field( $args['class'] ): '';

	$icon_left  = '';
	$icon_right = '';

	if ( true === $args['icons'] ) {
		$icon_left  = '<span class="fa fa-angle-double-left"  aria-hidden="true"></span>';
		$icon_right = '<span class="fa fa-angle-double-right" aria-hidden="true"></span>';
	}

	$previous = get_previous_post_link(
		'<div class="nav-previous">' . $icon_left . ' %link</div>',
		$args['prev_text'],
		$args['in_same_term'],
		$args['excluded_terms'],
		$args['taxonomy']
	);

	$next = get_next_post_link(
		'<div class="nav-next">%link ' . $icon_right . '</div>',
		$args['next_text'],
		$args['in_same_term'],
		$args['excluded_terms'],
		$args['taxonomy']
	);

	// Only add markup if there's somewhere to navigate to.
	if ( $previous || $next ) {
		$display_nav = '
		<nav class="post-navigation' . $additional_classes . '" >
		<h2 class="screen-reader-text">' . $screen_reader . '</h2>'
		. $previous . $next . '
		</nav>
		';
	}

	return $display_nav; //$navigation;
}

/**
* Add a responsive wrapper element for media embeds in posts/pages
*
* See supporting styles in stylesheet.
*
* @since 1.0.0
*/
function progenitor_wrap_embeds_html( $html, $url, $attr, $post_ID ) {

	$respon_oembed = '<div class="video-wrap">' . $html . '</div>';
	return $respon_oembed;
}
add_filter('embed_oembed_html', 'progenitor_wrap_embeds_html', 10, 4);

function filter_act_stream( $retval ) {

// We want to run this pre filter only on the main activity directory
// so return early if not dir & activity not true.
	if (  ! bp_is_directory() && ! bp_is_activity_directory() ) {
		return $retval;
	}

	$retval['action'] = 'new_blog_post, activity_update';

return $retval;
}
add_filter('bp_before_has_activities_parse_args', 'filter_act_stream', 10);

