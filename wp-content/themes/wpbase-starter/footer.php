<!-- ========== CTA ========== -->
<?php if (!is_page('contact') && !is_page('partners')) : ?>
<section class="section cta-section">
    <div class="cta-bg"></div>
    <div class="container cta-content">
        <h2><?php esc_html_e('プロジェクトのご相談は', 'eikou'); ?><br><?php esc_html_e('お気軽にお問い合わせください', 'eikou'); ?></h2>
        <p><?php esc_html_e('展示会出展、イベント企画、空間デザイン、Web制作、AIソリューションなど、', 'eikou'); ?><br><?php esc_html_e('お客様のニーズに合わせた最適なプランをご提案いたします。', 'eikou'); ?></p>
        <div class="cta-actions">
            <a href="<?php echo esc_url(eikou_page_url('contact')); ?>" class="btn btn-primary btn-lg"><?php esc_html_e('お問い合わせ', 'eikou'); ?></a>
            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', eikou_get('eikou_tel', '03-5876-9273'))); ?>" class="btn btn-ghost"><?php echo esc_html(eikou_get('eikou_tel', '03-5876-9273')); ?></a>
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
                <p class="footer-tagline"><?php esc_html_e('光で、空間を未来へ導く', 'eikou'); ?></p>
                <p class="footer-company"><?php echo esc_html(__(eikou_get('eikou_company_name', '荣光株式会社｜EIKOU Co., Ltd.'), 'eikou')); ?></p>
            </div>
            <div class="footer-nav">
                <div class="footer-nav-group">
                    <h4><?php esc_html_e('ナビゲーション', 'eikou'); ?></h4>
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
                    <h4><?php esc_html_e('その他', 'eikou'); ?></h4>
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
                    <h4><?php esc_html_e('お問い合わせ', 'eikou'); ?></h4>
                    <ul>
                        <li><?php echo esc_html(__(eikou_get('eikou_zip', '〒XXX-XXXX'), 'eikou')); ?></li>
                        <li><?php echo esc_html(__(eikou_get('eikou_address', '東京都XX区XX X-X-X'), 'eikou')); ?></li>
                        <li><?php esc_html_e('TEL', 'eikou'); ?>: <?php echo esc_html(eikou_get('eikou_tel', '03-5876-9273')); ?></li>
                        <li><?php esc_html_e('LINE', 'eikou'); ?>: <?php echo esc_html(eikou_get('eikou_line', 'eikoten')); ?></li>
                        <li><?php esc_html_e('WeChat', 'eikou'); ?>: <?php echo esc_html(eikou_get('eikou_wechat', 'zhanlan896')); ?></li>
                        <li><?php echo esc_html(eikou_get('eikou_email', 'liulin@eikoujp.net')); ?></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo esc_html(date('Y')); ?> EIKOU Co., Ltd. All Rights Reserved.</p>
            <div class="footer-links">
                <a href="#"><?php esc_html_e('プライバシーポリシー', 'eikou'); ?></a>
                <a href="#"><?php esc_html_e('利用規約', 'eikou'); ?></a>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
