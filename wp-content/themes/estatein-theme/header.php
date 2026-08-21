<?php if (!defined('ABSPATH')) exit; ?>
<!doctype html><html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>><?php wp_body_open(); ?>
<?php
    // Preferred method using WP_Query
    $query = new WP_Query([
        'title'          => 'Estatein Settings',
        'post_type'      => 'page', // Adjust if it's a custom post type (e.g., 'page' or 'estatein_setting')
        'post_status'      => 'draft', // Adjust if it's a custom post type (e.g., 'page' or 'estatein_setting')
        'posts_per_page' => 1,
        'fields'         => 'ids',
    ]);

    $global_id = !empty($query->posts) ? $query->posts[0] : null;

    $announcement = estatein_field('announcement_text',$global_id,'✨ Discover Your Dream Property with Estatein');
    $announcement_link = estatein_field('announcement_link_text',$global_id,'Learn More');
    $announcement_url = estatein_field('announcement_link_url',$global_id,'#');
?>
<div class="announcement-bar">
    <div class="container-fluid px-3 px-lg-5">
        <div class="text-center position-relative">
            <span><?php echo esc_html($announcement); ?></span>
            <a href="<?php echo esc_url($announcement_url); ?>"><?php echo esc_html($announcement_link); ?></a>
            <button class="announcement-close position-absolute top-50 end-0" aria-label="Close"><img class="img-fluid" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/close-button.svg' ) ); ?>" alt="close button" /></button>
        </div>
    </div>
</div>
<header class="site-header">
    <nav class="navbar navbar-expand-lg">
        <div class="container-xl">
            <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">
                <?php
                $logo=estatein_field('site_logo',$global_id);
                if($logo)
                { echo estatein_image('site_logo','medium',$global_id,'site-logo img-fluid'); }
                else {
                ?>
                <span class="brand-mark">E</span>
                <span class="brand-text">estatein</span><?php } ?>
            </a>
            <button class="hamburger navbar-toggler hamburger--elastic"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#primaryNav"
                    aria-controls="primaryNav"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                  <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                  </span>
            </button>
            <div class="collapse navbar-collapse" id="primaryNav">
                <?php wp_nav_menu(['theme_location'=>'primary','container'=>false,'menu_class'=>'navbar-nav
                align-items-lg-center gap-lg-2','fallback_cb'=>function(){echo '
                <ul class="navbar-nav align-items-lg-center gap-lg-2 ms-lg-auto">
                    <li class="nav-item"><a class="nav-link" href="'.esc_url(home_url('/')).'">Home</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="'.esc_url(home_url('/about-us/')).'">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="'.esc_url(estatein_archive_url('property')).'">Properties</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="'.esc_url(home_url('/contact/')).'">Services</a></li>
                </ul>
                ';}]); ?>

                <?php wp_nav_menu(['theme_location'=>'header-right-menu','container'=>false,'menu_class'=>'header-right-menu p-lg-0 m-lg-0 ms-lg-auto d-none d-lg-block','fallback_cb'=>function(){echo '
                <a
                        class="btn btn-outline-light ms-lg-auto"
                        href="'. esc_url(estatein_field('header_contact_url','option',home_url('/contact/'))) .'"
                >'. esc_html(estatein_field('header_contact_text','option','Contact Us')) .'</a
                >';}]); ?>
            </div>
        </div>
    </nav>
</header>
