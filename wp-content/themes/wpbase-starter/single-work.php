<?php
/**
 * Single Work (成功事例) Template
 */
get_header();

$location = get_post_meta(get_the_ID(), '_work_location', true);
$year     = get_post_meta(get_the_ID(), '_work_year', true);
$cats     = get_the_terms(get_the_ID(), 'work_category');
$cat_name = ($cats && !is_wp_error($cats)) ? $cats[0]->name : '';
?>

<!-- ========== PAGE HERO ========== -->
<section class="page-hero">
    <?php if (has_post_thumbnail()) : ?>
    <div class="page-hero-bg" style="background-image: url('<?php echo esc_url(eikou_mobile_img_url(get_the_post_thumbnail_url(get_the_ID(), 'full'))); ?>')"></div>
    <?php else : ?>
    <div class="page-hero-bg" style="background-image: url('<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1920&q=80')); ?>')"></div>
    <?php endif; ?>
    <div class="page-hero-overlay"></div>
    <div class="container page-hero-content">
        <?php if ($cat_name) : ?>
            <span class="section-tag"><?php echo esc_html($cat_name); ?></span>
        <?php endif; ?>
        <h1><?php the_title(); ?></h1>
        <?php if ($location || $year) : ?>
            <p><?php echo esc_html($location); ?><?php if ($location && $year) echo '｜'; ?><?php echo esc_html($year); ?></p>
        <?php endif; ?>
    </div>
</section>

<!-- ========== CONTENT ========== -->
<section class="section">
    <div class="container">
        <div class="work-detail-content">
            <?php the_content(); ?>
        </div>
        <div class="section-action" style="margin-top: 60px;">
            <a href="<?php echo esc_url(eikou_page_url('works')); ?>" class="btn btn-outline">← <?php esc_html_e('すべての事例に戻る', 'eikou'); ?></a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
