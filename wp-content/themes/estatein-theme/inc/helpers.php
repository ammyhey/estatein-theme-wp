<?php
/**
 * Field, image and formatting helpers.
 *
 * Helpers whose name reads like a noun (`estatein_image`, `estatein_button`,
 * `estatein_icon`) return **already escaped HTML**. Templates echo the return
 * value directly and must not wrap it in `esc_*` again. Helpers that return a
 * plain value (`estatein_field`, `estatein_image_url`, `estatein_currency`)
 * return raw data and the template is responsible for escaping it.
 *
 * @package Estatein
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Translate the 'option' pseudo post ID into the Estatein Settings page ID.
 *
 * ACF Free has no Options Page, so global fields live on a normal page. Passing
 * 'option' to any helper below resolves to that page's ID.
 *
 * @param int|string|false $post_id 'option', a post ID, or false for the current post.
 * @return int|false
 */
function estatein_resolve_field_post_id($post_id = false) {
	if ('option' !== $post_id) {
		return $post_id ?: false;
	}

	if (!function_exists('estatein_settings_page_id')) {
		return false;
	}

	return estatein_settings_page_id() ?: false;
}

/**
 * Read an ACF field.
 *
 * With no explicit `$default`, the Figma fallback from `inc/content-defaults.php`
 * is used, so section copy never renders blank on an unfinished site. Pass `''`
 * to opt out and get an empty value.
 *
 * @param string           $key     ACF field name.
 * @param int|string|false $post_id 'option', a post ID, or false for the current post.
 * @param mixed            $default Explicit fallback; null uses the design default.
 * @return mixed
 */
function estatein_field($key, $post_id = false, $default = null) {
	if (null === $default) {
		$default = estatein_default($key);
	}

	if (!function_exists('get_field')) {
		return $default;
	}

	$value = get_field($key, estatein_resolve_field_post_id($post_id));

	return (null === $value || '' === $value) ? $default : $value;
}

/**
 * Attachment ID behind an ACF image field, whichever return format it uses.
 *
 * @param string           $field   ACF field name.
 * @param int|string|false $post_id 'option', a post ID, or false for the current post.
 * @return int 0 when the field is empty.
 */
function estatein_image_id($field, $post_id = false) {
	$image = estatein_field($field, $post_id, '');

	if (is_array($image)) {
		$id = $image['ID'] ?? $image['id'] ?? 0;
		return (int) $id;
	}

	return is_numeric($image) ? (int) $image : 0;
}

/**
 * Render an ACF image using core markup, so it keeps srcset, sizes and dimensions.
 *
 * @param string           $field        ACF field name.
 * @param string           $size         Registered image size.
 * @param int|string|false $post_id      'option', a post ID, or false for the current post.
 * @param string           $class        CSS classes for the img tag.
 * @param string           $fallback_alt Alt text to use when the attachment has none.
 * @param bool             $eager        True for above-the-fold images; skips lazy loading.
 * @return string Escaped HTML, or '' when the field is empty.
 */
function estatein_image($field, $size = 'large', $post_id = false, $class = '', $fallback_alt = '', $eager = false) {
	$id = estatein_image_id($field, $post_id);
	if (!$id) {
		return '';
	}

	$attr = ['class' => $class];

	$alt = trim((string) get_post_meta($id, '_wp_attachment_image_alt', true));
	if ('' === $alt && '' !== $fallback_alt) {
		$attr['alt'] = $fallback_alt;
	}

	// The hero is the LCP element; lazy loading it delays the largest paint.
	if ($eager) {
		$attr['loading']       = 'eager';
		$attr['fetchpriority'] = 'high';
	}

	return wp_get_attachment_image($id, $size, false, $attr);
}

/**
 * URL of an ACF image, for cases that need a bare src (CSS, decorative markup).
 *
 * @param string           $field   ACF field name.
 * @param string           $size    Registered image size.
 * @param int|string|false $post_id 'option', a post ID, or false for the current post.
 * @return string Raw URL, or '' when the field is empty.
 */
function estatein_image_url($field, $size = 'large', $post_id = false) {
	$id = estatein_image_id($field, $post_id);

	return $id ? (string) wp_get_attachment_image_url($id, $size) : '';
}

/**
 * Anchor styled as a button.
 *
 * @param string $label Visible label; an empty label renders nothing.
 * @param string $url   Destination.
 * @param string $class CSS classes.
 * @return string Escaped HTML.
 */
function estatein_button($label, $url = '#', $class = 'btn btn-primary') {
	if (!$label) {
		return '';
	}

	return '<a class="' . esc_attr($class) . '" href="' . esc_url($url) . '">' . esc_html($label) . '</a>';
}

