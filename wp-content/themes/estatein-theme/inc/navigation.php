<?php
/**
 * Navigation links.
 *
 * Inner pages are unpublished and the CPTs have no public single or archive
 * views, so every navigation destination other than the front page is parked
 * on "#". Menu labels and ordering stay under editor control as usual.
 */
if (!defined('ABSPATH')) exit;

define('ESTATEIN_NAV_PLACEHOLDER', '#');

function estatein_url_is_front_page($url) {
  $url = trim((string) $url);
  if ($url === '' || $url === ESTATEIN_NAV_PLACEHOLDER) return false;

  $path = trim((string) wp_parse_url($url, PHP_URL_PATH), '/');
  if ($path === '') return true;

  $front_id = (int) get_option('page_on_front');
  if (!$front_id) return false;

  $front = get_post($front_id);
  return $front && $path === $front->post_name;
}

function estatein_nav_url($url = '') {
  return estatein_url_is_front_page($url) ? home_url('/') : ESTATEIN_NAV_PLACEHOLDER;
}

/**
 * Permalink for a CPT entry, or "#" when that post type has no public single view.
 */
function estatein_single_url($post_id = null) {
  $post_id = $post_id ?: get_the_ID();
  if (!$post_id) return ESTATEIN_NAV_PLACEHOLDER;

  $type = get_post_type_object(get_post_type($post_id));
  if ($type && !empty($type->publicly_queryable)) {
    return get_permalink($post_id) ?: ESTATEIN_NAV_PLACEHOLDER;
  }
  return ESTATEIN_NAV_PLACEHOLDER;
}

function estatein_park_menu_links($items) {
  if (is_admin() && !wp_doing_ajax()) return $items;

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
 * Footer: top-level items are column headings; children are the links.
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

		if (0 === (int) $depth) {
			$slug = sanitize_title($item->title);
			$slug_map = [
				'about-us'   => 'about',
				'contact-us' => 'contact',
			];
			if (isset($slug_map[ $slug ])) {
				$slug = $slug_map[ $slug ];
			}
			$slug = sanitize_html_class($slug);
			$output .= '<div class="footer-nav-col footer-nav-' . esc_attr($slug) . '">';
			$heading = esc_html($title);
			if (estatein_url_is_front_page($item->url)) {
				$heading = '<a href="' . esc_url(home_url('/')) . '">' . $heading . '</a>';
			}
			$output .= '<h3 class="footer-heading">' . $heading . '</h3>';
			return;
		}

		$output .= '<li><a href="' . esc_url($item->url) . '">' . esc_html($title) . '</a></li>';
	}

	public function end_el(&$output, $data_object, $depth = 0, $args = null) {
		if (0 === (int) $depth) {
			$output .= '</div>';
		}
	}
}

function estatein_footer_menu_fallback() {
	foreach (estatein_footer_nav_columns() as $col) {
		echo '<div class="footer-nav-col footer-nav-' . esc_attr($col['slug']) . '">';
		$heading = esc_html($col['title']);
		if ('home' === $col['slug']) {
			$heading = '<a href="' . esc_url(home_url('/')) . '">' . $heading . '</a>';
		}
		echo '<h3 class="footer-heading">' . $heading . '</h3>';
		echo '<ul class="list-unstyled footer-links">';
		foreach ($col['links'] as $label) {
			echo '<li><a href="' . esc_url(ESTATEIN_NAV_PLACEHOLDER) . '">' . esc_html($label) . '</a></li>';
		}
		echo '</ul></div>';
	}
}
