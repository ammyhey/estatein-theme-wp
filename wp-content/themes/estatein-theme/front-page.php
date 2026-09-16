<?php
/**
 * Homepage: hero, then the three carousel sections.
 *
 * Copy comes from the "Homepage — 01 Hero" ACF group; the fallbacks live in
 * inc/content-defaults.php.
 *
 * @package Estatein
 */

if (!defined('ABSPATH')) {
	exit;
}

get_header();

$hero_image     = estatein_image('hero_image', 'full', false, 'hero-image', __('Modern glass building', 'estatein'), true);
$hero_fallback  = get_theme_file_path('assets/images/home/hero.webp');
$orbit_url      = estatein_image_url('hero_orbit_image', 'full');
$orbit_link     = estatein_nav_url(estatein_field('hero_orbit_url', false, ''));
$feature_arrow  = estatein_image_url('hero_feature_arrow', 'full');
?>
<main id="content" tabindex="-1">
	<section class="hero-section">
		<div class="hero-split">
			<div class="hero-copy order-2 order-lg-1">
				<div class="hero-content">
					<div class="hero-text">
						<h1 class="hero-reveal"><?php echo nl2br(esc_html(estatein_field('hero_heading'))); ?></h1>
						<p class="hero-reveal"><?php echo esc_html(estatein_field('hero_description')); ?></p>
					</div>
					<div class="hero-actions">
						<?php
						echo estatein_button(
							estatein_field('hero_primary_label'),
							estatein_nav_url(estatein_field('hero_primary_url', false, '')),
							'btn btn-outline-light hero-reveal'
						);
						echo estatein_button(
							estatein_field('hero_secondary_label'),
							estatein_nav_url(estatein_field('hero_secondary_url', false, '')),
							'btn btn-primary hero-reveal'
						);
						?>
					</div>
					<div class="hero-stats">
						<?php for ($i = 1; $i <= 3; $i++) : ?>
							<div class="stat-card hero-reveal">
								<strong><?php echo esc_html(estatein_field('hero_stat_' . $i . '_number')); ?></strong>
								<span><?php echo esc_html(estatein_field('hero_stat_' . $i . '_label')); ?></span>
							</div>
						<?php endfor; ?>
					</div>
				</div>
			</div>
			<div class="hero-image-wrap order-1 order-lg-2">
				<div class="hero-image-clip">
					<?php
					if ($hero_image) {
						echo $hero_image; // Escaped by estatein_image().
					} elseif (file_exists($hero_fallback)) {
						printf(
							'<img class="hero-image" src="%s" alt="%s" fetchpriority="high" decoding="async">',
							esc_url(get_theme_file_uri('assets/images/home/hero.webp')),
							esc_attr__('Estatein properties', 'estatein')
						);
					} else {
						echo '<div class="hero-placeholder"></div>';
					}
					?>
				</div>
				<?php if ($orbit_url) : ?>
					<a class="hero-orbit" href="<?php echo esc_url($orbit_link); ?>" data-parallax="orbit">
						<img class="hero-orbit-img" src="<?php echo esc_url($orbit_url); ?>" alt="<?php esc_attr_e('Discover Your Dream Property', 'estatein'); ?>" width="175" height="175" loading="lazy" decoding="async">
					</a>
				<?php endif; ?>
			</div>
		</div>
		<div class="hero-feature-bar">
			<div class="hero-feature-grid">
				<?php
				for ($i = 1; $i <= 4; $i++) :
					$title = estatein_field('hero_feature_' . $i);
					$url   = estatein_nav_url(estatein_field('hero_feature_' . $i . '_url', false, ''));
					$icon  = estatein_image_url('hero_feature_' . $i . '_icon', 'full');

					if (!$icon) {
						$icon = get_theme_file_uri('assets/icons/feature-' . $i . '.svg');
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

	<?php
	get_template_part('template-parts/section', 'featured-properties');
	get_template_part('template-parts/section', 'testimonials');
	get_template_part('template-parts/section', 'faq');
	?>
</main>
<?php
get_footer();
