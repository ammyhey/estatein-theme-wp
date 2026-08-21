<?php
if (!defined('ABSPATH')) {
	exit;
}
$posts = estatein_home_cpt_query('testimonial', 'testimonials_count', 6);
?>
<section class="content-section" id="testimonials">
	<div class="container-xl">
		<div class="section-heading-row">
			<div>
				<?php echo estatein_kicker('Testimonials'); ?>
				<h2><?php echo esc_html(estatein_field('testimonials_heading', false, 'What Our Clients Say')); ?></h2>
				<p><?php echo esc_html(estatein_field('testimonials_description', false, 'Read the success stories and heartfelt testimonials from our valued clients. Discover why they chose Estatein for their real estate needs.')); ?></p>
			</div>
			<?php echo estatein_button(estatein_field('testimonials_button_label', false, 'View All Testimonials'), estatein_nav_url(estatein_field('testimonials_button_url', false, '')), 'btn btn-view-all d-none d-lg-inline-flex'); ?>
		</div>

		<?php if ($posts) : ?>
			<div class="estatein-slider" data-estatein-slider data-slides="3">
				<div class="estatein-slider-track">
					<?php foreach ($posts as $post) :
						setup_postdata($post);
						$pid = $post->ID;
						$rating = (int) min(5, max(0, estatein_field('rating', $pid, 5)));
						?>
						<div class="estatein-slide">
							<article class="testimonial-card h-100">
								<div class="stars" aria-label="<?php echo esc_attr($rating . ' out of 5 stars'); ?>">
									<?php for ($s = 0; $s < $rating; $s++) : ?>
										<?php echo estatein_asset_icon('rating-star', 'rating-star', 44, 44); ?>
									<?php endfor; ?>
								</div>
								<h3><?php echo esc_html(get_the_title($pid)); ?></h3>
								<p><?php echo esc_html(estatein_field('testimonial_quote', $pid, wp_strip_all_tags(get_post_field('post_content', $pid)))); ?></p>
								<div class="client-meta">
									<?php
									$photo = estatein_image('client_photo', 'thumbnail', $pid, 'client-avatar');
									echo $photo ?: '<span class="client-avatar client-avatar-fallback" aria-hidden="true"></span>';
									?>
									<div>
										<strong><?php echo esc_html(estatein_field('client_name', $pid, get_the_title($pid))); ?></strong>
										<span><?php echo esc_html(estatein_field('client_location', $pid)); ?></span>
									</div>
								</div>
							</article>
						</div>
					<?php endforeach; wp_reset_postdata(); ?>
				</div>
				<?php estatein_slider_controls(estatein_button(estatein_field('testimonials_button_label', false, 'View All Testimonials'), estatein_nav_url(estatein_field('testimonials_button_url', false, '')), 'btn btn-view-all')); ?>
			</div>
		<?php else : ?>
			<div class="empty-state"><?php esc_html_e('Add Testimonials to populate this section.', 'estatein'); ?></div>
		<?php endif; ?>
	</div>
</section>
