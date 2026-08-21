<?php
if (!defined('ABSPATH')) exit;
function estatein_assets() {
  wp_enqueue_style('bootstrap','https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',[], '5.3.3');
  wp_enqueue_style('estatein-font','https://fonts.googleapis.com/css2?family=Urbanist:wght@300;500;600;700&display=swap',[],null);
    wp_enqueue_style('estatein-style-hamburger',get_template_directory_uri().'/assets/css/hamburgers.css');
    wp_enqueue_style('estatein-style',get_template_directory_uri().'/assets/css/theme.css',['bootstrap'],'1.0.0');
    wp_enqueue_style('estatein-style-front',get_template_directory_uri().'/assets/css/front-page.css',['bootstrap'],'1.0.0');
    wp_enqueue_script('jquery');
  wp_enqueue_script('bootstrap','https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',[], '5.3.3',true);
  wp_enqueue_script('estatein-main',get_template_directory_uri().'/assets/js/theme.js',[], '1.0.0',true);
}
add_action('wp_enqueue_scripts','estatein_assets');
