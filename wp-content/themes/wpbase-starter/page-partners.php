<?php
/**
 * Template Name: Partners Page
 * Slug: partners
 */
get_header();
?>

<!-- ========== PAGE HERO ========== -->
<section class="page-hero">
    <div class="page-hero-bg" style="background-image: url('https://images.unsplash.com/photo-1559136555-9303baea8ebd?w=1920&q=80')"></div>
    <div class="page-hero-overlay"></div>
    <div class="container page-hero-content">
        <span class="section-tag">Partners</span>
        <h1>パートナー企業</h1>
        <p>国内外の多くの企業様にご信頼いただいています</p>
    </div>
</section>

<!-- ========== PARTNER LOGOS ========== -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Clients</span>
            <h2 class="section-title">主要パートナー</h2>
            <p class="section-desc">業界を代表する企業様とのパートナーシップ</p>
        </div>
        <div class="partners-grid">
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
            <div class="partner-card"><span><?php the_title(); ?></span></div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
            ?>
            <div class="partner-card"><span>SONY</span></div>
            <div class="partner-card"><span>TOYOTA</span></div>
            <div class="partner-card"><span>PANASONIC</span></div>
            <div class="partner-card"><span>HITACHI</span></div>
            <div class="partner-card"><span>NEC</span></div>
            <div class="partner-card"><span>FUJITSU</span></div>
            <div class="partner-card"><span>TOSHIBA</span></div>
            <div class="partner-card"><span>MITSUBISHI</span></div>
            <div class="partner-card"><span>SHARP</span></div>
            <div class="partner-card"><span>CANON</span></div>
            <div class="partner-card"><span>EPSON</span></div>
            <div class="partner-card"><span>RICOH</span></div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ========== TESTIMONIALS ========== -->
<section class="section" style="background: var(--color-bg-alt);">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Testimonials</span>
            <h2 class="section-title">お客様の声</h2>
        </div>
        <div class="testimonials-grid">
            <?php
            $testimonials = new WP_Query([
                'post_type'      => 'testimonial',
                'posts_per_page' => -1,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ]);
            if ($testimonials->have_posts()) :
                while ($testimonials->have_posts()) : $testimonials->the_post();
                    $author_title   = get_post_meta(get_the_ID(), '_testimonial_author_title', true);
                    $author_company = get_post_meta(get_the_ID(), '_testimonial_author_company', true);
            ?>
            <div class="testimonial-card">
                <div class="testimonial-quote">"</div>
                <p><?php echo esc_html(get_the_content()); ?></p>
                <div class="testimonial-author">
                    <div>
                        <?php if ($author_title) : ?>
                            <strong><?php echo esc_html($author_title); ?></strong>
                        <?php endif; ?>
                        <?php if ($author_company) : ?>
                            <span><?php echo esc_html($author_company); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
            ?>
            <div class="testimonial-card">
                <div class="testimonial-quote">"</div>
                <p>展示会ブースのデザインから施工まで、すべてお任せできる安心感があります。毎回期待を超えるクオリティを提供していただいています。</p>
                <div class="testimonial-author">
                    <div>
                        <strong>マーケティング部長</strong>
                        <span>大手電機メーカー</span>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-quote">"</div>
                <p>日本市場への進出にあたり、言語・文化面でのサポートが非常に心強かったです。トリリンガル対応でコミュニケーションもスムーズでした。</p>
                <div class="testimonial-author">
                    <div>
                        <strong>日本支社長</strong>
                        <span>中国系テクノロジー企業</span>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-quote">"</div>
                <p>ショールームのリニューアルプロジェクトでは、空間デザインだけでなくインタラクティブ技術の導入まで一貫して対応いただきました。</p>
                <div class="testimonial-author">
                    <div>
                        <strong>事業企画部</strong>
                        <span>大手自動車メーカー</span>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ========== CTA (Partners-specific) ========== -->
<section class="section cta-section">
    <div class="cta-bg"></div>
    <div class="container cta-content">
        <h2>パートナーシップのご相談</h2>
        <p>新規プロジェクトのご相談や、パートナーシップについてのお問い合わせを承っております。</p>
        <div class="cta-actions">
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-primary btn-lg">お問い合わせ</a>
            <a href="tel:+81-3-XXXX-XXXX" class="btn btn-ghost"><?php echo esc_html(eikou_get('eikou_tel', '03-XXXX-XXXX')); ?></a>
        </div>
    </div>
</section>

<?php
// Skip the default CTA in footer for partners page (has its own)
remove_action('wp_footer', ''); // placeholder - CTA is handled via is_page check in footer
get_footer();
?>
