<?php
if (!defined('ABSPATH')) exit;
function estatein_assets() {
  wp_enqueue_style('bootstrap','https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',[], '5.3.3');
  wp_enqueue_style('estatein-font','https://fonts.googleapis.com/css2?family=Urbanist:wght@300;500;600;700&display=swap',[],null);
  wp_enqueue_style('estatein-style-hamburger',get_template_directory_uri().'/assets/css/hamburgers.css');
  wp_enqueue_style('estatein-style',get_template_directory_uri().'/assets/css/theme.css',['bootstrap'],'1.7.9');

  if (is_front_page()) {
    wp_enqueue_style('slick','https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css',[],'1.8.1');
    wp_enqueue_style('estatein-style-front',get_template_directory_uri().'/assets/css/front-page.css',['estatein-style','slick'],'1.6.3');
    wp_enqueue_script('jquery');
    wp_enqueue_script('slick','https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js',['jquery'],'1.8.1',true);
  }

  wp_enqueue_script('bootstrap','https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',[], '5.3.3',true);
  wp_enqueue_script('estatein-main',get_template_directory_uri().'/assets/js/theme.js', is_front_page() ? ['jquery','slick'] : [], '1.7.6', true);
}
add_action('wp_enqueue_scripts','estatein_assets', 40);
