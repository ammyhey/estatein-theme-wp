<?php
/**
 * Content model.
 *
 * Property, FAQ, and Testimonial entries are admin-only: editors manage them
 * in wp-admin and the homepage pulls them in via WP_Query. They have no public
 * single or archive pages.
 */
if (!defined('ABSPATH')) exit;

function estatein_register_content(){
  $types=[
    'property'=>['Properties','Property','dashicons-building'],
    'faq'=>['FAQs','FAQ','dashicons-editor-help'],
    'testimonial'=>['Testimonials','Testimonial','dashicons-format-quote'],
  ];
  foreach($types as $slug=>$v){
    [$plural,$singular,$icon]=$v;
    register_post_type($slug,[
      'labels'=>['name'=>$plural,'singular_name'=>$singular,'add_new_item'=>'Add '.$singular,'edit_item'=>'Edit '.$singular],
      'public'=>false,
      'publicly_queryable'=>false,
      'exclude_from_search'=>true,
      'show_ui'=>true,
      'show_in_menu'=>true,
      'show_in_nav_menus'=>false,
      'show_in_rest'=>true,
      'has_archive'=>false,
      'rewrite'=>false,
      'query_var'=>false,
      'menu_icon'=>$icon,
      'supports'=>['title','thumbnail','excerpt'],
    ]);
  }
}
add_action('init','estatein_register_content');
