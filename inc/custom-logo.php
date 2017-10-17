<?php
/**
* Custom logo
*
* This is a lift of the WP core template function for convenience.
* The original is not easily filterable.
* This version adds custon logo width from user options settings.
* Version ommits customizer preview $html, core function will handle that.
*
* @since 0.1.0
*/

function progenitor_custom_logo( $blog_id = 0) {
	echo progenitor_get_custom_logo();
}

function progenitor_get_custom_logo( $blog_id = 0 ) {

			$html = '';
			$switched_blog = false;

			if ( is_multisite() && ! empty( $blog_id ) && (int) $blog_id !== get_current_blog_id() ) {
				switch_to_blog( $blog_id );
				$switched_blog = true;
			}

			$custom_logo_id = get_theme_mod( 'custom_logo' );

			$custom_logo_size = 'width-' . progenitor_logo_size();

			// If the site menu is vertical then we need logo 100%
			if ( 'vertical' === progenitor_menu_style() ) {
				$custom_logo_size = 'width-100';
			}

			// We have a logo. Logo is go.
			if ( $custom_logo_id ) {
				$custom_logo_attr = array(
						'class'    => 'custom-logo',
						'itemprop' => 'logo',
				);

				/*
					* If the logo alt attribute is empty, get the site title and explicitly
					* pass it to the attributes used by wp_get_attachment_image().
					*/
				$image_alt = get_post_meta( $custom_logo_id, '_wp_attachment_image_alt', true );
				if ( empty( $image_alt ) ) {
								$custom_logo_attr['alt'] = get_bloginfo( 'name', 'display' );
				}

				/*
					* If the alt attribute is not empty, there's no need to explicitly pass
					* it because wp_get_attachment_image() already adds the alt attribute.
					*/
				$html = sprintf( '<a href="%1$s" class="custom-logo-link %2$s" rel="home" itemprop="url">%3$s</a>',
								esc_url( home_url( '/' ) ), $custom_logo_size,
								wp_get_attachment_image( $custom_logo_id, 'full', false, $custom_logo_attr )
				);
			}

			if ( $switched_blog ) {
					restore_current_blog();
			}

			/**
				* Filters the custom logo output.
				*
				* @since 4.5.0
				* @since 4.6.0 Added the `$blog_id` parameter.
				*
				* @param string $html    Custom logo HTML output.
				* @param int    $blog_id ID of the blog to get the custom logo for.
				*/
			return apply_filters( 'get_custom_logo', $html, $blog_id );
}
