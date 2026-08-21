<?php
/**
 * Content model.
 *
 * Property has public permalinks for cards, but the single view redirects home.
 * FAQ and Testimonial stay admin-only.
 */
if (!defined('ABSPATH')) exit;

function estatein_register_content() {
	register_post_type('property', [
		'labels' => [
			'name'          => 'Properties',
			'singular_name' => 'Property',
			'add_new_item'  => 'Add Property',
			'edit_item'     => 'Edit Property',
		],
		'public'              => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_rest'        => true,
		'has_archive'         => false,
		'rewrite'             => ['slug' => 'property'],
		'query_var'           => true,
		'menu_icon'           => 'dashicons-building',
		'supports'            => ['title', 'editor', 'thumbnail', 'excerpt'],
	]);

	$admin_only = [
		'faq'         => ['FAQs', 'FAQ', 'dashicons-editor-help'],
		'testimonial' => ['Testimonials', 'Testimonial', 'dashicons-format-quote'],
	];
	foreach ($admin_only as $slug => $v) {
		[$plural, $singular, $icon] = $v;
		register_post_type($slug, [
			'labels'              => ['name' => $plural, 'singular_name' => $singular, 'add_new_item' => 'Add ' . $singular, 'edit_item' => 'Edit ' . $singular],
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_rest'        => true,
			'has_archive'         => false,
			'rewrite'             => false,
			'query_var'           => false,
			'menu_icon'           => $icon,
			'supports'            => ['title', 'thumbnail', 'excerpt'],
		]);
	}

	estatein_register_property_taxonomies();
}
add_action('init', 'estatein_register_content');

function estatein_register_property_taxonomies() {
	$taxes = [
		'featured_property' => [
			'label'        => 'Featured Property',
			'singular'     => 'Featured',
			'hierarchical' => true,
		],
		'bedroom' => [
			'label'        => 'Bedrooms',
			'singular'     => 'Bedroom',
			'hierarchical' => true,
		],
		'bathroom' => [
			'label'        => 'Bathrooms',
			'singular'     => 'Bathroom',
			'hierarchical' => true,
		],
		'property_type' => [
			'label'        => 'Property Types',
			'singular'     => 'Property Type',
			'hierarchical' => true,
		],
	];

	foreach ($taxes as $slug => $args) {
		register_taxonomy($slug, 'property', [
			'labels'            => [
				'name'          => $args['label'],
				'singular_name' => $args['singular'],
				'search_items'  => 'Search ' . $args['label'],
				'all_items'     => 'All ' . $args['label'],
				'edit_item'     => 'Edit ' . $args['singular'],
				'add_new_item'  => 'Add New ' . $args['singular'],
			],
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'hierarchical'      => $args['hierarchical'],
			'rewrite'           => false,
			'meta_box_cb'       => 'post_categories_meta_box',
		]);
	}
}

function estatein_redirect_property_singles() {
	if (is_singular('property')) {
		wp_safe_redirect(home_url('/'), 302);
		exit;
	}
}
add_action('template_redirect', 'estatein_redirect_property_singles');

function estatein_maybe_flush_rewrites() {
	if (get_option('estatein_flush_rewrites')) {
		flush_rewrite_rules(false);
		delete_option('estatein_flush_rewrites');
	}
}
add_action('init', 'estatein_maybe_flush_rewrites', 20);

function estatein_request_rewrite_flush() {
	update_option('estatein_flush_rewrites', 1);
}
add_action('after_switch_theme', 'estatein_request_rewrite_flush');
