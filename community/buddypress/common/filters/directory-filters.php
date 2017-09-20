<?php
/**
 * BP Progenitor Component's  filters template.
 *
 * @since 1.0.0
 */
?>

<div id="dir-filters" class="component-filters clearfix">
	<div id="<?php bp_progenitor_filter_container_id(); ?>" class="last filter">
		<label class="bp-screen-reader-text" for="<?php bp_progenitor_filter_id(); ?>">
			<span ><?php bp_progenitor_filter_label(); ?></span>
		</label>
		<div class="select-wrap">
			<select id="<?php bp_progenitor_filter_id(); ?>" data-bp-filter="<?php bp_progenitor_filter_component(); ?>">

				<?php bp_progenitor_filter_options(); ?>

			</select>
			<span class="select-arrow" aria-hidden="true"></span>
		</div>
	</div>
</div>
