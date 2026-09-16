<?php
/**
 * ACF field groups.
 *
 * Registered in code rather than only in the ACF UI, so the content model ships
 * with the theme and survives a database that has not been exported.
 *
 * This site runs **ACF Free**: no Repeater, Gallery, Flexible Content or Options
 * Page. Two consequences are visible below:
 *
 * - Repeating hero cards are numbered fields (`hero_feature_1_*` … `_4_*`).
 * - Global settings live on a normal page using `page-estatein-settings.php`
 *   instead of an options page. `estatein_field( $key, 'option' )` resolves
 *   'option' to that page.
 *
 * Field keys and names are part of the data contract — renaming one orphans the
 * values already saved in the database.
 *
 * @package Estatein
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * One field definition.
 *
 * @param string $name  Field name; also forms the field key.
 * @param string $label Admin label.
 * @param string $type  ACF field type.
 * @param array  $extra Extra ACF settings.
 * @return array
 */
function estatein_acf_field($name, $label, $type = 'text', array $extra = []) {
	return array_merge(
		[
			'key'   => 'field_' . $name,
			'label' => $label,
			'name'  => $name,
			'type'  => $type,
		],
		$extra
	);
}

/**
 * Multi-line text field.
 */
function estatein_acf_textarea($name, $label) {
	return estatein_acf_field($name, $label, 'textarea');
}

/**
 * Number field.
 */
function estatein_acf_number($name, $label, array $extra = []) {
	return estatein_acf_field($name, $label, 'number', $extra);
}

/**
 * URL field, with the parking rule spelled out for editors.
 *
 * Every destination other than the homepage renders as "#" while the inner pages
 * are unbuilt, so the field would otherwise look broken. See inc/navigation.php.
 */
function estatein_acf_url($name, $label) {
	return estatein_acf_field($name, $label, 'url', [
		'instructions' => __('The inner pages are not published yet, so this link renders as "#" on the front end. Enter the homepage URL if it should point home.', 'estatein'),
	]);
}

/**
 * Image field returning the full attachment array.
 */
function estatein_acf_image($name, $label) {
	return estatein_acf_field($name, $label, 'image', [
		'return_format' => 'array',
		'preview_size'  => 'medium',
		'library'       => 'all',
	]);
}

/**
 * A single location rule.
 *
 * @param string $param ACF location parameter, e.g. 'post_type'.
 * @param string $value Value to match.
 * @return array
 */
function estatein_acf_location($param, $value) {
	return [
		'param'    => $param,
		'operator' => '==',
		'value'    => $value,
	];
}

/**
 * Register one field group.
 *
 * @param string $key      Group key suffix; the stored key becomes "group_{$key}".
 * @param string $title    Admin heading.
 * @param array  $location Single location rule from estatein_acf_location().
 * @param array  $fields   Field definitions.
 */
function estatein_acf_group($key, $title, array $location, array $fields) {
	acf_add_local_field_group([
		'key'      => 'group_' . $key,
		'title'    => $title,
		'fields'   => $fields,
		'location' => [[$location]],
		'position' => 'normal',
		'style'    => 'seamless',
		'active'   => true,
	]);
}

/**
 * Fields for one hero feature card.
 *
 * Numbered rather than a Repeater because this site is on ACF Free.
 *
 * @param int $index Card number, 1-4.
 * @return array
 */
function estatein_acf_hero_feature_fields($index) {
	return [
		/* translators: %d: hero feature card number. */
		estatein_acf_field('hero_feature_' . $index, sprintf(__('Feature Card %d Title', 'estatein'), $index)),
		/* translators: %d: hero feature card number. */
		estatein_acf_url('hero_feature_' . $index . '_url', sprintf(__('Feature Card %d URL', 'estatein'), $index)),
		/* translators: %d: hero feature card number. */
		estatein_acf_image('hero_feature_' . $index . '_icon', sprintf(__('Feature Card %d Icon', 'estatein'), $index)),
	];
}

/**
 * Fields for one hero statistic.
 *
 * @param int $index Stat number, 1-3.
 * @return array
 */
function estatein_acf_hero_stat_fields($index) {
	return [
		/* translators: %d: hero statistic number. */
		estatein_acf_field('hero_stat_' . $index . '_number', sprintf(__('Stat %d Number', 'estatein'), $index)),
		/* translators: %d: hero statistic number. */
		estatein_acf_field('hero_stat_' . $index . '_label', sprintf(__('Stat %d Label', 'estatein'), $index)),
	];
}

/**
 * Heading, description and button fields shared by the three carousel sections.
 *
 * @param string $prefix     Field name prefix, e.g. 'featured'.
 * @param string $count_label Label for the item-count field.
 * @return array
 */
function estatein_acf_section_fields($prefix, $count_label) {
	return [
		estatein_acf_field($prefix . '_heading', __('Heading', 'estatein')),
		estatein_acf_textarea($prefix . '_description', __('Description', 'estatein')),
		estatein_acf_field($prefix . '_button_label', __('Button Label', 'estatein')),
		estatein_acf_url($prefix . '_button_url', __('Button URL', 'estatein')),
		estatein_acf_number($prefix . '_count', $count_label, ['min' => 1]),
	];
}

/**
 * Global settings, edited on the Estatein Settings page.
 */
