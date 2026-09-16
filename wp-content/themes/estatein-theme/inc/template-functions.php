<?php
/**
 * Template helpers that render plugin output.
 *
 * @package Estatein
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Render a Ninja Form stored in an ACF field.
 *
 * The field may hold a full shortcode (`[ninja_form id=3]`) or a bare form ID, so
 * an editor cannot break the footer by typing just the number. Nothing is output
 * when the field is empty; the caller decides what the fallback looks like.
 *
 * @param string           $field   ACF field holding the shortcode or form ID.
 * @param int|string|false $post_id 'option', a post ID, or false for the current post.
 */
function estatein_render_ninja_form($field, $post_id = 'option') {
	$value = trim((string) estatein_field($field, $post_id, ''));
	if ('' === $value) {
		return;
	}

	$shortcode = ctype_digit($value) ? '[ninja_form id=' . (int) $value . ']' : $value;

	echo '<div class="estatein-form">' . do_shortcode($shortcode) . '</div>';
}
