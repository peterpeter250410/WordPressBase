<?php
/**
 * H5 Front Page Template
 */
include get_template_directory() . '/h5/header.php';
?>

<!-- ========== HERO - Swipeable Card Carousel ========== -->
<?php $hero_slides = eikou_get_hero_slides(); ?>
<section class="h5-hero">
    <div class="h5-hero-carousel" id="heroCarousel">
        <?php foreach ($hero_slides as $idx => $slide) : ?>
        <div class="h5-hero-card">
            <img src="<?php echo esc_url($slide['image']); ?>"
                 alt="Slide <?php echo $idx + 1; ?>"
                 loading="<?php echo $idx === 0 ? 'eager' : 'lazy'; ?>">
            <div class="h5-hero-card-overlay">
                <?php if ($idx === 0) : ?>
                <div class="h5-hero-card-content">
                    <span class="h5-hero-badge">EIKOU Co., Ltd.</span>
                    <h2>光で、空間を<br><span class="h5-gold">未来へ導く</span></h2>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="h5-hero-dots" id="heroDots">
        <?php foreach ($hero_slides as $idx => $slide) : ?>
        <span class="h5-dot<?php echo $idx === 0 ? ' active' : ''; ?>" data-index="<?php echo $idx; ?>"></span>
        <?php endforeach; ?>
    </div>
    <div class="h5-hero-info">
        <p class="h5-hero-desc">展示・イベント・商業空間の企画から施工・運営まで、日本市場におけるブランド体験をトータルプロデュース</p>
        <div class="h5-hero-actions">
            <a href="<?php echo esc_url(eikou_page_url('services')); ?>" class="btn btn-primary">サービスを見る</a>
            <a href="<?php echo esc_url(eikou_page_url('works')); ?>" class="btn btn-outline">実績を見る</a>
        </div>
        <div class="h5-hero-stats">
            <div class="h5-stat">
                <span class="h5-stat-num">500<small>+</small></span>
                <span class="h5-stat-label">プロジェクト実績</span>
            </div>
            <div class="h5-stat">
                <span class="h5-stat-num">15<small>+</small></span>
                <span class="h5-stat-label">年の業界経験</span>
            </div>
            <div class="h5-stat">
                <span class="h5-stat-num">200<small>+</small></span>
                <span class="h5-stat-label">グローバル企業</span>
            </div>
        </div>
    </div>
</section>

<!-- ========== CORE BUSINESS ========== -->
<section class="h5-section">
    <div class="h5-container">
        <div class="h5-section-header">
            <span class="h5-tag">Core Business</span>
            <h2 class="h5-section-title">主要事業</h2>
        </div>
        <div class="h5-service-grid">
            <a href="<?php echo esc_url(eikou_page_url('service-exhibition')); ?>" class="h5-service-card">
                <div class="h5-service-img">
                    <img src="<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&q=80', 400, 50)); ?>" alt="Exhibition" loading="lazy">
                </div>
                <div class="h5-service-info">
                    <span class="h5-service-num">01</span>
                    <h3>展示会・イベント</h3>
                </div>
            </a>
            <a href="<?php echo esc_url(eikou_page_url('service-brand-event')); ?>" class="h5-service-card">
                <div class="h5-service-img">
                    <img src="<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=600&q=80', 400, 50)); ?>" alt="Brand Event" loading="lazy">
                </div>
                <div class="h5-service-info">
                    <span class="h5-service-num">02</span>
                    <h3>ブランドイベント</h3>
                </div>
            </a>
            <a href="<?php echo esc_url(eikou_page_url('service-digital')); ?>" class="h5-service-card">
                <div class="h5-service-img">
                    <img src="<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&q=80', 400, 50)); ?>" alt="Digital" loading="lazy">
                </div>
                <div class="h5-service-info">
                    <span class="h5-service-num">03</span>
                    <h3>デジタル・Web</h3>
                </div>
            </a>
            <a href="<?php echo esc_url(eikou_page_url('service-ai')); ?>" class="h5-service-card">
                <div class="h5-service-img">
                    <img src="<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1677442136019-21780ecad995?w=600&q=80', 400, 50)); ?>" alt="AI" loading="lazy">
                </div>
                <div class="h5-service-info">
                    <span class="h5-service-num">04</span>
                    <h3>AIソリューション</h3>
                </div>
            </a>
            <a href="<?php echo esc_url(eikou_page_url('service-branding')); ?>" class="h5-service-card">
                <div class="h5-service-img">
                    <img src="<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1561070791-2526d30994b5?w=600&q=80', 400, 50)); ?>" alt="Branding" loading="lazy">
                </div>
                <div class="h5-service-info">
                    <span class="h5-service-num">05</span>
                    <h3>ブランディング</h3>
                </div>
            </a>
            <a href="<?php echo esc_url(eikou_page_url('service-media')); ?>" class="h5-service-card">
                <div class="h5-service-img">
                    <img src="<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?w=600&q=80', 400, 50)); ?>" alt="Media" loading="lazy">
                </div>
                <div class="h5-service-info">
                    <span class="h5-service-num">06</span>
                    <h3>メディア・映像</h3>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- ========== SUCCESS CASES ========== -->
