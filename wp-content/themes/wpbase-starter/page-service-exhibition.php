<?php
/**
 * Template Name: Service - 展示会・イベント
 * Slug: service-exhibition
 */
get_header();

$all_items = eikou_get_service_items();
$items = array_filter($all_items, function($v) { return $v['category'] === 'service-exhibition'; });
?>

<!-- ========== PAGE HERO ========== -->
<section class="page-hero">
    <div class="page-hero-bg" style="background-image: url('<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1920&q=80')); ?>')"></div>
    <div class="page-hero-overlay"></div>
    <div class="container page-hero-content">
        <span class="section-tag">Exhibition & Events</span>
        <h1><?php esc_html_e('展示会・イベント', 'eikou'); ?></h1>
        <p><?php esc_html_e('展示会の企画・設計・施工・運営まで、ワンストップで対応いたします', 'eikou'); ?></p>
    </div>
</section>

<!-- ========== CATEGORY INTRO ========== -->
<section class="section">
    <div class="container">
        <div class="service-category-intro scroll-reveal">
            <p><?php esc_html_e('EIKOUは、展示会・イベントにおけるあらゆるニーズにお応えします。ブースの企画・デザインから、構造物の制作・施工、機材の手配、物流管理、そして当日の現場運営まで——。東京ビッグサイト、幕張メッセをはじめとする日本全国の主要会場での豊富な実績を活かし、お客様の出展を成功へ導きます。', 'eikou'); ?></p>
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

<!-- ========== RELATED WORKS ========== -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Works</span>
            <h2 class="section-title"><?php esc_html_e('関連実績', 'eikou'); ?></h2>
        </div>
        <?php
        $works = new WP_Query([
            'post_type'      => 'work',
            'posts_per_page' => 3,
            'tax_query'      => [
                [
                    'taxonomy' => 'work_category',
                    'field'    => 'slug',
                    'terms'    => 'exhibition',
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
                        <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&q=80" alt="<?php the_title_attribute(); ?>">
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
        <p style="text-align:center; color: var(--color-text-muted);"><?php esc_html_e('関連実績は準備中です。', 'eikou'); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
