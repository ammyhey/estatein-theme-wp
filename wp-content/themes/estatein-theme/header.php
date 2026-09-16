<?php
/**
 * Announcement bar and primary navigation.
 *
 * Copy comes from the "Estatein — Global Settings" group on the Estatein Settings
 * page, read with `estatein_field( $key, 'option' )`.
 *
 * @package Estatein
 */

if (!defined('ABSPATH')) {
	exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
	<noscript>
		<style>.content-section,.footer-cta,.hero-reveal,.card-reveal{opacity:1!important;transform:none!important;animation:none!important}</style>
	</noscript>
</head>
<body <?php body_class(); ?>>
<?php
wp_body_open();

$announcement_url  = estatein_nav_url(estatein_field('announcement_link_url', 'option', ''));
$contact_url       = estatein_nav_url(estatein_field('header_contact_url', 'option', ''));
$logo              = estatein_image('site_logo', 'medium', 'option', 'site-logo img-fluid', get_bloginfo('name'), true);
?>
<aside class="announcement-bar" aria-label="<?php esc_attr_e('Announcement', 'estatein'); ?>">
	<div class="container-xl">
		<div class="announcement-bar-inner d-flex justify-content-center align-items-center gap-2 text-center position-relative py-2">
			<?php echo estatein_icon('sparkle', 'announce-spark'); ?>
			<span><?php echo esc_html(estatein_field('announcement_text', 'option')); ?></span>
			<a href="<?php echo esc_url($announcement_url); ?>"><?php echo esc_html(estatein_field('announcement_link_text', 'option')); ?></a>
			<button class="announcement-close" type="button" aria-label="<?php esc_attr_e('Close announcement', 'estatein'); ?>">
				<img src="<?php echo esc_url(get_theme_file_uri('assets/images/close-button.svg')); ?>" alt="" width="28" height="28" aria-hidden="true">
			</button>
		</div>
	</div>
</aside>
<header class="site-header">
	<nav class="navbar navbar-expand-lg" aria-label="<?php esc_attr_e('Primary', 'estatein'); ?>">
		<div class="container-xl">
			<a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">
				<?php
				if ($logo) {
					echo $logo; // Escaped by estatein_image().
				} else {
					printf(
						'<img class="site-logo img-fluid" src="%s" alt="%s" width="161" height="48">',
						esc_url(get_theme_file_uri('assets/images/logo.svg')),
						esc_attr(get_bloginfo('name'))
					);
				}
				?>
			</a>
			<button class="hamburger navbar-toggler hamburger--elastic" type="button" data-bs-toggle="collapse" data-bs-target="#primaryNav" aria-controls="primaryNav" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle navigation', 'estatein'); ?>" data-close-label="<?php esc_attr_e('Close navigation', 'estatein'); ?>">
				<span class="hamburger-box"><span class="hamburger-inner"></span></span>
			</button>
			<div class="collapse navbar-collapse" id="primaryNav">
				<?php
				wp_nav_menu([
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'navbar-nav align-items-lg-center',
					'fallback_cb'    => 'estatein_primary_menu_fallback',
				]);

				echo estatein_button(
					estatein_field('header_contact_text', 'option'),
					$contact_url,
					'btn btn-outline-light ms-lg-auto mt-3 mt-lg-0'
				);
				?>
			</div>
		</div>
	</nav>
</header>
