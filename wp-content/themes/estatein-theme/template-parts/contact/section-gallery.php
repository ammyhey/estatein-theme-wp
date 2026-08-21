<?php
if (!defined('ABSPATH')) {
	exit;
}
$images = [];
for ($i = 1; $i <= 3; $i++) {
	$html = estatein_image('contact_gallery_image_' . $i, 'large', false, 'gallery-mosaic-image');
	if ($html) {
		$images[] = $html;
	}
}
$heading = estatein_field('contact_gallery_heading', false, '');
if (!$heading && !$images) {
	return;
}
?>
<section class="content-section contact-gallery-section">
	<div class="container-xl">
		<div class="row g-4 align-items-center">
			<div class="col-lg-5">
				<?php echo estatein_kicker(estatein_field('contact_gallery_eyebrow', false, 'Gallery')); ?>
				<h2><?php echo esc_html($heading ?: "Explore Estatein's World"); ?></h2>
				<p><?php echo esc_html(estatein_field('contact_gallery_description', false, 'Step inside the world of Estatein, where professionalism meets warmth, and expertise meets passion.')); ?></p>
			</div>
			<div class="col-lg-7">
				<?php if ($images) : ?>
					<div class="gallery-mosaic">
						<?php foreach ($images as $img) : ?>
							<div class="gallery-mosaic-item"><?php echo $img; ?></div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
