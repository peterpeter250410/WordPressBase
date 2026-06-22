<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ========== HEADER ========== -->
<header class="site-header">
    <div class="container header-inner">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="logo">
            <?php if (has_custom_logo()) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-eikou.png'); ?>" alt="EIKOU" class="logo-image">
            <?php endif; ?>
        </a>
        <nav class="main-nav">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'items_wrap'     => '<ul>%3$s</ul>',
                'fallback_cb'    => 'eikou_fallback_menu',
            ]);
            ?>
        </nav>
        <div class="header-right">
            <div class="lang-dropdown">
                <button class="lang-current">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10A15.3 15.3 0 0 1 12 2z"/></svg>
                    <span>日本語</span>
                    <svg class="arrow" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <ul class="lang-menu">
                    <li><a href="#" class="active">日本語</a></li>
                    <li><a href="#">中文</a></li>
                    <li><a href="#">English</a></li>
                </ul>
            </div>
            <a href="<?php echo esc_url(eikou_page_url('contact')); ?>" class="btn-contact" aria-label="Contact" title="お問い合わせ">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </a>
        </div>
        <button class="mobile-menu-btn" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>

