<?php
/**
 * Homepage section: testimonials carousel.
 *
 * @package Estatein
 */

if (!defined('ABSPATH')) {
	exit;
}

$testimonials = estatein_home_cpt_query('testimonial', 'testimonials_count');
$heading      = estatein_field('testimonials_heading');
$view_all     = estatein_button(
	estatein_field('testimonials_button_label'),
	estatein_nav_url(estatein_field('testimonials_button_url', false, '')),
	'btn btn-view-all'
);
?>
<section class="content-section" id="testimonials">
	<div class="container-xl">
		<div class="section-heading-row">
			<div>
				<?php echo estatein_kicker($heading); ?>
				<h2><?php echo esc_html($heading); ?></h2>
				<p><?php echo esc_html(estatein_field('testimonials_description')); ?></p>
			</div>
			<?php
			echo estatein_button(
				estatein_field('testimonials_button_label'),
				estatein_nav_url(estatein_field('testimonials_button_url', false, '')),
				'btn btn-view-all d-none d-lg-inline-flex'
			);
			?>
		</div>

		<?php if ($testimonials) : ?>
			<div class="estatein-slider" data-estatein-slider data-slides="3" role="region" aria-roledescription="carousel" aria-label="<?php echo esc_attr($heading); ?>">
				<div class="estatein-slider-track" id="testimonials-slides">
					<?php
					foreach ($testimonials as $testimonial) :
						$id          = $testimonial->ID;
						$rating      = min(5, max(0, (int) estatein_field('rating', $id, 5)));
						$client_name = estatein_field('client_name', $id, get_the_title($id));
						$photo       = estatein_image('client_photo', 'thumbnail', $id, 'client-avatar', $client_name);
						$quote       = estatein_field('testimonial_quote', $id, wp_strip_all_tags($testimonial->post_content));
						?>
						<div class="estatein-slide">
							<article class="testimonial-card card-reveal h-100">
								<?php
								/* translators: %d: star rating out of five. */
								$rating_label = sprintf(__('%d out of 5 stars', 'estatein'), $rating);
								?>
								<div class="stars" role="img" aria-label="<?php echo esc_attr($rating_label); ?>">
									<?php for ($star = 0; $star < $rating; $star++) : ?>
										<?php echo estatein_asset_icon('rating-star', 'rating-star', 44, 44); ?>
									<?php endfor; ?>
								</div>
								<h3><?php echo esc_html(get_the_title($id)); ?></h3>
								<p><?php echo esc_html($quote); ?></p>
								<div class="client-meta">
									<?php
									// Escaped by estatein_image(); the fallback keeps the card layout intact.
									echo $photo ?: '<span class="client-avatar client-avatar-fallback" aria-hidden="true"></span>';
									?>
									<div>
										<strong><?php echo esc_html($client_name); ?></strong>
										<span><?php echo esc_html(estatein_field('client_location', $id, '')); ?></span>
									</div>
								</div>
							</article>
						</div>
					<?php endforeach; ?>
				</div>
				<?php estatein_slider_controls($view_all, __('testimonials', 'estatein'), 'testimonials-slides'); ?>
			</div>
		<?php else : ?>
			<div class="empty-state"><?php esc_html_e('Add Testimonials to populate this section.', 'estatein'); ?></div>
		<?php endif; ?>
	</div>
</section>
