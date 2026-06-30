<?php
/**
 * H5 Service Category Template (shared partial)
 * Variables expected: $hero_img, $cat_en, $cat_jp, $cat_desc, $items, $work_cat_slug
 */
?>
<section class="h5-page-hero">
    <div class="h5-page-hero-bg" style="background-image: url('<?php echo esc_url($hero_img); ?>')"></div>
    <div class="h5-page-hero-overlay"></div>
    <div class="h5-container h5-page-hero-content">
        <span class="h5-tag"><?php echo esc_html($cat_en); ?></span>
        <h1><?php echo esc_html(__($cat_jp, 'eikou')); ?></h1>
    </div>
</section>

<section class="h5-section">
    <div class="h5-container">
        <p class="h5-intro-text"><?php echo esc_html(__($cat_desc, 'eikou')); ?></p>
    </div>
</section>

<section class="h5-section h5-section-alt">
    <div class="h5-container">
        <div class="h5-section-header">
            <span class="h5-tag">Services</span>
            <h2 class="h5-section-title"><?php esc_html_e('サービス内容', 'eikou'); ?></h2>
        </div>
        <div class="h5-detail-list">
            <?php foreach ($items as $slug => $item) : ?>
            <a href="<?php echo esc_url(eikou_page_url($slug)); ?>" class="h5-detail-card">
                <div class="h5-detail-num"><?php echo esc_html($item['number']); ?></div>
                <div class="h5-detail-body">
                    <h3><?php echo esc_html(__($item['title'], 'eikou')); ?></h3>
                    <p><?php echo esc_html(mb_substr(__($item['description'], 'eikou'), 0, 80)) . '...'; ?></p>
                    <div class="h5-detail-tags">
                        <?php foreach (array_slice($item['tags'], 0, 3) as $tag) : ?>
                        <span><?php echo esc_html(__($tag, 'eikou')); ?></span>
                        <?php endforeach; ?>
                    </div>
                    <span class="h5-detail-more"><?php esc_html_e('詳細を見る', 'eikou'); ?> →</span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="h5-section">
    <div class="h5-container">
        <div class="h5-section-header">
            <span class="h5-tag">Works</span>
            <h2 class="h5-section-title"><?php esc_html_e('関連実績', 'eikou'); ?></h2>
        </div>
        <?php
        $works = new WP_Query([
            'post_type' => 'work', 'posts_per_page' => 4,
            'tax_query' => [['taxonomy' => 'work_category', 'field' => 'slug', 'terms' => $work_cat_slug]],
        ]);
        if ($works->have_posts()) : ?>
        <div class="h5-works-grid">
            <?php while ($works->have_posts()) : $works->the_post(); ?>
            <div class="h5-work-card">
                <div class="h5-work-img">
                    <?php if (has_post_thumbnail()) : the_post_thumbnail('h5-thumb');
                    else : ?><img src="<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&q=80', 400, 50)); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy"><?php endif; ?>
                </div>
                <div class="h5-work-info"><h3><?php the_title(); ?></h3></div>
            </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php else : ?>
        <p class="h5-empty-text"><?php esc_html_e('関連実績は準備中です。', 'eikou'); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php include get_template_directory() . '/h5/footer.php'; ?>
