<?php
/**
 * Progenitor Groups Widgets
 *
 * @package bp_progenitor
 * @subpackage GroupsWidgets
 * @since 0.1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * De-register original BP widget
 *
 * @since 0.1.0
 */
	add_action( 'widgets_init', function() { unregister_widget( 'BP_Groups_Widget' ); }, 15 );

/**
 * Register widgets for progenitor groups component.
 *
 * @since 0.1.0
 */

	add_action( 'widgets_init', function() { register_widget( 'BP_Progenitor_Groups_Widget' ); } );

class BP_Progenitor_Groups_Widget extends WP_Widget {

	/**
	 * Working as a group, we get things done better.
	 *
	 * @since 1.0.3
	 */
	public function __construct() {
		$widget_ops = array(
			'description'                 => __( 'A dynamic list of recently active, popular, newest, or alphabetical groups', 'bp-progenitor' ),
			'classname'                   => 'widget_bp_groups_widget buddypress widget',
			'customize_selective_refresh' => true,
		);
		parent::__construct( false, _x( '(BuddyPress) Groups', 'widget name', 'bp-progenitor' ), $widget_ops );

		if ( is_customize_preview() || is_active_widget( false, false, $this->id_base ) ) {
			add_action( 'bp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 2.6.0
	 */
	public function enqueue_scripts() {
		$min = bp_core_get_minified_asset_suffix();
		wp_enqueue_script( 'groups_widget_groups_list-js', buddypress()->plugin_url . "bp-groups/js/widget-groups{$min}.js", array( 'jquery' ), bp_get_version() );
	}

	/**
	 * Extends our front-end output method.
	 *
	 * @since 1.0.3
	 *
	 * @param array $args     Array of arguments for the widget.
	 * @param array $instance Widget instance data.
	 */
	public function widget( $args, $instance ) {
		global $groups_template;

		/**
		 * Filters the user ID to use with the widget instance.
		 *
		 * @since 1.5.0
		 *
		 * @param string $value Empty user ID.
		 */
		$user_id = apply_filters( 'bp_group_widget_user_id', '0' );

		extract( $args );

		if ( empty( $instance['group_default'] ) ) {
			$instance['group_default'] = 'popular';
		}

		if ( empty( $instance['title'] ) ) {
			$instance['title'] = __( 'Groups', 'bp-progenitor' );
		}

		/**
		 * Filters the title of the Groups widget.
		 *
		 * @since 1.8.0
		 * @since 2.3.0 Added 'instance' and 'id_base' to arguments passed to filter.
		 *
		 * @param string $title    The widget title.
		 * @param array  $instance The settings for the particular instance of the widget.
		 * @param string $id_base  Root ID for all widgets of this type.
		 */
		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		/**
		 * Filters the separator of the group widget links.
		 *
		 * @since 2.4.0
		 *
		 * @param string $separator Separator string. Default '|'.
		 */
		$separator = apply_filters( 'bp_groups_widget_separator', '|' );

		echo $before_widget;

		$title = ! empty( $instance['link_title'] ) ? '<a href="' . bp_get_groups_directory_permalink() . '">' . $title . '</a>' : $title;

		echo $before_title . $title . $after_title;

		$max_groups = ! empty( $instance['max_groups'] ) ? (int) $instance['max_groups'] : 5;

		$group_args = array(
			'user_id'         => $user_id,
			'type'            => $instance['group_default'],
			'per_page'        => $max_groups,
			'max'             => $max_groups,
		);

		// Back up the global.
		$old_groups_template = $groups_template;

		?>

		<?php if ( bp_has_groups( $group_args ) ) : ?>
			<div class="item-options" id="groups-list-options">
				<a href="<?php bp_groups_directory_permalink(); ?>" id="newest-groups"<?php if ( $instance['group_default'] == 'newest' ) : ?> class="selected"<?php endif; ?>><?php _e("Newest", 'bp-progenitor') ?></a>
				<span class="bp-separator" role="separator"><?php echo esc_html( $separator ); ?></span>
				<a href="<?php bp_groups_directory_permalink(); ?>" id="recently-active-groups"<?php if ( $instance['group_default'] == 'active' ) : ?> class="selected"<?php endif; ?>><?php _e("Active", 'bp-progenitor') ?></a>
				<span class="bp-separator" role="separator"><?php echo esc_html( $separator ); ?></span>
				<a href="<?php bp_groups_directory_permalink(); ?>" id="popular-groups" <?php if ( $instance['group_default'] == 'popular' ) : ?> class="selected"<?php endif; ?>><?php _e("Popular", 'bp-progenitor') ?></a>
				<span class="bp-separator" role="separator"><?php echo esc_html( $separator ); ?></span>
				<a href="<?php bp_groups_directory_permalink(); ?>" id="alphabetical-groups" <?php if ( $instance['group_default'] == 'alphabetical' ) : ?> class="selected"<?php endif; ?>><?php _e("Alphabetical", 'bp-progenitor') ?></a>
			</div>

			<ul id="groups-list" class="item-list" aria-live="polite" aria-relevant="all" aria-atomic="true">
				<?php while ( bp_groups() ) : bp_the_group(); ?>
					<li <?php bp_group_class(); ?>>
						<div class="list-wrap">
							<div class="item-avatar">
								<a href="<?php bp_group_permalink() ?>" class="bp-tooltip" data-bp-tooltip="<?php bp_group_name() ?>"><?php bp_group_avatar_thumb() ?></a>
							</div>

							<div class="item">
								<div class="item-title"><?php bp_group_link(); ?></div>
								<div class="item-meta">
									<span class="activity">
									<?php
										if ( 'newest' == $instance['group_default'] ) {
											printf( __( 'created %s', 'bp-progenitor' ), bp_get_group_date_created() );
										} elseif ( 'popular' == $instance['group_default'] ) {
											bp_group_member_count();
										} else {
											printf( __( 'active %s', 'bp-progenitor' ), bp_get_group_last_active() );
										}
									?>
									</span>
								</div>
							</div>
						</div>
					</li>

				<?php endwhile; ?>
			</ul>
			<?php wp_nonce_field( 'groups_widget_groups_list', '_wpnonce-groups' ); ?>
			<input type="hidden" name="groups_widget_max" id="groups_widget_max" value="<?php echo esc_attr( $max_groups ); ?>" />

		<?php else: ?>

			<div class="widget-error">
				<?php _e('There are no groups to display.', 'bp-progenitor') ?>
			</div>

		<?php endif; ?>

		<?php echo $after_widget;

		// Restore the global.
		$groups_template = $old_groups_template;
	}

	/**
	 * Extends our update method.
	 *
	 * @since 1.0.3
	 *
	 * @param array $new_instance New instance data.
	 * @param array $old_instance Original instance data.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']         = strip_tags( $new_instance['title'] );
		$instance['max_groups']    = strip_tags( $new_instance['max_groups'] );
		$instance['group_default'] = strip_tags( $new_instance['group_default'] );
		$instance['link_title']    = (bool) $new_instance['link_title'];

		return $instance;
	}

	/**
	 * Extends our form method.
	 *
	 * @since 1.0.3
	 *
	 * @param array $instance Current instance.
	 * @return mixed
	 */
	public function form( $instance ) {
		$defaults = array(
			'title'         => __( 'Groups', 'bp-progenitor' ),
			'max_groups'    => 5,
			'group_default' => 'active',
			'link_title'    => false
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$title 	       = strip_tags( $instance['title'] );
		$max_groups    = strip_tags( $instance['max_groups'] );
		$group_default = strip_tags( $instance['group_default'] );
		$link_title    = (bool) $instance['link_title'];
		?>

		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'bp-progenitor'); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" style="width: 100%" /></label></p>

		<p><label for="<?php echo $this->get_field_id('link_title') ?>"><input type="checkbox" name="<?php echo $this->get_field_name('link_title') ?>" id="<?php echo $this->get_field_id('link_title') ?>" value="1" <?php checked( $link_title ) ?> /> <?php _e( 'Link widget title to Groups directory', 'bp-progenitor' ) ?></label></p>

		<p><label for="<?php echo $this->get_field_id( 'max_groups' ); ?>"><?php _e('Max groups to show:', 'bp-progenitor'); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'max_groups' ); ?>" name="<?php echo $this->get_field_name( 'max_groups' ); ?>" type="text" value="<?php echo esc_attr( $max_groups ); ?>" style="width: 30%" /></label></p>

		<p>
			<label for="<?php echo $this->get_field_id( 'group_default' ); ?>"><?php _e('Default groups to show:', 'bp-progenitor'); ?></label>
			<select name="<?php echo $this->get_field_name( 'group_default' ); ?>" id="<?php echo $this->get_field_id( 'group_default' ); ?>">
				<option value="newest" <?php selected( $group_default, 'newest' ); ?>><?php _e( 'Newest', 'bp-progenitor' ) ?></option>
				<option value="active" <?php selected( $group_default, 'active' ); ?>><?php _e( 'Active', 'bp-progenitor' ) ?></option>
				<option value="popular"  <?php selected( $group_default, 'popular' ); ?>><?php _e( 'Popular', 'bp-progenitor' ) ?></option>
				<option value="alphabetical" <?php selected( $group_default, 'alphabetical' ); ?>><?php _e( 'Alphabetical', 'bp-progenitor' ) ?></option>
			</select>
		</p>
	<?php
	}
}


/**
 * AJAX callback for the Groups List widget.
 *
 * @since 1.0.0
 */
function progenitor_groups_ajax_widget_groups_list() {

	check_ajax_referer( 'groups_widget_groups_list' );

	switch ( $_POST['filter'] ) {
		case 'newest-groups':
			$type = 'newest';
		break;
		case 'recently-active-groups':
			$type = 'active';
		break;
		case 'popular-groups':
			$type = 'popular';
		break;
		case 'alphabetical-groups':
			$type = 'alphabetical';
		break;
	}

	$per_page = isset( $_POST['max_groups'] ) ? intval( $_POST['max_groups'] ) : 5;

	$groups_args = array(
		'user_id'  => 0,
		'type'     => $type,
		'per_page' => $per_page,
		'max'      => $per_page,
	);

	if ( bp_has_groups( $groups_args ) ) : ?>
		<?php echo "0[[SPLIT]]"; ?>
		<?php while ( bp_groups() ) : bp_the_group(); ?>
			<li <?php bp_group_class(); ?>>
				<div class="list-wrap">
					<div class="item-avatar">
						<a href="<?php bp_group_permalink() ?>"><?php bp_group_avatar_thumb() ?></a>
					</div>

					<div class="item">
						<div class="item-title"><?php bp_group_link(); ?></div>
						<div class="item-meta">
							<?php if ( 'newest-groups' === $_POST['filter'] ) : ?>
								<span class="activity" data-livestamp="<?php bp_core_iso8601_date( bp_get_group_date_created( 0, array( 'relative' => false ) ) ); ?>"><?php printf( __( 'created %s', 'bp-progenitor' ), bp_get_group_date_created() ); ?></span>
							<?php elseif ( 'popular-groups' === $_POST['filter'] ) : ?>
								<span class="activity"><?php bp_group_member_count(); ?></span>
							<?php else : ?>
								<span class="activity" data-livestamp="<?php bp_core_iso8601_date( bp_get_group_last_active( 0, array( 'relative' => false ) ) ); ?>"><?php printf( __( 'active %s', 'bp-progenitor' ), bp_get_group_last_active() ); ?></span>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</li>
		<?php endwhile; ?>

		<?php wp_nonce_field( 'progenitor_groups_widget_groups_list', '_wpnonce-groups' ); ?>
		<input type="hidden" name="groups_widget_max" id="groups_widget_max" value="<?php echo esc_attr( $_POST['max_groups'] ); ?>" />

	<?php else: ?>

		<?php echo "-1[[SPLIT]]<li>" . __( "No groups matched the current filter.", 'bp-progenitor' ); ?>

	<?php endif;

}
add_action( 'wp_ajax_widget_groups_list',        'progenitor_groups_ajax_widget_groups_list' );
add_action( 'wp_ajax_nopriv_widget_groups_list', 'progenitor_groups_ajax_widget_groups_list' );
