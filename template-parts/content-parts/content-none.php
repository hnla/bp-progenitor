<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package BP_Progenitor
 */

?>

<div class="no-results not-found">

	<header class="page-info">

		<?php if ( is_search() ) { ?>
		<h2 class="page-info-title"><?php printf( __('No Results for the query: %1$s', 'bp-progenitor' ), get_search_query() ); ?></h2>
	<?php } else { ?>
		<h2 class="page-info-title"><?php esc_html_e( 'No Results', 'bp-progenitor' ); ?></h2>
		<?php } ?>
	</header><!-- .page-header -->

	<div class="page-content">
		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php
				printf(
					wp_kses(
						/* translators: 1: link to WP admin new post page. */
						__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'bp-progenitor' ),
						array(
							'a' => array(
								'href' => array(),
							),
						)
					),
					esc_url( admin_url( 'post-new.php' ) )
				);
			?></p>

		<?php elseif ( is_search() ) : ?>

			<p><?php esc_html_e( 'Sorry, we couldn&rsquo;t locate any content for your search terms. Please try again with some different keywords or browse the most recent content listed below.', 'bp-progenitor' ); ?></p>
			<?php
				progenitor_site_search( array( 'parent_class' => array('wide') ) );
			 get_template_part('template-parts/content-parts/no-results-suggested-content');

		else : ?>

			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'bp-progenitor' ); ?></p>
			<?php
				get_search_form();

		endif; ?>
	</div><!-- .page-content -->
</div><!-- .no-results -->
