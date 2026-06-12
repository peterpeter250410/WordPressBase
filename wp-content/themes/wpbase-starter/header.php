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
            <a href="<?php echo esc_url(eikou_page_url('contact')); ?>" class="btn-contact">Contact</a>
        </div>
        <button class="mobile-menu-btn" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>

    <?php if (function_exists('eikou_is_mobile_device') && eikou_is_mobile_device()) : ?>
    <!-- Mobile Full-screen Menu Overlay -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay">
        <div class="mobile-menu-overlay-inner">
            <nav class="mobile-menu-nav">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container'      => false,
                    'items_wrap'     => '<ul>%3$s</ul>',
                    'fallback_cb'    => 'eikou_fallback_menu',
                ]);
                ?>
            </nav>
            <div class="mobile-menu-contact">
                <a href="tel:<?php echo esc_attr(eikou_get('eikou_tel', '03-XXXX-XXXX')); ?>" class="btn btn-outline">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <?php echo esc_html(eikou_get('eikou_tel', '03-XXXX-XXXX')); ?>
                </a>
                <a href="<?php echo esc_url(eikou_page_url('contact')); ?>" class="btn btn-primary">お問い合わせ</a>
            </div>
            <div class="mobile-menu-lang">
                <a href="#" class="active">日本語</a>
                <a href="#">中文</a>
                <a href="#">English</a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</header>
