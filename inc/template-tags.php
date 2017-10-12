<?php
/**
 * Custom template tags for this theme.
 *
 *
 * @package BP_Progenitor
 */


/**
 * Check whether post loops are set as grid layout
 *
 * Use to add additional markup conditional of type of display.
 *
 * @return bool
 * @since 0.1.0
 */
function post_loops_as_grid() {
	return (bool) progenitor_opts('post_loops_grid');
}

/**
 * Set a class for the post loop parent wrapper
 * 	* Swap out loop-wrap for 'box-align' if grid set.
 *
 * @return string
 * @since 0.1.0
 */
function post_loops_wrap_class() {
	$grid = (bool) progenitor_opts('post_loops_grid');

	if( $grid ) {
		$value =  esc_attr('box-align');
	} else {
		$value = esc_attr('loop-wrap');
	}
	echo $value;
}

/**
 * Set a class for WP entry-content element to denote bp-content
 *
 * @return string
 * @since 0.1.0
 */
function entry_content_class() {
	if ( ! function_exists( 'bp_loaded' ) ) {
		return;
	}

	if ( bp_is_directory() || bp_is_user() || bp_is_group() ) {
		return ' bp-entry-content';
	} else {
		return false;
	}
}

/**
 * Check if sidebars are set to be shown for BP directories
 *
 * @since 0.1.0
 *
 * @return bool
 */
function show_sbar_for_bp_dir() {

return (bool) progenitor_opts('bp_dir_sbar');

}

/**
 * Set footer widgets class on parent wrapper
 * Builds a class for widget container to report number of widgets being used
 * enables styling based on the known number of active foot widgets.
 *
 * @return string
 * @since 0.1.0
 */


function progenitor_foot_widgets_count() {
	echo progenitor_footer_widgets_active();
}

function progenitor_footer_widgets_active() {

$our_active_foot_widgets = array();
$our_active_foot_widgets['first']  =  is_active_sidebar( 'first-footer-widget-area' );
$our_active_foot_widgets['second'] =  is_active_sidebar( 'second-footer-widget-area' );
$our_active_foot_widgets['third']  =  is_active_sidebar( 'third-footer-widget-area' );
$our_active_foot_widgets['fourth'] =  is_active_sidebar( 'fourth-footer-widget-area' );

// Count how many keys are true to get our active widget count.
$array_count_true = count( array_filter( $our_active_foot_widgets) );

// if no array count (no active widgets) lets return false
// and use this function as a body class conditional.
if ( ! $array_count_true ) {
	return false;
}

// Add class string to array to use on parent widget container.
$the_active_foot_widgets = 'foot-widget-active-' . $array_count_true;

return $the_active_foot_widgets;
}


if ( ! function_exists( 'bp_progenitor_posted_on' ) ) :
	/**
		* Prints HTML with meta information for the current post-date/time and author.
		*/
	function bp_progenitor_posted_on( $post ) {

		// This function is used mainly outside of the direct loop so find the post id
		$id = $post->ID;

		// If BP active fetch the BP user domain url
		// else we'll use the WP link to author posts loop.
		if ( function_exists( 'bp_loaded' ) ) {
			$author_url = bp_core_get_user_domain( $post->post_author );
			// Simple bool var to set all posts link true
			$show_all_posts = true;
		} else {
			$author_url = get_author_posts_url( get_the_author_meta( 'ID', $post->post_author ) );
			// set false $author url directs to all posts
			$show_all_posts = false;
		}

		$user_name  = get_the_author_meta( 'display_name', $post->post_author );

//var_dump($post);
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'bp-progenitor' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'bp-progenitor' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( $author_url ) . '">' . esc_html( $user_name ) . '</a></span>'
		);

		$all_author_posts = sprintf(
			/* translators: %s: post author. */
			esc_html_x( ' - All posts by %s', 'post author', 'bp-progenitor' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID', $post->post_author ) ) ) . '">' . esc_html( $user_name ) . '</a></span>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

		if ( $show_all_posts ) {
			echo  '<span class="all-author-posts">' . $all_author_posts . '</span>';
		}

	}
endif;

if ( ! function_exists( 'bp_progenitor_entry_footer' ) ) :
	/**
  * Prints HTML with meta information for the categories, tags and comments.
  */
	function bp_progenitor_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'bp-progenitor' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'bp-progenitor' ) . '</span>', $categories_list ); // WPCS: XSS OK.
				echo '<span aria-hidden="true"> - </span>';
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'bp-progenitor' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'bp-progenitor' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span aria-hidden="true"> - </span><span class="fa fa-comment" aria-hidden="true"></span> <span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'bp-progenitor' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			echo '</span><span aria-hidden="true"> - </span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( ' Edit <span class="screen-reader-text">%s</span>', 'bp-progenitor' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			' <span class="edit-link fa fa-pencil-square-o"> ',
			'</span>'
		);
	}
endif;

/**
 *  Paginate post loops
 *
 * @since 0.1.0
 */
function progenitor_postloop_paginate() {
	global $wp_query, $wp_rewrite;


	$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;

	$pagination = array(
		'base' => esc_url( add_query_arg('page','%#%') ),
		'format' => '',
		'total' => $wp_query->max_num_pages,
		'current' => $current,
		'show_all' => false,
		'end_size' => 3,
		'mid_size' => 5,
		'type' => 'list'
	);

	if ( $wp_rewrite->using_permalinks() ) {
		$pagination['base'] =
			user_trailingslashit(
				trailingslashit(
					esc_url( remove_query_arg( 's', get_pagenum_link( 1 ) ) )
				) . 'page/%#%/', 'paged'
			);
	}

	if ( !empty( $wp_query->query_vars['s'] ) ) {
		$pagination['add_args'] = array(
			's' => urlencode( get_query_var( 's' ) )
		);
	}

	$the_links = '<div class="paginate-loop" role="navigation">' . paginate_links( $pagination ) . '</div>';
	echo $the_links;
}
