<?php
/**
 * Template Name: Video Page
 * Slug: video
 */
get_header();
?>

<!-- ========== PAGE HERO ========== -->
<section class="page-hero">
    <div class="page-hero-bg" style="background-image: url('https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?w=1920&q=80')"></div>
    <div class="page-hero-overlay"></div>
    <div class="container page-hero-content">
        <span class="section-tag">Video</span>
        <h1>動画センター</h1>
        <p>プロジェクトの舞台裏と成果をご覧ください</p>
    </div>
</section>

<!-- ========== VIDEO LIST ========== -->
<section class="section">
    <div class="container">
        <div class="video-grid-full">
            <?php
            $videos = new WP_Query([
                'post_type'      => 'eikou_video',
                'posts_per_page' => -1,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ]);
            if ($videos->have_posts()) :
                while ($videos->have_posts()) : $videos->the_post();
                    $duration = get_post_meta(get_the_ID(), '_video_duration', true);
            ?>
            <div class="video-card">
                <div class="video-thumb">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('video-thumb'); ?>
                    <?php endif; ?>
                    <div class="video-play">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="23" stroke="white" stroke-width="2"/><polygon points="20,16 34,24 20,32" fill="white"/></svg>
                    </div>
                </div>
                <div class="video-info">
                    <h3><?php the_title(); ?></h3>
                    <?php if ($duration) : ?>
                        <span><?php echo esc_html($duration); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
            ?>
            <div class="video-card">
                <div class="video-thumb">
                    <img src="https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?w=600&q=80" alt="Video thumbnail">
                    <div class="video-play">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="23" stroke="white" stroke-width="2"/><polygon points="20,16 34,24 20,32" fill="white"/></svg>
                    </div>
                </div>
                <div class="video-info">
                    <h3>EIKOU プロジェクトハイライト 2024</h3>
                    <span>03:24</span>
                </div>
            </div>
            <div class="video-card">
                <div class="video-thumb">
                    <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=600&q=80" alt="Video thumbnail">
                    <div class="video-play">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="23" stroke="white" stroke-width="2"/><polygon points="20,16 34,24 20,32" fill="white"/></svg>
                    </div>
                </div>
                <div class="video-info">
                    <h3>展示会施工タイムラプス</h3>
                    <span>01:45</span>
                </div>
            </div>
            <div class="video-card">
                <div class="video-thumb">
                    <img src="https://images.unsplash.com/photo-1559136555-9303baea8ebd?w=600&q=80" alt="Video thumbnail">
                    <div class="video-play">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="23" stroke="white" stroke-width="2"/><polygon points="20,16 34,24 20,32" fill="white"/></svg>
                    </div>
                </div>
                <div class="video-info">
                    <h3>ブランドイベント メイキング</h3>
                    <span>02:30</span>
                </div>
            </div>
            <div class="video-card">
                <div class="video-thumb">
                    <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&q=80" alt="Video thumbnail">
                    <div class="video-play">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="23" stroke="white" stroke-width="2"/><polygon points="20,16 34,24 20,32" fill="white"/></svg>
                    </div>
                </div>
                <div class="video-info">
                    <h3>東京ビッグサイト展示会レポート</h3>
                    <span>04:12</span>
                </div>
            </div>
            <div class="video-card">
                <div class="video-thumb">
                    <img src="https://images.unsplash.com/photo-1531058020387-3be344556be6?w=600&q=80" alt="Video thumbnail">
                    <div class="video-play">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="23" stroke="white" stroke-width="2"/><polygon points="20,16 34,24 20,32" fill="white"/></svg>
                    </div>
                </div>
                <div class="video-info">
                    <h3>ポップアップストア設営風景</h3>
                    <span>02:15</span>
                </div>
            </div>
            <div class="video-card">
                <div class="video-thumb">
                    <img src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=600&q=80" alt="Video thumbnail">
                    <div class="video-play">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="23" stroke="white" stroke-width="2"/><polygon points="20,16 34,24 20,32" fill="white"/></svg>
                    </div>
                </div>
                <div class="video-info">
                    <h3>ショールームデザイン紹介</h3>
                    <span>03:50</span>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
