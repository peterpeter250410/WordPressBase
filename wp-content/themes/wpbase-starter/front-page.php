<?php
/**
 * Front Page Template
 */
get_header();
?>

<!-- ========== HERO - Apple-style image crossfade ========== -->
<?php $hero_slides = eikou_get_hero_slides(); ?>
<section class="hero">
    <div class="hero-slideshow">
        <?php foreach ($hero_slides as $idx => $slide) : ?>
        <div class="hero-slide<?php echo $idx === 0 ? ' active' : ''; ?>" style="background-image: url('<?php echo esc_url($slide['image']); ?>')"></div>
        <?php endforeach; ?>
        <div class="hero-overlay"></div>
    </div>
    <div class="container hero-content">
        <div class="hero-badge">EIKOU Co., Ltd.</div>
        <h1 class="hero-title">
            <span class="hero-title-line"><?php esc_html_e('光で、空間を', 'eikou'); ?></span>
            <span class="hero-title-line hero-title-accent"><?php esc_html_e('未来へ導く', 'eikou'); ?></span>
        </h1>
        <p class="hero-subtitle">
            <?php esc_html_e('展示・イベント・商業空間の企画から施工・運営まで', 'eikou'); ?><br>
            <?php esc_html_e('日本市場におけるブランド体験をトータルプロデュース', 'eikou'); ?>
        </p>
        <div class="hero-actions">
            <a href="<?php echo esc_url(eikou_page_url('services')); ?>" class="btn btn-primary"><?php esc_html_e('サービスを見る', 'eikou'); ?></a>
            <a href="<?php echo esc_url(eikou_page_url('works')); ?>" class="btn btn-outline"><?php esc_html_e('実績を見る', 'eikou'); ?></a>
        </div>
        <div class="hero-stats">
            <div class="stat-item">
                <span class="stat-number">500<small>+</small></span>
                <span class="stat-label"><?php esc_html_e('プロジェクト実績', 'eikou'); ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-number">15<small>+</small></span>
                <span class="stat-label"><?php esc_html_e('年の業界経験', 'eikou'); ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-number">200<small>+</small></span>
                <span class="stat-label"><?php esc_html_e('グローバル企業', 'eikou'); ?></span>
            </div>
        </div>
    </div>
    <div class="hero-scroll">
        <span>Scroll</span>
        <div class="scroll-line"></div>
    </div>
    <div class="hero-indicators">
        <?php foreach ($hero_slides as $idx => $slide) : ?>
        <button class="indicator<?php echo $idx === 0 ? ' active' : ''; ?>" data-slide="<?php echo $idx; ?>"></button>
        <?php endforeach; ?>
    </div>
</section>

