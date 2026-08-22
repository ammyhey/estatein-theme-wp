<?php
if (!defined('ABSPATH')) exit;

function estatein_render_ninja_form($field = 'form_shortcode', $post_id = 'option') {
  estatein_render_form($field, '', $post_id);
}

/**
 * Render a Ninja Form from ACF shortcode and/or form ID fields.
 * Shortcode wins when present; otherwise Form ID is used.
 */
function estatein_render_form($shortcode_field, $id_field = '', $post_id = false) {
  $shortcode = trim((string) estatein_field($shortcode_field, $post_id, ''));
  $form_id = $id_field !== '' ? trim((string) estatein_field($id_field, $post_id, '')) : '';

  if ($shortcode !== '') {
    if (ctype_digit($shortcode)) {
      $shortcode = '[ninja_form id=' . (int) $shortcode . ']';
    }
    echo '<div class="estatein-form">' . do_shortcode($shortcode) . '</div>';
    return;
  }

  if ($form_id !== '' && ctype_digit($form_id)) {
    echo '<div class="estatein-form">' . do_shortcode('[ninja_form id=' . (int) $form_id . ']') . '</div>';
  }
}
