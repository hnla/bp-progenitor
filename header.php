<?php
/**
 * The header for BP Progenitor
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

				<?php if( has_header_image() && 'horizontal' === progenitor_opts( 'main_site_menu' ) ) : ?>
				<div class="header-background">
				<?php endif; ?>

					<div class="site-branding">

						<?php
						if ( 'vertical' === progenitor_opts( 'main_site_menu' ) || progenitor_overlay_logo() ) :
							progenitor_custom_logo();
					 endif;

					 if ( progenitor_show_title() ) {
							if ( is_front_page() && is_home() ) : ?>
								<h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
							<?php else : ?>
								<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
							<?php
							endif;
						};

						if ( progenitor_show_tagline() ) {
							$description = get_bloginfo( 'description', 'display' );
							if ( $description || is_customize_preview() ) : ?>
								<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
							<?php
							endif;
						} ?>

					</div><!-- .site-branding -->

				<?php if( has_header_image() && 'horizontal' === progenitor_opts( 'main_site_menu' ) ) : ?>
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

				<?php
				/**
				 * Dont display empty meta bar on front page i.e static page & vertical main menu
				 */
				if ( progenitor_display_metabar() ) : ?>

				<div class="meta-bar">
					<?php if( ( is_page() || is_single() ) && ! is_front_page() ) { ?>

						<header class="entry-header">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

						<?php if ( 'post' === get_post_type() ) : ?>
							<div class="entry-meta">
								<?php bp_progenitor_posted_on( $post ); ?>
							</div><!-- .entry-meta -->
							<?php bp_progenitor_post_navigation( array( 'prev_text' => 'Prev post', 'next_text' => 'Next Post', 'icons' => true ) ); ?>
						<?php	endif; ?>

						<?php if ( bp_is_group() ) : ?>
							<p class="highlight group-status">
								<?php esc_html( bp_progenitor_group_status() ) . _e(' Group', 'bp-progenitor'); ?>
							</p>
						<?php endif; ?>

						<?php if ( bp_is_user() ) : ?>
							<?php if ( bp_is_active( 'activity' ) && bp_activity_do_mentions() ) : ?>
								<span class="user-nicename" aria-role="hidden">@<?php bp_displayed_user_mentionname(); ?></span>
							<?php endif; ?>
						<?php endif; ?>

						</header>

					<?php } elseif( is_category() || is_archive() || is_tag() ) { ?>

						<header class="page-header">
						<?php
							the_archive_title( '<h1 class="page-title">', '</h1>' );
							the_archive_description( '<div class="archive-description">', '</div>' );
						?>
						</header>

					<?php } elseif ( is_search() ) { ?>
						<header class="page-header">
							<h1 class="page-title"><?php _e('Search Results', 'bp-progenitor'); ?></h1>
						</header>
					<?php } elseif ( is_home() && ! is_front_page() ) { ?>
						<header>
							<h1 class="page-title"><?php  single_post_title() . _e(': Index', 'bp-progenitor'); ?></h1>
						</header>
				<?php }; ?>
				</div>
			<?php endif; ?>
