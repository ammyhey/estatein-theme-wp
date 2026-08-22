<?php
if (!defined('ABSPATH')) {
	exit;
}
$properties = estatein_home_properties_query();
?>
<section class="content-section" id="featured-properties">
	<div class="container-xl">
		<div class="section-heading-row">
			<div>
				<?php echo estatein_kicker('Featured Properties'); ?>
				<h2><?php echo esc_html(estatein_field('featured_heading', false, 'Featured Properties')); ?></h2>
				<p><?php echo esc_html(estatein_field('featured_description', false, 'Explore our handpicked selection of featured properties. Each listing offers exceptional homes and investments ready to fulfill your unique vision.')); ?></p>
			</div>
			<?php echo estatein_button(estatein_field('featured_button_label', false, 'View All Properties'), estatein_nav_url(estatein_field('featured_button_url', false, '')), 'btn btn-view-all d-none d-lg-inline-flex'); ?>
		</div>

		<?php if ($properties->have_posts()) : ?>
			<div class="estatein-slider" data-estatein-slider data-slides="3" role="region" aria-roledescription="carousel" aria-label="<?php echo esc_attr(estatein_field('featured_heading', false, 'Featured Properties')); ?>">
				<div class="estatein-slider-track" id="featured-properties-slides">
					<?php
					while ($properties->have_posts()) :
						$properties->the_post();
						$type  = estatein_first_term_name('property_type') ?: 'Villa';
						$beds  = estatein_first_term_name('bedroom') ?: '—';
						$baths = estatein_first_term_name('bathroom') ?: '—';
						$title = get_the_title();
						$excerpt_source = get_the_excerpt() ?: wp_strip_all_tags(get_the_content(null, false));
						$excerpt = wp_trim_words($excerpt_source, 24, '');
						?>
						<div class="estatein-slide">
							<article <?php post_class('property-card card-reveal h-100'); ?>>
								<?php
								if (has_post_thumbnail()) {
									$thumb_id = get_post_thumbnail_id();
									$alt = trim((string) get_post_meta($thumb_id, '_wp_attachment_image_alt', true));
									the_post_thumbnail('estatein-property-card', [
										'class' => 'property-card-image',
										'alt'   => $alt !== '' ? $alt : $title,
									]);
								}
								?>
								<div class="property-card-body">
									<h3><?php the_title(); ?></h3>
									<p class="property-excerpt">
										<?php echo esc_html($excerpt); ?>
										<?php if ($excerpt) : ?>
											<a class="property-excerpt-more" href="<?php the_permalink(); ?>"><?php esc_html_e('Read More', 'estatein'); ?></a>
										<?php endif; ?>
									</p>
									<div class="property-meta">
										<span><?php echo estatein_asset_icon('meta-bed', 'meta-icon'); ?> <?php echo esc_html($beds); ?>-Bedroom</span>
										<span><?php echo estatein_asset_icon('meta-bath', 'meta-icon'); ?> <?php echo esc_html($baths); ?>-Bathroom</span>
										<span><?php echo estatein_asset_icon('meta-type', 'meta-icon'); ?> <?php echo esc_html($type); ?></span>
									</div>
									<div class="property-price-row">
										<div class="property-price">
											<small><?php esc_html_e('Price', 'estatein'); ?></small>
											<strong><?php echo esc_html(estatein_currency(estatein_field('price'))); ?></strong>
										</div>
										<a class="btn btn-primary btn-property-details" href="<?php the_permalink(); ?>">
											<?php esc_html_e('View Property Details', 'estatein'); ?>
											<span class="visually-hidden"> <?php echo esc_html($title); ?></span>
										</a>
									</div>
								</div>
							</article>
						</div>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
				<?php estatein_slider_controls(estatein_button(estatein_field('featured_button_label', false, 'View All Properties'), estatein_nav_url(estatein_field('featured_button_url', false, '')), 'btn btn-view-all'), __('properties', 'estatein'), 'featured-properties-slides'); ?>
			</div>
		<?php else : ?>
			<div class="empty-state"><?php esc_html_e('Add featured Property posts to populate this section.', 'estatein'); ?></div>
		<?php endif; ?>
	</div>
</section>
