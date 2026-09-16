<?php
/**
 * Theme bootstrap.
 *
 * Loader only — every feature lives in inc/ so it can be found by filename.
 *
 * @package Estatein
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Theme version, read from style.css so it is declared in one place.
 */
if (!defined('ESTATEIN_VERSION')) {
	define('ESTATEIN_VERSION', wp_get_theme(get_template())->get('Version') ?: '1.0.0');
}

require_once get_template_directory() . '/inc/theme-setup.php';
require_once get_template_directory() . '/inc/assets.php';
require_once get_template_directory() . '/inc/cpt.php';
require_once get_template_directory() . '/inc/acf-fields.php';
require_once get_template_directory() . '/inc/content-defaults.php';
require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/navigation.php';
require_once get_template_directory() . '/inc/template-functions.php';
require_once get_template_directory() . '/inc/slider.php';
