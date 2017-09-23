<?php
/**
 * Sample implementation of the Custom Header feature
 *
 * Set header background images or inline images.
 *
	* <?php the_header_image_tag(); ?>
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package BP_Progenitor
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses bp_progenitor_header_style()
 */
function bp_progenitor_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'bp_progenitor_custom_header_args', array(
		'default-image'          => get_template_directory_uri() . '/images/header/default-background.png',
		'default-text-color'     => '000000',
		'width'                  => 1400,
		'height'                 => 350,
		'flex-height'            => false,
		'wp-head-callback'       => 'bp_progenitor_header_style',
	) ) );
}
add_action( 'after_setup_theme', 'bp_progenitor_custom_header_setup' );

if ( ! function_exists( 'bp_progenitor_header_style' ) ) :
	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @see bp_progenitor_custom_header_setup().
	 */
	function bp_progenitor_header_style() {
		$header_text_color = get_header_textcolor();

		if ( $header_image = get_header_image_tag() ) {



		}

		/*
		 * If no custom options for text are set, let's bail.
		 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
		 */
		if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
			return;
		}

		// If we get this far, we have custom styles. Let's do this.
		?>
		<style type="text/css">
		<?php
		// Has the text been hidden?
		if ( ! display_header_text() ) :
		?>
			.site-title,
			.site-description {
				position: absolute;
				clip: rect(1px, 1px, 1px, 1px);
			}
		<?php
			// If the user has set a custom color for the text use that.
			else :
		?>
			.site-title a,
			.site-description {
				color: #<?php echo esc_attr( $header_text_color ); ?>;
			}
		<?php endif; ?>
		</style>
		<?php
	}
endif;

/**
* Set an image as background to header
*
* @version 0.1.0
*/
function set_logo_as_header_background() {
	$options = bp_progenitor_get_appearance_settings();
	$set_header_as_background = $options['header_img_background'];

	if (	0 === $set_header_as_background || ! has_header_image() ) {
		return;
	}

	$img_height = get_custom_header()->height;
	$user_logo = '';

	$get_directory = ( is_child_theme() )? get_stylesheet_directory_uri() : get_template_directory_uri();
	$get_directory_path = ( is_child_theme() )? get_stylesheet_directory() : get_template_directory();

//var_dump( has_header_image() );

	if ( file_exists($get_directory_path . '/assets/images/site-logo/' . $user_logo) && $set_header_background ) : ?>

	<style type="text/css">
		#site-header {background: url(<?php echo $get_directory . '/assets/images/site-logo/' . $user_logo ?>) no-repeat 0 0 ; background-size:  100%;}
	</style>

	<?php
	elseif( $set_header_as_background ) : ?>

	<style type="text/css">
		body:not(.site-nav-vertical) .site-header {
			background: transparent;
		}

		body:not(.site-nav-vertical) .header-background {
			background: url(<?php echo esc_url(header_image()); ?>) no-repeat 0 0 ;
			background-size:  cover;
			min-height: <?php echo $img_height; ?>px;
		}
	</style>

	<?php
	else:
		return false;
	endif;
}
add_action('wp_head', 'set_logo_as_header_background');
