<!-- ========== CTA ========== -->
<?php if (!is_page('contact') && !is_page('partners')) : ?>
<section class="section cta-section">
    <div class="cta-bg"></div>
    <div class="container cta-content">
        <h2>プロジェクトのご相談は<br>お気軽にお問い合わせください</h2>
        <p>展示会出展、イベント企画、空間デザイン、Web制作、AIソリューションなど、<br>お客様のニーズに合わせた最適なプランをご提案いたします。</p>
        <div class="cta-actions">
            <a href="<?php echo esc_url(eikou_page_url('contact')); ?>" class="btn btn-primary btn-lg">お問い合わせ</a>
            <a href="tel:+81-3-XXXX-XXXX" class="btn btn-ghost"><?php echo esc_html(eikou_get('eikou_tel', '03-XXXX-XXXX')); ?></a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ========== FOOTER ========== -->
<footer class="site-footer">
    <div class="container">
        <div class="footer-top">
            <div class="footer-brand">
                <div class="footer-logo">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-eikou.png'); ?>" alt="EIKOU" class="logo-image">
                </div>
                <p class="footer-tagline">光で、空間を未来へ導く</p>
                <p class="footer-company"><?php echo esc_html(eikou_get('eikou_company_name', '荣光株式会社｜EIKOU Co., Ltd.')); ?></p>
            </div>
            <div class="footer-nav">
                <div class="footer-nav-group">
                    <h4>ナビゲーション</h4>
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'footer-nav',
                        'container'      => false,
                        'items_wrap'     => '<ul>%3$s</ul>',
                        'fallback_cb'    => 'eikou_footer_nav_fallback',
                    ]);
                    ?>
                </div>
                <div class="footer-nav-group">
                    <h4>その他</h4>
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'footer-other',
                        'container'      => false,
                        'items_wrap'     => '<ul>%3$s</ul>',
                        'fallback_cb'    => 'eikou_footer_other_fallback',
                    ]);
                    ?>
                </div>
                <div class="footer-nav-group">
                    <h4>お問い合わせ</h4>
                    <ul>
                        <li><?php echo esc_html(eikou_get('eikou_zip', '〒XXX-XXXX')); ?></li>
                        <li><?php echo esc_html(eikou_get('eikou_address', '東京都XX区XX X-X-X')); ?></li>
                        <li>TEL: <?php echo esc_html(eikou_get('eikou_tel', '03-XXXX-XXXX')); ?></li>
                        <li><?php echo esc_html(eikou_get('eikou_email', 'info@eikou.co.jp')); ?></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo esc_html(date('Y')); ?> EIKOU Co., Ltd. All Rights Reserved.</p>
            <div class="footer-links">
                <a href="#">プライバシーポリシー</a>
                <a href="#">利用規約</a>
            </div>
        </div>
    </div>
</footer>

<?php if (function_exists('eikou_is_mobile_device') && eikou_is_mobile_device()) : ?>
<!-- Mobile Bottom Tab Bar -->
<nav class="mobile-tab-bar" id="mobileTabBar">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="tab-item" data-tab="home">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
            <polyline points="9 22 9 12 15 12 15 22"/>
        </svg>
        <span>ホーム</span>
    </a>
    <a href="<?php echo esc_url(eikou_page_url('services')); ?>" class="tab-item" data-tab="services">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
            <line x1="8" y1="21" x2="16" y2="21"/>
            <line x1="12" y1="17" x2="12" y2="21"/>
        </svg>
        <span>サービス</span>
    </a>
    <a href="<?php echo esc_url(eikou_page_url('works')); ?>" class="tab-item" data-tab="works">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
        </svg>
        <span>実績</span>
    </a>
    <a href="<?php echo esc_url(eikou_page_url('video')); ?>" class="tab-item" data-tab="videos">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <polygon points="5 3 19 12 5 21 5 3"/>
        </svg>
        <span>動画</span>
    </a>
    <a href="<?php echo esc_url(eikou_page_url('contact')); ?>" class="tab-item" data-tab="contact">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
            <polyline points="22,6 12,13 2,6"/>
        </svg>
        <span>お問い合わせ</span>
    </a>
</nav>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
