<?php
/**
 * Template Name: Services Page
 * Slug: services
 */
get_header();

$img_base = content_url('/uploads/services/');
$service_categories = [
    [
        'slug'  => 'service-exhibition',
        'number' => '01',
        'en'    => 'Exhibition & Events',
        'title' => '展示会・イベント',
        'desc'  => '展示会ブースの企画・設計・施工から運営まで、ワンストップで対応いたします。',
        'count' => 7,
        'image' => $img_base . 'catl-booth.jpg',
    ],
    [
        'slug'  => 'service-brand-event',
        'number' => '02',
        'en'    => 'Brand Events',
        'title' => 'ブランドイベント',
        'desc'  => '企業発表会・ロードショー・ポップアップストアなど、ブランド体験を創出します。',
        'count' => 5,
        'image' => $img_base . 'gokin-presentation.jpg',
    ],
    [
        'slug'  => 'service-digital',
        'number' => '03',
        'en'    => 'Digital & Web',
        'title' => 'デジタル・Web',
        'desc'  => 'Webサイト制作・最適化・アプリ開発で、デジタルプレゼンスを強化します。',
        'count' => 3,
        'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&q=80',
    ],
    [
        'slug'  => 'service-ai',
        'number' => '04',
        'en'    => 'AI Solutions',
        'title' => 'AIソリューション',
        'desc'  => 'AIチャットボット・自動化システム導入で、業務効率と顧客体験を革新します。',
        'count' => 3,
        'image' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=800&q=80',
    ],
    [
        'slug'  => 'service-branding',
        'number' => '05',
        'en'    => 'Branding & Design',
        'title' => 'ブランディング・デザイン',
        'desc'  => 'ブランド戦略の立案からパッケージ・販促ツールまで、統一されたデザインを提供します。',
        'count' => 3,
        'image' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=800&q=80',
    ],
    [
        'slug'  => 'service-media',
        'number' => '06',
        'en'    => 'Media & Production',
        'title' => 'メディア・映像',
        'desc'  => '動画制作・メディア運営・サイン看板まで、あらゆるメディアニーズに対応します。',
        'count' => 3,
        'image' => $img_base . 'hithium-mc.jpg',
    ],
];
?>

<!-- ========== PAGE HERO ========== -->
<section class="page-hero">
    <div class="page-hero-bg" style="background-image: url('<?php echo esc_url(content_url('/uploads/services/catl-venue.jpg')); ?>')"></div>
    <div class="page-hero-overlay"></div>
    <div class="container page-hero-content">
        <span class="section-tag">Services</span>
        <h1><?php esc_html_e('サービス', 'eikou'); ?></h1>
        <p><?php esc_html_e('6つの事業領域で、お客様のビジネスをトータルサポート', 'eikou'); ?></p>
    </div>
</section>

<!-- ========== SERVICE CATEGORIES HUB ========== -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Our Services</span>
            <h2 class="section-title"><?php esc_html_e('サービス一覧', 'eikou'); ?></h2>
            <p class="section-desc"><?php esc_html_e('展示空間の企画・施工からデジタル・AIソリューションまで、24のサービスでビジネスの成長を支援します', 'eikou'); ?></p>
        </div>
        <div class="service-hub-grid">
            <?php foreach ($service_categories as $cat) : ?>
            <a href="<?php echo esc_url(eikou_page_url($cat['slug'])); ?>" class="service-hub-card scroll-reveal">
                <div class="service-hub-image">
                    <img src="<?php echo esc_url($cat['image']); ?>" alt="<?php echo esc_attr(__($cat['title'], 'eikou')); ?>">
                    <div class="service-hub-overlay"></div>
                </div>
                <div class="service-hub-info">
                    <span class="business-number"><?php echo esc_html($cat['number']); ?></span>
                    <span class="section-tag"><?php echo esc_html($cat['en']); ?></span>
                    <h3><?php echo esc_html(__($cat['title'], 'eikou')); ?></h3>
                    <p><?php echo esc_html(__($cat['desc'], 'eikou')); ?></p>
                    <span class="service-count"><?php echo esc_html($cat['count']); ?> <?php esc_html_e('サービス', 'eikou'); ?> →</span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ========== WORKFLOW ========== -->
<section class="section" style="background: var(--color-bg-alt);">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Workflow</span>
            <h2 class="section-title"><?php esc_html_e('プロジェクトの流れ', 'eikou'); ?></h2>
            <p class="section-desc"><?php esc_html_e('お問い合わせからプロジェクト完了まで、6つのステップで進行します', 'eikou'); ?></p>
        </div>
        <div class="workflow-grid">
            <div class="workflow-step scroll-reveal">
                <div class="workflow-number">01</div>
                <h3><?php esc_html_e('ヒアリング', 'eikou'); ?></h3>
                <p><?php esc_html_e('ご要望・予算・スケジュールを丁寧にヒアリングし、プロジェクトの方向性を確認します。', 'eikou'); ?></p>
            </div>
            <div class="workflow-step scroll-reveal">
                <div class="workflow-number">02</div>
                <h3><?php esc_html_e('企画・提案', 'eikou'); ?></h3>
                <p><?php esc_html_e('コンセプト設計、デザイン案、見積もりをご提案。複数プランからお選びいただけます。', 'eikou'); ?></p>
            </div>
            <div class="workflow-step scroll-reveal">
                <div class="workflow-number">03</div>
                <h3><?php esc_html_e('デザイン', 'eikou'); ?></h3>
                <p><?php esc_html_e('3Dパース、CGシミュレーション等で完成イメージを可視化。細部まで確認いただけます。', 'eikou'); ?></p>
            </div>
            <div class="workflow-step scroll-reveal">
                <div class="workflow-number">04</div>
                <h3><?php esc_html_e('制作・施工', 'eikou'); ?></h3>
                <p><?php esc_html_e('自社工場での制作と現場施工を一貫管理。品質とスケジュールを厳密にコントロールします。', 'eikou'); ?></p>
            </div>
            <div class="workflow-step scroll-reveal">
                <div class="workflow-number">05</div>
                <h3><?php esc_html_e('運営・実施', 'eikou'); ?></h3>
                <p><?php esc_html_e('イベント当日の運営サポート、トラブル対応、来場者対応まで万全の体制で臨みます。', 'eikou'); ?></p>
            </div>
            <div class="workflow-step scroll-reveal">
                <div class="workflow-number">06</div>
                <h3><?php esc_html_e('撤去・報告', 'eikou'); ?></h3>
                <p><?php esc_html_e('撤去作業、効果測定レポートの作成。次回プロジェクトへの改善提案も行います。', 'eikou'); ?></p>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
