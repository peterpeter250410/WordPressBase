<?php
/**
 * Front Page Template
 */
get_header();
?>

<!-- ========== HERO - Apple-style image crossfade ========== -->
<section class="hero">
    <div class="hero-slideshow">
        <div class="hero-slide active" style="background-image: url('https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1920&q=80')"></div>
        <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1511578314322-379afb476865?w=1920&q=80')"></div>
        <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1497366216548-37526070297c?w=1920&q=80')"></div>
        <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1531058020387-3be344556be6?w=1920&q=80')"></div>
        <div class="hero-overlay"></div>
    </div>
    <div class="container hero-content">
        <div class="hero-badge">EIKOU Co., Ltd.</div>
        <h1 class="hero-title">
            <span class="hero-title-line">光で、空間を</span>
            <span class="hero-title-line hero-title-accent">未来へ導く</span>
        </h1>
        <p class="hero-subtitle">
            展示・イベント・商業空間の企画から施工・運営まで<br>
            日本市場におけるブランド体験をトータルプロデュース
        </p>
        <div class="hero-actions">
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>" class="btn btn-primary">サービスを見る</a>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('works'))); ?>" class="btn btn-outline">実績を見る</a>
        </div>
        <div class="hero-stats">
            <div class="stat-item">
                <span class="stat-number">500<small>+</small></span>
                <span class="stat-label">プロジェクト実績</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">15<small>+</small></span>
                <span class="stat-label">年の業界経験</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">200<small>+</small></span>
                <span class="stat-label">グローバル企業</span>
            </div>
        </div>
    </div>
    <div class="hero-scroll">
        <span>Scroll</span>
        <div class="scroll-line"></div>
    </div>
    <div class="hero-indicators">
        <button class="indicator active" data-slide="0"></button>
        <button class="indicator" data-slide="1"></button>
        <button class="indicator" data-slide="2"></button>
        <button class="indicator" data-slide="3"></button>
    </div>
</section>

<!-- ========== CORE BUSINESS 主营业务 ========== -->
<section class="section core-business">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Core Business</span>
            <h2 class="section-title">主要事業</h2>
            <p class="section-desc">展示空間からデジタルソリューションまで、ワンストップでブランド体験を創出</p>
        </div>
        <div class="business-showcase">
            <div class="business-item">
                <div class="business-image">
                    <img src="https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?w=800&q=80" alt="Exhibition">
                    <div class="business-image-overlay"></div>
                </div>
                <div class="business-info">
                    <span class="business-number">01</span>
                    <h3>展示会・展覧会</h3>
                    <p>国際展示会のブースデザイン・設計・施工・運営までワンストップで対応。東京ビッグサイト、幕張メッセなど主要会場での豊富な実績。</p>
                    <ul class="business-tags">
                        <li>ブースデザイン</li>
                        <li>施工管理</li>
                        <li>運営サポート</li>
                    </ul>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>" class="service-link">詳しく見る →</a>
                </div>
            </div>
            <div class="business-item business-item-reverse">
                <div class="business-image">
                    <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=800&q=80" alt="Brand Event">
                    <div class="business-image-overlay"></div>
                </div>
                <div class="business-info">
                    <span class="business-number">02</span>
                    <h3>ブランドイベント・発表会</h3>
                    <p>製品発表会、プレスカンファレンス、プロモーションイベント、ロードショーの企画から実施まで。ブランドの世界観を体験として具現化します。</p>
                    <ul class="business-tags">
                        <li>企画・演出</li>
                        <li>会場設営</li>
                        <li>映像制作</li>
                    </ul>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>" class="service-link">詳しく見る →</a>
                </div>
            </div>
            <div class="business-item">
                <div class="business-image">
                    <img src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=800&q=80" alt="Commercial Space">
                    <div class="business-image-overlay"></div>
                </div>
                <div class="business-info">
                    <span class="business-number">03</span>
                    <h3>商業空間・ショールーム</h3>
                    <p>企業ショールーム、ポップアップストア、フラッグシップショップの空間デザイン・施工。ブランド体験を最大化する空間を創出します。</p>
                    <ul class="business-tags">
                        <li>空間デザイン</li>
                        <li>内装施工</li>
                        <li>LED演出</li>
                    </ul>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>" class="service-link">詳しく見る →</a>
                </div>
            </div>
            <div class="business-item business-item-reverse">
                <div class="business-image">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&q=80" alt="Digital AI">
                    <div class="business-image-overlay"></div>
                </div>
                <div class="business-info">
                    <span class="business-number">04</span>
                    <h3>デジタル・AI ソリューション</h3>
                    <p>Webサイト構築、業務システム開発、AIソリューション導入支援。テクノロジーの力でビジネスの可能性を拡張します。</p>
                    <ul class="business-tags">
                        <li>Web開発</li>
                        <li>システム構築</li>
                        <li>AI導入</li>
                    </ul>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>" class="service-link">詳しく見る →</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== SUCCESS CASES 成功事例 ========== -->
<section class="section featured-work">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Works</span>
            <h2 class="section-title">成功事例</h2>
            <p class="section-desc">これまでに手がけたプロジェクトの一部をご紹介します</p>
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
                    <span class="work-category">ポップアップ</span>
                    <h3>ブランドポップアップ</h3>
                    <p>表参道｜2024</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="section-action">
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('works'))); ?>" class="btn btn-outline">すべての実績を見る</a>
        </div>
    </div>
</section>

<!-- ========== VIDEO CENTER 動画センター ========== -->
<section class="section video-section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Video</span>
            <h2 class="section-title">動画センター</h2>
            <p class="section-desc">プロジェクトの舞台裏と成果をご覧ください</p>
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
                    <h3>EIKOU プロジェクトハイライト 2024</h3>
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
                    <h3>展示会施工タイムラプス</h3>
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
                    <h3>ブランドイベント メイキング</h3>
                    <span>02:30</span>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="section-action">
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('video'))); ?>" class="btn btn-outline">すべての動画を見る</a>
        </div>
    </div>
</section>

<!-- ========== WHY EIKOU ========== -->
<section class="section why-eikou">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Why EIKOU</span>
            <h2 class="section-title">選ばれる理由</h2>
        </div>
        <div class="reasons-grid">
            <div class="reason-item">
                <div class="reason-number">01</div>
                <h3>ワンストップサービス</h3>
                <p>企画・デザインから制作・施工・運営まで一貫対応。窓口を一本化し、プロジェクト全体の品質とスケジュールを管理します。</p>
            </div>
            <div class="reason-item">
                <div class="reason-number">02</div>
                <h3>日本市場の深い理解</h3>
                <p>15年以上の日本市場での実績。日本特有の品質基準、商習慣、法規制に精通したチームが対応します。</p>
            </div>
            <div class="reason-item">
                <div class="reason-number">03</div>
                <h3>グローバル対応力</h3>
                <p>日本語・中国語・英語のトリリンガル対応。海外企業の日本進出をシームレスにサポートします。</p>
            </div>
            <div class="reason-item">
                <div class="reason-number">04</div>
                <h3>テクノロジー統合</h3>
                <p>LED・インタラクティブ技術・AIソリューションを空間演出に統合。最先端の体験を創出します。</p>
            </div>
        </div>
    </div>
</section>

<!-- ========== PARTNERS 合作客户 ========== -->
<section class="section partners-section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Partners</span>
            <h2 class="section-title">パートナー企業</h2>
            <p class="section-desc">国内外の多くの企業様にご信頼いただいています</p>
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
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('partners'))); ?>" class="btn btn-outline">パートナー一覧を見る</a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
