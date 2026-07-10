<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <?php wp_head(); ?>
</head>
<body <?php body_class('is-mobile'); ?>>
<?php wp_body_open(); ?>

<!-- ========== H5 HEADER ========== -->
<header class="h5-header" id="h5Header">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="h5-header-logo">
        <?php if (has_custom_logo()) : ?>
            <?php the_custom_logo(); ?>
        <?php else : ?>
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-eikou.png'); ?>" alt="EIKOU" class="h5-logo-image">
        <?php endif; ?>
    </a>
    <button class="h5-hamburger" id="h5Hamburger" aria-label="Menu">
        <span></span><span></span><span></span>
    </button>
</header>

<!-- ========== H5 FULL-SCREEN MENU OVERLAY ========== -->
<div class="h5-menu-overlay" id="h5MenuOverlay">
    <button class="h5-menu-close" id="h5MenuClose" aria-label="<?php esc_attr_e('閉じる', 'eikou'); ?>">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <div class="h5-menu-inner">
        <nav class="h5-menu-nav">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'items_wrap'     => '<ul>%3$s</ul>',
                'fallback_cb'    => 'eikou_fallback_menu',
            ]);
            ?>
        </nav>
        <div class="h5-menu-contact">
            <a href="tel:<?php echo esc_attr(eikou_get('eikou_tel', '03-XXXX-XXXX')); ?>" class="btn btn-outline">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                <?php echo esc_html(eikou_get('eikou_tel', '03-XXXX-XXXX')); ?>
            </a>
            <a href="<?php echo esc_url(eikou_page_url('contact')); ?>" class="btn btn-primary">お問い合わせ</a>
        </div>
        <div class="h5-menu-lang">
            <?php
            $eikou_langs = eikou_get_languages();
            if ($eikou_langs) :
                foreach ($eikou_langs as $l) :
                    printf('<a href="%s" class="%s">%s</a>',
                        esc_url($l['url']),
                        !empty($l['current_lang']) ? 'active' : '',
                        esc_html($l['name']));
                endforeach;
            else : ?>
                <a href="#" class="active">日本語</a>
                <a href="#">中文</a>
                <a href="#">English</a>
            <?php endif; ?>
        </div>
    </div>
</div>
