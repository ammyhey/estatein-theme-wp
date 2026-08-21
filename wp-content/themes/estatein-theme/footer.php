<?php
if (!defined('ABSPATH')) {
	exit;
}
$cta_heading = 'Start Your Real Estate Journey Today';
$cta_desc = 'Your dream property is just a click away. Whether you are looking for a new home, a strategic investment, or expert real estate guidance, Estatein is here to assist you every step of the way.';
$cta_label = 'Explore Properties';
$cta_url = estatein_archive_url('property');

if (is_page_template('page-contact.php')) {
	$cta_heading = estatein_field('contact_cta_heading', false, $cta_heading);
	$cta_desc = estatein_field('contact_cta_description', false, $cta_desc);
	$cta_label = estatein_field('contact_cta_button_label', false, $cta_label);
	$cta_url = estatein_field('contact_cta_button_url', false, $cta_url);
} elseif (is_front_page()) {
	$cta_heading = estatein_field('footer_cta_heading', false, $cta_heading);
	$cta_desc = estatein_field('footer_cta_description', false, $cta_desc);
	$cta_label = estatein_field('footer_cta_button_label', false, $cta_label);
	$cta_url = estatein_field('footer_cta_button_url', false, $cta_url);
}
?>
<section class="footer-cta">
	<img class="footer-cta-deco footer-cta-deco--left" src="<?php echo esc_url(get_theme_file_uri('assets/images/abstract/cta-deco-left.svg')); ?>" alt="" width="566" height="308" aria-hidden="true">
	<img class="footer-cta-deco footer-cta-deco--right" src="<?php echo esc_url(get_theme_file_uri('assets/images/abstract/cta-deco-right.svg')); ?>" alt="" width="725" height="394" aria-hidden="true">
	<div class="container-xl">
		<div class="footer-cta-inner">
			<div class="footer-cta-copy">
				<h2><?php echo esc_html($cta_heading); ?></h2>
				<p><?php echo esc_html($cta_desc); ?></p>
			</div>
			<?php echo estatein_button($cta_label, $cta_url, 'btn btn-primary footer-cta-btn'); ?>
		</div>
	</div>
</section>
<footer class="site-footer">
	<div class="container-xl">
		<div class="row g-5">
			<div class="col-lg-4">
				<a class="navbar-brand d-inline-flex mb-4" href="<?php echo esc_url(home_url('/')); ?>">
					<img class="site-logo img-fluid" src="<?php echo esc_url(get_theme_file_uri('assets/images/logo.svg')); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
				</a>
				<div class="newsletter-form">
					<?php estatein_render_ninja_form('footer_newsletter_form_shortcode'); ?>
					<?php if (!estatein_field('footer_newsletter_form_shortcode', 'option')) : ?>
						<form class="d-flex gap-2" action="#" method="post" onsubmit="return false;">
							<label class="visually-hidden" for="estatein-newsletter"><?php esc_html_e('Email', 'estatein'); ?></label>
							<input class="form-control" id="estatein-newsletter" type="email" placeholder="<?php echo esc_attr(estatein_field('footer_email_placeholder', 'option', 'Enter Your Email')); ?>">
							<button class="btn btn-primary" type="submit" aria-label="<?php esc_attr_e('Subscribe', 'estatein'); ?>"><?php echo estatein_icon('arrow-up-right'); ?></button>
						</form>
					<?php endif; ?>
				</div>
			</div>
			<div class="col-lg-8">
				<div class="row g-4">
					<?php
					$footer_defaults = [
						1 => ['Home', ['Home', 'About Us', 'Services', 'Properties']],
						2 => ['About Us', ['Our Story', 'Our Works', 'How It Works', 'Our Team']],
						3 => ['Properties', ['Portfolio', 'Categories']],
						4 => ['Services', ['Valuation Mastery', 'Strategic Marketing', 'Negotiation Wizardry', 'Closing Success', 'Property Management']],
					];
					for ($i = 1; $i <= 4; $i++) :
						$title = estatein_field('footer_col' . $i . '_title', 'option', $footer_defaults[ $i ][0]);
						?>
						<div class="col-6 col-md-3">
							<h3 class="footer-heading"><?php echo esc_html($title); ?></h3>
							<ul class="list-unstyled footer-links">
								<?php
								$has_link = false;
								for ($j = 1; $j <= 5; $j++) {
									$label = estatein_field('footer_col' . $i . '_link' . $j, 'option');
									$url   = estatein_field('footer_col' . $i . '_link' . $j . '_url', 'option');
									if ($label) {
										$has_link = true;
										echo '<li><a href="' . esc_url($url ?: '#') . '">' . esc_html($label) . '</a></li>';
									}
								}
								if (!$has_link) {
									foreach ($footer_defaults[ $i ][1] as $label) {
										echo '<li><a href="#">' . esc_html($label) . '</a></li>';
									}
								}
								?>
							</ul>
						</div>
					<?php endfor; ?>
				</div>
			</div>
		</div>
		<hr>
		<div class="d-flex flex-column flex-md-row justify-content-between gap-3 footer-bottom">
			<div>
				<span><?php echo esc_html(estatein_field('footer_copyright', 'option', '© ' . date('Y') . ' Estatein. All Rights Reserved.')); ?></span>
				<span class="ms-md-4"><a href="<?php echo esc_url(estatein_field('footer_privacy_url', 'option', home_url('/privacy-policy/'))); ?>"><?php echo esc_html(estatein_field('footer_privacy_text', 'option', 'Terms & Conditions')); ?></a></span>
			</div>
			<div class="social-links">
				<?php
				$social = false;
				for ($i = 1; $i <= 4; $i++) {
					$url = estatein_field('social_link_' . $i, 'option');
					if ($url) {
						$social = true;
						echo '<a href="' . esc_url($url) . '" aria-label="Social link">' . estatein_icon('arrow-up-right') . '</a>';
					}
				}
				if (!$social) {
					foreach (['#', '#', '#', '#'] as $url) {
						echo '<a href="' . esc_url($url) . '" aria-label="Social">' . estatein_icon('arrow-up-right') . '</a>';
					}
				}
				?>
			</div>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