<!-- ========== CORE BUSINESS 主营业务 ========== -->
<section class="section core-business">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Core Business</span>
            <h2 class="section-title"><?php esc_html_e('主要事業', 'eikou'); ?></h2>
            <p class="section-desc"><?php esc_html_e('6つの事業領域で、お客様のビジネスをトータルサポート', 'eikou'); ?></p>
        </div>
        <div class="service-grid-home">
            <a href="<?php echo esc_url(eikou_page_url('service-exhibition')); ?>" class="service-card-home scroll-reveal">
                <div class="service-card-image">
                    <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&q=80" alt="Exhibition">
                    <div class="service-card-overlay"></div>
                </div>
                <div class="service-card-content">
                    <span class="service-card-number">01</span>
                    <h3><?php esc_html_e('展示会・イベント', 'eikou'); ?></h3>
                    <p><?php esc_html_e('ブースデザイン・施工・運営をワンストップで', 'eikou'); ?></p>
                    <span class="service-card-link"><?php esc_html_e('詳しく見る', 'eikou'); ?> →</span>
                </div>
            </a>
            <a href="<?php echo esc_url(eikou_page_url('service-brand-event')); ?>" class="service-card-home scroll-reveal">
                <div class="service-card-image">
                    <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=600&q=80" alt="Brand Event">
                    <div class="service-card-overlay"></div>
                </div>
                <div class="service-card-content">
                    <span class="service-card-number">02</span>
                    <h3><?php esc_html_e('ブランドイベント', 'eikou'); ?></h3>
                    <p><?php esc_html_e('発表会・ロードショー・ポップアップストア', 'eikou'); ?></p>
                    <span class="service-card-link"><?php esc_html_e('詳しく見る', 'eikou'); ?> →</span>
                </div>
            </a>
            <a href="<?php echo esc_url(eikou_page_url('service-digital')); ?>" class="service-card-home scroll-reveal">
                <div class="service-card-image">
                    <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&q=80" alt="Digital">
                    <div class="service-card-overlay"></div>
                </div>
                <div class="service-card-content">
                    <span class="service-card-number">03</span>
                    <h3><?php esc_html_e('デジタル・Web', 'eikou'); ?></h3>
                    <p><?php esc_html_e('Webサイト制作・最適化・アプリ開発', 'eikou'); ?></p>
                    <span class="service-card-link"><?php esc_html_e('詳しく見る', 'eikou'); ?> →</span>
                </div>
            </a>
            <a href="<?php echo esc_url(eikou_page_url('service-ai')); ?>" class="service-card-home scroll-reveal">
                <div class="service-card-image">
                    <img src="https://images.unsplash.com/photo-1677442136019-21780ecad995?w=600&q=80" alt="AI">
                    <div class="service-card-overlay"></div>
                </div>
                <div class="service-card-content">
                    <span class="service-card-number">04</span>
                    <h3><?php esc_html_e('AIソリューション', 'eikou'); ?></h3>
                    <p><?php esc_html_e('チャットボット・AI建模・自動化システム', 'eikou'); ?></p>
                    <span class="service-card-link"><?php esc_html_e('詳しく見る', 'eikou'); ?> →</span>
                </div>
            </a>
            <a href="<?php echo esc_url(eikou_page_url('service-branding')); ?>" class="service-card-home scroll-reveal">
                <div class="service-card-image">
                    <img src="https://images.unsplash.com/photo-1561070791-2526d30994b5?w=600&q=80" alt="Branding">
                    <div class="service-card-overlay"></div>
                </div>
                <div class="service-card-content">
                    <span class="service-card-number">05</span>
                    <h3><?php esc_html_e('ブランディング・デザイン', 'eikou'); ?></h3>
                    <p><?php esc_html_e('パッケージ・販促ツール・宣伝デザイン', 'eikou'); ?></p>
                    <span class="service-card-link"><?php esc_html_e('詳しく見る', 'eikou'); ?> →</span>
                </div>
            </a>
            <a href="<?php echo esc_url(eikou_page_url('service-media')); ?>" class="service-card-home scroll-reveal">
                <div class="service-card-image">
                    <img src="https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?w=600&q=80" alt="Media">
                    <div class="service-card-overlay"></div>
                </div>
                <div class="service-card-content">
                    <span class="service-card-number">06</span>
                    <h3><?php esc_html_e('メディア・映像', 'eikou'); ?></h3>
                    <p><?php esc_html_e('動画制作・メディア運営・サイン看板', 'eikou'); ?></p>
                    <span class="service-card-link"><?php esc_html_e('詳しく見る', 'eikou'); ?> →</span>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- ========== SUCCESS CASES 成功事例 ========== -->
