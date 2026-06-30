<?php
/**
 * H5 Contact Page
 */
include get_template_directory() . '/h5/header.php';
?>

<section class="h5-page-hero">
    <div class="h5-page-hero-bg" style="background-image: url('<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1497366216548-37526070297c?w=1920&q=80')); ?>')"></div>
    <div class="h5-page-hero-overlay"></div>
    <div class="h5-container h5-page-hero-content">
        <span class="h5-tag">Contact</span>
        <h1><?php esc_html_e('お問い合わせ', 'eikou'); ?></h1>
    </div>
</section>

<section class="h5-section">
    <div class="h5-container">
        <div class="h5-contact-info">
            <div class="h5-contact-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold)" stroke-width="1.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                <a href="tel:<?php echo esc_attr(eikou_get('eikou_tel', '03-XXXX-XXXX')); ?>"><?php echo esc_html(eikou_get('eikou_tel', '03-XXXX-XXXX')); ?></a>
            </div>
            <div class="h5-contact-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold)" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                <span><?php echo esc_html(eikou_get('eikou_email', 'info@eikou.co.jp')); ?></span>
            </div>
        </div>

        <?php $eikou_cf7 = eikou_render_contact_form('eikou_cf7_full'); ?>
        <?php if ($eikou_cf7) : ?>
        <div class="eikou-cf7-wrap"><?php echo $eikou_cf7; ?></div>
        <?php else : ?>
        <form class="h5-contact-form" onsubmit="return false;">
            <div class="h5-form-group">
                <label for="company"><?php esc_html_e('会社名 *', 'eikou'); ?></label>
                <input type="text" id="company" name="company" required placeholder="<?php esc_attr_e('株式会社XXXX', 'eikou'); ?>">
            </div>
            <div class="h5-form-group">
                <label for="name"><?php esc_html_e('ご担当者名 *', 'eikou'); ?></label>
                <input type="text" id="name" name="name" required placeholder="<?php esc_attr_e('山田 太郎', 'eikou'); ?>">
            </div>
            <div class="h5-form-group">
                <label for="email"><?php esc_html_e('メールアドレス *', 'eikou'); ?></label>
                <input type="email" id="email" name="email" required placeholder="example@company.com">
            </div>
            <div class="h5-form-group">
                <label for="phone"><?php esc_html_e('電話番号', 'eikou'); ?></label>
                <input type="tel" id="phone" name="phone" placeholder="03-XXXX-XXXX">
            </div>
            <div class="h5-form-group">
                <label for="service"><?php esc_html_e('ご相談内容 *', 'eikou'); ?></label>
                <select id="service" name="service" required>
                    <option value=""><?php esc_html_e('選択してください', 'eikou'); ?></option>
                    <option value="exhibition"><?php esc_html_e('展示会・展覧会', 'eikou'); ?></option>
                    <option value="event"><?php esc_html_e('ブランドイベント・発表会', 'eikou'); ?></option>
                    <option value="space"><?php esc_html_e('商業空間・ショールーム', 'eikou'); ?></option>
                    <option value="digital"><?php esc_html_e('デジタル・AI ソリューション', 'eikou'); ?></option>
                    <option value="other"><?php esc_html_e('その他', 'eikou'); ?></option>
                </select>
            </div>
            <div class="h5-form-group">
                <label for="message"><?php esc_html_e('メッセージ *', 'eikou'); ?></label>
                <textarea id="message" name="message" rows="5" required placeholder="<?php esc_attr_e('プロジェクトの概要、ご予算、スケジュール等をお知らせください', 'eikou'); ?>"></textarea>
            </div>
            <button type="submit" class="btn btn-primary h5-submit-btn"><?php esc_html_e('送信する', 'eikou'); ?></button>
        </form>
        <?php endif; ?>
    </div>
</section>

<?php include get_template_directory() . '/h5/footer.php'; ?>
