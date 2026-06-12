<?php
/**
 * Template Name: Service - AIソリューション
 * Slug: service-ai
 */
get_header();

$all_items = eikou_get_service_items();
$items = array_filter($all_items, function($v) { return $v['category'] === 'service-ai'; });
?>

<!-- ========== PAGE HERO ========== -->
<section class="page-hero">
    <div class="page-hero-bg" style="background-image: url('https://images.unsplash.com/photo-1677442136019-21780ecad995?w=1920&q=80')"></div>
    <div class="page-hero-overlay"></div>
    <div class="container page-hero-content">
        <span class="section-tag">AI Solutions</span>
        <h1>AIソリューション</h1>
        <p>最新AI技術で業務効率化と顧客体験の革新を実現します</p>
    </div>
</section>

<!-- ========== CATEGORY INTRO ========== -->
<section class="section">
    <div class="container">
        <div class="service-category-intro scroll-reveal">
            <p>最新の人工知能技術を活用し、ビジネスの課題を解決するAIソリューションを提供します。GPT/LLMを活用したインテリジェントチャットボットの導入、カスタムAIモデルの開発、問い合わせ管理の自動化まで、企業のDX（デジタルトランスフォーメーション）を包括的にサポートします。</p>
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
