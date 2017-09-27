<?php
/**
 * Custom template tags for this theme.
 *
 *
 * @package BP_Progenitor
 */


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
	function bp_progenitor_posted_on() {
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
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

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
