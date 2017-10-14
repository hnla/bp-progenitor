<?php
/**
 * BP Progenitor functions and definitions
 *
 * @package BP_Progenitor
 *
 * @version 0.1.0 Pre-alpha
 */

if ( ! function_exists( 'bp_progenitor_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function bp_progenitor_setup() {

		/**
		 * @todo: Lets setup something for managing adminbar and users
		 *
		 */
//		add_filter('show_admin_bar', '__return_false');

		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on BP Progenitor, use a find and replace
		 * to change 'bp-progenitor' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'bp-progenitor', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'bp-progenitor' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'bp_progenitor_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );

		/**
		 * Add custom images
		 */
		add_image_size('single-featured', '800', '200', true );
		add_image_size('lists-featured-thumb', '1200', '400', true );
		add_image_size('featured-sticky', '650', '400', true );

		function progenitor_groups_types() {
			bp_groups_register_group_type( 'team', array(
				'labels' => array(
				'name' => 'Teams',
				'singular_name' => 'Team'
				),

				// New parameters as of BP 2.7.
				'has_directory' => 'teams',
				'show_in_create_screen' => true,
				'show_in_list' => true,
				'description' => 'Teams are good',
				'create_screen_checked' => true
				) );
		}
		add_action( 'bp_groups_register_group_types', 'progenitor_groups_types' );

	}
endif;
add_action( 'after_setup_theme', 'bp_progenitor_setup' );

/**
 * Provide site theme options early on
 *
 * @version 0.1.0
 */

function progenitor_opts( $option = '' ) {

	$progenitor_opts = bp_progenitor_get_appearance_settings( $option );

	return $progenitor_opts;
}
add_action('bp_after_setup_theme', 'progenitor_opts');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function bp_progenitor_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'bp_progenitor_content_width', 640 );
}
add_action( 'after_setup_theme', 'bp_progenitor_content_width', 0 );

/**
 * Register widget areas.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function bp_progenitor_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Homepage Block 1 Loggedout', 'bp-progenitor' ),
		'id'            => 'homepage-block-1-loggedout',
		'description'   => esc_html__( 'Add widgets here.', 'bp-progenitor' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Homepage Block 1 Loggedin', 'bp-progenitor' ),
		'id'            => 'homepage-block-1-loggedin',
		'description'   => esc_html__( 'Add widgets here.', 'bp-progenitor' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	// Site sidebars
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'bp-progenitor' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'bp-progenitor' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar Posts', 'bp-progenitor' ),
		'id'            => 'sidebar-posts',
		'description'   => esc_html__( 'Add widgets here.', 'bp-progenitor' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar Pages', 'bp-progenitor' ),
		'id'            => 'sidebar-pages',
		'description'   => esc_html__( 'Add widgets here.', 'bp-progenitor' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	// footer widgets
	register_sidebar(
		array(
			'name' => __('First Footer Widget', 'bp-progenitor'),
			'id' =>   'first-footer-widget-area',
			'description' => __('footer widget region', 'bp-progenitor'),
			'before_widget' => '<div id="%1$s" class="widget first-foot-widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h2 class="widgettitle">',
			'after_title' => '</h2>'
		)
	);
	register_sidebar(
		array(
			'name' => __('Second Footer Widget', 'bp-progenitor'),
			'id' =>   'second-footer-widget-area',
			'description' => __('footer widget region', 'bp-progenitor'),
			'before_widget' => '<div id="%1$s" class="widget first-foot-widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h2 class="widgettitle">',
			'after_title' => '</h2>'
		)
	);
	register_sidebar(
		array(
			'name' => __('third Footer Widget', 'bp-progenitor'),
			'id' =>   'third-footer-widget-area',
			'description' => __('footer widget region', 'bp-progenitor'),
			'before_widget' => '<div id="%1$s" class="widget first-foot-widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h2 class="widgettitle">',
			'after_title' => '</h2>'
		)
	);
}

add_action( 'widgets_init', 'bp_progenitor_widgets_init' );

/**
 * Enqueue styles.
 */
