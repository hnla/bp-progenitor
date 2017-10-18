<?php
/**
 * BP - Progenitor Activity Widget template - plain style
 *
 * @since 0.1.0
 */
?>

<?php if ( bp_has_activities( bp_progenitor_activity_widget_query() ) ) : ?>

		<div class="activity-list item-list">

			<?php while ( bp_activities() ) : bp_the_activity(); ?>

				<?php if ( bp_activity_has_content() ) : ?>

					<blockquote>

						<?php bp_activity_content_body(); ?>

						<footer>

							<cite>
								<a href="<?php bp_activity_user_link(); ?>">
									<?php bp_activity_avatar( array(
										'type'   => 'thumb',
										'width'  => '',
										'height' => '',
									) ); ?>
								</a>
							</cite>

							<?php echo bp_insert_activity_meta(); ?>

						</footer>

					</blockquote>

				<?php else: ?>

					<p><?php bp_activity_action(); ?></p>

				<?php endif; ?>

			<?php endwhile; ?>

		</div>

<?php else : ?>

	<div class="widget-error">
		<?php bp_progenitor_user_feedback( 'activity-loop-none' ); ?>
	</div>

<?php endif; ?>
