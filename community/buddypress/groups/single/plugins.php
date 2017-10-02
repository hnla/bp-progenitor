<?php
/**
 * BuddyPress - Groups plugins
 *
 * @since 1.0.0
 */

bp_progenitor_group_hook( 'before', 'plugin_template' );

bp_progenitor_plugin_hook( 'content' );

bp_progenitor_group_hook( 'after', 'plugin_template' );
