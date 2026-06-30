<?php
/**
 * H5 About Page
 */
include get_template_directory() . '/h5/header.php';
?>

<section class="h5-page-hero">
    <div class="h5-page-hero-bg" style="background-image: url('<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1497366216548-37526070297c?w=1920&q=80')); ?>')"></div>
    <div class="h5-page-hero-overlay"></div>
    <div class="h5-container h5-page-hero-content">
        <span class="h5-tag">About EIKOU</span>
        <h1><?php esc_html_e('荣光について', 'eikou'); ?></h1>
    </div>
</section>

<section class="h5-section">
    <div class="h5-container">
        <div class="h5-about-intro">
            <img src="<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=800&q=80', 400, 50)); ?>" alt="EIKOU Office" class="h5-about-img" loading="lazy">
            <span class="h5-tag">Our Story</span>
            <h2 class="h5-section-title" style="text-align:left;"><?php esc_html_e('ブランドの輝きを、空間で表現する', 'eikou'); ?></h2>
            <p><?php esc_html_e('荣光株式会社（EIKOU Co., Ltd.）は、展示会・ブランドイベント・商業空間の企画・デザイン・施工・運営をワンストップで提供する総合プロデュース企業です。', 'eikou'); ?></p>
            <p><?php esc_html_e('日本市場での15年以上の実績を持ち、国内外の大手企業のブランド体験を数多く手がけてきました。', 'eikou'); ?></p>
        </div>
    </div>
</section>

<section class="h5-section h5-section-alt">
    <div class="h5-container">
        <div class="h5-section-header">
            <span class="h5-tag">Mission & Vision</span>
            <h2 class="h5-section-title"><?php esc_html_e('ミッション＆ビジョン', 'eikou'); ?></h2>
        </div>
        <div class="h5-mv-list">
            <div class="h5-mv-card">
                <h3>Mission</h3>
                <p class="h5-mv-main"><?php esc_html_e('光で、空間を未来へ導く', 'eikou'); ?></p>
                <p><?php esc_html_e('ブランドの価値を空間体験として最大化し、テクノロジーの力でビジネスの可能性を拡張する。', 'eikou'); ?></p>
            </div>
            <div class="h5-mv-card">
                <h3>Vision</h3>
                <p class="h5-mv-main"><?php esc_html_e('アジアNo.1の空間体験パートナー', 'eikou'); ?></p>
                <p><?php esc_html_e('日本を拠点に、アジア全域で最も信頼される空間プロデュース企業を目指す。', 'eikou'); ?></p>
            </div>
        </div>
    </div>
</section>

<section class="h5-section">
    <div class="h5-container">
        <div class="h5-section-header">
            <span class="h5-tag">Numbers</span>
            <h2 class="h5-section-title"><?php esc_html_e('数字で見る荣光', 'eikou'); ?></h2>
        </div>
        <div class="h5-numbers-grid">
            <div class="h5-number-item"><span class="h5-number-val">500<small>+</small></span><span class="h5-number-label"><?php esc_html_e('プロジェクト実績', 'eikou'); ?></span></div>
            <div class="h5-number-item"><span class="h5-number-val">15<small>+</small></span><span class="h5-number-label"><?php esc_html_e('年の業界経験', 'eikou'); ?></span></div>
            <div class="h5-number-item"><span class="h5-number-val">200<small>+</small></span><span class="h5-number-label"><?php esc_html_e('グローバル企業', 'eikou'); ?></span></div>
            <div class="h5-number-item"><span class="h5-number-val">50<small>+</small></span><span class="h5-number-label"><?php esc_html_e('専門スタッフ', 'eikou'); ?></span></div>
        </div>
    </div>
</section>

<section class="h5-section h5-section-alt">
    <div class="h5-container">
        <div class="h5-section-header">
            <span class="h5-tag">Company</span>
            <h2 class="h5-section-title"><?php esc_html_e('会社概要', 'eikou'); ?></h2>
        </div>
        <div class="h5-company-table">
            <table>
                <tr><th><?php esc_html_e('会社名', 'eikou'); ?></th><td><?php echo esc_html(eikou_get('eikou_company_name', '荣光株式会社（EIKOU Co., Ltd.）')); ?></td></tr>
                <tr><th><?php esc_html_e('設立', 'eikou'); ?></th><td><?php esc_html_e('2009年', 'eikou'); ?></td></tr>
                <tr><th><?php esc_html_e('代表者', 'eikou'); ?></th><td><?php esc_html_e('代表取締役社長 XXX', 'eikou'); ?></td></tr>
                <tr><th><?php esc_html_e('所在地', 'eikou'); ?></th><td><?php echo esc_html(eikou_get('eikou_zip', '〒XXX-XXXX')); ?> <?php echo esc_html(eikou_get('eikou_address', '東京都XX区XX X-X-X')); ?></td></tr>
                <tr><th><?php esc_html_e('事業内容', 'eikou'); ?></th><td><?php esc_html_e('展示会・イベント企画運営、商業空間デザイン・施工、Web/システム開発、AIソリューション', 'eikou'); ?></td></tr>
            </table>
        </div>
    </div>
</section>

<?php include get_template_directory() . '/h5/footer.php'; ?>
