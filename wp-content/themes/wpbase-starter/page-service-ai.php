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
    <div class="page-hero-bg" style="background-image: url('<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1677442136019-21780ecad995?w=1920&q=80')); ?>')"></div>
    <div class="page-hero-overlay"></div>
    <div class="container page-hero-content">
        <span class="section-tag">AI Solutions</span>
        <h1><?php esc_html_e('AIソリューション', 'eikou'); ?></h1>
        <p><?php esc_html_e('最新AI技術で業務効率化と顧客体験の革新を実現します', 'eikou'); ?></p>
    </div>
</section>

<!-- ========== CATEGORY INTRO ========== -->
<section class="section">
    <div class="container">
        <div class="service-category-intro scroll-reveal">
            <p><?php esc_html_e('最新の人工知能技術を活用し、ビジネスの課題を解決するAIソリューションを提供します。GPT/LLMを活用したインテリジェントチャットボットの導入、カスタムAIモデルの開発、問い合わせ管理の自動化まで、企業のDX（デジタルトランスフォーメーション）を包括的にサポートします。', 'eikou'); ?></p>
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
