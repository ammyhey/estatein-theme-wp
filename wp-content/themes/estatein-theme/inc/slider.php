<?php
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Query helpers for homepage carousels.
 */
function estatein_home_properties_query() {
	$ids = [];
	for ($i = 1; $i <= 3; $i++) {
		$id = (int) estatein_field('featured_property_' . $i, false);
		if ($id) {
			$ids[] = $id;
		}
	}

	$count = max(6, (int) estatein_field('featured_count', false, 9));

	if ($ids) {
		$featured = new WP_Query([
			'post_type'      => 'property',
			'post_status'    => 'publish',
			'posts_per_page' => count($ids),
			'post__in'       => $ids,
			'orderby'        => 'post__in',
		]);
		$exclude = wp_list_pluck($featured->posts, 'ID');
		$rest = new WP_Query([
			'post_type'      => 'property',
			'post_status'    => 'publish',
			'posts_per_page' => max(0, $count - count($exclude)),
			'post__not_in'   => $exclude,
			'orderby'        => 'date',
			'order'          => 'DESC',
		]);
		$posts = array_merge($featured->posts, $rest->posts);
		return $posts;
	}

	$q = new WP_Query([
		'post_type'      => 'property',
		'post_status'    => 'publish',
		'posts_per_page' => $count,
		'orderby'        => 'date',
		'order'          => 'DESC',
	]);
	return $q->posts;
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
	$svg = str_replace('style="display: block;"', 'aria-hidden="true"', $svg);
	return $svg;
}

function estatein_slider_controls($view_all_html = '') {
	?>
	<div class="estatein-slider-controls">
		<?php if ($view_all_html) : ?>
			<div class="estatein-slider-view-all"><?php echo $view_all_html; ?></div>
		<?php endif; ?>
		<div class="estatein-slider-counter" aria-live="polite">
			<span class="current">01</span>
			<span class="sep">of</span>
			<span class="total">01</span>
		</div>
		<button type="button" class="estatein-slider-btn estatein-slider-prev" aria-label="<?php esc_attr_e('Previous', 'estatein'); ?>">
			<?php echo estatein_slider_arrow_icon('prev'); ?>
		</button>
		<button type="button" class="estatein-slider-btn estatein-slider-next" aria-label="<?php esc_attr_e('Next', 'estatein'); ?>">
			<?php echo estatein_slider_arrow_icon('next'); ?>
		</button>
	</div>
	<?php
}
