<?php get_header(); ?>
<main class="site-main py-5"><div class="container"><div class="row"><div class="col-12">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<article <?php post_class('mb-5'); ?>><h1 class="h2 mb-3"><?php the_title(); ?></h1><?php the_excerpt(); ?></article>
<?php endwhile; else : ?><p><?php esc_html_e('Nothing found.', 'estatein'); ?></p><?php endif; ?>
</div></div></div></main>
<?php get_footer(); ?>
