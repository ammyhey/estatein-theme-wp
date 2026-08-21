<?php
if (!defined('ABSPATH')) exit;
/** ACF Free-compatible field groups. No Repeater, Gallery, Flexible Content, or Pro-only fields are used. */
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

  // Global settings.
  $group('global','Estatein — Global Settings',['page_template'=>'page-estatein-settings.php'],[
    $text('site_tagline','Site Tagline'),$image('site_logo','Logo'),$text('announcement_text','Announcement Text'),$text('announcement_link_text','Announcement Link Text'),$text('announcement_link_url','Announcement Link URL','url'),$text('header_contact_text','Header Contact Button'),$text('header_contact_url','Header Contact URL','url'),
    $text('footer_email_placeholder','Footer Email Placeholder'),$text('footer_newsletter_form_shortcode','Footer Newsletter Ninja Form Shortcode','textarea'),
    $text('footer_copyright','Footer Copyright'),$text('footer_privacy_text','Privacy Link Text'),$text('footer_privacy_url','Privacy URL','url'),$text('footer_terms_text','Terms Link Text'),$text('footer_terms_url','Terms URL','url'),
    $text('social_link_1','Social Link 1','url'),$text('social_link_2','Social Link 2','url'),$text('social_link_3','Social Link 3','url'),$text('social_link_4','Social Link 4','url'),
    $text('footer_col1_title','Footer Column 1 Title'),$text('footer_col1_link1','Footer Column 1 Link 1 Label'),$text('footer_col1_link1_url','Footer Column 1 Link 1 URL','url'),$text('footer_col1_link2','Footer Column 1 Link 2 Label'),$text('footer_col1_link2_url','Footer Column 1 Link 2 URL','url'),$text('footer_col1_link3','Footer Column 1 Link 3 Label'),$text('footer_col1_link3_url','Footer Column 1 Link 3 URL','url'),$text('footer_col1_link4','Footer Column 1 Link 4 Label'),$text('footer_col1_link4_url','Footer Column 1 Link 4 URL','url'),
    $text('footer_col2_title','Footer Column 2 Title'),$text('footer_col2_link1','Footer Column 2 Link 1 Label'),$text('footer_col2_link1_url','Footer Column 2 Link 1 URL','url'),$text('footer_col2_link2','Footer Column 2 Link 2 Label'),$text('footer_col2_link2_url','Footer Column 2 Link 2 URL','url'),$text('footer_col2_link3','Footer Column 2 Link 3 Label'),$text('footer_col2_link3_url','Footer Column 2 Link 3 URL','url'),
    $text('footer_col3_title','Footer Column 3 Title'),$text('footer_col3_link1','Footer Column 3 Link 1 Label'),$text('footer_col3_link1_url','Footer Column 3 Link 1 URL','url'),$text('footer_col3_link2','Footer Column 3 Link 2 Label'),$text('footer_col3_link2_url','Footer Column 3 Link 2 URL','url'),
    $text('footer_col4_title','Footer Column 4 Title'),$text('footer_col4_link1','Footer Column 4 Link 1 Label'),$text('footer_col4_link1_url','Footer Column 4 Link 1 URL','url'),$text('footer_col4_link2','Footer Column 4 Link 2 Label'),$text('footer_col4_link2_url','Footer Column 4 Link 2 URL','url')
  ]);

  // Homepage sections. Each section is a dedicated group to keep the editor UI clear.
  $group('home_hero','Homepage — 01 Hero Banner',['page_type'=>'front_page'],[
    $text('hero_eyebrow','Eyebrow'),$text('hero_heading','Heading','textarea'),$text('hero_description','Description','textarea'),$text('hero_primary_label','Primary Button Label'),$text('hero_primary_url','Primary Button URL','url'),$text('hero_secondary_label','Secondary Button Label'),$text('hero_secondary_url','Secondary Button URL','url'),
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
  $group('home_square','Homepage — 02 Square CTA (legacy)',['page_type'=>'front_page'],[$text('square_cta_text','Circular CTA Text'),$text('square_cta_url','Circular CTA URL','url'),$image('square_cta_icon','CTA Icon')]);
  $group('home_featured','Homepage — 03 Featured Properties',['page_type'=>'front_page'],[$text('featured_heading','Heading'),$text('featured_description','Description','textarea'),$text('featured_button_label','Button Label'),$text('featured_button_url','Button URL','url'),$text('featured_property_1','Property 1 ID','number'),$text('featured_property_2','Property 2 ID','number'),$text('featured_property_3','Property 3 ID','number'),$text('featured_count','Carousel item count','number')]);
  $group('home_testimonials','Homepage — 04 Testimonials',['page_type'=>'front_page'],[$text('testimonials_heading','Heading'),$text('testimonials_description','Description','textarea'),$text('testimonials_button_label','Button Label'),$text('testimonials_button_url','Button URL','url'),$text('testimonials_count','Number of Testimonials','number')]);
  $group('home_faq','Homepage — 05 FAQ',['page_type'=>'front_page'],[$text('faq_heading','Heading'),$text('faq_description','Description','textarea'),$text('faq_button_label','Button Label'),$text('faq_button_url','Button URL','url'),$text('faq_count','Number of FAQs','number')]);
  $group('home_cta','Homepage — 06 Footer Banner / CTA',['page_type'=>'front_page'],[$text('footer_cta_heading','Heading'),$text('footer_cta_description','Description','textarea'),$text('footer_cta_button_label','Button Label'),$text('footer_cta_button_url','Button URL','url')]);

  // Contact page — one ACF group per section (ACF Free compatible).
  $group('contact_hero','Contact — 01 Hero & Contact Methods',['page_template'=>'page-contact.php'],[
    $text('contact_hero_eyebrow','Eyebrow / Kicker'),
    $text('contact_hero_heading','Heading','textarea'),
    $text('contact_hero_description','Description','textarea'),
    $text('contact_method_1_label','Method 1 Label'),$text('contact_method_1_value','Method 1 Value'),$text('contact_method_1_url','Method 1 URL','url'),$image('contact_method_1_icon','Method 1 Icon'),
    $text('contact_method_2_label','Method 2 Label'),$text('contact_method_2_value','Method 2 Value'),$text('contact_method_2_url','Method 2 URL','url'),$image('contact_method_2_icon','Method 2 Icon'),
    $text('contact_method_3_label','Method 3 Label'),$text('contact_method_3_value','Method 3 Value'),$text('contact_method_3_url','Method 3 URL','url'),$image('contact_method_3_icon','Method 3 Icon'),
    $text('contact_method_4_label','Method 4 Label'),$text('contact_method_4_value','Method 4 Value'),$text('contact_method_4_url','Method 4 URL','url'),$image('contact_method_4_icon','Method 4 Icon'),
  ]);
  $group('contact_form','Contact — 02 Contact Form',['page_template'=>'page-contact.php'],[
    $text('contact_form_eyebrow','Eyebrow / Kicker'),
    $text('contact_form_heading','Heading'),
    $text('contact_form_description','Description','textarea'),
    $text('contact_form_id','Ninja Form ID','number',['instructions'=>'Enter the Ninja Form ID (e.g. 2). Used if shortcode is empty.']),
    $text('contact_form_shortcode','Ninja Form Shortcode','textarea',['instructions'=>'Optional. Example: [ninja_form id=2]. Overrides Form ID when set.']),
  ]);
  $group('contact_offices','Contact — 03 Office Locations',['page_template'=>'page-contact.php'],[
    $text('contact_offices_eyebrow','Eyebrow / Kicker'),
    $text('contact_offices_heading','Heading'),
    $text('contact_offices_description','Description','textarea'),
    $text('office_1_title','Office 1 Title'),$text('office_1_address','Office 1 Address','textarea'),$text('office_1_description','Office 1 Description','textarea'),$text('office_1_email','Office 1 Email','email'),$text('office_1_phone','Office 1 Phone'),$text('office_1_city','Office 1 City'),$text('office_1_map_url','Office 1 Map URL','url'),$image('office_1_image','Office 1 Image'),
    $text('office_2_title','Office 2 Title'),$text('office_2_address','Office 2 Address','textarea'),$text('office_2_description','Office 2 Description','textarea'),$text('office_2_email','Office 2 Email','email'),$text('office_2_phone','Office 2 Phone'),$text('office_2_city','Office 2 City'),$text('office_2_map_url','Office 2 Map URL','url'),$image('office_2_image','Office 2 Image'),
    $text('office_3_title','Office 3 Title'),$text('office_3_address','Office 3 Address','textarea'),$text('office_3_description','Office 3 Description','textarea'),$text('office_3_email','Office 3 Email','email'),$text('office_3_phone','Office 3 Phone'),$text('office_3_city','Office 3 City'),$text('office_3_map_url','Office 3 Map URL','url'),$image('office_3_image','Office 3 Image'),
  ]);
  $group('contact_gallery','Contact — 04 Gallery Banner',['page_template'=>'page-contact.php'],[
    $text('contact_gallery_eyebrow','Eyebrow / Kicker'),
    $text('contact_gallery_heading','Heading'),
    $text('contact_gallery_description','Description','textarea'),
    $image('contact_gallery_image_1','Gallery Image 1'),
    $image('contact_gallery_image_2','Gallery Image 2'),
    $image('contact_gallery_image_3','Gallery Image 3'),
  ]);
  $group('contact_cta','Contact — 05 Footer CTA',['page_template'=>'page-contact.php'],[
    $text('contact_cta_heading','Heading'),
    $text('contact_cta_description','Description','textarea'),
    $text('contact_cta_button_label','Button Label'),
    $text('contact_cta_button_url','Button URL','url'),
  ]);
  // Legacy single contact group removed in favor of section groups above.
  $group('about','About Us — Sections',['page_template'=>'page-about-us.php'],[$text('about_hero_heading','Hero Heading'),$text('about_hero_description','Hero Description','textarea'),$image('about_hero_image','Hero Image'),$text('about_story_heading','Story Heading'),$text('about_story_content','Story Content','wysiwyg',['toolbar'=>'basic','media_upload'=>0]),$text('about_value_1_title','Value 1 Title'),$text('about_value_1_text','Value 1 Text','textarea'),$text('about_value_2_title','Value 2 Title'),$text('about_value_2_text','Value 2 Text','textarea'),$text('about_value_3_title','Value 3 Title'),$text('about_value_3_text','Value 3 Text','textarea'),$text('about_team_heading','Team Heading'),$text('about_team_description','Team Description','textarea'),$text('about_team_count','Team Count','number'),$text('about_form_shortcode','Contact Form Ninja Form Shortcode','textarea')]);

  // Property fields.
  $group('property','Property — Details & Pricing',['post_type'=>'property'],[
    $text('featured_property','Featured Property','true_false',['ui'=>1]),$text('price','Price','number'),$text('currency','Currency'),$text('price_label','Price Label'),$text('short_excerpt','Property Excerpt','textarea'),$text('card_building_label','Card building label (Villa, Cottage, etc.)'),$text('property_status_label','Status Label'),$text('property_highlight_1','Highlight 1'),$text('property_highlight_2','Highlight 2'),$text('property_highlight_3','Highlight 3'),$text('property_highlight_4','Highlight 4'),$text('property_highlight_5','Highlight 5'),$text('property_highlight_6','Highlight 6'),
    $text('bedrooms','Bedrooms','number'),$text('bathrooms','Bathrooms','number'),$text('parking_spaces','Parking Spaces','number'),$text('property_size','Property Size','number'),$text('property_size_unit','Property Size Unit'),$text('lot_size','Lot Size','number'),$text('lot_size_unit','Lot Size Unit'),$text('build_year','Build Year','number'),$text('address','Address','textarea'),$text('city','City'),$text('state','State'),$text('postal_code','Postal Code'),$text('country','Country'),$text('latitude','Latitude'),$text('longitude','Longitude'),$text('map_embed','Map Embed URL','url'),
    $text('price_per_sqft','Price per Sq Ft','number'),$text('hoa_fee','HOA / Association Fee','number'),$text('property_tax_annual','Annual Property Tax','number'),$text('insurance_annual','Annual Insurance Estimate','number'),$text('maintenance_monthly','Monthly Maintenance Estimate','number'),$text('utilities_monthly','Monthly Utilities Estimate','number'),$text('down_payment_percent','Suggested Down Payment %','number'),$text('closing_cost_percent','Estimated Closing Costs %','number'),$text('estimated_monthly_payment','Estimated Monthly Payment','number'),$text('pricing_notes','Pricing Notes','textarea'),
    $image('gallery_1','Gallery Image 1'),$image('gallery_2','Gallery Image 2'),$image('gallery_3','Gallery Image 3'),$image('gallery_4','Gallery Image 4'),$image('gallery_5','Gallery Image 5'),$image('gallery_6','Gallery Image 6'),$image('floor_plan','Floor Plan'),$text('virtual_tour_url','Virtual Tour URL','url'),$text('video_url','Video URL','url'),$text('property_form_shortcode','Property Inquiry Ninja Form Shortcode','textarea')
  ]);

  $group('faq','FAQ — Detail',['post_type'=>'faq'],[$text('faq_short_answer','Short Answer','textarea'),$text('faq_category_label','Category Label'),$text('faq_related_property','Related Property','post_object',['post_type'=>['property'],'return_format'=>'id','ui'=>1])]);
  $group('testimonial','Testimonial — Detail',['post_type'=>'testimonial'],[$text('client_name','Client Name'),$text('client_location','Client Location'),$text('rating','Rating','number'),$image('client_photo','Client Photo'),$text('testimonial_quote','Quote','textarea'),$text('testimonial_property','Property Purchased','post_object',['post_type'=>['property'],'return_format'=>'id','ui'=>1])]);
  $group('team','Team Member — Detail',['post_type'=>'team_member'],[$text('role','Role'),$text('short_bio','Short Bio','textarea'),$text('bio','Biography','wysiwyg',['toolbar'=>'basic','media_upload'=>0]),$text('email','Email','email'),$text('phone','Phone'),$text('linkedin','LinkedIn','url'),$text('instagram','Instagram','url'),$image('team_photo','Profile Photo')]);
  $group('client','Client — Detail',['post_type'=>'client'],[$text('client_company','Company Name'),$text('client_website','Website','url'),$text('client_description','Description','textarea'),$image('client_logo','Logo'),$text('client_link_label','Link Label')]);

  // Archive settings are stored on the archive root pages using normal Pages where available.
  $group('property_archive','Property Archive — Intro & Form',['page_template'=>'page-estatein-settings.php'],[$text('property_archive_heading','Archive Heading'),$text('property_archive_description','Archive Description','textarea'),$text('property_archive_form_shortcode','Property Search / Inquiry Ninja Form Shortcode','textarea'),$image('property_archive_banner','Archive Banner')]);
}
add_action('acf/init','estatein_register_acf_fields');
