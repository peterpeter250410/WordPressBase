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
    <div class="page-hero-bg" style="background-image: url('<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1561070791-2526d30994b5?w=1920&q=80')); ?>')"></div>
    <div class="page-hero-overlay"></div>
    <div class="container page-hero-content">
        <span class="section-tag">Branding & Design</span>
        <h1><?php esc_html_e('ブランディング・デザイン', 'eikou'); ?></h1>
        <p><?php esc_html_e('ブランド価値を高めるビジュアルデザインとクリエイティブを提供します', 'eikou'); ?></p>
    </div>
</section>

<!-- ========== CATEGORY INTRO ========== -->
<section class="section">
    <div class="container">
        <div class="service-category-intro scroll-reveal">
            <p><?php esc_html_e('企業のブランドアイデンティティを構築し、あらゆる接点で一貫したブランド体験を創出します。パッケージデザイン、VI（ビジュアルアイデンティティ）設計、販促プロモーション、ポスター・カタログなどの印刷物デザインまで、ブランド価値を最大化するクリエイティブソリューションを提供します。', 'eikou'); ?></p>
        </div>
    </div>
</section>

<!-- ========== SUB-SERVICES ========== -->
<section class="section" style="background: var(--color-bg-alt);">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Services</span>
            <h2 class="section-title"><?php esc_html_e('サービス内容', 'eikou'); ?></h2>
        </div>
        <div class="service-detail-grid">
            <?php foreach ($items as $slug => $item) : ?>
            <a href="<?php echo esc_url(eikou_page_url($slug)); ?>" class="service-detail-card service-detail-card-link scroll-reveal">
                <div class="service-detail-number"><?php echo esc_html($item['number']); ?></div>
                <h3><?php echo esc_html(__($item['title'], 'eikou')); ?></h3>
                <p><?php echo esc_html(mb_substr(__($item['description'], 'eikou'), 0, 120)) . '...'; ?></p>
                <ul class="service-detail-list">
                    <?php foreach (array_slice($item['features'], 0, 4) as $f) : ?>
                        <li><?php echo esc_html(__($f, 'eikou')); ?></li>
                    <?php endforeach; ?>
                </ul>
                <ul class="business-tags">
                    <?php foreach (array_slice($item['tags'], 0, 3) as $tag) : ?>
                        <li><?php echo esc_html(__($tag, 'eikou')); ?></li>
                    <?php endforeach; ?>
                </ul>
                <span class="service-detail-more"><?php esc_html_e('詳細を見る', 'eikou'); ?> →</span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