function estatein_register_global_fields() {
	estatein_acf_group(
		'global',
		__('Estatein — Global Settings', 'estatein'),
		estatein_acf_location('page_template', 'page-estatein-settings.php'),
		[
			estatein_acf_image('site_logo', __('Logo', 'estatein')),

			estatein_acf_field('announcement_text', __('Announcement Text', 'estatein')),
			estatein_acf_field('announcement_link_text', __('Announcement Link Text', 'estatein')),
			estatein_acf_url('announcement_link_url', __('Announcement Link URL', 'estatein')),

			estatein_acf_field('header_contact_text', __('Header Contact Button', 'estatein')),
			estatein_acf_url('header_contact_url', __('Header Contact URL', 'estatein')),

			estatein_acf_field('footer_email_placeholder', __('Footer Email Placeholder', 'estatein')),
			estatein_acf_textarea('footer_newsletter_form_shortcode', __('Footer Newsletter Ninja Form Shortcode', 'estatein')),

			estatein_acf_field('footer_copyright', __('Footer Copyright', 'estatein')),
			estatein_acf_field('footer_privacy_text', __('Privacy Link Text', 'estatein')),
			estatein_acf_url('footer_privacy_url', __('Privacy URL', 'estatein')),

			estatein_acf_url('social_link_1', __('Social Link 1 — Facebook', 'estatein')),
			estatein_acf_url('social_link_2', __('Social Link 2 — Twitter', 'estatein')),
			estatein_acf_url('social_link_3', __('Social Link 3 — LinkedIn', 'estatein')),
			estatein_acf_url('social_link_4', __('Social Link 4 — YouTube', 'estatein')),
		]
	);
}

/**
 * Homepage section groups, all located on the front page.
 */
function estatein_register_homepage_fields() {
	$front_page = estatein_acf_location('page_type', 'front_page');

	$hero = [
		estatein_acf_textarea('hero_heading', __('Heading', 'estatein')),
		estatein_acf_textarea('hero_description', __('Description', 'estatein')),
		estatein_acf_field('hero_primary_label', __('Primary Button Label', 'estatein')),
		estatein_acf_url('hero_primary_url', __('Primary Button URL', 'estatein')),
		estatein_acf_field('hero_secondary_label', __('Secondary Button Label', 'estatein')),
		estatein_acf_url('hero_secondary_url', __('Secondary Button URL', 'estatein')),
	];

	for ($i = 1; $i <= 3; $i++) {
		$hero = array_merge($hero, estatein_acf_hero_stat_fields($i));
	}

	$hero[] = estatein_acf_image('hero_image', __('Hero Image', 'estatein'));
	$hero[] = estatein_acf_image('hero_orbit_image', __('Hero Circle Badge Image', 'estatein'));
	$hero[] = estatein_acf_url('hero_orbit_url', __('Hero Circle Badge URL', 'estatein'));

	for ($i = 1; $i <= 4; $i++) {
		$hero = array_merge($hero, estatein_acf_hero_feature_fields($i));
	}

	$hero[] = estatein_acf_image('hero_feature_arrow', __('Feature Card Arrow Icon', 'estatein'));

	estatein_acf_group('home_hero', __('Homepage — 01 Hero Banner', 'estatein'), $front_page, $hero);

	estatein_acf_group(
		'home_featured',
		__('Homepage — 02 Featured Properties', 'estatein'),
		$front_page,
		estatein_acf_section_fields('featured', __('Carousel item count', 'estatein'))
	);

	estatein_acf_group(
		'home_testimonials',
		__('Homepage — 03 Testimonials', 'estatein'),
		$front_page,
		estatein_acf_section_fields('testimonials', __('Number of Testimonials', 'estatein'))
	);

	estatein_acf_group(
		'home_faq',
		__('Homepage — 04 FAQ', 'estatein'),
		$front_page,
		estatein_acf_section_fields('faq', __('Number of FAQs', 'estatein'))
	);

	estatein_acf_group(
		'home_cta',
		__('Homepage — 05 Footer Banner / CTA', 'estatein'),
		$front_page,
		[
			estatein_acf_field('footer_cta_heading', __('Heading', 'estatein')),
			estatein_acf_textarea('footer_cta_description', __('Description', 'estatein')),
			estatein_acf_field('footer_cta_button_label', __('Button Label', 'estatein')),
			estatein_acf_url('footer_cta_button_url', __('Button URL', 'estatein')),
		]
	);
}

/**
 * CPT groups.
 *
 * Deliberately thin. Anything a card shows that WordPress already models stays
 * in core fields (title, excerpt, featured image) or taxonomies (bedrooms,
 * bathrooms, property type), so editors get native UI and admin columns.
 */
function estatein_register_cpt_fields() {
	estatein_acf_group(
		'property',
		__('Property — Card', 'estatein'),
		estatein_acf_location('post_type', 'property'),
		[
			estatein_acf_number('price', __('Price', 'estatein'), ['min' => 0]),
		]
	);

	estatein_acf_group(
		'faq',
		__('FAQ — Detail', 'estatein'),
		estatein_acf_location('post_type', 'faq'),
		[
			estatein_acf_textarea('faq_short_answer', __('Short Answer', 'estatein')),
		]
	);

	estatein_acf_group(
		'testimonial',
		__('Testimonial — Detail', 'estatein'),
		estatein_acf_location('post_type', 'testimonial'),
		[
			estatein_acf_field('client_name', __('Client Name', 'estatein')),
			estatein_acf_field('client_location', __('Client Location', 'estatein')),
			estatein_acf_number('rating', __('Rating', 'estatein'), ['min' => 0, 'max' => 5]),
			estatein_acf_image('client_photo', __('Client Photo', 'estatein')),
			estatein_acf_textarea('testimonial_quote', __('Quote', 'estatein')),
		]
	);
}

/**
 * Register every group once ACF is ready.
 */
function estatein_register_acf_fields() {
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	estatein_register_global_fields();
	estatein_register_homepage_fields();
	estatein_register_cpt_fields();
}
add_action('acf/init', 'estatein_register_acf_fields');
