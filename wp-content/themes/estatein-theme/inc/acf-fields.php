<?php
if (!defined('ABSPATH')) exit;
/** ACF Free-compatible field groups used by the homepage and its CPT cards. */
function estatein_register_acf_fields(){
  if(!function_exists('acf_add_local_field_group')) return;
  $text=function($key,$label,$type='text',$extra=[]){return array_merge(['key'=>'field_'.$key,'label'=>$label,'name'=>$key,'type'=>$type],$extra);};
  $image=function($key,$label){return ['key'=>'field_'.$key,'label'=>$label,'name'=>$key,'type'=>'image','return_format'=>'array','preview_size'=>'medium','library'=>'all'];};
  $to_location=function($shorthand){
    $param=array_key_first($shorthand);
    return [[[
      'param'=>$param,
      'operator'=>'==',
      'value'=>$shorthand[$param],
    ]]];
  };
  $group=function($key,$title,$location,$fields) use ($to_location){
    acf_add_local_field_group([
      'key'=>'group_'.$key,
      'title'=>$title,
      'fields'=>$fields,
      'location'=>$to_location($location),
      'position'=>'normal',
      'style'=>'seamless',
      'active'=>true,
    ]);
  };

  $group('global','Estatein — Global Settings',['page_template'=>'page-estatein-settings.php'],[
    $image('site_logo','Logo'),$text('announcement_text','Announcement Text'),$text('announcement_link_text','Announcement Link Text'),$text('announcement_link_url','Announcement Link URL','url'),$text('header_contact_text','Header Contact Button'),$text('header_contact_url','Header Contact URL','url'),
    $text('footer_email_placeholder','Footer Email Placeholder'),$text('footer_newsletter_form_shortcode','Footer Newsletter Ninja Form Shortcode','textarea'),
    $text('footer_copyright','Footer Copyright'),$text('footer_privacy_text','Privacy Link Text'),$text('footer_privacy_url','Privacy URL','url'),
    $text('social_link_1','Social Link 1','url'),$text('social_link_2','Social Link 2','url'),$text('social_link_3','Social Link 3','url'),$text('social_link_4','Social Link 4','url'),
    $text('footer_col1_title','Footer Column 1 Title'),$text('footer_col1_link1','Footer Column 1 Link 1 Label'),$text('footer_col1_link1_url','Footer Column 1 Link 1 URL','url'),$text('footer_col1_link2','Footer Column 1 Link 2 Label'),$text('footer_col1_link2_url','Footer Column 1 Link 2 URL','url'),$text('footer_col1_link3','Footer Column 1 Link 3 Label'),$text('footer_col1_link3_url','Footer Column 1 Link 3 URL','url'),$text('footer_col1_link4','Footer Column 1 Link 4 Label'),$text('footer_col1_link4_url','Footer Column 1 Link 4 URL','url'),$text('footer_col1_link5','Footer Column 1 Link 5 Label'),$text('footer_col1_link5_url','Footer Column 1 Link 5 URL','url'),
    $text('footer_col2_title','Footer Column 2 Title'),$text('footer_col2_link1','Footer Column 2 Link 1 Label'),$text('footer_col2_link1_url','Footer Column 2 Link 2 URL','url'),$text('footer_col2_link2','Footer Column 2 Link 2 Label'),$text('footer_col2_link2_url','Footer Column 2 Link 2 URL','url'),$text('footer_col2_link3','Footer Column 2 Link 3 Label'),$text('footer_col2_link3_url','Footer Column 2 Link 3 URL','url'),$text('footer_col2_link4','Footer Column 2 Link 4 Label'),$text('footer_col2_link4_url','Footer Column 2 Link 4 URL','url'),$text('footer_col2_link5','Footer Column 2 Link 5 Label'),$text('footer_col2_link5_url','Footer Column 2 Link 5 URL','url'),
    $text('footer_col3_title','Footer Column 3 Title'),$text('footer_col3_link1','Footer Column 3 Link 1 Label'),$text('footer_col3_link1_url','Footer Column 3 Link 1 URL','url'),$text('footer_col3_link2','Footer Column 3 Link 2 Label'),$text('footer_col3_link2_url','Footer Column 3 Link 2 URL','url'),
    $text('footer_col4_title','Footer Column 4 Title'),$text('footer_col4_link1','Footer Column 4 Link 1 Label'),$text('footer_col4_link1_url','Footer Column 4 Link 1 URL','url'),$text('footer_col4_link2','Footer Column 4 Link 2 Label'),$text('footer_col4_link2_url','Footer Column 4 Link 2 URL','url'),$text('footer_col4_link3','Footer Column 4 Link 3 Label'),$text('footer_col4_link3_url','Footer Column 4 Link 4 URL','url'),$text('footer_col4_link4','Footer Column 4 Link 4 Label'),$text('footer_col4_link4_url','Footer Column 4 Link 4 URL','url'),$text('footer_col4_link5','Footer Column 4 Link 5 Label'),$text('footer_col4_link5_url','Footer Column 4 Link 5 URL','url'),
    $text('footer_col5_title','Footer Column 5 Title'),$text('footer_col5_link1','Footer Column 5 Link 1 Label'),$text('footer_col5_link1_url','Footer Column 5 Link 1 URL','url'),$text('footer_col5_link2','Footer Column 5 Link 2 Label'),$text('footer_col5_link2_url','Footer Column 5 Link 2 URL','url')
  ]);

  $group('home_hero','Homepage — 01 Hero Banner',['page_type'=>'front_page'],[
    $text('hero_heading','Heading','textarea'),$text('hero_description','Description','textarea'),$text('hero_primary_label','Primary Button Label'),$text('hero_primary_url','Primary Button URL','url'),$text('hero_secondary_label','Secondary Button Label'),$text('hero_secondary_url','Secondary Button URL','url'),
    $text('hero_stat_1_number','Stat 1 Number'),$text('hero_stat_1_label','Stat 1 Label'),$text('hero_stat_2_number','Stat 2 Number'),$text('hero_stat_2_label','Stat 2 Label'),$text('hero_stat_3_number','Stat 3 Number'),$text('hero_stat_3_label','Stat 3 Label'),
    $image('hero_image','Hero Image'),
    $image('hero_orbit_image','Hero Circle Badge Image'),
    $text('hero_orbit_url','Hero Circle Badge URL','url'),
    $text('hero_feature_1','Feature Card 1 Title'),$text('hero_feature_1_url','Feature Card 1 URL','url'),$image('hero_feature_1_icon','Feature Card 1 Icon'),
    $text('hero_feature_2','Feature Card 2 Title'),$text('hero_feature_2_url','Feature Card 2 URL','url'),$image('hero_feature_2_icon','Feature Card 2 Icon'),
    $text('hero_feature_3','Feature Card 3 Title'),$text('hero_feature_3_url','Feature Card 3 URL','url'),$image('hero_feature_3_icon','Feature Card 3 Icon'),
    $text('hero_feature_4','Feature Card 4 Title'),$text('hero_feature_4_url','Feature Card 4 URL','url'),$image('hero_feature_4_icon','Feature Card 4 Icon'),
    $image('hero_feature_arrow','Feature Card Arrow Icon'),
  ]);
  $group('home_featured','Homepage — 02 Featured Properties',['page_type'=>'front_page'],[$text('featured_heading','Heading'),$text('featured_description','Description','textarea'),$text('featured_button_label','Button Label'),$text('featured_button_url','Button URL','url'),$text('featured_property_1','Property 1 ID','number'),$text('featured_property_2','Property 2 ID','number'),$text('featured_property_3','Property 3 ID','number'),$text('featured_count','Carousel item count','number')]);
  $group('home_testimonials','Homepage — 03 Testimonials',['page_type'=>'front_page'],[$text('testimonials_heading','Heading'),$text('testimonials_description','Description','textarea'),$text('testimonials_button_label','Button Label'),$text('testimonials_button_url','Button URL','url'),$text('testimonials_count','Number of Testimonials','number')]);
  $group('home_faq','Homepage — 04 FAQ',['page_type'=>'front_page'],[$text('faq_heading','Heading'),$text('faq_description','Description','textarea'),$text('faq_button_label','Button Label'),$text('faq_button_url','Button URL','url'),$text('faq_count','Number of FAQs','number')]);
  $group('home_cta','Homepage — 05 Footer Banner / CTA',['page_type'=>'front_page'],[$text('footer_cta_heading','Heading'),$text('footer_cta_description','Description','textarea'),$text('footer_cta_button_label','Button Label'),$text('footer_cta_button_url','Button URL','url')]);

  $group('property','Property — Card',['post_type'=>'property'],[
    $text('price','Price','number'),$text('short_excerpt','Property Excerpt','textarea'),$text('card_building_label','Card building label (Villa, Cottage, etc.)'),
    $text('bedrooms','Bedrooms','number'),$text('bathrooms','Bathrooms','number'),
  ]);
  $group('faq','FAQ — Detail',['post_type'=>'faq'],[$text('faq_short_answer','Short Answer','textarea')]);
  $group('testimonial','Testimonial — Detail',['post_type'=>'testimonial'],[$text('client_name','Client Name'),$text('client_location','Client Location'),$text('rating','Rating','number'),$image('client_photo','Client Photo'),$text('testimonial_quote','Quote','textarea')]);
}
add_action('acf/init','estatein_register_acf_fields');
