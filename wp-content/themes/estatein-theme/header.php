<?php
if (!defined('ABSPATH')) {
	exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php
$announcement = estatein_field('announcement_text', 'option', 'Discover Your Dream Property with Estatein');
$announcement_link = estatein_field('announcement_link_text', 'option', 'Learn More');
$announcement_url = estatein_field('announcement_link_url', 'option', home_url('/about-us/'));
$logo = estatein_field('site_logo', 'option');
?>
<div class="announcement-bar">
	<div class="container-xl">
		<div class="d-flex justify-content-center align-items-center gap-2 text-center position-relative py-2">
			<?php echo estatein_icon('sparkle', 'announce-spark'); ?>
			<span><?php echo esc_html($announcement); ?></span>
			<a href="<?php echo esc_url($announcement_url); ?>"><?php echo esc_html($announcement_link); ?></a>
			<button class="announcement-close" type="button" aria-label="<?php esc_attr_e('Close announcement', 'estatein'); ?>">
				<img src="<?php echo esc_url(get_theme_file_uri('assets/images/close-button.svg')); ?>" alt="">
			</button>
		</div>
	</div>
</div>
<header class="site-header">
	<nav class="navbar navbar-expand-lg">
		<div class="container-xl">
			<a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">
				<?php
				if ($logo) {
					echo estatein_image('site_logo', 'medium', 'option', 'site-logo img-fluid');
				} else {
					echo '<img class="site-logo img-fluid" src="' . esc_url(get_theme_file_uri('assets/images/logo.svg')) . '" alt="' . esc_attr(get_bloginfo('name')) . '">';
				}
				?>
			</a>
			<button class="hamburger navbar-toggler hamburger--elastic" type="button" data-bs-toggle="collapse" data-bs-target="#primaryNav" aria-controls="primaryNav" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle navigation', 'estatein'); ?>">
				<span class="hamburger-box"><span class="hamburger-inner"></span></span>
			</button>
			<div class="collapse navbar-collapse" id="primaryNav">
				<?php
				wp_nav_menu([
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'navbar-nav align-items-lg-center',
					'fallback_cb'    => function () {
						echo '<ul class="navbar-nav align-items-lg-center">';
						echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/')) . '">Home</a></li>';
						echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/about-us/')) . '">About Us</a></li>';
						echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(estatein_archive_url('property')) . '">Properties</a></li>';
						echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/services/')) . '">Services</a></li></ul>';
					},
				]);
				$contact_url = estatein_field('header_contact_url', 'option', home_url('/contact-us/'));
				$contact_txt = estatein_field('header_contact_text', 'option', 'Contact Us');
				echo '<a class="btn btn-outline-light ms-lg-auto mt-3 mt-lg-0" href="' . esc_url($contact_url) . '">' . esc_html($contact_txt) . '</a>';
				?>
			</div>
		</div>
	</nav>
</header>