/**
 * Format a price the way the Figma cards show it.
 *
 * @param string|int|float $value Raw field value.
 * @return string Raw text, e.g. "$550,000". Escape before output.
 */
function estatein_currency($value) {
	if ('' === $value || null === $value) {
		return '';
	}

	return '$' . number_format((float) $value, 0, '.', ',');
}

/**
 * Read an SVG from the theme and make it safe to inline.
 *
 * Decorative icons must not be announced, so aria-hidden and focusable are
 * added when the exported file lacks them.
 *
 * @param string $relative_path Path inside the theme.
 * @return string Inline SVG, or '' when the file is missing.
 */
function estatein_inline_svg($relative_path) {
	$file = get_theme_file_path($relative_path);
	if (!is_readable($file)) {
		return '';
	}

	$svg = file_get_contents($file);
	if (!$svg) {
		return '';
	}

	foreach (['aria-hidden="true"', 'focusable="false"'] as $attribute) {
		$name = strstr($attribute, '=', true);
		if (false === stripos($svg, $name)) {
			$svg = preg_replace('/<svg\b/', '<svg ' . $attribute, $svg, 1);
		}
	}

	return $svg;
}

/**
 * Inline a decorative icon from assets/icons.
 *
 * @param string $name  Icon file name without extension.
 * @param string $class CSS classes for the svg tag.
 * @return string Inline SVG, or '' when the icon is missing.
 */
function estatein_icon($name, $class = '') {
	$svg = estatein_inline_svg('assets/icons/' . sanitize_file_name($name) . '.svg');

	if ($svg && $class) {
		$svg = preg_replace('/<svg /', '<svg class="' . esc_attr($class) . '" ', $svg, 1);
	}

	return $svg;
}

/**
 * Decorative icon rendered as an img tag, for icons exported as PNG.
 *
 * @param string $name   Icon file name without extension; .svg is preferred over .png.
 * @param string $class  CSS classes.
 * @param int    $width  Intrinsic width.
 * @param int    $height Intrinsic height.
 * @return string Escaped HTML, or '' when the icon is missing.
 */
function estatein_asset_icon($name, $class = '', $width = 24, $height = 24) {
	$base = 'assets/icons/' . sanitize_file_name($name);
	$path = '';

	foreach (['svg', 'png'] as $extension) {
		if (file_exists(get_theme_file_path($base . '.' . $extension))) {
			$path = $base . '.' . $extension;
			break;
		}
	}

	if (!$path) {
		return '';
	}

	return '<img' . ($class ? ' class="' . esc_attr($class) . '"' : '')
		. ' src="' . esc_url(get_theme_file_uri($path)) . '"'
		. ' alt="" width="' . (int) $width . '" height="' . (int) $height . '"'
		. ' loading="lazy" decoding="async" aria-hidden="true">';
}

/**
 * Theme asset URL carrying a cache-busting version.
 *
 * Used for assets referenced straight from markup, which get no version from
 * `wp_enqueue_*`.
 *
 * @param string $relative_path Path inside the theme.
 * @return string Raw URL. Escape before output.
 */
function estatein_asset_uri($relative_path) {
	return add_query_arg('ver', estatein_asset_version($relative_path), get_theme_file_uri($relative_path));
}

/**
 * Name of the first term a post has in a taxonomy.
 *
 * @param string $taxonomy Taxonomy slug.
 * @param int    $post_id  Defaults to the current post.
 * @return string Raw term name, or '' when the post has no term. Escape before output.
 */
function estatein_first_term_name($taxonomy, $post_id = 0) {
	$post_id = $post_id ?: get_the_ID();
	if (!$post_id) {
		return '';
	}

	$terms = get_the_terms($post_id, $taxonomy);
	if (empty($terms) || is_wp_error($terms)) {
		return '';
	}

	return $terms[0]->name;
}

/**
 * Star flourish that sits above every section heading in the design.
 *
 * The star is decorative, so the section name is exposed to screen readers only.
 *
 * @param string $label Section name for assistive technology.
 * @return string Escaped HTML.
 */
function estatein_kicker($label = '') {
	$html = '<span class="section-kicker d-inline-flex align-items-center gap-2">'
		. '<img class="kicker-icon" src="' . esc_url(get_theme_file_uri('assets/icons/section-star.png')) . '"'
		. ' alt="" width="69" height="30" loading="lazy" decoding="async" aria-hidden="true">';

	if ($label) {
		$html .= '<span class="visually-hidden">' . esc_html($label) . '</span>';
	}

	return $html . '</span>';
}
