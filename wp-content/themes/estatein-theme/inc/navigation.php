<?php
/**
 * Navigation links.
 *
 * This build is homepage-only. The inner pages are unpublished, so every
 * navigation destination except the front page is parked on "#". Menu labels and
 * ordering stay under editor control; only the href is rewritten.
 *
 * `property` is the one CPT with real permalinks, because the Figma cards need a
 * "Read More" target. Its single view redirects home (see inc/cpt.php). `faq`
 * and `testimonial` are not publicly queryable, so `estatein_single_url()`
 * parks their links instead of printing a 404.
 *
 * When the inner pages are built, parking can be lifted per destination rather
 * than rewriting the header and footer.
 *
 * @package Estatein
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Href used for every destination that does not exist yet.
 */
define('ESTATEIN_NAV_PLACEHOLDER', '#');

/**
 * Does this URL point at this site's front page?
 *
 * Deliberately strict, because the answer decides whether a link survives or
 * gets parked:
 *
 * - Another host is never our front page, so `https://example.com/` stays put
 *   instead of being rewritten to the homepage.
 * - A URL carrying a fragment is treated as parked. Editors type
 *   `http://estatein.local/#` to mean "no destination yet", and that must not
 *   read as a link to the front page.
 *
 * @param string $url Absolute or root-relative URL.
 * @return bool
 */
function estatein_url_is_front_page($url) {
	$url = trim((string) $url);
	if ('' === $url || false !== strpos($url, '#')) {
		return false;
	}

	$parts = wp_parse_url($url);
	$home  = wp_parse_url(home_url('/'));
	if (!is_array($parts) || !is_array($home)) {
		return false;
	}

	if (!empty($parts['host']) && strtolower($parts['host']) !== strtolower((string) ($home['host'] ?? ''))) {
		return false;
	}

	$path      = trim((string) ($parts['path'] ?? ''), '/');
	$home_path = trim((string) ($home['path'] ?? ''), '/');

	// Drop the install's subdirectory, if WordPress lives in one.
	if ('' !== $home_path && 0 === strpos($path, $home_path)) {
		$path = trim(substr($path, strlen($home_path)), '/');
	}

	if ('' === $path) {
		return true;
	}

	$front = get_post((int) get_option('page_on_front'));

	return $front instanceof WP_Post && $path === $front->post_name;
}

/**
 * Resolve an editor-supplied URL under the parking rule.
 *
 * @param string $url Value from an ACF URL field, or '' for a bare placeholder.
 * @return string Home URL for the front page, otherwise "#".
 */
function estatein_nav_url($url = '') {
	return estatein_url_is_front_page($url) ? home_url('/') : ESTATEIN_NAV_PLACEHOLDER;
}

/**
 * Permalink for a CPT entry, or "#" when that post type has no public single view.
 *
 * @param int|null $post_id Defaults to the current post.
 * @return string
 */
function estatein_single_url($post_id = null) {
	$post_id = $post_id ?: get_the_ID();
	if (!$post_id) {
		return ESTATEIN_NAV_PLACEHOLDER;
	}

	$type = get_post_type_object(get_post_type($post_id));
	if ($type && !empty($type->publicly_queryable)) {
		return get_permalink($post_id) ?: ESTATEIN_NAV_PLACEHOLDER;
	}

	return ESTATEIN_NAV_PLACEHOLDER;
}

/**
 * Park every menu destination except the front page.
 *
 * Runs on the front end only, so the admin menu editor keeps showing the real
 * URLs an editor typed.
 *
 * @param array $items Menu item objects.
 * @return array
 */
function estatein_park_menu_links($items) {
	if (is_admin() && !wp_doing_ajax()) {
		return $items;
	}

	$front_id = (int) get_option('page_on_front');

	foreach ($items as $item) {
		$is_front = ($front_id && 'post_type' === $item->type && (int) $item->object_id === $front_id)
			|| estatein_url_is_front_page($item->url);

		$item->url = $is_front ? home_url('/') : ESTATEIN_NAV_PLACEHOLDER;
	}

	return $items;
}
add_filter('wp_nav_menu_objects', 'estatein_park_menu_links');

/**
 * Footer menu walker: top-level items are column headings, children are links.
 *
 * That mirrors the Figma footer, where Home / About Us / Properties / Services /
 * Contact Us are headings rather than links.
 */
class Estatein_Footer_Nav_Walker extends Walker_Nav_Menu {

	public function start_lvl(&$output, $depth = 0, $args = null) {
		if (0 === (int) $depth) {
			$output .= '<ul class="list-unstyled footer-links">';
		}
	}