<section class="h5-section">
    <div class="h5-container">
        <div class="h5-section-header">
            <span class="h5-tag">Works</span>
            <h2 class="h5-section-title">成功事例</h2>
        </div>
        <div class="h5-works-grid">
            <?php
            $works = new WP_Query([
                'post_type'      => 'work',
                'posts_per_page' => 4,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ]);
            if ($works->have_posts()) :
                while ($works->have_posts()) : $works->the_post();
                    $cat_terms = get_the_terms(get_the_ID(), 'work_category');
                    $cat_name  = ($cat_terms && !is_wp_error($cat_terms)) ? $cat_terms[0]->name : '';
            ?>
            <div class="h5-work-card">
                <div class="h5-work-img">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('h5-thumb'); ?>
                    <?php else : ?>
                        <img src="<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&q=80', 400, 50)); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                    <?php endif; ?>
                </div>
                <div class="h5-work-info">
                    <?php if ($cat_name) : ?><span class="h5-work-cat"><?php echo esc_html($cat_name); ?></span><?php endif; ?>
                    <h3><?php the_title(); ?></h3>
                </div>
            </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
            ?>
            <div class="h5-work-card">
                <div class="h5-work-img"><img src="<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&q=80', 400, 50)); ?>" alt="Exhibition" loading="lazy"></div>
                <div class="h5-work-info"><span class="h5-work-cat">展示会</span><h3>国際展示会ブースデザイン</h3></div>
            </div>
            <div class="h5-work-card">
                <div class="h5-work-img"><img src="<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1511578314322-379afb476865?w=600&q=80', 400, 50)); ?>" alt="Event" loading="lazy"></div>
                <div class="h5-work-info"><span class="h5-work-cat">イベント</span><h3>新製品発表会</h3></div>
            </div>
            <div class="h5-work-card">
                <div class="h5-work-img"><img src="<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=600&q=80', 400, 50)); ?>" alt="Showroom" loading="lazy"></div>
                <div class="h5-work-info"><span class="h5-work-cat">商業空間</span><h3>企業ショールーム</h3></div>
            </div>
            <div class="h5-work-card">
                <div class="h5-work-img"><img src="<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1531058020387-3be344556be6?w=600&q=80', 400, 50)); ?>" alt="Pop-up" loading="lazy"></div>
                <div class="h5-work-info"><span class="h5-work-cat">ポップアップ</span><h3>ブランドポップアップ</h3></div>
            </div>
            <?php endif; ?>
        </div>
        <div class="h5-section-action">
            <a href="<?php echo esc_url(eikou_page_url('works')); ?>" class="btn btn-outline">すべての実績を見る</a>
        </div>
    </div>
</section>

