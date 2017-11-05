<?php
/**
* 404 & search results recent content suggestions
*
* @package BP_Progenitor
*/
?>

<div class="suggested-content">

	<div class="box">
		<?php	the_widget( 'WP_Widget_Recent_Posts' ); ?>
	</div>


	<div class="box">

		<div class="widget widget_categories">

			<h2 class="widget-title"><?php esc_html_e( 'Most Used Categories', 'bp-progenitor' ); ?></h2>

			<ul>
			<?php
				wp_list_categories( array(
				'orderby'    => 'count',
				'order'      => 'DESC',
				'show_count' => 1,
				'title_li'   => '',
				'number'     => 10,
				) );
			?>
			</ul>

		</div>

	</div>

	<div class="box">
		<?php
			$archive_content = '<p>' . sprintf( esc_html__( 'Try looking in the monthly archives.', 'bp-progenitor' ) ) . '</p>';
			the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$archive_content" );
			?>
	</div>

	<div class="box">
	<?php		the_widget( 'WP_Widget_Tag_Cloud' ); ?>
	</div>

</div>