<section class="section featured-work">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Works</span>
            <h2 class="section-title"><?php esc_html_e('成功事例', 'eikou'); ?></h2>
            <p class="section-desc"><?php esc_html_e('これまでに手がけたプロジェクトの一部をご紹介します', 'eikou'); ?></p>
        </div>
        <div class="works-grid">
            <?php
            $works = new WP_Query([
                'post_type'      => 'work',
                'posts_per_page' => 4,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ]);
            $i = 0;
            if ($works->have_posts()) :
                while ($works->have_posts()) : $works->the_post();
                    $location = get_post_meta(get_the_ID(), '_work_location', true);
                    $year     = get_post_meta(get_the_ID(), '_work_year', true);
                    $cats     = get_the_terms(get_the_ID(), 'work_category');
                    $cat_name = ($cats && !is_wp_error($cats)) ? $cats[0]->name : '';
                    $class    = ($i === 0) ? 'work-card work-card-large' : 'work-card';
            ?>
            <div class="<?php echo esc_attr($class); ?>">
                <div class="work-image">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail(($i === 0) ? 'work-large' : 'work-thumb'); ?>
                    <?php else : ?>
                        <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=<?php echo ($i === 0) ? '1000' : '600'; ?>&q=80" alt="<?php the_title_attribute(); ?>">
                    <?php endif; ?>
                </div>
                <div class="work-info">
                    <?php if ($cat_name) : ?>
                        <span class="work-category"><?php echo esc_html($cat_name); ?></span>
                    <?php endif; ?>
                    <h3><?php the_title(); ?></h3>
                    <?php if ($location || $year) : ?>
                        <p><?php echo esc_html($location); ?><?php if ($location && $year) echo '｜'; ?><?php echo esc_html($year); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php
                    $i++;
                endwhile;
                wp_reset_postdata();
            else :
                // Fallback static content
            ?>
            <div class="work-card work-card-large">
                <div class="work-image">
                    <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1000&q=80" alt="Exhibition booth">
                </div>
                <div class="work-info">
                    <span class="work-category"><?php esc_html_e('展示会', 'eikou'); ?></span>
                    <h3><?php esc_html_e('国際展示会ブースデザイン', 'eikou'); ?></h3>
                    <p><?php esc_html_e('東京ビッグサイト｜2024', 'eikou'); ?></p>
                </div>
            </div>
            <div class="work-card">
                <div class="work-image">
                    <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?w=600&q=80" alt="Brand event">
                </div>
                <div class="work-info">
                    <span class="work-category"><?php esc_html_e('イベント', 'eikou'); ?></span>
                    <h3><?php esc_html_e('新製品発表会', 'eikou'); ?></h3>
                    <p><?php esc_html_e('六本木ヒルズ｜2024', 'eikou'); ?></p>
                </div>
            </div>
            <div class="work-card">
                <div class="work-image">
                    <img src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=600&q=80" alt="Showroom">
                </div>
                <div class="work-info">
                    <span class="work-category"><?php esc_html_e('商業空間', 'eikou'); ?></span>
                    <h3><?php esc_html_e('企業ショールーム', 'eikou'); ?></h3>
                    <p><?php esc_html_e('銀座｜2023', 'eikou'); ?></p>
                </div>
            </div>
            <div class="work-card">
                <div class="work-image">
                    <img src="https://images.unsplash.com/photo-1531058020387-3be344556be6?w=600&q=80" alt="Pop-up store">
                </div>
                <div class="work-info">
                    <span class="work-category"><?php esc_html_e('ポップアップ', 'eikou'); ?></span>
                    <h3><?php esc_html_e('ブランドポップアップ', 'eikou'); ?></h3>
                    <p><?php esc_html_e('表参道｜2024', 'eikou'); ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="section-action">
            <a href="<?php echo esc_url(eikou_page_url('works')); ?>" class="btn btn-outline"><?php esc_html_e('すべての実績を見る', 'eikou'); ?></a>
        </div>
    </div>
</section>

<!-- ========== VIDEO CENTER 動画センター ========== -->
<section class="section video-section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Video</span>
            <h2 class="section-title"><?php esc_html_e('動画センター', 'eikou'); ?></h2>
            <p class="section-desc"><?php esc_html_e('プロジェクトの舞台裏と成果をご覧ください', 'eikou'); ?></p>
        </div>
        <div class="video-grid">
            <?php
            $videos = new WP_Query([
                'post_type'      => 'eikou_video',
                'posts_per_page' => 3,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ]);
            $v = 0;
            if ($videos->have_posts()) :
                while ($videos->have_posts()) : $videos->the_post();
                    $duration = get_post_meta(get_the_ID(), '_video_duration', true);
                    $class    = ($v === 0) ? 'video-card video-card-main' : 'video-card';
                    $svg_size = ($v === 0) ? 48 : 36;
            ?>
            <div class="<?php echo esc_attr($class); ?>">
                <div class="video-thumb">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('video-thumb'); ?>
                    <?php endif; ?>
                    <div class="video-play">
                        <svg width="<?php echo $svg_size; ?>" height="<?php echo $svg_size; ?>" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="23" stroke="white" stroke-width="2"/><polygon points="20,16 34,24 20,32" fill="white"/></svg>
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
                    $v++;
                endwhile;
                wp_reset_postdata();
            else :
            ?>
            <div class="video-card video-card-main">
                <div class="video-thumb">
                    <img src="https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?w=1000&q=80" alt="Video thumbnail">
                    <div class="video-play">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="23" stroke="white" stroke-width="2"/><polygon points="20,16 34,24 20,32" fill="white"/></svg>
                    </div>
                </div>
                <div class="video-info">
                    <h3><?php esc_html_e('EIKOU プロジェクトハイライト 2024', 'eikou'); ?></h3>
                    <span>03:24</span>
                </div>
            </div>
            <div class="video-card">
                <div class="video-thumb">
                    <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=600&q=80" alt="Video thumbnail">
                    <div class="video-play">
                        <svg width="36" height="36" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="23" stroke="white" stroke-width="2"/><polygon points="20,16 34,24 20,32" fill="white"/></svg>
                    </div>
                </div>
                <div class="video-info">
                    <h3><?php esc_html_e('展示会施工タイムラプス', 'eikou'); ?></h3>
                    <span>01:45</span>
                </div>
            </div>
            <div class="video-card">
                <div class="video-thumb">
                    <img src="https://images.unsplash.com/photo-1559136555-9303baea8ebd?w=600&q=80" alt="Video thumbnail">
                    <div class="video-play">
                        <svg width="36" height="36" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="23" stroke="white" stroke-width="2"/><polygon points="20,16 34,24 20,32" fill="white"/></svg>
                    </div>
                </div>
                <div class="video-info">
                    <h3><?php esc_html_e('ブランドイベント メイキング', 'eikou'); ?></h3>
                    <span>02:30</span>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="section-action">
            <a href="<?php echo esc_url(eikou_page_url('video')); ?>" class="btn btn-outline"><?php esc_html_e('すべての動画を見る', 'eikou'); ?></a>
        </div>
    </div>
