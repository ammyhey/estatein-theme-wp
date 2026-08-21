<?php
if (!defined('ABSPATH')) exit;
function estatein_setup() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
  add_theme_support('custom-logo', ['height'=>48,'width'=>160,'flex-height'=>true,'flex-width'=>true]);
  register_nav_menus([
    'primary' => 'Primary Navigation',
    'footer'  => 'Footer Navigation',
  ]);
  add_image_size('estatein-property-card', 864, 636, true);
}
add_action('after_setup_theme','estatein_setup');

/**
 * ACF Free does not provide the Pro Options Page feature.
 * We therefore use a normal WordPress Page as the theme settings screen.
 */
function estatein_settings_page_id() {
  $page = get_page_by_path('estatein-settings', OBJECT, 'page');
  return $page ? (int) $page->ID : 0;
}

function estatein_create_settings_page() {
  $existing = estatein_settings_page_id();
  if ($existing) {
    if (get_page_template_slug($existing) !== 'page-estatein-settings.php') {
      update_post_meta($existing, '_wp_page_template', 'page-estatein-settings.php');
    }
    return $existing;
  }

  $id = wp_insert_post([
    'post_title'   => 'Estatein Settings',
    'post_name'    => 'estatein-settings',
    'post_status'  => 'publish',
    'post_type'    => 'page',
    'post_content' => 'Use the fields below to manage Estatein global header, footer, announcement, newsletter, and social links.',
  ]);

  if (!is_wp_error($id) && $id) {
    update_post_meta($id, '_wp_page_template', 'page-estatein-settings.php');
    return (int) $id;
  }
  return 0;
}
add_action('after_switch_theme','estatein_create_settings_page');
add_action('admin_init', function () {
  if (current_user_can('manage_options')) estatein_create_settings_page();
});

function estatein_redirect_settings_page() {
  if (is_page_template('page-estatein-settings.php')) {
    wp_safe_redirect(home_url('/'));
    exit;
  }
}
add_action('template_redirect', 'estatein_redirect_settings_page');
