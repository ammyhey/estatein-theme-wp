<?php
if (!defined('ABSPATH')) exit;
function estatein_register_content(){
  $types=[
    'property'=>['Properties','Property','properties',true],
    'faq'=>['FAQs','FAQ','faqs',true],
    'testimonial'=>['Testimonials','Testimonial','testimonials',true],
    'team_member'=>['Our Team','Team Member','our-team',true],
    'client'=>['Clients','Client','clients',true],
  ];
  foreach($types as $slug=>$v){ [$plural,$singular,$rewrite,$archive]=$v; register_post_type($slug,['labels'=>['name'=>$plural,'singular_name'=>$singular,'add_new_item'=>'Add '.$singular,'edit_item'=>'Edit '.$singular],'public'=>true,'show_in_rest'=>true,'has_archive'=>$archive,'rewrite'=>['slug'=>$rewrite,'with_front'=>false],'menu_icon'=>'dashicons-building','supports'=>['title','editor','thumbnail','excerpt','page-attributes']]); }
  register_taxonomy('location',['property'],['labels'=>['name'=>'Locations','singular_name'=>'Location'],'public'=>true,'show_in_rest'=>true,'hierarchical'=>true,'rewrite'=>['slug'=>'property-location']]);
  register_taxonomy('property_type',['property'],['labels'=>['name'=>'Property Types','singular_name'=>'Property Type'],'public'=>true,'show_in_rest'=>true,'hierarchical'=>true,'rewrite'=>['slug'=>'property-type']]);
  register_taxonomy('property_status',['property'],['labels'=>['name'=>'Property Status','singular_name'=>'Property Status'],'public'=>true,'show_in_rest'=>true,'hierarchical'=>false,'rewrite'=>['slug'=>'property-status']]);
  register_taxonomy('pricing_range',['property'],['labels'=>['name'=>'Pricing Ranges','singular_name'=>'Pricing Range'],'public'=>true,'show_in_rest'=>true,'hierarchical'=>true,'rewrite'=>['slug'=>'pricing-range']]);
  register_taxonomy('property_size_range',['property'],['labels'=>['name'=>'Property Size Ranges','singular_name'=>'Property Size Range'],'public'=>true,'show_in_rest'=>true,'hierarchical'=>true,'rewrite'=>['slug'=>'property-size']]);
  register_taxonomy('build_year',['property'],['labels'=>['name'=>'Build Years','singular_name'=>'Build Year'],'public'=>true,'show_in_rest'=>true,'hierarchical'=>false,'rewrite'=>['slug'=>'build-year']]);
  register_taxonomy('service_type',['property'],['labels'=>['name'=>'Service Types','singular_name'=>'Service Type'],'public'=>false,'show_ui'=>true,'show_in_rest'=>true,'hierarchical'=>true]);
}
add_action('init','estatein_register_content');
function estatein_flush_rewrite(){ if(get_option('estatein_rewrite_version')!=='1'){flush_rewrite_rules();update_option('estatein_rewrite_version','1');} }
add_action('init','estatein_flush_rewrite',99);
