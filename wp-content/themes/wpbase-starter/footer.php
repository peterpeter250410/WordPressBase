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

<?php wp_footer(); ?>
</body>
</html>
