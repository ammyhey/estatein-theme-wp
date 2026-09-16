<?php
/**
 * Template Name: Estatein Settings
 *
 * Editing surface for the global ACF group. It is never a public page: the
 * `template_redirect` hook in inc/theme-setup.php sends it home before the
 * template loads, and this redirect is the belt to that braces in case the hook
 * is ever removed.
 *
 * @package Estatein
 */

if (!defined('ABSPATH')) {
	exit;
}

wp_safe_redirect(home_url('/'));
exit;
