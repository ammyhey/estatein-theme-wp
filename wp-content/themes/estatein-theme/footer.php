<?php
/**
 * Footer CTA banner, footer navigation, newsletter and legal bar.
 *
 * The CTA copy belongs to the homepage ("Homepage — 05 Footer Banner / CTA"), so
 * it is read from the front page ID even on other templates. Everything else
 * comes from the Estatein Settings page.
 *
 * @package Estatein
 */

if (!defined('ABSPATH')) {
	exit;
}

$front_id = (int) get_option('page_on_front');

$cta_url = estatein_nav_url(estatein_field('footer_cta_button_url', $front_id, ''));

/* translators: %s: current year. */
$copyright_default = sprintf(__('© %s Estatein. All Rights Reserved.', 'estatein'), wp_date('Y'));

$social_links = [
	['icon' => 'social-facebook', 'label' => __('Facebook', 'estatein'), 'url' => estatein_field('social_link_1', 'option', '')],
	['icon' => 'social-twitter', 'label' => __('Twitter', 'estatein'), 'url' => estatein_field('social_link_2', 'option', '')],
	['icon' => 'social-linkedin', 'label' => __('LinkedIn', 'estatein'), 'url' => estatein_field('social_link_3', 'option', '')],
	['icon' => 'social-youtube', 'label' => __('YouTube', 'estatein'), 'url' => estatein_field('social_link_4', 'option', '')],
];
?>
<section class="footer-cta" aria-labelledby="estatein-footer-cta-heading">
	<div class="footer-cta-bg" aria-hidden="true">
		<img class="footer-cta-deco footer-cta-deco--left" src="<?php echo esc_url(get_theme_file_uri('assets/images/abstract/cta-deco-left.svg')); ?>" alt="" width="566" height="308" data-parallax="cta" data-parallax-dir="1">
		<img class="footer-cta-deco footer-cta-deco--right" src="<?php echo esc_url(get_theme_file_uri('assets/images/abstract/cta-deco-right.svg')); ?>" alt="" width="725" height="394" data-parallax="cta" data-parallax-dir="-1">
	</div>
	<div class="container-xl">
		<div class="footer-cta-inner">
			<div class="footer-cta-copy">
				<h2 id="estatein-footer-cta-heading"><?php echo esc_html(estatein_field('footer_cta_heading', $front_id)); ?></h2>
				<p><?php echo esc_html(estatein_field('footer_cta_description', $front_id)); ?></p>
			</div>
			<?php
			echo estatein_button(
				estatein_field('footer_cta_button_label', $front_id),
				$cta_url,
				'btn btn-primary footer-cta-btn'
			);
			?>
		</div>
	</div>
</section>
<footer class="site-footer">
	<div class="footer-top">
		<div class="container-xl">
			<div class="d-lg-flex">
				<div class="footer-brand mb-5 mb-lg-5">
					<a class="navbar-brand d-inline-flex" href="<?php echo esc_url(home_url('/')); ?>">
						<img class="site-logo img-fluid" src="<?php echo esc_url(get_theme_file_uri('assets/images/logo.svg')); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" width="161" height="48" loading="lazy" decoding="async">
					</a>
					<div class="newsletter-form" data-icon-email="<?php echo esc_url(estatein_asset_uri('assets/icons/newsletter-email.png')); ?>" data-icon-send="<?php echo esc_url(estatein_asset_uri('assets/icons/newsletter-send.png')); ?>">
						<?php
						estatein_render_ninja_form('footer_newsletter_form_shortcode');

						// Non-submitting stand-in so the footer still matches Figma before a form is chosen.
						if (!estatein_field('footer_newsletter_form_shortcode', 'option', '')) :
							?>
							<form class="newsletter-field" action="#" method="post" onsubmit="return false;">
								<label class="visually-hidden" for="estatein-newsletter"><?php esc_html_e('Email', 'estatein'); ?></label>
								<input class="form-control" id="estatein-newsletter" type="email" placeholder="<?php echo esc_attr(estatein_field('footer_email_placeholder', 'option')); ?>" autocomplete="email">
								<button class="newsletter-send" type="submit" aria-label="<?php esc_attr_e('Subscribe', 'estatein'); ?>">
									<img src="<?php echo esc_url(get_theme_file_uri('assets/icons/newsletter-send.png')); ?>" alt="" width="24" height="24" aria-hidden="true">
								</button>
							</form>
						<?php endif; ?>
					</div>
				</div>
				<nav class="footer-nav" aria-label="<?php esc_attr_e('Footer', 'estatein'); ?>">
					<?php
					wp_nav_menu([
						'theme_location' => 'footer',
						'container'      => false,
						'items_wrap'     => '%3$s',
						'depth'          => 2,
						'fallback_cb'    => 'estatein_footer_menu_fallback',
						'walker'         => new Estatein_Footer_Nav_Walker(),
					]);
					?>
				</nav>
			</div>
		</div>
	</div>
	<div class="footer-bottom">
		<div class="container-xl">
			<div class="footer-bottom-inner">
				<div class="footer-legal">
					<span><?php echo esc_html(estatein_field('footer_copyright', 'option', $copyright_default)); ?></span>
					<a class="footer-legal-link" href="<?php echo esc_url(estatein_nav_url(estatein_field('footer_privacy_url', 'option', ''))); ?>"><?php echo esc_html(estatein_field('footer_privacy_text', 'option')); ?></a>
				</div>
				<div class="social-links">
					<?php foreach ($social_links as $social) : ?>
						<a href="<?php echo esc_url($social['url'] ?: ESTATEIN_NAV_PLACEHOLDER); ?>" aria-label="<?php echo esc_attr($social['label']); ?>">
							<?php echo estatein_asset_icon($social['icon'], '', 20, 20); ?>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
