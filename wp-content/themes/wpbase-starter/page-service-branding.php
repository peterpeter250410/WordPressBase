<?php
/**
 * Template Name: Service - ブランディング・デザイン
 * Slug: service-branding
 */
get_header();

$all_items = eikou_get_service_items();
$items = array_filter($all_items, function($v) { return $v['category'] === 'service-branding'; });
?>

<!-- ========== PAGE HERO ========== -->
<section class="page-hero">
    <div class="page-hero-bg" style="background-image: url('https://images.unsplash.com/photo-1561070791-2526d30994b5?w=1920&q=80')"></div>
    <div class="page-hero-overlay"></div>
    <div class="container page-hero-content">
        <span class="section-tag">Branding & Design</span>
        <h1>ブランディング・デザイン</h1>
        <p>ブランド価値を高めるビジュアルデザインとクリエイティブを提供します</p>
    </div>
</section>

<!-- ========== CATEGORY INTRO ========== -->
<section class="section">
    <div class="container">
        <div class="service-category-intro scroll-reveal">
            <p>企業のブランドアイデンティティを構築し、あらゆる接点で一貫したブランド体験を創出します。パッケージデザイン、VI（ビジュアルアイデンティティ）設計、販促プロモーション、ポスター・カタログなどの印刷物デザインまで、ブランド価値を最大化するクリエイティブソリューションを提供します。</p>
        </div>
    </div>
</section>

<!-- ========== SUB-SERVICES ========== -->
<section class="section" style="background: var(--color-bg-alt);">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Services</span>
            <h2 class="section-title">サービス内容</h2>
        </div>
        <div class="service-detail-grid">
            <?php foreach ($items as $slug => $item) : ?>
            <a href="<?php echo esc_url(eikou_page_url($slug)); ?>" class="service-detail-card service-detail-card-link scroll-reveal">
                <div class="service-detail-number"><?php echo esc_html($item['number']); ?></div>
                <h3><?php echo esc_html($item['title']); ?></h3>
                <p><?php echo mb_substr($item['description'], 0, 120) . '...'; ?></p>
                <ul class="service-detail-list">
                    <?php foreach (array_slice($item['features'], 0, 4) as $f) : ?>
                        <li><?php echo esc_html($f); ?></li>
                    <?php endforeach; ?>
                </ul>
                <ul class="business-tags">
                    <?php foreach (array_slice($item['tags'], 0, 3) as $tag) : ?>
                        <li><?php echo esc_html($tag); ?></li>
                    <?php endforeach; ?>
                </ul>
                <span class="service-detail-more">詳細を見る →</span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
