<?php
/**
 * Template Name: Services Page
 * Slug: services
 */
get_header();

$service_categories = [
    [
        'slug'  => 'service-exhibition',
        'number' => '01',
        'en'    => 'Exhibition & Events',
        'title' => '展示会・イベント',
        'desc'  => '展示会ブースの企画・設計・施工から運営まで、ワンストップで対応いたします。',
        'count' => 7,
        'image' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&q=80',
    ],
    [
        'slug'  => 'service-brand-event',
        'number' => '02',
        'en'    => 'Brand Events',
        'title' => 'ブランドイベント',
        'desc'  => '企業発表会・ロードショー・ポップアップストアなど、ブランド体験を創出します。',
        'count' => 5,
        'image' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=800&q=80',
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
        'image' => 'https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?w=800&q=80',
    ],
];
?>

<!-- ========== PAGE HERO ========== -->
<section class="page-hero">
    <div class="page-hero-bg" style="background-image: url('https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?w=1920&q=80')"></div>
    <div class="page-hero-overlay"></div>
    <div class="container page-hero-content">
        <span class="section-tag">Services</span>
        <h1>サービス</h1>
        <p>6つの事業領域で、お客様のビジネスをトータルサポート</p>
    </div>
</section>

<!-- ========== SERVICE CATEGORIES HUB ========== -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Our Services</span>
            <h2 class="section-title">サービス一覧</h2>
            <p class="section-desc">展示空間の企画・施工からデジタル・AIソリューションまで、24のサービスでビジネスの成長を支援します</p>
        </div>
        <div class="service-hub-grid">
            <?php foreach ($service_categories as $cat) : ?>
            <a href="<?php echo esc_url(eikou_page_url($cat['slug'])); ?>" class="service-hub-card scroll-reveal">
                <div class="service-hub-image">
                    <img src="<?php echo esc_url($cat['image']); ?>" alt="<?php echo esc_attr($cat['title']); ?>">
                    <div class="service-hub-overlay"></div>
                </div>
                <div class="service-hub-info">
                    <span class="business-number"><?php echo esc_html($cat['number']); ?></span>
                    <span class="section-tag"><?php echo esc_html($cat['en']); ?></span>
                    <h3><?php echo esc_html($cat['title']); ?></h3>
                    <p><?php echo esc_html($cat['desc']); ?></p>
                    <span class="service-count"><?php echo esc_html($cat['count']); ?> サービス →</span>
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
            <h2 class="section-title">プロジェクトの流れ</h2>
            <p class="section-desc">お問い合わせからプロジェクト完了まで、6つのステップで進行します</p>
        </div>
        <div class="workflow-grid">
            <div class="workflow-step scroll-reveal">
                <div class="workflow-number">01</div>
                <h3>ヒアリング</h3>
                <p>ご要望・予算・スケジュールを丁寧にヒアリングし、プロジェクトの方向性を確認します。</p>
            </div>
            <div class="workflow-step scroll-reveal">
                <div class="workflow-number">02</div>
                <h3>企画・提案</h3>
                <p>コンセプト設計、デザイン案、見積もりをご提案。複数プランからお選びいただけます。</p>
            </div>
            <div class="workflow-step scroll-reveal">
                <div class="workflow-number">03</div>
                <h3>デザイン</h3>
                <p>3Dパース、CGシミュレーション等で完成イメージを可視化。細部まで確認いただけます。</p>
            </div>
            <div class="workflow-step scroll-reveal">
                <div class="workflow-number">04</div>
                <h3>制作・施工</h3>
                <p>自社工場での制作と現場施工を一貫管理。品質とスケジュールを厳密にコントロールします。</p>
            </div>
            <div class="workflow-step scroll-reveal">
                <div class="workflow-number">05</div>
                <h3>運営・実施</h3>
                <p>イベント当日の運営サポート、トラブル対応、来場者対応まで万全の体制で臨みます。</p>
            </div>
            <div class="workflow-step scroll-reveal">
                <div class="workflow-number">06</div>
                <h3>撤去・報告</h3>
                <p>撤去作業、効果測定レポートの作成。次回プロジェクトへの改善提案も行います。</p>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
