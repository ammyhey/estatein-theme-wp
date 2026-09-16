<?php
/**
 * Homepage section: FAQ carousel.
 *
 * `faq` has no public single view, so "Read More" resolves to "#" through
 * `estatein_single_url()` rather than linking to a 404.
 *
 * @package Estatein
 */

if (!defined('ABSPATH')) {
	exit;
}

$faqs     = estatein_home_cpt_query('faq', 'faq_count');
$heading  = estatein_field('faq_heading');
$view_all = estatein_button(
	estatein_field('faq_button_label'),
	estatein_nav_url(estatein_field('faq_button_url', false, '')),
	'btn btn-view-all'
);
?>
<section class="content-section" id="faqs">
	<div class="container-xl">
		<div class="section-heading-row">
			<div>
				<?php echo estatein_kicker($heading); ?>
				<h2><?php echo esc_html($heading); ?></h2>
				<p><?php echo esc_html(estatein_field('faq_description')); ?></p>
			</div>
			<?php
			echo estatein_button(
				estatein_field('faq_button_label'),
				estatein_nav_url(estatein_field('faq_button_url', false, '')),
				'btn btn-view-all d-none d-lg-inline-flex'
			);
			?>
		</div>

		<?php if ($faqs) : ?>
			<div class="estatein-slider" data-estatein-slider data-slides="3" role="region" aria-roledescription="carousel" aria-label="<?php echo esc_attr($heading); ?>">
				<div class="estatein-slider-track" id="faq-slides">
					<?php
					foreach ($faqs as $faq) :
						$id     = $faq->ID;
						$title  = get_the_title($id);
						$answer = estatein_field('faq_short_answer', $id, wp_trim_words($faq->post_content, 28));
						?>
						<div class="estatein-slide">
							<article class="faq-card card-reveal h-100">
								<h3><?php echo esc_html($title); ?></h3>
								<p><?php echo esc_html($answer); ?></p>
								<a class="btn btn-view-all faq-read-more" href="<?php echo esc_url(estatein_single_url($id)); ?>">
									<?php esc_html_e('Read More', 'estatein'); ?>
									<span class="visually-hidden"> <?php echo esc_html($title); ?></span>
								</a>
							</article>
						</div>
					<?php endforeach; ?>
				</div>
				<?php estatein_slider_controls($view_all, __('questions', 'estatein'), 'faq-slides'); ?>
			</div>
		<?php else : ?>
			<div class="empty-state"><?php esc_html_e('Add FAQ posts to populate this section.', 'estatein'); ?></div>
		<?php endif; ?>
	</div>
</section>
