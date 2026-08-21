<?php
if (!defined('ABSPATH')) {
	exit;
}
$posts = estatein_home_cpt_query('faq', 'faq_count', 6);
?>
<section class="content-section" id="faqs">
	<div class="container-xl">
		<div class="section-heading-row">
			<div>
				<?php echo estatein_kicker('FAQ'); ?>
				<h2><?php echo esc_html(estatein_field('faq_heading', false, 'Frequently Asked Questions')); ?></h2>
				<p><?php echo esc_html(estatein_field('faq_description', false, 'Find answers to common questions about Estatein’s services, property listings, and the real estate process. We’re here to provide clarity and assist you every step of the way.')); ?></p>
			</div>
			<?php echo estatein_button(estatein_field('faq_button_label', false, "View All FAQ's"), estatein_field('faq_button_url', false, estatein_archive_url('faq')), 'btn btn-view-all d-none d-md-inline-flex'); ?>
		</div>

		<?php if ($posts) : ?>
			<div class="estatein-slider" data-estatein-slider data-slides="3">
				<div class="estatein-slider-track">
					<?php foreach ($posts as $post) :
						setup_postdata($post);
						$pid = $post->ID;
						?>
						<div class="estatein-slide">
							<article class="faq-card h-100">
								<h3><?php echo esc_html(get_the_title($pid)); ?></h3>
								<p><?php echo esc_html(estatein_field('faq_short_answer', $pid, wp_trim_words(get_post_field('post_content', $pid), 28))); ?></p>
								<a class="btn btn-view-all faq-read-more" href="<?php echo esc_url(get_permalink($pid)); ?>"><?php esc_html_e('Read More', 'estatein'); ?></a>
							</article>
						</div>
					<?php endforeach; wp_reset_postdata(); ?>
				</div>
				<?php estatein_slider_controls(); ?>
			</div>
			<div class="d-md-none mt-4">
				<?php echo estatein_button(estatein_field('faq_button_label', false, "View All FAQ's"), estatein_field('faq_button_url', false, estatein_archive_url('faq')), 'btn btn-view-all w-100'); ?>
			</div>
		<?php else : ?>
			<div class="empty-state"><?php esc_html_e('Add FAQ posts to populate this section.', 'estatein'); ?></div>
		<?php endif; ?>
	</div>
</section>
