<?php
/**
 * H5 Single Work (成功事例) Template
 */
include get_template_directory() . '/h5/header.php';

$location = get_post_meta(get_the_ID(), '_work_location', true);
$year     = get_post_meta(get_the_ID(), '_work_year', true);
$cats     = get_the_terms(get_the_ID(), 'work_category');
$cat_name = ($cats && !is_wp_error($cats)) ? $cats[0]->name : '';
?>

<section class="h5-page-hero">
    <?php if (has_post_thumbnail()) : ?>
    <div class="h5-page-hero-bg" style="background-image: url('<?php echo esc_url(eikou_mobile_img_url(get_the_post_thumbnail_url(get_the_ID(), 'h5-hero'))); ?>')"></div>
    <?php else : ?>
    <div class="h5-page-hero-bg" style="background-image: url('<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1920&q=80')); ?>')"></div>
    <?php endif; ?>
    <div class="h5-page-hero-overlay"></div>
    <div class="h5-container h5-page-hero-content">
        <?php if ($cat_name) : ?>
            <span class="h5-tag"><?php echo esc_html($cat_name); ?></span>
        <?php endif; ?>
        <h1><?php the_title(); ?></h1>
        <?php if ($location || $year) : ?>
            <p><?php echo esc_html(__($location, 'eikou')); ?><?php if ($location && $year) echo '｜'; ?><?php echo esc_html($year); ?></p>
        <?php endif; ?>
    </div>
</section>

<section class="h5-section">
    <div class="h5-container">
        <div class="h5-work-detail-content">
            <?php the_content(); ?>
        </div>
        <div class="h5-section-action">
            <a href="<?php echo esc_url(eikou_page_url('works')); ?>" class="btn btn-outline">← <?php esc_html_e('すべての事例に戻る', 'eikou'); ?></a>
        </div>
    </div>
</section>

<?php include get_template_directory() . '/h5/footer.php'; ?>
