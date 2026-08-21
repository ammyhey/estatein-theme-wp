<?php
if (!defined('ABSPATH')) {
	exit;
}
$methods = [];
for ($i = 1; $i <= 4; $i++) {
	$label = estatein_field('contact_method_' . $i . '_label', false, '');
	$value = estatein_field('contact_method_' . $i . '_value', false, '');
	if (!$label && !$value) {
		continue;
	}
	$methods[] = [
		'label' => $label,
		'value' => $value,
		'url'   => estatein_field('contact_method_' . $i . '_url', false, ''),
		'icon'  => estatein_image_url('contact_method_' . $i . '_icon', 'thumbnail', false),
	];
}
?>
<section class="content-section contact-hero-section">
	<div class="container-xl">
		<div class="section-heading-row section-heading-row--stack">
			<div>
				<?php echo estatein_kicker(estatein_field('contact_hero_eyebrow', false, 'Contact Us')); ?>
				<h1><?php echo esc_html(estatein_field('contact_hero_heading', false, 'Get in Touch With Estatein')); ?></h1>
				<p><?php echo esc_html(estatein_field('contact_hero_description', false, "Welcome to Estatein's Contact Us page. We're here to assist you with any inquiries, requests, or feedback you may have.")); ?></p>
			</div>
		</div>

		<?php if ($methods) : ?>
			<div class="row g-3 g-xl-4 contact-methods">
				<?php foreach ($methods as $method) : ?>
					<div class="col-md-6 col-xl-3">
						<?php
						$tag = $method['url'] ? 'a' : 'div';
						$href = $method['url'] ? ' href="' . esc_url($method['url']) . '"' : '';
						?>
						<<?php echo $tag; ?> class="info-card contact-method-card h-100"<?php echo $href; ?>>
							<span class="info-card-icon">
								<?php if ($method['icon']) : ?>
									<img src="<?php echo esc_url($method['icon']); ?>" alt="" width="34" height="34" loading="lazy">
								<?php else : ?>
									<?php echo estatein_icon('arrow-up-right'); ?>
								<?php endif; ?>
							</span>
							<span class="info-card-arrow" aria-hidden="true"><?php echo estatein_icon('arrow-up-right'); ?></span>
							<span class="info-card-label visually-hidden"><?php echo esc_html($method['label']); ?></span>
							<span class="info-card-value"><?php echo esc_html($method['value']); ?></span>
						</<?php echo $tag; ?>>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
