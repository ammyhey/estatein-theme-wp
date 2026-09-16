<?php
/**
 * Homepage carousel queries and shared controls.
 *
 * @package Estatein
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Featured properties for the homepage carousel.
 *
 * "Featured" is the `featured_property` term, so an editor can keep unfeatured
 * listings in the catalog without them appearing on the homepage.
 *
 * @return WP_Query
 */
function estatein_home_properties_query() {
	return new WP_Query([
		'post_type'      => 'property',
		'post_status'    => 'publish',
		'posts_per_page' => estatein_home_item_count('featured_count'),
		'orderby'        => 'date',
		'order'          => 'DESC',
		'tax_query'      => [
			[
				'taxonomy' => 'featured_property',
				'field'    => 'slug',
				'terms'    => 'featured',
			],
		],
	]);
}

/**
 * Latest entries of a homepage CPT carousel.
 *
 * @param string $post_type   Post type slug.
 * @param string $count_field ACF field holding how many to show.
 * @return WP_Post[]
 */
function estatein_home_cpt_query($post_type, $count_field) {
	$query = new WP_Query([
		'post_type'      => $post_type,
		'post_status'    => 'publish',
		'posts_per_page' => estatein_home_item_count($count_field),
		'orderby'        => 'date',
		'order'          => 'DESC',
	]);

	return $query->posts;
}

/**
 * How many items a homepage carousel should show.
 *
 * @param string $count_field ACF field name; its design default is the fallback.
 * @return int
 */
function estatein_home_item_count($count_field) {
	$count = (int) estatein_field($count_field);

	return $count > 0 ? $count : (int) estatein_default($count_field, 6);
}

/**
 * Carousel arrow, recoloured so CSS can drive it.
 *
 * The exported SVGs have hard-coded white and grey fills; swapping them for
 * currentColor lets hover and focus states live in the stylesheet.
 *
 * @param string $direction 'prev' or 'next'.
 * @return string Inline SVG, or '' when the icon is missing.
 */
function estatein_slider_arrow_icon($direction = 'next') {
	$file = 'prev' === $direction ? 'slider-arrow-prev.svg' : 'slider-arrow-next.svg';
	$svg  = estatein_inline_svg('assets/icons/' . $file);

	if (!$svg) {
		return '';
	}

	$svg = preg_replace('/fill="(white|#808080|#fff|#FFFFFF)"/i', 'fill="currentColor"', $svg);

	return str_replace('style="display: block;"', '', $svg);
}

/**
 * Previous / next buttons, live slide counter and the mobile "View All" button.
 *
 * @param string $view_all_html Pre-escaped markup from estatein_button(), or ''.
 * @param string $label         Plural item name for the button labels, e.g. "properties".
 * @param string $track_id      ID of the slide track, for aria-controls.
 */
function estatein_slider_controls($view_all_html = '', $label = '', $track_id = '') {
	$label = $label ?: __('slides', 'estatein');

	/* translators: %s: plural item name, e.g. "properties". */
	$prev_label = sprintf(__('Previous %s', 'estatein'), $label);
	/* translators: %s: plural item name, e.g. "properties". */
	$next_label = sprintf(__('Next %s', 'estatein'), $label);

	$controls = $track_id ? ' aria-controls="' . esc_attr($track_id) . '"' : '';
	?>
	<div class="estatein-slider-controls">
		<?php if ($view_all_html) : ?>
			<?php // Escaped by estatein_button(). ?>
			<div class="estatein-slider-view-all"><?php echo $view_all_html; ?></div>
		<?php endif; ?>
		<div class="estatein-slider-counter" aria-live="polite" aria-atomic="true">
			<span class="visually-hidden"><?php esc_html_e('Slide', 'estatein'); ?> </span>
			<span class="current">01</span>
			<span class="sep"><?php esc_html_e('of', 'estatein'); ?></span>
			<span class="total">01</span>
		</div>
		<button type="button" class="estatein-slider-btn estatein-slider-prev" aria-label="<?php echo esc_attr($prev_label); ?>"<?php echo $controls; ?>>
			<?php echo estatein_slider_arrow_icon('prev'); ?>
		</button>
		<button type="button" class="estatein-slider-btn estatein-slider-next" aria-label="<?php echo esc_attr($next_label); ?>"<?php echo $controls; ?>>
			<?php echo estatein_slider_arrow_icon('next'); ?>
		</button>
	</div>
	<?php
}
