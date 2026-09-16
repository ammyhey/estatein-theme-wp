<?php
/**
 * Design fallbacks for editable copy.
 *
 * Every string here is the wording from the Figma home frame. It renders only
 * when the matching ACF field is empty, so an unfinished site still looks like
 * the design instead of collapsing. The live values always win.
 *
 * Keeping them in one file means a developer can answer "where does this text
 * come from?" without grepping the templates, and `estatein_field()` picks the
 * fallback up automatically when a caller passes no explicit default.
 *
 * These are brand copy, not interface chrome, so they are deliberately not run
 * through `__()`: a translator should not be rewriting the client's marketing
 * text. Interface strings ("Read More", "Price", slider labels) are translated
 * in the templates where they appear.
 *
 * The footer copyright default is not listed here because it needs the current
 * year; `footer.php` builds it.
 *
 * @package Estatein
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Fallback values keyed by ACF field name.
 *
 * @return array<string, string|int>
 */
function estatein_content_defaults() {
	static $defaults = null;

	if (null !== $defaults) {
		return $defaults;
	}

	$defaults = [
		// Global — Estatein Settings page.
		'announcement_text'         => 'Discover Your Dream Property with Estatein',
		'announcement_link_text'    => 'Learn More',
		'header_contact_text'       => 'Contact Us',
		'footer_email_placeholder'  => 'Enter Your Email',
		'footer_privacy_text'       => 'Terms & Conditions',

		// Homepage — 01 Hero.
		'hero_heading'              => 'Discover Your Dream Property with Estatein',
		'hero_description'          => 'Your journey to finding the perfect property begins here. Explore our listings to find the home that matches your dreams.',
		'hero_primary_label'        => 'Learn More',
		'hero_secondary_label'      => 'Browse Properties',
		'hero_stat_1_number'        => '200+',
		'hero_stat_1_label'         => 'Happy Customers',
		'hero_stat_2_number'        => '10k+',
		'hero_stat_2_label'         => 'Properties For Clients',
		'hero_stat_3_number'        => '16+',
		'hero_stat_3_label'         => 'Years of Experience',
		'hero_feature_1'            => 'Find Your Dream Home',
		'hero_feature_2'            => 'Unlock Property Value',
		'hero_feature_3'            => 'Effortless Property Management',
		'hero_feature_4'            => 'Smart Investments, Informed Decisions',

		// Homepage — 02 Featured Properties.
		'featured_heading'          => 'Featured Properties',
		'featured_description'      => 'Explore our handpicked selection of featured properties. Each listing offers exceptional homes and investments ready to fulfill your unique vision.',
		'featured_button_label'     => 'View All Properties',
		'featured_count'            => 9,

		// Homepage — 03 Testimonials.
		'testimonials_heading'      => 'What Our Clients Say',
		'testimonials_description'  => 'Read the success stories and heartfelt testimonials from our valued clients. Discover why they chose Estatein for their real estate needs.',
		'testimonials_button_label' => 'View All Testimonials',
		'testimonials_count'        => 6,

		// Homepage — 04 FAQ.
		'faq_heading'               => 'Frequently Asked Questions',
		'faq_description'           => 'Find answers to common questions about Estatein’s services, property listings, and the real estate process. We’re here to provide clarity and assist you every step of the way.',
		'faq_button_label'          => "View All FAQ's",
		'faq_count'                 => 6,

		// Homepage — 05 Footer CTA.
		'footer_cta_heading'        => 'Start Your Real Estate Journey Today',
		'footer_cta_description'    => 'Your dream property is just a click away. Whether you are looking for a new home, a strategic investment, or expert real estate guidance, Estatein is here to assist you every step of the way.',
		'footer_cta_button_label'   => 'Explore Properties',
	];

	return $defaults;
}

/**
 * Fallback for a single field.
 *
 * @param string $key      ACF field name.
 * @param mixed  $fallback Returned when the field has no design default.
 * @return mixed
 */
function estatein_default($key, $fallback = '') {
	$defaults = estatein_content_defaults();

	return array_key_exists($key, $defaults) ? $defaults[ $key ] : $fallback;
}
