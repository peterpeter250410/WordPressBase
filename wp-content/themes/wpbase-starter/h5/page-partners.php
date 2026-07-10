<?php
/**
 * H5 Partners Page
 */
include get_template_directory() . '/h5/header.php';
?>

<section class="h5-page-hero">
    <div class="h5-page-hero-bg" style="background-image: url('<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1559136555-9303baea8ebd?w=1920&q=80')); ?>')"></div>
    <div class="h5-page-hero-overlay"></div>
    <div class="h5-container h5-page-hero-content">
        <span class="h5-tag">Partners</span>
        <h1><?php esc_html_e('パートナー企業', 'eikou'); ?></h1>
    </div>
</section>

<section class="h5-section">
    <div class="h5-container">
        <div class="h5-section-header">
            <span class="h5-tag">Clients</span>
            <h2 class="h5-section-title"><?php esc_html_e('主要パートナー', 'eikou'); ?></h2>
        </div>
        <div class="h5-partners-grid">
            <?php
            $partners = new WP_Query(['post_type' => 'partner', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC']);
            if ($partners->have_posts()) :
                while ($partners->have_posts()) : $partners->the_post(); ?>
            <div class="h5-partner-item"><span><?php the_title(); ?></span></div>
            <?php endwhile; wp_reset_postdata(); else :
                $names = ['SONY','TOYOTA','PANASONIC','HITACHI','NEC','FUJITSU','TOSHIBA','MITSUBISHI','SHARP','CANON','EPSON','RICOH'];
                foreach ($names as $n) : ?>
            <div class="h5-partner-item"><span><?php echo $n; ?></span></div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<section class="h5-section h5-section-alt">
    <div class="h5-container">
        <div class="h5-section-header">
            <span class="h5-tag">Testimonials</span>
            <h2 class="h5-section-title"><?php esc_html_e('お客様の声', 'eikou'); ?></h2>
        </div>
        <div class="h5-testimonials-list">
            <?php
            $testimonials = new WP_Query(['post_type' => 'testimonial', 'posts_per_page' => -1, 'orderby' => 'date', 'order' => 'DESC']);
            if ($testimonials->have_posts()) :
                while ($testimonials->have_posts()) : $testimonials->the_post();
                    $author_title   = get_post_meta(get_the_ID(), '_testimonial_author_title', true);
                    $author_company = get_post_meta(get_the_ID(), '_testimonial_author_company', true);
            ?>
            <div class="h5-testimonial-card">
                <div class="h5-quote">&ldquo;</div>
                <p><?php echo esc_html(__(get_the_content(), 'eikou')); ?></p>
                <div class="h5-testimonial-author">
                    <?php if ($author_title) : ?><strong><?php echo esc_html(__($author_title, 'eikou')); ?></strong><?php endif; ?>
                    <?php if ($author_company) : ?><span><?php echo esc_html(__($author_company, 'eikou')); ?></span><?php endif; ?>
                </div>
            </div>
            <?php endwhile; wp_reset_postdata(); else : ?>
            <div class="h5-testimonial-card">
                <div class="h5-quote">&ldquo;</div>
                <p><?php esc_html_e('展示会ブースのデザインから施工まで、すべてお任せできる安心感があります。毎回期待を超えるクオリティを提供していただいています。', 'eikou'); ?></p>
                <div class="h5-testimonial-author"><strong><?php esc_html_e('マーケティング部長', 'eikou'); ?></strong><span><?php esc_html_e('大手電機メーカー', 'eikou'); ?></span></div>
            </div>
            <div class="h5-testimonial-card">
                <div class="h5-quote">&ldquo;</div>
                <p><?php esc_html_e('日本市場への進出にあたり、言語・文化面でのサポートが非常に心強かったです。トリリンガル対応でコミュニケーションもスムーズでした。', 'eikou'); ?></p>
                <div class="h5-testimonial-author"><strong><?php esc_html_e('日本支社長', 'eikou'); ?></strong><span><?php esc_html_e('中国系テクノロジー企業', 'eikou'); ?></span></div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include get_template_directory() . '/h5/footer.php'; ?>
