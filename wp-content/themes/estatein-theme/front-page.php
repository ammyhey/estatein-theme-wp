<?php
if (!defined('ABSPATH')) {
	exit;
}
get_header();

$feature_defaults = [
	1 => 'Find Your Dream Home',
	2 => 'Unlock Property Value',
	3 => 'Effortless Property Management',
	4 => 'Smart Investments, Informed Decisions',
];
$hero_fallback = get_theme_file_uri('assets/images/home/hero.webp');
$hero_img = estatein_image('hero_image', 'large', false, 'hero-image');
$orbit_url = estatein_image_url('hero_orbit_image', 'full', false);
$orbit_link = estatein_field('hero_orbit_url', false, estatein_archive_url('property'));
$feature_arrow = estatein_image_url('hero_feature_arrow', 'full', false);
?>
<main id="content">
	<section class="hero-section">
		<div class="hero-split">
			<div class="hero-copy order-2 order-lg-1">
				<div class="hero-content">
					<?php echo estatein_kicker('Welcome'); ?>
					<h1><?php echo nl2br(esc_html(estatein_field('hero_heading', false, 'Discover Your Dream Property with Estatein'))); ?></h1>
					<p><?php echo esc_html(estatein_field('hero_description', false, 'Your journey to finding the perfect property begins here. Explore our listings to find the home that matches your dreams.')); ?></p>
					<div class="d-flex flex-wrap gap-3">
						<?php echo estatein_button(estatein_field('hero_primary_label', false, 'Learn More'), estatein_field('hero_primary_url', false, home_url('/about-us/')), 'btn btn-outline-light'); ?>
						<?php echo estatein_button(estatein_field('hero_secondary_label', false, 'Browse Properties'), estatein_field('hero_secondary_url', false, estatein_archive_url('property')), 'btn btn-primary'); ?>
					</div>
					<div class="row stats g-3">
						<?php
						$stat_n = ['200+', '10k+', '16+'];
						$stat_l = ['Happy Customers', 'Properties For Clients', 'Years of Experience'];
						for ($i = 1; $i <= 3; $i++) :
							?>
							<div class="col-12 col-sm-4">
								<div class="stat-card">
									<strong><?php echo esc_html(estatein_field('hero_stat_' . $i . '_number', false, $stat_n[ $i - 1 ])); ?></strong>
									<span><?php echo esc_html(estatein_field('hero_stat_' . $i . '_label', false, $stat_l[ $i - 1 ])); ?></span>
								</div>
							</div>
						<?php endfor; ?>
					</div>
				</div>
			</div>
			<div class="hero-image-wrap order-1 order-lg-2">
				<?php
				if ($hero_img) {
					echo $hero_img;
				} elseif (file_exists(get_template_directory() . '/assets/images/home/hero.webp')) {
					echo '<img class="hero-image" src="' . esc_url($hero_fallback) . '" alt="' . esc_attr__('Estatein properties', 'estatein') . '">';
				} else {
					echo '<div class="hero-placeholder"></div>';
				}
				?>
				<?php if ($orbit_url) : ?>
					<a class="hero-orbit" href="<?php echo esc_url($orbit_link); ?>" data-parallax="orbit">
						<img class="hero-orbit-img" src="<?php echo esc_url($orbit_url); ?>" alt="<?php echo esc_attr(estatein_field('square_cta_text', false, 'Discover Your Dream Property')); ?>" width="175" height="175" loading="lazy">
					</a>
				<?php endif; ?>
			</div>
		</div>
		<div class="hero-feature-bar">
			<div class="container-xl">
				<div class="row g-3 g-xl-4">
					<?php for ($i = 1; $i <= 4; $i++) :
						$title = estatein_field('hero_feature_' . $i, false, $feature_defaults[ $i ]);
						$url = estatein_field('hero_feature_' . $i . '_url', false, home_url('/services/'));
						$icon = estatein_image_url('hero_feature_' . $i . '_icon', 'full', false);
						?>
						<div class="col-md-6 col-xl-3">
							<a class="feature-service-card" href="<?php echo esc_url($url); ?>">
								<?php if ($icon) : ?>
									<span class="feature-service-icon">
										<img src="<?php echo esc_url($icon); ?>" alt="" width="82" height="82" loading="lazy">
									</span>
								<?php endif; ?>
								<?php if ($feature_arrow) : ?>
									<span class="feature-service-arrow" aria-hidden="true">
										<img src="<?php echo esc_url($feature_arrow); ?>" alt="" width="26" height="26" loading="lazy">
									</span>
								<?php endif; ?>
								<span class="feature-service-title"><?php echo esc_html($title); ?></span>
							</a>
						</div>
					<?php endfor; ?>
				</div>
			</div>
		</div>
	</section>

	<?php get_template_part('template-parts/section', 'featured-properties'); ?>
	<?php get_template_part('template-parts/section', 'testimonials'); ?>
	<?php get_template_part('template-parts/section', 'faq'); ?>
</main>
<?php get_footer(); ?>
