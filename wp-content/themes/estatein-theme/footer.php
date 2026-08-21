<?php
if (!defined('ABSPATH')) {
	exit;
}
$cta_heading = 'Start Your Real Estate Journey Today';
$cta_desc = 'Your dream property is just a click away. Whether you are looking for a new home, a strategic investment, or expert real estate guidance, Estatein is here to assist you every step of the way.';
$cta_label = 'Explore Properties';
$cta_url = estatein_nav_url();

if (is_page_template('page-contact.php')) {
	$cta_heading = estatein_field('contact_cta_heading', false, $cta_heading);
	$cta_desc = estatein_field('contact_cta_description', false, $cta_desc);
	$cta_label = estatein_field('contact_cta_button_label', false, $cta_label);
	$cta_url = estatein_nav_url(estatein_field('contact_cta_button_url', false, ''));
} elseif (is_front_page()) {
	$cta_heading = estatein_field('footer_cta_heading', false, $cta_heading);
	$cta_desc = estatein_field('footer_cta_description', false, $cta_desc);
	$cta_label = estatein_field('footer_cta_button_label', false, $cta_label);
	$cta_url = estatein_nav_url(estatein_field('footer_cta_button_url', false, ''));
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
    <div class="footer-top">
        <div class="container-xl">
            <div class="d-lg-flex">
                <div class="footer-brand mb-5 mb-lg-5">
                    <a class="navbar-brand d-inline-flex" href="<?php echo esc_url(home_url('/')); ?>">
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
                <nav class="footer-nav" aria-label="<?php esc_attr_e('Footer', 'estatein'); ?>">
                    <?php foreach (estatein_footer_nav_columns() as $i => $col) :
                        $title = estatein_field('footer_col' . $i . '_title', 'option', $col['title']);
                        ?>
                        <div class="footer-nav-col footer-nav-<?php echo esc_attr($col['slug']); ?>">
                            <h3 class="footer-heading"><?php echo esc_html($title); ?></h3>
                            <ul class="list-unstyled footer-links">
                                <?php foreach ($col['links'] as $j => $default_label) :
                                    $n = $j + 1;
                                    $label = estatein_field('footer_col' . $i . '_link' . $n, 'option', $default_label);
                                    $url   = estatein_field('footer_col' . $i . '_link' . $n . '_url', 'option', '');
                                    if (!$label) {
                                        continue;
                                    }
                                    ?>
                                    <li><a href="<?php echo esc_url(estatein_nav_url($url)); ?>"><?php echo esc_html($label); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                </nav>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container-xl">
            <div class="footer-bottom-inner">
                <div class="footer-legal">
                    <span><?php echo esc_html(estatein_field('footer_copyright', 'option', '© ' . date('Y') . ' Estatein. All Rights Reserved.')); ?></span>
                    <a class="footer-legal-link" href="<?php echo esc_url(estatein_nav_url(estatein_field('footer_privacy_url', 'option', ''))); ?>"><?php echo esc_html(estatein_field('footer_privacy_text', 'option', 'Terms & Conditions')); ?></a>
                </div>
                <div class="social-links">
                    <?php
                    $socials = [
                        ['facebook', 'Facebook', estatein_field('social_link_1', 'option', '#')],
                        ['twitter', 'Twitter', estatein_field('social_link_2', 'option', '#')],
                        ['linkedin', 'LinkedIn', estatein_field('social_link_3', 'option', '#')],
                        ['youtube', 'YouTube', estatein_field('social_link_4', 'option', '#')],
                    ];
                    foreach ($socials as $social) :
                        $icon = get_theme_file_uri('assets/icons/social-' . $social[0] . '.png');
                        ?>
                        <a href="<?php echo esc_url($social[2] ?: '#'); ?>" aria-label="<?php echo esc_attr($social[1]); ?>">
                            <img src="<?php echo esc_url($icon); ?>" alt="" width="20" height="20">
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
