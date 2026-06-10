<?php
/**
 * Template Name: Works Page
 * Slug: works
 */
get_header();
?>

<!-- ========== PAGE HERO ========== -->
<section class="page-hero">
    <div class="page-hero-bg" style="background-image: url('https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1920&q=80')"></div>
    <div class="page-hero-overlay"></div>
    <div class="container page-hero-content">
        <span class="section-tag">Works</span>
        <h1>成功事例</h1>
        <p>これまでに手がけたプロジェクトをご紹介します</p>
    </div>
</section>

<!-- ========== FILTER + WORKS ========== -->
<section class="section">
    <div class="container">
        <!-- Filter tabs -->
        <div class="works-filter">
            <button class="filter-btn active" data-filter="all">すべて</button>
            <?php
            $work_cats = get_terms(['taxonomy' => 'work_category', 'hide_empty' => false]);
            if ($work_cats && !is_wp_error($work_cats)) :
                foreach ($work_cats as $cat) :
            ?>
            <button class="filter-btn" data-filter="<?php echo esc_attr($cat->slug); ?>"><?php echo esc_html($cat->name); ?></button>
            <?php
                endforeach;
            else :
            ?>
            <button class="filter-btn">展示会</button>
            <button class="filter-btn">イベント</button>
            <button class="filter-btn">商業空間</button>
            <button class="filter-btn">デジタル</button>
            <?php endif; ?>
        </div>

        <!-- Works grid -->
        <div class="works-grid-full">
            <?php
            $works = new WP_Query([
                'post_type'      => 'work',
                'posts_per_page' => -1,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ]);
            if ($works->have_posts()) :
                while ($works->have_posts()) : $works->the_post();
                    $location = get_post_meta(get_the_ID(), '_work_location', true);
                    $year     = get_post_meta(get_the_ID(), '_work_year', true);
                    $cats     = get_the_terms(get_the_ID(), 'work_category');
                    $cat_name = ($cats && !is_wp_error($cats)) ? $cats[0]->name : '';
                    $cat_slug = ($cats && !is_wp_error($cats)) ? $cats[0]->slug : '';
            ?>
            <div class="work-card" data-category="<?php echo esc_attr($cat_slug); ?>">
                <div class="work-image">
                    <a href="<?php the_permalink(); ?>">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('work-thumb'); ?>
                    <?php else : ?>
                        <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&q=80" alt="<?php the_title_attribute(); ?>">
                    <?php endif; ?>
                    </a>
                </div>
                <div class="work-info">
                    <?php if ($cat_name) : ?>
                        <span class="work-category"><?php echo esc_html($cat_name); ?></span>
                    <?php endif; ?>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <?php if ($location || $year) : ?>
                        <p><?php echo esc_html($location); ?><?php if ($location && $year) echo '｜'; ?><?php echo esc_html($year); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
            ?>
            <div class="work-card">
                <div class="work-image">
                    <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&q=80" alt="Exhibition booth">
                </div>
                <div class="work-info">
                    <span class="work-category">展示会</span>
                    <h3>国際展示会ブースデザイン</h3>
                    <p>東京ビッグサイト｜2024</p>
                </div>
            </div>
            <div class="work-card">
                <div class="work-image">
                    <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?w=600&q=80" alt="Brand event">
                </div>
                <div class="work-info">
                    <span class="work-category">イベント</span>
                    <h3>新製品発表会</h3>
                    <p>六本木ヒルズ｜2024</p>
                </div>
            </div>
            <div class="work-card">
                <div class="work-image">
                    <img src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=600&q=80" alt="Showroom">
                </div>
                <div class="work-info">
                    <span class="work-category">商業空間</span>
                    <h3>企業ショールーム</h3>
                    <p>銀座｜2023</p>
                </div>
            </div>
            <div class="work-card">
                <div class="work-image">
                    <img src="https://images.unsplash.com/photo-1531058020387-3be344556be6?w=600&q=80" alt="Pop-up store">
                </div>
                <div class="work-info">
                    <span class="work-category">イベント</span>
                    <h3>ブランドポップアップ</h3>
                    <p>表参道｜2024</p>
                </div>
            </div>
            <div class="work-card">
                <div class="work-image">
                    <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=600&q=80" alt="Conference">
                </div>
                <div class="work-info">
                    <span class="work-category">イベント</span>
                    <h3>年次カンファレンス</h3>
                    <p>パシフィコ横浜｜2023</p>
                </div>
            </div>
            <div class="work-card">
                <div class="work-image">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600&q=80" alt="Digital solution">
                </div>
                <div class="work-info">
                    <span class="work-category">デジタル</span>
                    <h3>AIダッシュボード開発</h3>
                    <p>オンライン｜2024</p>
                </div>
            </div>
            <div class="work-card">
                <div class="work-image">
                    <img src="https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?w=600&q=80" alt="Exhibition">
                </div>
                <div class="work-info">
                    <span class="work-category">展示会</span>
                    <h3>自動車技術展ブース</h3>
                    <p>幕張メッセ｜2023</p>
                </div>
            </div>
            <div class="work-card">
                <div class="work-image">
                    <img src="https://images.unsplash.com/photo-1559136555-9303baea8ebd?w=600&q=80" alt="Corporate event">
                </div>
                <div class="work-info">
                    <span class="work-category">商業空間</span>
                    <h3>旗艦店リニューアル</h3>
                    <p>青山｜2024</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
