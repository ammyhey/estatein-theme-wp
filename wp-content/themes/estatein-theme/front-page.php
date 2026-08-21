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
$hero_img = estatein_image('hero_image', 'large', false, 'hero-image', __('Modern glass building', 'estatein'));
$orbit_url = estatein_image_url('hero_orbit_image', 'full', false);
$orbit_link = estatein_nav_url(estatein_field('hero_orbit_url', false, ''));
$feature_arrow = estatein_image_url('hero_feature_arrow', 'full', false);
?>
<main id="content" tabindex="-1">
	<section class="hero-section">
		<div class="hero-split">
			<div class="hero-copy order-2 order-lg-1">
				<div class="hero-content">
					<div class="hero-text">
						<h1 class="hero-reveal"><?php echo nl2br(esc_html(estatein_field('hero_heading', false, 'Discover Your Dream Property with Estatein'))); ?></h1>
						<p class="hero-reveal"><?php echo esc_html(estatein_field('hero_description', false, 'Your journey to finding the perfect property begins here. Explore our listings to find the home that matches your dreams.')); ?></p>
					</div>
					<div class="hero-actions">
						<?php echo estatein_button(estatein_field('hero_primary_label', false, 'Learn More'), estatein_nav_url(estatein_field('hero_primary_url', false, '')), 'btn btn-outline-light hero-reveal'); ?>
						<?php echo estatein_button(estatein_field('hero_secondary_label', false, 'Browse Properties'), estatein_nav_url(estatein_field('hero_secondary_url', false, '')), 'btn btn-primary hero-reveal'); ?>
					</div>
					<div class="hero-stats">
						<?php
						$stat_n = ['200+', '10k+', '16+'];
						$stat_l = ['Happy Customers', 'Properties For Clients', 'Years of Experience'];
						for ($i = 1; $i <= 3; $i++) :
							?>
							<div class="stat-card hero-reveal">
								<strong><?php echo esc_html(estatein_field('hero_stat_' . $i . '_number', false, $stat_n[ $i - 1 ])); ?></strong>
								<span><?php echo esc_html(estatein_field('hero_stat_' . $i . '_label', false, $stat_l[ $i - 1 ])); ?></span>
							</div>
						<?php endfor; ?>
					</div>
				</div>
			</div>
			<div class="hero-image-wrap order-1 order-lg-2">
				<div class="hero-image-clip">
					<?php
					if ($hero_img) {
						echo $hero_img;
					} elseif (file_exists(get_template_directory() . '/assets/images/home/hero.webp')) {
						echo '<img class="hero-image" src="' . esc_url($hero_fallback) . '" alt="' . esc_attr__('Estatein properties', 'estatein') . '">';
					} else {
						echo '<div class="hero-placeholder"></div>';
					}
					?>
				</div>
				<?php if ($orbit_url) : ?>
					<a class="hero-orbit" href="<?php echo esc_url($orbit_link); ?>" data-parallax="orbit">
						<img class="hero-orbit-img" src="<?php echo esc_url($orbit_url); ?>" alt="<?php echo esc_attr__('Discover Your Dream Property', 'estatein'); ?>" width="175" height="175" loading="lazy" decoding="async">
					</a>
				<?php endif; ?>
			</div>
		</div>
		<div class="hero-feature-bar">
			<div class="hero-feature-grid">
				<?php for ($i = 1; $i <= 4; $i++) :
					$title = estatein_field('hero_feature_' . $i, false, $feature_defaults[ $i ]);
					$url = estatein_nav_url(estatein_field('hero_feature_' . $i . '_url', false, ''));
					$icon_i = ( 2 === $i ) ? 4 : ( ( 4 === $i ) ? 2 : $i );
					$icon = estatein_image_url('hero_feature_' . $icon_i . '_icon', 'full', false);
					if (!$icon) {
						$icon = get_theme_file_uri('assets/icons/feature-' . $icon_i . '.svg');
					}
					?>
					<a class="feature-service-card card-reveal" href="<?php echo esc_url($url); ?>">
						<?php if ($icon) : ?>
							<span class="feature-service-icon" aria-hidden="true">
								<img src="<?php echo esc_url($icon); ?>" alt="" width="82" height="82" loading="lazy" decoding="async">
							</span>
						<?php endif; ?>
						<?php if ($feature_arrow) : ?>
							<span class="feature-service-arrow" aria-hidden="true">
								<img src="<?php echo esc_url($feature_arrow); ?>" alt="" width="34" height="34" loading="lazy" decoding="async">
							</span>
						<?php endif; ?>
						<span class="feature-service-title"><?php echo esc_html($title); ?></span>
					</a>
				<?php endfor; ?>
			</div>
		</div>
	</section>

	<?php get_template_part('template-parts/section', 'featured-properties'); ?>
	<?php get_template_part('template-parts/section', 'testimonials'); ?>
	<?php get_template_part('template-parts/section', 'faq'); ?>
</main>
<?php get_footer(); ?>
