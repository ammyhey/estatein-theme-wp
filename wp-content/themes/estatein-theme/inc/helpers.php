<?php
if (!defined('ABSPATH')) exit;

function estatein_resolve_field_post_id($post_id = false) {
  if ($post_id === 'option') {
    if (function_exists('estatein_settings_page_id')) {
      $settings_id = estatein_settings_page_id();
      return $settings_id ?: false;
    }
    return false;
  }
  return $post_id ?: false;
}

function estatein_field($key,$post_id=false,$default='') {
  if (!function_exists('get_field')) return $default;
  $post_id = estatein_resolve_field_post_id($post_id);
  $v=get_field($key,$post_id);
  return ($v===null||$v==='')?$default:$v;
}

function estatein_image($field,$size='large',$post_id=false,$class='') {
  $post_id = estatein_resolve_field_post_id($post_id);
  $img=estatein_field($field,$post_id);
  if (!$img) return '';
  if (is_array($img)) { $url=$img['sizes'][$size]??$img['url']??''; $alt=$img['alt']??''; }
  else { $url=wp_get_attachment_image_url((int)$img,$size); $alt=get_post_meta((int)$img,'_wp_attachment_image_alt',true); }
  return $url ? '<img class="'.esc_attr($class).'" src="'.esc_url($url).'" alt="'.esc_attr($alt).'" loading="lazy">' : '';
}
function estatein_button($label,$url='#',$class='btn btn-primary') { if (!$label) return ''; return '<a class="'.esc_attr($class).'" href="'.esc_url($url).'">'.esc_html($label).'</a>'; }
function estatein_archive_url($post_type){ $p=get_post_type_object($post_type); return $p && $p->has_archive ? get_post_type_archive_link($post_type) : home_url('/'); }
function estatein_currency($value){ if ($value===''||$value===null) return ''; return '$'.number_format((float)$value,0,'.',','); }

function estatein_icon($name, $class = '') {
  $file = get_template_directory() . '/assets/icons/' . sanitize_file_name($name) . '.svg';
  if (!file_exists($file)) return '';
  $svg = file_get_contents($file);
  if ($class && $svg) {
    $svg = preg_replace('/<svg /', '<svg class="' . esc_attr($class) . '" ', $svg, 1);
  }
  return $svg ?: '';
}

function estatein_term_name($post_id, $taxonomy, $default = '') {
  $terms = get_the_terms($post_id, $taxonomy);
  if (is_wp_error($terms) || empty($terms)) return $default;
  return $terms[0]->name;
}

function estatein_kicker($label = '') {
  $html = '<span class="section-kicker d-inline-flex align-items-center gap-2">' . estatein_icon('sparkle', 'kicker-icon');
  if ($label) $html .= '<span class="visually-hidden">' . esc_html($label) . '</span>';
  return $html . '</span>';
}
