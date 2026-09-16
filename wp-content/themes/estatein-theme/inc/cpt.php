<?php
/**
 * Content model: post types and taxonomies.
 *
 * Nothing in this build has a public archive, and the only public single view is
 * `property` — which exists purely so the Figma cards have a real "Read More"
 * target, and which redirects home until a Property Details template is built.
 *
 * `faq` and `testimonial` are admin-only: they appear on the homepage as cards
 * with no link, so a public URL would be a 404 waiting to happen.
 *
 * Bedrooms, bathrooms and property type are taxonomies rather than ACF fields so
 * editors get native checkboxes and sortable admin columns. They are labels on a
 * card, not browsable archives, hence `public => false`.
 *
 * @package Estatein
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Standard label set for a post type.
 *
 * @param string $plural   Plural name.
 * @param string $singular Singular name.
 * @return array
 */
function estatein_post_type_labels($plural, $singular) {
	return [
		'name'          => $plural,
		'singular_name' => $singular,
		/* translators: %s: post type singular name. */
		'add_new_item'  => sprintf(__('Add %s', 'estatein'), $singular),
		/* translators: %s: post type singular name. */
		'edit_item'     => sprintf(__('Edit %s', 'estatein'), $singular),
	];
}

/**
 * Standard label set for a taxonomy.
 *
 * @param string $plural   Plural name.
 * @param string $singular Singular name.
 * @return array
 */
function estatein_taxonomy_labels($plural, $singular) {
	return [
		'name'          => $plural,
		'singular_name' => $singular,
		/* translators: %s: taxonomy plural name. */
		'search_items'  => sprintf(__('Search %s', 'estatein'), $plural),
		/* translators: %s: taxonomy plural name. */
		'all_items'     => sprintf(__('All %s', 'estatein'), $plural),
		/* translators: %s: taxonomy singular name. */
		'edit_item'     => sprintf(__('Edit %s', 'estatein'), $singular),
		/* translators: %s: taxonomy singular name. */
		'add_new_item'  => sprintf(__('Add New %s', 'estatein'), $singular),
	];
}

/**
 * Register post types and their taxonomies.
 */
function estatein_register_content() {
	register_post_type('property', [
		'labels'              => estatein_post_type_labels(__('Properties', 'estatein'), __('Property', 'estatein')),
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
		'faq' => [
			'plural'   => __('FAQs', 'estatein'),
			'singular' => __('FAQ', 'estatein'),
			'icon'     => 'dashicons-editor-help',
		],
		'testimonial' => [
			'plural'   => __('Testimonials', 'estatein'),
			'singular' => __('Testimonial', 'estatein'),
			'icon'     => 'dashicons-format-quote',
		],
	];

	foreach ($admin_only as $slug => $args) {
		register_post_type($slug, [
			'labels'              => estatein_post_type_labels($args['plural'], $args['singular']),
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
			'menu_icon'           => $args['icon'],
			'supports'            => ['title', 'thumbnail', 'excerpt'],
		]);
	}

	estatein_register_property_taxonomies();
}
add_action('init', 'estatein_register_content');

/**
 * Property taxonomies: the homepage carousel filter plus the three card labels.
 */
function estatein_register_property_taxonomies() {
	$taxonomies = [
		'featured_property' => [
			'plural'   => __('Featured Property', 'estatein'),
			'singular' => __('Featured', 'estatein'),
		],
		'bedroom' => [
			'plural'   => __('Bedrooms', 'estatein'),
			'singular' => __('Bedroom', 'estatein'),
		],
		'bathroom' => [
			'plural'   => __('Bathrooms', 'estatein'),
			'singular' => __('Bathroom', 'estatein'),
		],
		'property_type' => [
			'plural'   => __('Property Types', 'estatein'),
			'singular' => __('Property Type', 'estatein'),
		],
	];

	foreach ($taxonomies as $slug => $labels) {
		register_taxonomy($slug, 'property', [
			'labels'            => estatein_taxonomy_labels($labels['plural'], $labels['singular']),
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'hierarchical'      => true,
			'rewrite'           => false,
			'query_var'         => false,
			// Hierarchical UI gives editors checkboxes instead of a free-text tag box.
			'meta_box_cb'       => 'post_categories_meta_box',
		]);
	}
}

/**
 * Send property single views home until a Property Details template exists.
 */
function estatein_redirect_property_singles() {
	if (is_singular('property')) {
		wp_safe_redirect(home_url('/'), 302);
		exit;
	}
}
add_action('template_redirect', 'estatein_redirect_property_singles');

/**
 * Flush rewrite rules once, on the request after the theme is activated.
 *
 * Flushing on every load is expensive, and the property permalink structure only
 * changes when this file does.
 */
function estatein_maybe_flush_rewrites() {
	if (get_option('estatein_flush_rewrites')) {
		flush_rewrite_rules(false);
		delete_option('estatein_flush_rewrites');
	}
}
add_action('init', 'estatein_maybe_flush_rewrites', 20);

/**
 * Queue a rewrite flush for the next request.
 */
function estatein_request_rewrite_flush() {
	update_option('estatein_flush_rewrites', 1);
}
add_action('after_switch_theme', 'estatein_request_rewrite_flush');
