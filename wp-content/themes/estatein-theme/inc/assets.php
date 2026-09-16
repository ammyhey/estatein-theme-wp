<?php
/**
 * Stylesheet and script registration.
 *
 * Asset versions come from the theme version plus each file's modification time,
 * so an edited stylesheet busts caches on its own. Nothing here needs bumping by
 * hand after a CSS or JS change.
 *
 * Bootstrap, Slick and Urbanist load from a CDN. That is a deliberate trade-off
 * for this project — it keeps the repo free of vendor bundles — and it is the one
 * thing that would have to change before shipping to WordPress.org or to a site
 * with an offline requirement.
 *
 * @package Estatein
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Cache-busting version for a file inside the theme.
 *
 * @param string $relative_path Path inside the theme.
 * @return string
 */
function estatein_asset_version($relative_path) {
	$file  = get_theme_file_path($relative_path);
	$mtime = file_exists($file) ? filemtime($file) : 0;

	return $mtime ? ESTATEIN_VERSION . '.' . $mtime : ESTATEIN_VERSION;
}

/**
 * Enqueue theme CSS and JS.
 *
 * Slick and the homepage stylesheet load on the front page only, because that is
 * the only template with carousels.
 */
function estatein_assets() {
	wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', [], '5.3.3');
	wp_enqueue_style('estatein-font', 'https://fonts.googleapis.com/css2?family=Urbanist:wght@300;500;600;700&display=swap', [], null);

	wp_enqueue_style(
		'estatein-style-hamburger',
		get_theme_file_uri('assets/css/hamburgers.css'),
		[],
		estatein_asset_version('assets/css/hamburgers.css')
	);
	wp_enqueue_style(
		'estatein-style',
		get_theme_file_uri('assets/css/theme.css'),
		['bootstrap'],
		estatein_asset_version('assets/css/theme.css')
	);

	if (is_front_page()) {
		wp_enqueue_style('slick', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', [], '1.8.1');
		wp_enqueue_style(
			'estatein-style-front',
			get_theme_file_uri('assets/css/front-page.css'),
			['estatein-style', 'slick'],
			estatein_asset_version('assets/css/front-page.css')
		);

		wp_enqueue_script('jquery');
		wp_enqueue_script('slick', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', ['jquery'], '1.8.1', true);
	}

	wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', [], '5.3.3', true);
	wp_enqueue_script(
		'estatein-main',
		get_theme_file_uri('assets/js/theme.js'),
		is_front_page() ? ['jquery', 'slick'] : [],
		estatein_asset_version('assets/js/theme.js'),
		true
	);
}
add_action('wp_enqueue_scripts', 'estatein_assets', 40);
