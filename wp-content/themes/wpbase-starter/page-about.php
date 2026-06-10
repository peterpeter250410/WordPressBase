<?php
/**
 * Template Name: About Page
 * Slug: about
 */
get_header();
?>

<!-- ========== PAGE HERO ========== -->
<section class="page-hero">
    <div class="page-hero-bg" style="background-image: url('https://images.unsplash.com/photo-1497366216548-37526070297c?w=1920&q=80')"></div>
    <div class="page-hero-overlay"></div>
    <div class="container page-hero-content">
        <span class="section-tag">About EIKOU</span>
        <h1>荣光について</h1>
        <p>光で、空間を未来へ導く</p>
    </div>
</section>

<!-- ========== COMPANY INTRO ========== -->
<section class="section">
    <div class="container">
        <div class="about-intro">
            <div class="about-intro-text">
                <span class="section-tag">Our Story</span>
                <h2 class="section-title" style="text-align:left;">ブランドの輝きを、空間で表現する</h2>
                <p>荣光株式会社（EIKOU Co., Ltd.）は、展示会・ブランドイベント・商業空間の企画・デザイン・施工・運営をワンストップで提供する総合プロデュース企業です。</p>
                <p>日本市場での15年以上の実績を持ち、国内外の大手企業のブランド体験を数多く手がけてきました。「光で、空間を未来へ導く」をミッションに、テクノロジーとクリエイティビティの融合で、人々の記憶に残る空間を創出しています。</p>
                <p>近年はWebサイト構築、業務システム開発、AIソリューション導入支援など、デジタル領域にも事業を拡大。オフラインとオンラインを融合した統合的なブランド体験を提供しています。</p>
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
            <h2 class="section-title">ミッション＆ビジョン</h2>
        </div>
        <div class="mv-grid">
            <div class="mv-card">
                <div class="mv-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold)" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polygon points="12,2 15,9 22,9 16,14 18,21 12,17 6,21 8,14 2,9 9,9" fill="none"/></svg>
                </div>
                <h3>Mission</h3>
                <p class="mv-main">光で、空間を未来へ導く</p>
                <p class="mv-desc">ブランドの価値を空間体験として最大化し、テクノロジーの力でビジネスの可能性を拡張する。</p>
            </div>
            <div class="mv-card">
                <div class="mv-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold)" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </div>
                <h3>Vision</h3>
                <p class="mv-main">アジアNo.1の空間体験パートナー</p>
                <p class="mv-desc">日本を拠点に、アジア全域で最も信頼される空間プロデュース企業を目指す。</p>
            </div>
        </div>
    </div>
</section>

<!-- ========== COMPANY DATA ========== -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Numbers</span>
            <h2 class="section-title">数字で見る荣光</h2>
        </div>
        <div class="numbers-grid">
            <div class="number-item">
                <span class="number-value">500<small>+</small></span>
                <span class="number-label">プロジェクト実績</span>
            </div>
            <div class="number-item">
                <span class="number-value">15<small>+</small></span>
                <span class="number-label">年の業界経験</span>
            </div>
            <div class="number-item">
                <span class="number-value">200<small>+</small></span>
                <span class="number-label">グローバル企業</span>
            </div>
            <div class="number-item">
                <span class="number-value">50<small>+</small></span>
                <span class="number-label">専門スタッフ</span>
            </div>
        </div>
    </div>
</section>

<!-- ========== COMPANY INFO ========== -->
<section class="section" style="background: var(--color-bg-alt);">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Company</span>
            <h2 class="section-title">会社概要</h2>
        </div>
        <div class="company-table">
            <table>
                <tr><th>会社名</th><td><?php echo esc_html(eikou_get('eikou_company_name', '荣光株式会社（EIKOU Co., Ltd.）')); ?></td></tr>
                <tr><th>設立</th><td>2009年</td></tr>
                <tr><th>代表者</th><td>代表取締役社長 XXX</td></tr>
                <tr><th>所在地</th><td><?php echo esc_html(eikou_get('eikou_zip', '〒XXX-XXXX')); ?> <?php echo esc_html(eikou_get('eikou_address', '東京都XX区XX X-X-X')); ?></td></tr>
                <tr><th>資本金</th><td>XXXX万円</td></tr>
                <tr><th>従業員数</th><td>50名（2024年4月現在）</td></tr>
                <tr><th>事業内容</th><td>展示会・イベント企画運営、商業空間デザイン・施工、Web/システム開発、AIソリューション</td></tr>
                <tr><th>主要取引先</th><td>SONY、TOYOTA、PANASONIC、HITACHI、NEC 他</td></tr>
            </table>
        </div>
    </div>
</section>

<?php get_footer(); ?>
