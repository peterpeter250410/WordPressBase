<!-- ========== H5 CTA ========== -->
<?php if (!is_page('contact') && !is_page('partners')) : ?>
<section class="h5-section h5-cta">
    <div class="h5-container">
        <h2>プロジェクトのご相談は<br>お気軽にお問い合わせください</h2>
        <p>展示会出展、イベント企画、空間デザインなど、お客様のニーズに合わせた最適なプランをご提案いたします。</p>
        <div class="h5-cta-actions">
            <a href="<?php echo esc_url(eikou_page_url('contact')); ?>" class="btn btn-primary">お問い合わせ</a>
            <a href="tel:<?php echo esc_attr(eikou_get('eikou_tel', '03-XXXX-XXXX')); ?>" class="btn btn-ghost"><?php echo esc_html(eikou_get('eikou_tel', '03-XXXX-XXXX')); ?></a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ========== H5 FOOTER ========== -->
<footer class="h5-footer">
    <div class="h5-container">
        <div class="h5-footer-brand">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-eikou.png'); ?>" alt="EIKOU" class="h5-footer-logo">
            <p class="h5-footer-tagline">光で、空間を未来へ導く</p>
            <p class="h5-footer-company"><?php echo esc_html(eikou_get('eikou_company_name', '荣光株式会社｜EIKOU Co., Ltd.')); ?></p>
        </div>
        <div class="h5-footer-bottom">
            <p>&copy; <?php echo esc_html(date('Y')); ?> EIKOU Co., Ltd. All Rights Reserved.</p>
            <div class="h5-footer-links">
                <a href="#">プライバシーポリシー</a>
                <a href="#">利用規約</a>
            </div>
        </div>
    </div>
</footer>

<!-- ========== H5 BOTTOM TAB BAR ========== -->
<nav class="h5-tab-bar" id="h5TabBar">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="h5-tab-item" data-tab="home">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
            <polyline points="9 22 9 12 15 12 15 22"/>
        </svg>
        <span>ホーム</span>
    </a>
    <a href="<?php echo esc_url(eikou_page_url('services')); ?>" class="h5-tab-item" data-tab="services">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
            <line x1="8" y1="21" x2="16" y2="21"/>
            <line x1="12" y1="17" x2="12" y2="21"/>
        </svg>
        <span>サービス</span>
    </a>
    <a href="<?php echo esc_url(eikou_page_url('works')); ?>" class="h5-tab-item" data-tab="works">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
        </svg>
        <span>実績</span>
    </a>
    <a href="<?php echo esc_url(eikou_page_url('video')); ?>" class="h5-tab-item" data-tab="videos">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <polygon points="5 3 19 12 5 21 5 3"/>
        </svg>
        <span>動画</span>
    </a>
    <a href="<?php echo esc_url(eikou_page_url('contact')); ?>" class="h5-tab-item" data-tab="contact">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
            <polyline points="22,6 12,13 2,6"/>
        </svg>
        <span>お問い合わせ</span>
    </a>
</nav>

<!-- ========== H5 联系浮窗（浮动按钮 + 快捷小表单）========== -->
<div class="h5-fab-overlay" id="h5FabOverlay"></div>
<div class="h5-fab-contact" id="h5FabContact">
    <div class="h5-fab-panel" id="h5FabPanel">
        <div class="h5-fab-panel-head">
            <span>お気軽にお問い合わせ</span>
            <button class="h5-fab-close" id="h5FabClose" aria-label="閉じる">&times;</button>
        </div>
        <?php $eikou_quick = eikou_render_contact_form('eikou_cf7_quick'); ?>
        <?php if ($eikou_quick) : ?>
        <div class="eikou-cf7-wrap"><?php echo $eikou_quick; ?></div>
        <?php else : ?>
        <form class="h5-fab-form" onsubmit="return false;">
            <input type="text" name="name" placeholder="お名前 *" required>
            <input type="text" name="contact" placeholder="電話番号 / メール *" required>
            <button type="submit" class="btn btn-primary">送信する</button>
        </form>
        <p class="h5-fab-note">担当者より折り返しご連絡いたします</p>
        <?php endif; ?>
    </div>
    <button class="h5-fab-btn" id="h5FabBtn" aria-label="お問い合わせ">
        <svg class="h5-fab-icon-mail" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        <svg class="h5-fab-icon-close" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
</div>

<?php wp_footer(); ?>
</body>
</html>
