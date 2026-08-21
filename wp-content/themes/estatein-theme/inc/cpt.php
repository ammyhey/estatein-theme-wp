<?php
/**
 * Content model.
 *
 * The CPTs and their taxonomies are admin-only: editors manage the entries in
 * wp-admin and the homepage pulls them in via WP_Query, but they have no public
 * single, archive, or term pages of their own.
 */
if (!defined('ABSPATH')) exit;

function estatein_register_content(){
  $types=[
    'property'=>['Properties','Property'],
    'faq'=>['FAQs','FAQ'],
    'testimonial'=>['Testimonials','Testimonial'],
    'team_member'=>['Our Team','Team Member'],
    'client'=>['Clients','Client'],
  ];
  foreach($types as $slug=>$v){
    [$plural,$singular]=$v;
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
      'menu_icon'=>'dashicons-building',
      'supports'=>['title','editor','thumbnail','excerpt','page-attributes'],
    ]);
  }

  $taxonomies=[
    'location'=>['Locations','Location',true],
    'property_type'=>['Property Types','Property Type',true],
    'property_status'=>['Property Status','Property Status',false],
    'pricing_range'=>['Pricing Ranges','Pricing Range',true],
    'property_size_range'=>['Property Size Ranges','Property Size Range',true],
    'build_year'=>['Build Years','Build Year',false],
    'service_type'=>['Service Types','Service Type',true],
  ];
  foreach($taxonomies as $slug=>$v){
    [$plural,$singular,$hierarchical]=$v;
    register_taxonomy($slug,['property'],[
      'labels'=>['name'=>$plural,'singular_name'=>$singular],
      'public'=>false,
      'publicly_queryable'=>false,
      'show_ui'=>true,
      'show_in_menu'=>true,
      'show_in_nav_menus'=>false,
      'show_in_rest'=>true,
      'hierarchical'=>$hierarchical,
      'rewrite'=>false,
      'query_var'=>false,
    ]);
  }
}
add_action('init','estatein_register_content');

function estatein_flush_rewrite(){ if(get_option('estatein_rewrite_version')!=='2'){flush_rewrite_rules();update_option('estatein_rewrite_version','2');} }
add_action('init','estatein_flush_rewrite',99);
