<?php
/**
 * H5 Works Page
 */
include get_template_directory() . '/h5/header.php';
?>

<section class="h5-page-hero">
    <div class="h5-page-hero-bg" style="background-image: url('<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1920&q=80')); ?>')"></div>
    <div class="h5-page-hero-overlay"></div>
    <div class="h5-container h5-page-hero-content">
        <span class="h5-tag">Works</span>
        <h1>成功事例</h1>
    </div>
</section>

<section class="h5-section">
    <div class="h5-container">
        <div class="h5-filter-scroll">
            <button class="h5-filter-btn active" data-filter="all">すべて</button>
            <?php
            $work_cats = get_terms(['taxonomy' => 'work_category', 'hide_empty' => false]);
            if ($work_cats && !is_wp_error($work_cats)) :
                foreach ($work_cats as $cat) : ?>
            <button class="h5-filter-btn" data-filter="<?php echo esc_attr($cat->slug); ?>"><?php echo esc_html($cat->name); ?></button>
            <?php endforeach; else : ?>
            <button class="h5-filter-btn">展示会</button>
            <button class="h5-filter-btn">イベント</button>
            <button class="h5-filter-btn">商業空間</button>
            <?php endif; ?>
        </div>

        <div class="h5-works-grid">
            <?php
            $works = new WP_Query(['post_type' => 'work', 'posts_per_page' => -1, 'orderby' => 'date', 'order' => 'DESC']);
            if ($works->have_posts()) :
                while ($works->have_posts()) : $works->the_post();
                    $cats = get_the_terms(get_the_ID(), 'work_category');
                    $cat_slug = ($cats && !is_wp_error($cats)) ? $cats[0]->slug : '';
                    $cat_name = ($cats && !is_wp_error($cats)) ? $cats[0]->name : '';
            ?>
            <a href="<?php the_permalink(); ?>" class="h5-work-card" data-category="<?php echo esc_attr($cat_slug); ?>">
                <div class="h5-work-img">
                    <?php if (has_post_thumbnail()) : the_post_thumbnail('h5-thumb');
                    else : ?><img src="<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&q=80', 400, 50)); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy"><?php endif; ?>
                </div>
                <div class="h5-work-info">
                    <?php if ($cat_name) : ?><span class="h5-work-cat"><?php echo esc_html($cat_name); ?></span><?php endif; ?>
                    <h3><?php the_title(); ?></h3>
                </div>
            </a>
            <?php endwhile; wp_reset_postdata();
            else :
                $fallbacks = [
                    ['img' => 'photo-1540575467063-178a50c2df87', 'cat' => '展示会', 'title' => '国際展示会ブースデザイン'],
                    ['img' => 'photo-1511578314322-379afb476865', 'cat' => 'イベント', 'title' => '新製品発表会'],
                    ['img' => 'photo-1497366754035-f200968a6e72', 'cat' => '商業空間', 'title' => '企業ショールーム'],
                    ['img' => 'photo-1531058020387-3be344556be6', 'cat' => 'イベント', 'title' => 'ブランドポップアップ'],
                ];
                foreach ($fallbacks as $fb) : ?>
            <div class="h5-work-card">
                <div class="h5-work-img"><img src="<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/' . $fb['img'] . '?w=600&q=80', 400, 50)); ?>" alt="<?php echo esc_attr($fb['title']); ?>" loading="lazy"></div>
                <div class="h5-work-info"><span class="h5-work-cat"><?php echo $fb['cat']; ?></span><h3><?php echo $fb['title']; ?></h3></div>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<?php include get_template_directory() . '/h5/footer.php'; ?>
