<?php
/**
 * Homepage section: featured properties carousel.
 *
 * Cards are a normal WP_Query over the `property` CPT. Beds, baths and type are
 * taxonomy terms; price is the only ACF field on a property.
 *
 * @package Estatein
 */

if (!defined('ABSPATH')) {
	exit;
}

$properties = estatein_home_properties_query();
$heading    = estatein_field('featured_heading');
$view_all   = estatein_button(
	estatein_field('featured_button_label'),
	estatein_nav_url(estatein_field('featured_button_url', false, '')),
	'btn btn-view-all'
);
?>
<section class="content-section" id="featured-properties">
	<div class="container-xl">
		<div class="section-heading-row">
			<div>
				<?php echo estatein_kicker($heading); ?>
				<h2><?php echo esc_html($heading); ?></h2>
				<p><?php echo esc_html(estatein_field('featured_description')); ?></p>
			</div>
			<?php
			echo estatein_button(
				estatein_field('featured_button_label'),
				estatein_nav_url(estatein_field('featured_button_url', false, '')),
				'btn btn-view-all d-none d-lg-inline-flex'
			);
			?>
		</div>

		<?php if ($properties->have_posts()) : ?>
			<div class="estatein-slider" data-estatein-slider data-slides="3" role="region" aria-roledescription="carousel" aria-label="<?php echo esc_attr($heading); ?>">
				<div class="estatein-slider-track" id="featured-properties-slides">
					<?php
					while ($properties->have_posts()) :
						$properties->the_post();

						$title    = get_the_title();
						$excerpt  = wp_trim_words(get_the_excerpt() ?: wp_strip_all_tags(get_the_content(null, false)), 24, '');
						$beds     = estatein_first_term_name('bedroom');
						$baths    = estatein_first_term_name('bathroom');
						$type     = estatein_first_term_name('property_type');
						$details  = estatein_single_url();
						?>
						<div class="estatein-slide">
							<article <?php post_class('property-card card-reveal h-100'); ?>>
								<?php
								if (has_post_thumbnail()) {
									$alt = trim((string) get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true));
									the_post_thumbnail('estatein-property-card', [
										'class' => 'property-card-image',
										'alt'   => '' !== $alt ? $alt : $title,
									]);
								}
								?>
								<div class="property-card-body">
									<h3><?php the_title(); ?></h3>
									<?php if ($excerpt) : ?>
										<p class="property-excerpt">
											<?php echo esc_html($excerpt); ?>
											<a class="property-excerpt-more" href="<?php echo esc_url($details); ?>"><?php esc_html_e('Read More', 'estatein'); ?></a>
										</p>
									<?php endif; ?>
									<div class="property-meta">
										<?php if ($beds) : ?>
											<span>
												<?php echo estatein_asset_icon('meta-bed', 'meta-icon'); ?>
												<?php
												/* translators: %s: number of bedrooms. */
												printf(esc_html__('%s-Bedroom', 'estatein'), esc_html($beds));
												?>
											</span>
										<?php endif; ?>
										<?php if ($baths) : ?>
											<span>
												<?php echo estatein_asset_icon('meta-bath', 'meta-icon'); ?>
												<?php
												/* translators: %s: number of bathrooms. */
												printf(esc_html__('%s-Bathroom', 'estatein'), esc_html($baths));
												?>
											</span>
										<?php endif; ?>
										<?php if ($type) : ?>
											<span>
												<?php echo estatein_asset_icon('meta-type', 'meta-icon'); ?>
												<?php echo esc_html($type); ?>
											</span>
										<?php endif; ?>
									</div>
									<div class="property-price-row">
										<div class="property-price">
											<small><?php esc_html_e('Price', 'estatein'); ?></small>
											<strong><?php echo esc_html(estatein_currency(estatein_field('price', get_the_ID(), ''))); ?></strong>
										</div>
										<a class="btn btn-primary btn-property-details" href="<?php echo esc_url($details); ?>">
											<?php esc_html_e('View Property Details', 'estatein'); ?>
											<span class="visually-hidden"> <?php echo esc_html($title); ?></span>
										</a>
									</div>
								</div>
							</article>
						</div>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				</div>
				<?php estatein_slider_controls($view_all, __('properties', 'estatein'), 'featured-properties-slides'); ?>
			</div>
		<?php else : ?>
			<div class="empty-state"><?php esc_html_e('Add featured Property posts to populate this section.', 'estatein'); ?></div>
		<?php endif; ?>
	</div>
</section>
