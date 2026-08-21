<?php
if (!defined('ABSPATH')) {
	exit;
}
?>
<section class="content-section contact-form-section" id="contact-form">
	<div class="container-xl">
		<div class="section-heading-row section-heading-row--stack">
			<div>
				<?php echo estatein_kicker(estatein_field('contact_form_eyebrow', false, "Let's Connect")); ?>
				<h2><?php echo esc_html(estatein_field('contact_form_heading', false, "Let's Connect")); ?></h2>
				<p><?php echo esc_html(estatein_field('contact_form_description', false, "We're excited to connect with you and learn more about your real estate goals. Use the form below to get in touch with Estatein.")); ?></p>
			</div>
		</div>

		<div class="surface-panel contact-form-panel">
			<?php estatein_render_form('contact_form_shortcode', 'contact_form_id', false); ?>
		</div>
	</div>
</section>
