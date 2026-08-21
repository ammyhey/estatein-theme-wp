<?php
/**
 * Template Name: Contact
 */
if (!defined('ABSPATH')) {
	exit;
}
get_header();
?>
<main id="content" class="inner-page contact-page">
	<?php get_template_part('template-parts/contact/section', 'hero'); ?>
	<?php get_template_part('template-parts/contact/section', 'form'); ?>
	<?php get_template_part('template-parts/contact/section', 'offices'); ?>
	<?php get_template_part('template-parts/contact/section', 'gallery'); ?>
</main>
<?php get_footer(); ?>