function bp_progenitor_styles() {

	$rtl = '';
	if ( is_rtl() ) {
		$rtl = '-rtl';
	}

	$ext = '.css';
	wp_enqueue_style( 'bp-progenitor-style',       get_stylesheet_uri() );
	wp_enqueue_style( 'bp-progenitor-fontawesome', get_template_directory_uri()   . '/assets/css/font-awesome' . $ext, array() );
	wp_enqueue_style( 'bp-progenitor-core-style',  get_template_directory_uri()   . '/assets/css/core-style' . $rtl . $ext, array() );
	wp_enqueue_style( 'bp-progenitor-site-layout', get_template_directory_uri()   . '/assets/css/site-layout' . $rtl . $ext, array('bp-progenitor-style') );
	wp_enqueue_style( 'bp-progenitor-appearance',  get_stylesheet_directory_uri() . '/assets/css/appearance' . $rtl . $ext, array('bp-progenitor-site-layout') );

}
add_action( 'wp_enqueue_scripts', 'bp_progenitor_styles' );

/**
 * Remove inline WP adminbar styles.
 * We'll add our own that doesn't add margin to html element
 * upsetting layouts heights.
 */
function remove_adminbar_styles() {
	remove_action('wp_head', '_admin_bar_bump_cb');
}
add_action('get_header', 'remove_adminbar_styles');

/**
 * Enqueue scripts.
 */
function bp_progenitor_scripts() {

	wp_enqueue_script( 'bp-progenitor-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'bp-progenitor-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'bp_progenitor_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer-postmessage-support.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Add custom classes to WP $classes for body element
 * Manage styles for layout positioning based on option settings
 *
 */
function progenitor_body_classes( $classes ) {

	if( function_exists( 'bp_is_active' ) ) :
		$bp_is_directory = bp_is_directory();
	else:
		$bp_is_directory = false;
	endif;

	if ( is_active_sidebar( 'sidebar-posts' ) && is_single() ) {
		$classes[] = 'post-sbar-active';
	}

	if ( is_active_sidebar( 'sidebar-pages' )
		&& is_page() && ! bp_is_user()
			&& ! bp_is_group()
				&& ! bp_is_directory()
				 && ! bp_is_group_create() ){
		$classes[] = 'page-sbar-active';
	}

	if ( bp_is_directory() && 1 === progenitor_opts( 'bp_dir_sbar' ) ) {
		// however if user option is show sidebars for BP dir pages
		// then we'll override the above & add the page sbar class
		$classes[] = 'page-sbar-active';
	}

	if ( progenitor_footer_widgets_active() ) {
		$classes[] = 'footer-widgets-active';
	}

	if ( is_admin_bar_showing() ) {
		$classes[] = 'adminbar-visible';
	}

	if ( !is_user_logged_in() ) {
		$classes[] = 'logged-out';
	}

	if( is_active_sidebar('sidebar-1') && ! is_singular() && ! is_front_page() ) {
		$classes[] = 'main-sidebar';
	}

	if( 'vertical' == progenitor_opts('main_site_menu') ):
		$classes[] = 'site-nav-vertical';
	elseif( 'horizontal' == progenitor_opts( 'main_site_menu' ) ) :
		$classes[] = 'site-nav-horizontal';
	endif;

	if ( 1 === progenitor_opts('post_loops_grid') ) {
		$classes[] = 'post-list-grid';
	}

	if ( is_singular() && ! is_buddypress() ) {
		$classes[] = 'wp-single';
	}

	// If static pages are set check for is_home() ! is_front_page()
	// to set a body tag for style convenience.
	if ( is_home() && ! is_front_page() ) {
		$classes[] = 'static-posts-home';
	}

	return $classes;
}
add_filter('body_class', 'progenitor_body_classes', 15);


