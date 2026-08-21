<?php
if (!defined('ABSPATH')) {
	exit;
}
$offices = [];
for ($i = 1; $i <= 3; $i++) {
	$title = estatein_field('office_' . $i . '_title', false, '');
	if (!$title) {
		continue;
	}
	$offices[] = [
		'title' => $title,
		'address' => estatein_field('office_' . $i . '_address', false, ''),
		'description' => estatein_field('office_' . $i . '_description', false, ''),
		'email' => estatein_field('office_' . $i . '_email', false, ''),
		'phone' => estatein_field('office_' . $i . '_phone', false, ''),
		'city' => estatein_field('office_' . $i . '_city', false, ''),
		'map' => estatein_field('office_' . $i . '_map_url', false, '#'),
		'image' => estatein_image('office_' . $i . '_image', 'medium', false, 'office-card-image'),
	];
}
?>
<section class="content-section contact-offices-section" id="offices">
	<div class="container-xl">
		<div class="section-heading-row section-heading-row--stack">
			<div>
				<?php echo estatein_kicker(estatein_field('contact_offices_eyebrow', false, 'Offices')); ?>
				<h2><?php echo esc_html(estatein_field('contact_offices_heading', false, 'Discover Our Office Locations')); ?></h2>
				<p><?php echo esc_html(estatein_field('contact_offices_description', false, 'Estatein is here to serve you across multiple locations. Explore the offices below to find the Estatein office nearest to you.')); ?></p>
			</div>
		</div>

		<?php if ($offices) : ?>
			<div class="row g-4">
				<?php foreach ($offices as $office) : ?>
					<div class="col-md-6 col-lg-4">
						<article class="office-card surface-card h-100">
							<?php if ($office['image']) : ?>
								<div class="office-card-media"><?php echo $office['image']; ?></div>
							<?php endif; ?>
							<div class="office-card-body">
								<?php if ($office['city']) : ?>
									<span class="office-card-badge"><?php echo esc_html($office['city']); ?></span>
								<?php endif; ?>
								<h3><?php echo esc_html($office['title']); ?></h3>
								<p class="office-card-address"><?php echo esc_html($office['address']); ?></p>
								<p><?php echo esc_html($office['description']); ?></p>
								<ul class="office-card-meta list-unstyled">
									<?php if ($office['email']) : ?>
										<li><a href="mailto:<?php echo esc_attr($office['email']); ?>"><?php echo esc_html($office['email']); ?></a></li>
									<?php endif; ?>
									<?php if ($office['phone']) : ?>
										<li><a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $office['phone'])); ?>"><?php echo esc_html($office['phone']); ?></a></li>
									<?php endif; ?>
								</ul>
								<a class="btn btn-view-all" href="<?php echo esc_url($office['map']); ?>"><?php esc_html_e('Get Direction', 'estatein'); ?></a>
							</div>
						</article>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
