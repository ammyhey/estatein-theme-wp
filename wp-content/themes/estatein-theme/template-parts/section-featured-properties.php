<?php
if (!defined('ABSPATH')) {
	exit;
}
$posts = estatein_home_properties_query();
?>
<section class="content-section" id="featured-properties">
	<div class="container-xl">
		<div class="section-heading-row">
			<div>
				<?php echo estatein_kicker('Featured Properties'); ?>
				<h2><?php echo esc_html(estatein_field('featured_heading', false, 'Featured Properties')); ?></h2>
				<p><?php echo esc_html(estatein_field('featured_description', false, 'Explore our handpicked selection of featured properties. Each listing offers exceptional homes and investments ready to fulfill your unique vision.')); ?></p>
			</div>
			<?php echo estatein_button(estatein_field('featured_button_label', false, 'View All Properties'), estatein_field('featured_button_url', false, estatein_archive_url('property')), 'btn btn-view-all d-none d-md-inline-flex'); ?>
		</div>

		<?php if ($posts) : ?>
			<div class="estatein-slider" data-estatein-slider data-slides="3">
				<div class="estatein-slider-track">
					<?php foreach ($posts as $post) :
						setup_postdata($post);
						$pid   = $post->ID;
						$type  = estatein_field('card_building_label', $pid, estatein_term_name($pid, 'property_type', 'Villa'));
						$beds  = estatein_field('bedrooms', $pid, '—');
						$baths = estatein_field('bathrooms', $pid, '—');
						?>
						<div class="estatein-slide">
							<article class="property-card h-100">
								<?php
								if (has_post_thumbnail($pid)) {
									echo get_the_post_thumbnail($pid, 'estatein-property-card', ['class' => 'property-card-image']);
								}
								?>
								<div class="property-card-body">
									<h3><?php echo esc_html(get_the_title($pid)); ?></h3>
									<p><?php echo esc_html(estatein_field('short_excerpt', $pid, wp_trim_words(get_the_excerpt($pid) ?: get_post_field('post_content', $pid), 24))); ?></p>
									<div class="property-meta">
										<span><?php echo estatein_asset_icon('meta-bed', 'meta-icon'); ?> <?php echo esc_html($beds); ?>-Bedroom</span>
										<span><?php echo estatein_asset_icon('meta-bath', 'meta-icon'); ?> <?php echo esc_html($baths); ?>-Bathroom</span>
										<span><?php echo estatein_asset_icon('meta-type', 'meta-icon'); ?> <?php echo esc_html($type); ?></span>
									</div>
									<div class="property-price-row">
										<div class="property-price">
											<small><?php esc_html_e('Price', 'estatein'); ?></small>
											<strong><?php echo esc_html(estatein_currency(estatein_field('price', $pid))); ?></strong>
										</div>
										<a class="btn btn-primary btn-property-details" href="<?php echo esc_url(get_permalink($pid)); ?>"><?php esc_html_e('View Property Details', 'estatein'); ?></a>
									</div>
								</div>
							</article>
						</div>
					<?php endforeach; wp_reset_postdata(); ?>
				</div>
				<?php estatein_slider_controls(); ?>
			</div>
			<div class="d-md-none mt-4">
				<?php echo estatein_button(estatein_field('featured_button_label', false, 'View All Properties'), estatein_field('featured_button_url', false, estatein_archive_url('property')), 'btn btn-view-all w-100'); ?>
			</div>
		<?php else : ?>
			<div class="empty-state"><?php esc_html_e('Add Property posts to populate this section.', 'estatein'); ?></div>
		<?php endif; ?>
	</div>
</section>