	public function end_lvl(&$output, $depth = 0, $args = null) {
		if (0 === (int) $depth) {
			$output .= '</ul>';
		}
	}

	public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0) {
		$item  = $data_object;
		$title = apply_filters('the_title', $item->title, $item->ID);

		if (0 !== (int) $depth) {
			$output .= '<li><a href="' . esc_url($item->url) . '">' . esc_html($title) . '</a></li>';
			return;
		}

		$output .= '<div class="footer-nav-col footer-nav-' . esc_attr(estatein_footer_column_slug($item->title)) . '">';

		$heading = esc_html($title);
		if (estatein_url_is_front_page($item->url)) {
			$heading = '<a href="' . esc_url(home_url('/')) . '">' . $heading . '</a>';
		}

		$output .= '<h3 class="footer-heading">' . $heading . '</h3>';
	}

	public function end_el(&$output, $data_object, $depth = 0, $args = null) {
		if (0 === (int) $depth) {
			$output .= '</div>';
		}
	}
}

/**
 * CSS slug for a footer column heading.
 *
 * The stylesheet targets short slugs, so the two multi-word Figma headings are
 * mapped onto them.
 *
 * @param string $title Column heading.
 * @return string
 */
function estatein_footer_column_slug($title) {
	$slug = sanitize_title($title);

	$aliases = [
		'about-us'   => 'about',
		'contact-us' => 'contact',
	];

	return sanitize_html_class($aliases[ $slug ] ?? $slug);
}

/**
 * Footer columns from the design, used only when no footer menu is assigned.
 *
 * This is a layout safety net, not the editable footer: the real footer comes
 * from the "footer" menu location. Every link here is parked, because these
 * destinations do not exist in a homepage-only build.
 *
 * @return array<int, array{slug: string, title: string, links: string[]}>
 */
function estatein_footer_nav_columns() {
	return [
		[
			'slug'  => 'home',
			'title' => 'Home',
			'links' => ['Hero Section', 'Features', 'Properties', 'Testimonials', "FAQ's"],
		],
		[
			'slug'  => 'about',
			'title' => 'About Us',
			'links' => ['Our Story', 'Our Works', 'How It Works', 'Our Team', 'Our Clients'],
		],
		[
			'slug'  => 'properties',
			'title' => 'Properties',
			'links' => ['Portfolio', 'Categories'],
		],
		[
			'slug'  => 'services',
			'title' => 'Services',
			'links' => ['Valuation Mastery', 'Strategic Marketing', 'Negotiation Wizardry', 'Closing Success', 'Property Management'],
		],
		[
			'slug'  => 'contact',
			'title' => 'Contact Us',
			'links' => ['Contact Form', 'Our Offices'],
		],
	];
}

/**
 * Render the design's footer columns when the footer menu location is empty.
 */
function estatein_footer_menu_fallback() {
	foreach (estatein_footer_nav_columns() as $column) {
		echo '<div class="footer-nav-col footer-nav-' . esc_attr($column['slug']) . '">';

		$heading = esc_html($column['title']);
		if ('home' === $column['slug']) {
			$heading = '<a href="' . esc_url(home_url('/')) . '">' . $heading . '</a>';
		}

		echo '<h3 class="footer-heading">' . $heading . '</h3>';
		echo '<ul class="list-unstyled footer-links">';

		foreach ($column['links'] as $label) {
			echo '<li><a href="' . esc_url(ESTATEIN_NAV_PLACEHOLDER) . '">' . esc_html($label) . '</a></li>';
		}

		echo '</ul></div>';
	}
}

/**
 * Primary menu columns from the design, used only when no primary menu is assigned.
 *
 * Same idea as the footer fallback: the header still matches Figma on a fresh
 * install, with every inner destination parked.
 */
function estatein_primary_menu_fallback() {
	$items = [
		['label' => __('Home', 'estatein'), 'url' => home_url('/')],
		['label' => __('About Us', 'estatein'), 'url' => ESTATEIN_NAV_PLACEHOLDER],
		['label' => __('Properties', 'estatein'), 'url' => ESTATEIN_NAV_PLACEHOLDER],
		['label' => __('Services', 'estatein'), 'url' => ESTATEIN_NAV_PLACEHOLDER],
	];

	echo '<ul class="navbar-nav align-items-lg-center">';
	foreach ($items as $item) {
		echo '<li class="nav-item"><a class="nav-link" href="' . esc_url($item['url']) . '">' . esc_html($item['label']) . '</a></li>';
	}
	echo '</ul>';
}
