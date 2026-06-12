<?php
/**
 * Template Name: Service Item Detail
 * 通用サービス詳細ページテンプレート
 * slug から eikou_get_service_items() のデータを取得して表示
 */
get_header();

$slug  = get_post_field('post_name', get_the_ID());
$items = eikou_get_service_items();
$item  = isset($items[$slug]) ? $items[$slug] : null;

if (!$item) {
    // Fallback: 404-style message
    ?>
    <section class="page-hero">
        <div class="page-hero-bg" style="background-image: url('https://images.unsplash.com/photo-1497366216548-37526070297c?w=1920&q=80')"></div>
        <div class="page-hero-overlay"></div>
        <div class="container page-hero-content">
            <h1>サービスが見つかりません</h1>
        </div>
    </section>
    <?php
    get_footer();
    return;
}
?>

<!-- ========== PAGE HERO ========== -->
<section class="page-hero">
    <div class="page-hero-bg" style="background-image: url('<?php echo esc_url($item['hero_image']); ?>')"></div>
    <div class="page-hero-overlay"></div>
    <div class="container page-hero-content">
        <span class="section-tag"><?php echo esc_html($item['title_en']); ?></span>
        <h1><?php echo esc_html($item['title']); ?></h1>
        <p class="page-hero-breadcrumb">
            <a href="<?php echo esc_url(eikou_page_url('services')); ?>">サービス</a>
            <span> / </span>
            <a href="<?php echo esc_url(eikou_page_url($item['category'])); ?>"><?php echo esc_html($item['category_name']); ?></a>
            <span> / </span>
            <span><?php echo esc_html($item['title']); ?></span>
        </p>
    </div>
</section>

<!-- ========== SERVICE OVERVIEW ========== -->
<section class="section">
    <div class="container">
        <div class="service-item-overview scroll-reveal">
            <div class="service-item-number-bg"><?php echo esc_html($item['number']); ?></div>
            <p class="service-item-desc"><?php echo esc_html($item['description']); ?></p>
            <ul class="business-tags">
                <?php foreach ($item['tags'] as $tag) : ?>
                    <li><?php echo esc_html($tag); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>

<!-- ========== FEATURES LIST ========== -->
<section class="section" style="background: var(--color-bg-alt);">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Features</span>
            <h2 class="section-title">サービス内容</h2>
        </div>
        <div class="service-features-grid">
            <?php foreach ($item['features'] as $i => $feature) : ?>
                <div class="service-feature-item scroll-reveal">
                    <div class="service-feature-num"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></div>
                    <p><?php echo esc_html($feature); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ========== KEY POINTS ========== -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Strengths</span>
            <h2 class="section-title">EIKOUの強み</h2>
        </div>
        <div class="service-points-grid">
            <?php foreach ($item['points'] as $i => $point) : ?>
                <div class="service-point-card scroll-reveal">
                    <div class="service-point-num"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></div>
                    <h3><?php echo esc_html($point['title']); ?></h3>
                    <p><?php echo esc_html($point['desc']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ========== BACK TO CATEGORY ========== -->
<section class="section" style="background: var(--color-bg-alt);">
    <div class="container" style="text-align: center;">
        <a href="<?php echo esc_url(eikou_page_url($item['category'])); ?>" class="btn-outline scroll-reveal">
            ← <?php echo esc_html($item['category_name']); ?>一覧に戻る
        </a>
    </div>
</section>

<?php get_footer(); ?>
