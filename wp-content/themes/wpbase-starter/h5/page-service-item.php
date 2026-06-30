<?php
/**
 * H5 Service Item Detail
 */
include get_template_directory() . '/h5/header.php';

$slug  = get_post_field('post_name', get_the_ID());
$items = eikou_get_service_items();
$item  = isset($items[$slug]) ? $items[$slug] : null;

if (!$item) { ?>
<section class="h5-page-hero">
    <div class="h5-page-hero-bg" style="background-image: url('<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1497366216548-37526070297c?w=1920&q=80')); ?>')"></div>
    <div class="h5-page-hero-overlay"></div>
    <div class="h5-container h5-page-hero-content"><h1><?php esc_html_e('サービスが見つかりません', 'eikou'); ?></h1></div>
</section>
<?php include get_template_directory() . '/h5/footer.php'; return; } ?>

<section class="h5-page-hero">
    <div class="h5-page-hero-bg" style="background-image: url('<?php echo esc_url(eikou_mobile_img_url($item['hero_image'])); ?>')"></div>
    <div class="h5-page-hero-overlay"></div>
    <div class="h5-container h5-page-hero-content">
        <span class="h5-tag"><?php echo esc_html($item['title_en']); ?></span>
        <h1><?php echo esc_html(__($item['title'], 'eikou')); ?></h1>
        <p class="h5-breadcrumb">
            <a href="<?php echo esc_url(eikou_page_url('services')); ?>"><?php esc_html_e('サービス', 'eikou'); ?></a> /
            <a href="<?php echo esc_url(eikou_page_url($item['category'])); ?>"><?php echo esc_html(__($item['category_name'], 'eikou')); ?></a>
        </p>
    </div>
</section>

<section class="h5-section">
    <div class="h5-container">
        <p class="h5-item-desc"><?php echo esc_html(__($item['description'], 'eikou')); ?></p>
        <div class="h5-item-tags">
            <?php foreach ($item['tags'] as $tag) : ?>
            <span><?php echo esc_html(__($tag, 'eikou')); ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="h5-section h5-section-alt">
    <div class="h5-container">
        <div class="h5-section-header">
            <span class="h5-tag">Features</span>
            <h2 class="h5-section-title"><?php esc_html_e('サービス内容', 'eikou'); ?></h2>
        </div>
        <div class="h5-features-list">
            <?php foreach ($item['features'] as $i => $feature) : ?>
            <div class="h5-feature-item">
                <div class="h5-feature-num"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></div>
                <p><?php echo esc_html(__($feature, 'eikou')); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="h5-section">
    <div class="h5-container">
        <div class="h5-section-header">
            <span class="h5-tag">Strengths</span>
            <h2 class="h5-section-title"><?php esc_html_e('EIKOUの強み', 'eikou'); ?></h2>
        </div>
        <div class="h5-points-list">
            <?php foreach ($item['points'] as $i => $point) : ?>
            <div class="h5-point-card">
                <div class="h5-point-num"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></div>
                <h3><?php echo esc_html(__($point['title'], 'eikou')); ?></h3>
                <p><?php echo esc_html(__($point['desc'], 'eikou')); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="h5-section-action">
            <a href="<?php echo esc_url(eikou_page_url($item['category'])); ?>" class="btn btn-outline">← <?php echo esc_html(__($item['category_name'], 'eikou')); ?><?php esc_html_e('一覧に戻る', 'eikou'); ?></a>
        </div>
    </div>
</section>

<?php include get_template_directory() . '/h5/footer.php'; ?>
