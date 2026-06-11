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
                    $duration  = get_post_meta(get_the_ID(), '_video_duration', true);
                    $video_url = get_post_meta(get_the_ID(), '_video_url', true);
            ?>
            <div class="video-card scroll-reveal" data-video-url="<?php echo esc_attr($video_url); ?>" style="cursor:pointer;">
                <div class="video-thumb">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('video-thumb'); ?>
                    <?php else : ?>
                        <img src="https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?w=600&q=80" alt="<?php the_title_attribute(); ?>">
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
            <!-- Fallback: static video list using local files -->
            <?php
            $static_videos = [
                ['title' => 'CATL巡回展示会 - マツダ', 'file' => '宁德时代马自达巡展.mp4'],
                ['title' => 'CATL巡回展示会 - 日産', 'file' => '宁德时代日产巡展.mp4'],
                ['title' => 'CATL巡回展示会 - 三菱', 'file' => '宁德时代三菱巡展.mp4'],
                ['title' => 'CATL巡回展示会 - トヨタ', 'file' => '宁德时代丰田巡展.mp4'],
                ['title' => 'CATL巡回展示会 - ホンダ', 'file' => '宁德时代本田巡展.mp4'],
                ['title' => '高景ソーラー 新エネルギー技術発表会', 'file' => '高景日本新能源技术发布会.mp4'],
                ['title' => 'Momentum Electric Marine @ JAPAN BOAT SHOW 2026', 'file' => 'Momentum Electric Marine 亮相 JAPAN BOAT SHOW 2026.mp4'],
                ['title' => '德擎光学 展示ブース', 'file' => '德擎光学.mp4'],
                ['title' => 'HELIX ゴルフブランド Japan Golf Fair', 'file' => 'HELIX 高尔夫品牌亮相日本高尔夫展.mp4'],
                ['title' => '海辰エナジー 製品発表会', 'file' => '海辰储能产品发布会暨日本分公司成立一周年庆典.mp4'],
            ];
            $video_base_url = content_url('/uploads/videos/');
            foreach ($static_videos as $sv) :
                $full_url = $video_base_url . rawurlencode($sv['file']);
            ?>
            <div class="video-card scroll-reveal" data-video-url="<?php echo esc_attr($full_url); ?>" style="cursor:pointer;">
                <div class="video-thumb">
                    <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&q=80" alt="<?php echo esc_attr($sv['title']); ?>">
                    <div class="video-play">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="23" stroke="white" stroke-width="2"/><polygon points="20,16 34,24 20,32" fill="white"/></svg>
                    </div>
                </div>
                <div class="video-info">
                    <h3><?php echo esc_html($sv['title']); ?></h3>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ========== VIDEO MODAL ========== -->
<div class="video-modal" id="videoModal">
    <div class="video-modal-backdrop"></div>
    <div class="video-modal-content">
        <button class="video-modal-close">&times;</button>
        <video id="videoPlayer" controls playsinline>
            <source src="" type="video/mp4">
        </video>
    </div>
</div>

<?php get_footer(); ?>