</section>

<!-- ========== WHY EIKOU ========== -->
<section class="section why-eikou">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Why EIKOU</span>
            <h2 class="section-title"><?php esc_html_e('選ばれる理由', 'eikou'); ?></h2>
        </div>
        <div class="reasons-grid">
            <div class="reason-item">
                <div class="reason-number">01</div>
                <h3><?php esc_html_e('ワンストップサービス', 'eikou'); ?></h3>
                <p><?php esc_html_e('企画・デザインから制作・施工・運営まで一貫対応。窓口を一本化し、プロジェクト全体の品質とスケジュールを管理します。', 'eikou'); ?></p>
            </div>
            <div class="reason-item">
                <div class="reason-number">02</div>
                <h3><?php esc_html_e('日本市場の深い理解', 'eikou'); ?></h3>
                <p><?php esc_html_e('15年以上の日本市場での実績。日本特有の品質基準、商習慣、法規制に精通したチームが対応します。', 'eikou'); ?></p>
            </div>
            <div class="reason-item">
                <div class="reason-number">03</div>
                <h3><?php esc_html_e('グローバル対応力', 'eikou'); ?></h3>
                <p><?php esc_html_e('日本語・中国語・英語のトリリンガル対応。海外企業の日本進出をシームレスにサポートします。', 'eikou'); ?></p>
            </div>
            <div class="reason-item">
                <div class="reason-number">04</div>
                <h3><?php esc_html_e('テクノロジー統合', 'eikou'); ?></h3>
                <p><?php esc_html_e('LED・インタラクティブ技術・AIソリューションを空間演出に統合。最先端の体験を創出します。', 'eikou'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ========== PARTNERS 合作客户 ========== -->
<section class="section partners-section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Partners</span>
            <h2 class="section-title"><?php esc_html_e('パートナー企業', 'eikou'); ?></h2>
            <p class="section-desc"><?php esc_html_e('国内外の多くの企業様にご信頼いただいています', 'eikou'); ?></p>
        </div>
        <div class="partners-marquee">
            <div class="marquee-track">
                <?php
                $partners = new WP_Query([
                    'post_type'      => 'partner',
                    'posts_per_page' => -1,
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                ]);
                if ($partners->have_posts()) :
                    // First pass
                    while ($partners->have_posts()) : $partners->the_post();
                ?>
                <div class="partner-logo"><span><?php the_title(); ?></span></div>
                <?php endwhile;
                    // Duplicate for seamless marquee loop
                    $partners->rewind_posts();
                    while ($partners->have_posts()) : $partners->the_post();
                ?>
                <div class="partner-logo"><span><?php the_title(); ?></span></div>
                <?php endwhile;
                    wp_reset_postdata();
                else :
                ?>
                <div class="partner-logo"><span>SONY</span></div>
                <div class="partner-logo"><span>TOYOTA</span></div>
                <div class="partner-logo"><span>PANASONIC</span></div>
                <div class="partner-logo"><span>HITACHI</span></div>
                <div class="partner-logo"><span>NEC</span></div>
                <div class="partner-logo"><span>FUJITSU</span></div>
                <div class="partner-logo"><span>TOSHIBA</span></div>
                <div class="partner-logo"><span>MITSUBISHI</span></div>
                <div class="partner-logo"><span>SONY</span></div>
                <div class="partner-logo"><span>TOYOTA</span></div>
                <div class="partner-logo"><span>PANASONIC</span></div>
                <div class="partner-logo"><span>HITACHI</span></div>
                <div class="partner-logo"><span>NEC</span></div>
                <div class="partner-logo"><span>FUJITSU</span></div>
                <div class="partner-logo"><span>TOSHIBA</span></div>
                <div class="partner-logo"><span>MITSUBISHI</span></div>
                <?php endif; ?>
            </div>
        </div>
        <div class="section-action">
            <a href="<?php echo esc_url(eikou_page_url('partners')); ?>" class="btn btn-outline"><?php esc_html_e('パートナー一覧を見る', 'eikou'); ?></a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
