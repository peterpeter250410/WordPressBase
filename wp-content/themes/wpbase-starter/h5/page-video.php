<?php
/**
 * H5 Video Page
 */
include get_template_directory() . '/h5/header.php';
?>

<section class="h5-page-hero">
    <div class="h5-page-hero-bg" style="background-image: url('<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?w=1920&q=80')); ?>')"></div>
    <div class="h5-page-hero-overlay"></div>
    <div class="h5-container h5-page-hero-content">
        <span class="h5-tag">Video</span>
        <h1><?php esc_html_e('動画センター', 'eikou'); ?></h1>
    </div>
</section>

<section class="h5-section">
    <div class="h5-container">
        <div class="h5-video-list">
            <?php
            $videos = new WP_Query(['post_type' => 'eikou_video', 'posts_per_page' => -1, 'orderby' => 'date', 'order' => 'DESC']);
            if ($videos->have_posts()) :
                while ($videos->have_posts()) : $videos->the_post();
                    $duration  = get_post_meta(get_the_ID(), '_video_duration', true);
                    $video_url = get_post_meta(get_the_ID(), '_video_url', true);
            ?>
            <div class="h5-video-card" data-video-url="<?php echo esc_attr($video_url); ?>">
                <div class="h5-video-thumb">
                    <?php if (has_post_thumbnail()) : the_post_thumbnail('video-thumb');
                    else : ?><img src="<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?w=600&q=80', 400, 50)); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy"><?php endif; ?>
                    <div class="h5-video-play"><svg width="40" height="40" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="23" stroke="white" stroke-width="2"/><polygon points="20,16 34,24 20,32" fill="white"/></svg></div>
                    <?php if ($duration) : ?><span class="h5-video-duration"><?php echo esc_html($duration); ?></span><?php endif; ?>
                </div>
                <h3 class="h5-video-title"><?php the_title(); ?></h3>
            </div>
            <?php endwhile; wp_reset_postdata();
            else :
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
            <div class="h5-video-card" data-video-url="<?php echo esc_attr($full_url); ?>">
                <div class="h5-video-thumb">
                    <img src="<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&q=80', 400, 50)); ?>" alt="<?php echo esc_attr(__($sv['title'], 'eikou')); ?>" loading="lazy">
                    <div class="h5-video-play"><svg width="40" height="40" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="23" stroke="white" stroke-width="2"/><polygon points="20,16 34,24 20,32" fill="white"/></svg></div>
                </div>
                <h3 class="h5-video-title"><?php echo esc_html(__($sv['title'], 'eikou')); ?></h3>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<!-- Video Modal -->
<div class="h5-video-modal" id="h5VideoModal">
    <div class="h5-video-modal-bg"></div>
    <div class="h5-video-modal-body">
        <button class="h5-video-modal-close">&times;</button>
        <video id="h5VideoPlayer" controls playsinline><source src="" type="video/mp4"></video>
    </div>
</div>

<?php include get_template_directory() . '/h5/footer.php'; ?>
