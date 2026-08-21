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
