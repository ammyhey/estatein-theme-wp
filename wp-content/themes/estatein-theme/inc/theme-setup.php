<?php
/**
 * Theme supports, menus, image sizes, settings page and login screen.
 *
 * @package Estatein
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Theme supports, menu locations and the property card image size.
 */
function estatein_setup() {
	load_theme_textdomain('estatein', get_template_directory() . '/languages');

	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
	add_theme_support('custom-logo', [
		'height'      => 48,
		'width'       => 160,
		'flex-height' => true,
		'flex-width'  => true,
	]);

	register_nav_menus([
		'primary' => __('Primary Navigation', 'estatein'),
		'footer'  => __('Footer Navigation', 'estatein'),
	]);

	// Figma property card image: 864×636 at 2x.
	add_image_size('estatein-property-card', 864, 636, true);
}
add_action('after_setup_theme', 'estatein_setup');

/**
 * ID of the page used as the theme settings screen.
 *
 * ACF Free has no Options Page feature, so global fields live on a normal page
 * with the `page-estatein-settings.php` template.
 *
 * @return int 0 when the page is missing.
 */
function estatein_settings_page_id() {
	$page = get_page_by_path('estatein-settings', OBJECT, 'page');

	return $page ? (int) $page->ID : 0;
}

/**
 * Create the settings page, or repair its template assignment.
 *
 * The ACF group is located by page template, so a page whose template was reset
 * would silently lose every global field.
 *
 * @return int Page ID, or 0 on failure.
 */
function estatein_create_settings_page() {
	$existing = estatein_settings_page_id();

	if ($existing) {
		if (get_page_template_slug($existing) !== 'page-estatein-settings.php') {
			update_post_meta($existing, '_wp_page_template', 'page-estatein-settings.php');
		}

		return $existing;
	}

	$id = wp_insert_post([
		'post_title'   => __('Estatein Settings', 'estatein'),
		'post_name'    => 'estatein-settings',
		'post_status'  => 'publish',
		'post_type'    => 'page',
		'post_content' => __('Use the fields below to manage Estatein global header, footer, announcement, newsletter, and social links.', 'estatein'),
	]);

	if (is_wp_error($id) || !$id) {
		return 0;
	}

	update_post_meta($id, '_wp_page_template', 'page-estatein-settings.php');

	return (int) $id;
}
add_action('after_switch_theme', 'estatein_create_settings_page');

/**
 * Make sure an administrator always has the settings page available.
 *
 * Named rather than a closure so it can be unhooked.
 */
function estatein_ensure_settings_page() {
	if (current_user_can('manage_options')) {
		estatein_create_settings_page();
	}
}
add_action('admin_init', 'estatein_ensure_settings_page');

/**
 * Keep the settings page off the front end.
 *
 * Runs before the template loads. `page-estatein-settings.php` repeats the
 * redirect so the page stays private even if this hook is removed.
 */
function estatein_redirect_settings_page() {
	if (is_page_template('page-estatein-settings.php')) {
		wp_safe_redirect(home_url('/'));
		exit;
	}
}
add_action('template_redirect', 'estatein_redirect_settings_page');

/**
 * Logo shown on wp-login.php: the uploaded logo, or the bundled Figma SVG.
 *
 * @return string
 */
function estatein_login_logo_src() {
	$uploaded = function_exists('estatein_image_url') ? estatein_image_url('site_logo', 'full', 'option') : '';

	return $uploaded ?: get_theme_file_uri('assets/images/logo.svg');
}

/**
 * Brand the login screen with the theme logo and background token (Grey/08).
 */
function estatein_login_logo() {
	$logo = estatein_login_logo_src();
	?>
	<style>
		body.login {
			background: #141414;
		}

		#login h1 a,
		.login h1 a {
			background-image: url("<?php echo esc_url($logo); ?>");
			background-repeat: no-repeat;
			background-position: center;
			background-size: contain;
			width: 161px;
			height: 48px;
			margin-bottom: 24px;
		}
	</style>
	<?php
}
add_action('login_enqueue_scripts', 'estatein_login_logo');

/**
 * Point the login logo at the site instead of wordpress.org.
 *
 * @return string
 */
function estatein_login_headerurl() {
	return home_url('/');
}
add_filter('login_headerurl', 'estatein_login_headerurl');

/**
 * Title attribute for the login logo link.
 *
 * @return string
 */
function estatein_login_headertext() {
	return get_bloginfo('name');
}
add_filter('login_headertext', 'estatein_login_headertext');
