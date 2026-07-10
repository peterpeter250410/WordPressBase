<?php
/**
 * Template Name: Contact Page
 * Slug: contact
 */
get_header();
?>

<!-- ========== PAGE HERO ========== -->
<section class="page-hero page-hero-sm">
    <div class="page-hero-bg" style="background-image: url('<?php echo esc_url(eikou_mobile_img_url('https://images.unsplash.com/photo-1497366216548-37526070297c?w=1920&q=80')); ?>')"></div>
    <div class="page-hero-overlay"></div>
    <div class="container page-hero-content">
        <span class="section-tag">Contact</span>
        <h1><?php esc_html_e('お問い合わせ', 'eikou'); ?></h1>
        <p><?php esc_html_e('プロジェクトのご相談、お見積もりなど、お気軽にお問い合わせください', 'eikou'); ?></p>
    </div>
</section>

<!-- ========== CONTACT FORM + INFO ========== -->
<section class="section">
    <div class="container">
        <div class="contact-grid">
            <!-- Form -->
            <div class="contact-form-wrap">
                <h2><?php esc_html_e('お問い合わせフォーム', 'eikou'); ?></h2>
                <p class="form-note"><?php esc_html_e('*は必須項目です', 'eikou'); ?></p>
                <?php $eikou_cf7 = eikou_render_contact_form('eikou_cf7_full'); ?>
                <?php if ($eikou_cf7) : ?>
                    <div class="eikou-cf7-wrap"><?php echo $eikou_cf7; ?></div>
                <?php else : ?>
                <form class="contact-form" onsubmit="return false;">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="company"><?php esc_html_e('会社名 *', 'eikou'); ?></label>
                            <input type="text" id="company" name="company" required placeholder="<?php esc_attr_e('株式会社XXXX', 'eikou'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="name"><?php esc_html_e('ご担当者名 *', 'eikou'); ?></label>
                            <input type="text" id="name" name="name" required placeholder="<?php esc_attr_e('山田 太郎', 'eikou'); ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email"><?php esc_html_e('メールアドレス *', 'eikou'); ?></label>
                            <input type="email" id="email" name="email" required placeholder="example@company.com">
                        </div>
                        <div class="form-group">
                            <label for="phone"><?php esc_html_e('電話番号', 'eikou'); ?></label>
                            <input type="tel" id="phone" name="phone" placeholder="03-XXXX-XXXX">
                        </div>
                    </div>
                    <div class="form-group">
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
                    <div class="form-group">
                        <label for="message"><?php esc_html_e('メッセージ *', 'eikou'); ?></label>
                        <textarea id="message" name="message" rows="6" required placeholder="<?php esc_attr_e('プロジェクトの概要、ご予算、スケジュール等をお知らせください', 'eikou'); ?>"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg" style="width:100%;"><?php esc_html_e('送信する', 'eikou'); ?></button>
                </form>
                <?php endif; ?>
            </div>
            <!-- Info -->
            <div class="contact-info-wrap">
                <div class="contact-info-card">
                    <h3><?php esc_html_e('本社オフィス', 'eikou'); ?></h3>
                    <ul>
                        <li>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold)" stroke-width="1.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <div>
                                <strong><?php esc_html_e('所在地', 'eikou'); ?></strong>
                                <p><?php echo esc_html(__(eikou_get('eikou_zip', '〒XXX-XXXX'), 'eikou')); ?><br><?php echo esc_html(__(eikou_get('eikou_address', '東京都XX区XX X-X-X'), 'eikou')); ?></p>
                            </div>
                        </li>
                        <li>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold)" stroke-width="1.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <div>
                                <strong><?php esc_html_e('電話番号', 'eikou'); ?></strong>
                                <p><?php echo esc_html(eikou_get('eikou_tel', '03-XXXX-XXXX')); ?></p>
                            </div>
                        </li>
                        <li>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold)" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <div>
                                <strong><?php esc_html_e('メール', 'eikou'); ?></strong>
                                <p><?php echo esc_html(eikou_get('eikou_email', 'info@eikou.co.jp')); ?></p>
                            </div>
                        </li>
                        <li>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold)" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <div>
                                <strong><?php esc_html_e('営業時間', 'eikou'); ?></strong>
                                <p><?php esc_html_e('月〜金 9:00 - 18:00', 'eikou'); ?><br><?php esc_html_e('（土日祝日休み）', 'eikou'); ?></p>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- Map placeholder -->
                <div class="contact-map">
                    <div class="map-placeholder">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--color-text-dim)" stroke-width="1"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>Google Maps</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
