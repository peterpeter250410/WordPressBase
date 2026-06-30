<?php
/**
 * H5 Services Page
 */
include get_template_directory() . '/h5/header.php';

$img_base = content_url('/uploads/services/');
$service_categories = [
    ['slug' => 'service-exhibition', 'number' => '01', 'en' => 'Exhibition & Events', 'title' => '展示会・イベント', 'desc' => '展示会ブースの企画・設計・施工から運営まで、ワンストップで対応いたします。', 'count' => 7, 'image' => $img_base . 'catl-booth.jpg'],
    ['slug' => 'service-brand-event', 'number' => '02', 'en' => 'Brand Events', 'title' => 'ブランドイベント', 'desc' => '企業発表会・ロードショー・ポップアップストアなど、ブランド体験を創出します。', 'count' => 5, 'image' => $img_base . 'gokin-presentation.jpg'],
    ['slug' => 'service-digital', 'number' => '03', 'en' => 'Digital & Web', 'title' => 'デジタル・Web', 'desc' => 'Webサイト制作・最適化・アプリ開発で、デジタルプレゼンスを強化します。', 'count' => 3, 'image' => eikou_mobile_img_url('https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&q=80', 400, 50)],
    ['slug' => 'service-ai', 'number' => '04', 'en' => 'AI Solutions', 'title' => 'AIソリューション', 'desc' => 'AIチャットボット・自動化システム導入で、業務効率と顧客体験を革新します。', 'count' => 3, 'image' => eikou_mobile_img_url('https://images.unsplash.com/photo-1677442136019-21780ecad995?w=800&q=80', 400, 50)],
    ['slug' => 'service-branding', 'number' => '05', 'en' => 'Branding & Design', 'title' => 'ブランディング・デザイン', 'desc' => 'ブランド戦略の立案からパッケージ・販促ツールまで、統一されたデザインを提供します。', 'count' => 3, 'image' => eikou_mobile_img_url('https://images.unsplash.com/photo-1561070791-2526d30994b5?w=800&q=80', 400, 50)],
    ['slug' => 'service-media', 'number' => '06', 'en' => 'Media & Production', 'title' => 'メディア・映像', 'desc' => '動画制作・メディア運営・サイン看板まで、あらゆるメディアニーズに対応します。', 'count' => 3, 'image' => $img_base . 'hithium-mc.jpg'],
];
?>

<section class="h5-page-hero">
    <div class="h5-page-hero-bg" style="background-image: url('<?php echo esc_url(content_url('/uploads/services/catl-venue.jpg')); ?>')"></div>
    <div class="h5-page-hero-overlay"></div>
    <div class="h5-container h5-page-hero-content">
        <span class="h5-tag">Services</span>
        <h1><?php esc_html_e('サービス', 'eikou'); ?></h1>
    </div>
</section>

<section class="h5-section">
    <div class="h5-container">
        <div class="h5-section-header">
            <span class="h5-tag">Our Services</span>
            <h2 class="h5-section-title"><?php esc_html_e('サービス一覧', 'eikou'); ?></h2>
        </div>
        <div class="h5-service-hub-list">
            <?php foreach ($service_categories as $cat) : ?>
            <a href="<?php echo esc_url(eikou_page_url($cat['slug'])); ?>" class="h5-service-hub-card">
                <div class="h5-service-hub-img">
                    <img src="<?php echo esc_url($cat['image']); ?>" alt="<?php echo esc_attr(__($cat['title'], 'eikou')); ?>" loading="lazy">
                </div>
                <div class="h5-service-hub-info">
                    <span class="h5-service-num"><?php echo esc_html($cat['number']); ?></span>
                    <h3><?php echo esc_html(__($cat['title'], 'eikou')); ?></h3>
                    <p><?php echo esc_html(__($cat['desc'], 'eikou')); ?></p>
                    <span class="h5-service-count"><?php echo esc_html($cat['count']); ?> <?php esc_html_e('サービス', 'eikou'); ?> →</span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="h5-section h5-section-alt">
    <div class="h5-container">
        <div class="h5-section-header">
            <span class="h5-tag">Workflow</span>
            <h2 class="h5-section-title"><?php esc_html_e('プロジェクトの流れ', 'eikou'); ?></h2>
        </div>
        <div class="h5-workflow-list">
            <?php
            $steps = [
                ['num' => '01', 'title' => 'ヒアリング', 'desc' => 'ご要望・予算・スケジュールを丁寧にヒアリングし、プロジェクトの方向性を確認します。'],
                ['num' => '02', 'title' => '企画・提案', 'desc' => 'コンセプト設計、デザイン案、見積もりをご提案。複数プランからお選びいただけます。'],
                ['num' => '03', 'title' => 'デザイン', 'desc' => '3Dパース、CGシミュレーション等で完成イメージを可視化。細部まで確認いただけます。'],
                ['num' => '04', 'title' => '制作・施工', 'desc' => '自社工場での制作と現場施工を一貫管理。品質とスケジュールを厳密にコントロールします。'],
                ['num' => '05', 'title' => '運営・実施', 'desc' => 'イベント当日の運営サポート、トラブル対応、来場者対応まで万全の体制で臨みます。'],
                ['num' => '06', 'title' => '撤去・報告', 'desc' => '撤去作業、効果測定レポートの作成。次回プロジェクトへの改善提案も行います。'],
            ];
            foreach ($steps as $step) : ?>
            <div class="h5-workflow-step">
                <div class="h5-workflow-num"><?php echo $step['num']; ?></div>
                <div class="h5-workflow-body">
                    <h3><?php echo esc_html(__($step['title'], 'eikou')); ?></h3>
                    <p><?php echo esc_html(__($step['desc'], 'eikou')); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include get_template_directory() . '/h5/footer.php'; ?>