<!-- ========== VIDEO CENTER ========== -->
<section class="h5-section">
    <div class="h5-container">
        <div class="h5-section-header">
            <span class="h5-tag">Video</span>
            <h2 class="h5-section-title">動画センター</h2>
        </div>
        <div class="h5-video-list">
            <?php
            $videos = new WP_Query([
                'post_type'      => 'eikou_video',
                'posts_per_page' => 3,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ]);
            if ($videos->have_posts()) :
                while ($videos->have_posts()) : $videos->the_post();
                    $duration = get_post_meta(get_the_ID(), '_video_duration', true);
            ?>
            <div class="h5-video-card">
                <div class="h5-video-thumb">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('video-thumb'); ?>
                    <?php endif; ?>
                    <div class="h5-video-play">
                        <svg width="36" height="36" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="23" stroke="white" stroke-width="2"/><polygon points="20,16 34,24 20,32" fill="white"/></svg>
                    </div>
                    <?php if ($duration) : ?><span class="h5-video-duration"><?php echo esc_html($duration); ?></span><?php endif; ?>
                </div>
                <h3 class="h5-video-title"><?php the_title(); ?></h3>
            </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
            ?>
            <div class="h5-video-card">
                <div class="h5-video-thumb">
                    <img src="<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?w=600&q=80', 400, 50)); ?>" alt="Video" loading="lazy">
                    <div class="h5-video-play"><svg width="36" height="36" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="23" stroke="white" stroke-width="2"/><polygon points="20,16 34,24 20,32" fill="white"/></svg></div>
                    <span class="h5-video-duration">03:24</span>
                </div>
                <h3 class="h5-video-title">EIKOU プロジェクトハイライト 2024</h3>
            </div>
            <?php endif; ?>
        </div>
        <div class="h5-section-action">
            <a href="<?php echo esc_url(eikou_page_url('video')); ?>" class="btn btn-outline">すべての動画を見る</a>
        </div>
    </div>
</section>

<!-- ========== WHY EIKOU ========== -->
<section class="h5-section">
    <div class="h5-container">
        <div class="h5-section-header">
            <span class="h5-tag">Why EIKOU</span>
            <h2 class="h5-section-title">選ばれる理由</h2>
        </div>
        <div class="h5-reasons-list">
            <div class="h5-reason-card">
                <div class="h5-reason-num">01</div>
                <h3>ワンストップサービス</h3>
                <p>企画・デザインから制作・施工・運営まで一貫対応。窓口を一本化し、プロジェクト全体の品質とスケジュールを管理します。</p>
            </div>
            <div class="h5-reason-card">
                <div class="h5-reason-num">02</div>
                <h3>日本市場の深い理解</h3>
                <p>15年以上の日本市場での実績。日本特有の品質基準、商習慣、法規制に精通したチームが対応します。</p>
            </div>
            <div class="h5-reason-card">
                <div class="h5-reason-num">03</div>
                <h3>グローバル対応力</h3>
                <p>日本語・中国語・英語のトリリンガル対応。海外企業の日本進出をシームレスにサポートします。</p>
            </div>
            <div class="h5-reason-card">
                <div class="h5-reason-num">04</div>
                <h3>テクノロジー統合</h3>
                <p>LED・インタラクティブ技術・AIソリューションを空間演出に統合。最先端の体験を創出します。</p>
            </div>
        </div>
    </div>
</section>

<!-- ========== PARTNERS ========== -->
<section class="h5-section">
    <div class="h5-container">
        <div class="h5-section-header">
            <span class="h5-tag">Partners</span>
            <h2 class="h5-section-title">パートナー企業</h2>
        </div>
        <div class="h5-partners-grid">
            <?php
            $partners = new WP_Query([
                'post_type'      => 'partner',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
            ]);
            if ($partners->have_posts()) :
                while ($partners->have_posts()) : $partners->the_post();
            ?>
            <div class="h5-partner-item"><span><?php the_title(); ?></span></div>
            <?php endwhile; wp_reset_postdata();
            else :
            ?>
            <div class="h5-partner-item"><span>SONY</span></div>
            <div class="h5-partner-item"><span>TOYOTA</span></div>
            <div class="h5-partner-item"><span>PANASONIC</span></div>
            <div class="h5-partner-item"><span>HITACHI</span></div>
            <div class="h5-partner-item"><span>NEC</span></div>
            <div class="h5-partner-item"><span>FUJITSU</span></div>
            <div class="h5-partner-item"><span>TOSHIBA</span></div>
            <div class="h5-partner-item"><span>MITSUBISHI</span></div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include get_template_directory() . '/h5/footer.php'; ?>
