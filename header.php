<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package BP_Progenitor
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<div id="site-wrap">

		<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'bp-progenitor' ); ?></a>

		<div id="page" class="site">

			<header id="masthead" class="site-header">

				<?php if( has_header_image() ) : ?>
				<div class="header-background">
				<?php endif; ?>

					<div class="site-branding">

						<?php
						the_custom_logo();

						if ( is_front_page() && is_home() ) : ?>
							<h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
						<?php else : ?>
							<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
						<?php
						endif;

						$description = get_bloginfo( 'description', 'display' );
						if ( $description || is_customize_preview() ) : ?>
							<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
						<?php
						endif; ?>
					</div><!-- .site-branding -->

				<?php if( has_header_image() ) : ?>
				</div><!-- // .header-background -->
				<?php endif; ?>

				<nav id="site-navigation" class="main-navigation site-navs primary-color">
					<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'bp-progenitor' ); ?></button>
					<?php
						wp_nav_menu( array(
							'theme_location' => 'menu-1',
							'menu_id'        => 'primary-menu',
						) );
					?>
				</nav><!-- #site-navigation -->
			</header><!-- #masthead -->

			<div id="content" class="site-content">

				<div class="meta-bar">
					<?php if( is_page() ) { ?>
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
						<?php }elseif( is_single() ) { ?>
							<p>post</p>
					<?php } ?>
				</div>

