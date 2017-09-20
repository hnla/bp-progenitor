<?php
/**
 * BuddyPress - Progenitor Blogs Create
 *
 * @since 1.0.0
 */

bp_progenitor_blogs_create_hook( 'before', 'content_template' ); ?>

<?php bp_progenitor_template_notices(); ?>

<?php bp_progenitor_blogs_create_hook( 'before', 'content' ); ?>

<?php if ( bp_blog_signup_enabled() ) : ?>

	<?php bp_show_blog_signup_form(); ?>

<?php else:

	bp_progenitor_user_feedback( 'blogs-no-signup' );

endif; ?>

<?php
bp_progenitor_blogs_create_hook( 'after', 'content' );

bp_progenitor_blogs_create_hook( 'after', 'content_template' );
