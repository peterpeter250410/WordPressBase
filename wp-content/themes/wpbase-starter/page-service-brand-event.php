<?php
/**
 * Template Name: Service - ブランドイベント
 * Slug: service-brand-event
 */
get_header();

$all_items = eikou_get_service_items();
$items = array_filter($all_items, function($v) { return $v['category'] === 'service-brand-event'; });
?>

<!-- ========== PAGE HERO ========== -->
<section class="page-hero">
    <div class="page-hero-bg" style="background-image: url('https://images.unsplash.com/photo-1511578314322-379afb476865?w=1920&q=80')"></div>
    <div class="page-hero-overlay"></div>
    <div class="container page-hero-content">
        <span class="section-tag">Brand Events</span>
        <h1>ブランドイベント</h1>
        <p>発表会・ポップアップ・プロモーションなど、ブランド体験を創出します</p>
    </div>
</section>

<!-- ========== CATEGORY INTRO ========== -->
<section class="section">
    <div class="container">
        <div class="service-category-intro scroll-reveal">
            <p>新製品発表会やプレスカンファレンス、ポップアップストア、ブランドプロモーション、全国巡回イベントなど、企業ブランドの価値を高めるイベントを企画・実行します。特に海外企業の日本市場参入を包括的にサポートし、日中英トリリンガルチームが文化・言語の壁を越えたブランド展開を実現します。</p>
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

<!-- ========== RELATED WORKS ========== -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Works</span>
            <h2 class="section-title">関連実績</h2>
        </div>
        <?php
        $works = new WP_Query([
            'post_type'      => 'work',
            'posts_per_page' => 3,
            'tax_query'      => [
                [
                    'taxonomy' => 'work_category',
                    'field'    => 'slug',
                    'terms'    => 'event',
                ],
            ],
        ]);
        if ($works->have_posts()) : ?>
        <div class="related-works-grid">
            <?php while ($works->have_posts()) : $works->the_post(); ?>
            <a href="<?php the_permalink(); ?>" class="work-card scroll-reveal">
                <div class="work-image">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('work-thumb'); ?>
                    <?php else : ?>
                        <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?w=600&q=80" alt="<?php the_title_attribute(); ?>">
                    <?php endif; ?>
                    <div class="work-overlay"></div>
                </div>
                <div class="work-info">
                    <h3><?php the_title(); ?></h3>
                    <p><?php echo wp_trim_words(get_the_excerpt(), 30); ?></p>
                </div>
            </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php else : ?>
        <p style="text-align:center; color: var(--color-text-muted);">関連実績は準備中です。</p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
