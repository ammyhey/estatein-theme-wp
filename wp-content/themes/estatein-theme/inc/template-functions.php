<?php
if (!defined('ABSPATH')) exit;
function estatein_render_ninja_form($field='form_shortcode') {
  $shortcode=estatein_field($field,'option');
  if (!$shortcode) return;
  echo do_shortcode($shortcode);
}
function estatein_breadcrumbs(){ if (is_front_page()) return; echo '<nav aria-label="breadcrumb" class="breadcrumbs mb-4"><a href="'.esc_url(home_url('/')).'">Home</a><span>/</span><span>'.esc_html(wp_strip_all_tags(get_the_title())).'</span></nav>'; }
function estatein_property_filters(){
  $taxes=['location'=>'Location','property_type'=>'Property Type','property_status'=>'Status'];
  echo '<form class="property-filters row g-3 mb-5" method="get">';
  foreach($taxes as $tax=>$label){ $terms=get_terms(['taxonomy'=>$tax,'hide_empty'=>true]); if(is_wp_error($terms)||!$terms) continue; echo '<div class="col-md-4"><label class="form-label">'.esc_html($label).'</label><select name="'.esc_attr($tax).'" class="form-select"><option value="">All '.esc_html($label).'</option>'; foreach($terms as $term){$sel=isset($_GET[$tax])&&sanitize_title(wp_unslash($_GET[$tax]))===$term->slug?' selected':''; echo '<option value="'.esc_attr($term->slug).'"'.$sel.'>'.esc_html($term->name).'</option>';} echo '</select></div>'; }
  echo '<div class="col-md-4"><label class="form-label">Min Price</label><input class="form-control" type="number" name="min_price" value="'.esc_attr($_GET['min_price']??'').'"></div><div class="col-md-4"><label class="form-label">Max Price</label><input class="form-control" type="number" name="max_price" value="'.esc_attr($_GET['max_price']??'').'"></div><div class="col-md-4 d-flex align-items-end"><button class="btn btn-primary w-100" type="submit">Filter Properties</button></div></form>';
}
function estatein_property_query_args(){
  $args=['post_type'=>'property','post_status'=>'publish','posts_per_page'=>9,'paged'=>max(1,get_query_var('paged'))]; $tax_query=[];
  foreach(['location','property_type','property_status','pricing_range','property_size_range','build_year'] as $tax){if(!empty($_GET[$tax]))$tax_query[]=['taxonomy'=>$tax,'field'=>'slug','terms'=>sanitize_title(wp_unslash($_GET[$tax]))];}
  if($tax_query)$args['tax_query']=$tax_query; $meta=[];
  if(isset($_GET['min_price'])&&$_GET['min_price']!=='')$meta[]=['key'=>'price','value'=>(float)$_GET['min_price'],'type'=>'NUMERIC','compare'=>'>='];
  if(isset($_GET['max_price'])&&$_GET['max_price']!=='')$meta[]=['key'=>'price','value'=>(float)$_GET['max_price'],'type'=>'NUMERIC','compare'=>'<='];
  if($meta)$args['meta_query']=$meta; return $args;
}
