<?php
/**
 * Template Name: About Page
 * Slug: about
 */
get_header();
?>

<!-- ========== PAGE HERO ========== -->
<section class="page-hero">
    <div class="page-hero-bg" style="background-image: url('<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1497366216548-37526070297c?w=1920&q=80')); ?>')"></div>
    <div class="page-hero-overlay"></div>
    <div class="container page-hero-content">
        <span class="section-tag">About EIKOU</span>
        <h1><?php esc_html_e('荣光について', 'eikou'); ?></h1>
        <p><?php esc_html_e('光で、空間を未来へ導く', 'eikou'); ?></p>
    </div>
</section>

<!-- ========== COMPANY INTRO ========== -->
<section class="section">
    <div class="container">
        <div class="about-intro">
            <div class="about-intro-text">
                <span class="section-tag">Our Story</span>
                <h2 class="section-title" style="text-align:left;"><?php esc_html_e('ブランドの輝きを、空間で表現する', 'eikou'); ?></h2>
                <p><?php esc_html_e('荣光株式会社（EIKOU Co., Ltd.）は、展示会・ブランドイベント・商業空間の企画・デザイン・施工・運営をワンストップで提供する総合プロデュース企業です。', 'eikou'); ?></p>
                <p><?php esc_html_e('日本市場での15年以上の実績を持ち、国内外の大手企業のブランド体験を数多く手がけてきました。「光で、空間を未来へ導く」をミッションに、テクノロジーとクリエイティビティの融合で、人々の記憶に残る空間を創出しています。', 'eikou'); ?></p>
                <p><?php esc_html_e('近年はWebサイト構築、業務システム開発、AIソリューション導入支援など、デジタル領域にも事業を拡大。オフラインとオンラインを融合した統合的なブランド体験を提供しています。', 'eikou'); ?></p>
            </div>
            <div class="about-intro-image">
                <img src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=800&q=80" alt="EIKOU Office">
            </div>
        </div>
    </div>
</section>

<!-- ========== MISSION & VISION ========== -->
<section class="section" style="background: var(--color-bg-alt);">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Mission & Vision</span>
            <h2 class="section-title"><?php esc_html_e('ミッション＆ビジョン', 'eikou'); ?></h2>
        </div>
        <div class="mv-grid">
            <div class="mv-card">
                <div class="mv-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold)" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polygon points="12,2 15,9 22,9 16,14 18,21 12,17 6,21 8,14 2,9 9,9" fill="none"/></svg>
                </div>
                <h3>Mission</h3>
                <p class="mv-main"><?php esc_html_e('光で、空間を未来へ導く', 'eikou'); ?></p>
                <p class="mv-desc"><?php esc_html_e('ブランドの価値を空間体験として最大化し、テクノロジーの力でビジネスの可能性を拡張する。', 'eikou'); ?></p>
            </div>
            <div class="mv-card">
                <div class="mv-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold)" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </div>
                <h3>Vision</h3>
                <p class="mv-main"><?php esc_html_e('アジアNo.1の空間体験パートナー', 'eikou'); ?></p>
                <p class="mv-desc"><?php esc_html_e('日本を拠点に、アジア全域で最も信頼される空間プロデュース企業を目指す。', 'eikou'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ========== COMPANY DATA ========== -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Numbers</span>
            <h2 class="section-title"><?php esc_html_e('数字で見る荣光', 'eikou'); ?></h2>
        </div>
        <div class="numbers-grid">
            <div class="number-item">
                <span class="number-value">500<small>+</small></span>
                <span class="number-label"><?php esc_html_e('プロジェクト実績', 'eikou'); ?></span>
            </div>
            <div class="number-item">
                <span class="number-value">15<small>+</small></span>
                <span class="number-label"><?php esc_html_e('年の業界経験', 'eikou'); ?></span>
            </div>
            <div class="number-item">
                <span class="number-value">200<small>+</small></span>
                <span class="number-label"><?php esc_html_e('グローバル企業', 'eikou'); ?></span>
            </div>
            <div class="number-item">
                <span class="number-value">50<small>+</small></span>
                <span class="number-label"><?php esc_html_e('専門スタッフ', 'eikou'); ?></span>
            </div>
        </div>
    </div>
</section>

<!-- ========== COMPANY INFO ========== -->
<section class="section" style="background: var(--color-bg-alt);">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Company</span>
            <h2 class="section-title"><?php esc_html_e('会社概要', 'eikou'); ?></h2>
        </div>
        <div class="company-table">
            <table>
                <tr><th><?php esc_html_e('会社名', 'eikou'); ?></th><td><?php echo esc_html(eikou_get('eikou_company_name', '荣光株式会社（EIKOU Co., Ltd.）')); ?></td></tr>
                <tr><th><?php esc_html_e('設立', 'eikou'); ?></th><td><?php esc_html_e('2009年', 'eikou'); ?></td></tr>
                <tr><th><?php esc_html_e('代表者', 'eikou'); ?></th><td><?php esc_html_e('代表取締役社長 XXX', 'eikou'); ?></td></tr>
                <tr><th><?php esc_html_e('所在地', 'eikou'); ?></th><td><?php echo esc_html(eikou_get('eikou_zip', '〒XXX-XXXX')); ?> <?php echo esc_html(eikou_get('eikou_address', '東京都XX区XX X-X-X')); ?></td></tr>
                <tr><th><?php esc_html_e('資本金', 'eikou'); ?></th><td><?php esc_html_e('XXXX万円', 'eikou'); ?></td></tr>
                <tr><th><?php esc_html_e('従業員数', 'eikou'); ?></th><td><?php esc_html_e('50名（2024年4月現在）', 'eikou'); ?></td></tr>
                <tr><th><?php esc_html_e('事業内容', 'eikou'); ?></th><td><?php esc_html_e('展示会・イベント企画運営、商業空間デザイン・施工、Web/システム開発、AIソリューション', 'eikou'); ?></td></tr>
                <tr><th><?php esc_html_e('主要取引先', 'eikou'); ?></th><td><?php esc_html_e('SONY、TOYOTA、PANASONIC、HITACHI、NEC 他', 'eikou'); ?></td></tr>
            </table>
        </div>
    </div>
</section>

<?php get_footer(); ?>
