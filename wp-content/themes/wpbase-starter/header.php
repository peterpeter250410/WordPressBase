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
            <span class="logo-en">EIKOU</span>
            <span class="logo-divider"></span>
            <span class="logo-jp">荣光</span>
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
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn-contact">Contact</a>
        </div>
        <button class="mobile-menu-btn" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>
<?php
/**
 * Fallback menu if no menu assigned
 */
function eikou_fallback_menu() {
    ?>
    <ul>
        <li><a href="<?php echo esc_url(home_url('/')); ?>"<?php if (is_front_page()) echo ' class="active"'; ?>>ホーム</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>"<?php if (is_page('services')) echo ' class="active"'; ?>>サービス</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('works'))); ?>"<?php if (is_page('works')) echo ' class="active"'; ?>>成功事例</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('video'))); ?>"<?php if (is_page('video')) echo ' class="active"'; ?>>動画</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('partners'))); ?>"<?php if (is_page('partners')) echo ' class="active"'; ?>>パートナー</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>"<?php if (is_page('contact')) echo ' class="active"'; ?>>お問い合わせ</a></li>
    </ul>
    <?php
}
