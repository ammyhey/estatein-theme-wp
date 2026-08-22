<?php
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Query helpers for homepage carousels.
 */
function estatein_home_properties_query() {
	$count = (int) estatein_field('featured_count', false, 9);
	return new WP_Query([
		'post_type'      => 'property',
		'post_status'    => 'publish',
		'posts_per_page' => $count > 0 ? $count : 9,
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

function estatein_home_cpt_query($post_type, $count_field, $default = 6) {
	$count = (int) estatein_field($count_field, false, $default);
	$q = new WP_Query([
		'post_type'      => $post_type,
		'post_status'    => 'publish',
		'posts_per_page' => $count ?: $default,
		'orderby'        => 'date',
		'order'          => 'DESC',
	]);
	return $q->posts;
}

function estatein_slider_arrow_icon($direction = 'next') {
	$file = $direction === 'prev' ? 'slider-arrow-prev.svg' : 'slider-arrow-next.svg';
	$path = get_template_directory() . '/assets/icons/' . $file;
	if (!is_readable($path)) {
		return '';
	}
	$svg = file_get_contents($path);
	$svg = preg_replace('/fill="(white|#808080|#fff|#FFFFFF)"/i', 'fill="currentColor"', $svg);
	if (stripos($svg, 'aria-hidden') === false) {
		$svg = preg_replace('/<svg\b/', '<svg aria-hidden="true"', $svg, 1);
	}
	if (stripos($svg, 'focusable') === false) {
		$svg = preg_replace('/<svg\b/', '<svg focusable="false"', $svg, 1);
	}
	$svg = str_replace('style="display: block;"', '', $svg);
	return $svg;
}

function estatein_slider_controls($view_all_html = '', $label = '', $track_id = '') {
	$label = $label ?: __('slides', 'estatein');
	$prev_label = sprintf(__('Previous %s', 'estatein'), $label);
	$next_label = sprintf(__('Next %s', 'estatein'), $label);
	?>
	<div class="estatein-slider-controls">
		<?php if ($view_all_html) : ?>
			<div class="estatein-slider-view-all"><?php echo $view_all_html; ?></div>
		<?php endif; ?>
		<div class="estatein-slider-counter" aria-live="polite" aria-atomic="true">
			<span class="visually-hidden"><?php esc_html_e('Slide', 'estatein'); ?> </span>
			<span class="current">01</span>
			<span class="sep"><?php esc_html_e('of', 'estatein'); ?></span>
			<span class="total">01</span>
		</div>
		<button type="button" class="estatein-slider-btn estatein-slider-prev" aria-label="<?php echo esc_attr($prev_label); ?>"<?php echo $track_id ? ' aria-controls="' . esc_attr($track_id) . '"' : ''; ?>>
			<?php echo estatein_slider_arrow_icon('prev'); ?>
		</button>
		<button type="button" class="estatein-slider-btn estatein-slider-next" aria-label="<?php echo esc_attr($next_label); ?>"<?php echo $track_id ? ' aria-controls="' . esc_attr($track_id) . '"' : ''; ?>>
			<?php echo estatein_slider_arrow_icon('next'); ?>
		</button>
	</div>
	<?php
}
