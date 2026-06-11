<?php
/**
 * Template Name: Service - AIソリューション
 * Slug: service-ai
 */
get_header();
?>

<!-- ========== PAGE HERO ========== -->
<section class="page-hero">
    <div class="page-hero-bg" style="background-image: url('https://images.unsplash.com/photo-1677442136019-21780ecad995?w=1920&q=80')"></div>
    <div class="page-hero-overlay"></div>
    <div class="container page-hero-content">
        <span class="section-tag">AI Solutions</span>
        <h1>AIソリューション</h1>
        <p>AI技術の導入で、業務効率と顧客体験を革新します</p>
    </div>
</section>

<!-- ========== CATEGORY INTRO ========== -->
<section class="section">
    <div class="container">
        <div class="service-category-intro scroll-reveal">
            <p>最先端のAI技術を活用し、企業の業務効率化と顧客体験の向上を支援します。AIチャットボットによる24時間対応のカスタマーサポート、機械学習モデルを活用したビジネス分析、自動化ワークフローシステムの構築など、お客様のビジネス課題に合わせた最適なAIソリューションを提供します。</p>
        </div>
    </div>
</section>

<!-- ========== 3 SUB-SERVICES ========== -->
<section class="section" style="background: var(--color-bg-alt);">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Services</span>
            <h2 class="section-title">サービス内容</h2>
        </div>
        <div class="service-detail-grid">

            <div class="service-detail-card scroll-reveal">
                <div class="service-detail-number">01</div>
                <h3>AIチャットボット導入</h3>
                <p>企業のWebサイトやSNSに統合できるAIチャットボットを開発・導入します。自然言語処理（NLP）技術を活用し、顧客からの問い合わせに24時間自動対応。多言語対応も可能で、日本語・中国語・英語での顧客サポートを実現します。</p>
                <ul class="service-detail-list">
                    <li>GPT/LLM技術を活用したインテリジェントチャットボット</li>
                    <li>FAQ自動応答・問い合わせ分類システム</li>
                    <li>LINE・WeChat・Webサイトへの統合</li>
                    <li>多言語対応（日本語・中国語・英語）</li>
                    <li>チャット履歴分析・改善レポート</li>
                </ul>
                <ul class="business-tags">
                    <li>チャットボット</li>
                    <li>NLP</li>
                    <li>多言語対応</li>
                </ul>
            </div>

            <div class="service-detail-card scroll-reveal">
                <div class="service-detail-number">02</div>
                <h3>AIモデリング・AIアプリケーション</h3>
                <p>機械学習・ディープラーニングを活用したAIモデルの設計・構築・運用を提供します。画像認識、需要予測、レコメンデーション、異常検知など、ビジネス課題に応じたカスタムAIソリューションを開発し、データ駆動型の意思決定を支援します。</p>
                <ul class="service-detail-list">
                    <li>カスタムAIモデルの設計・学習・デプロイ</li>
                    <li>画像認識・物体検出システムの開発</li>
                    <li>需要予測・売上分析AIの構築</li>
                    <li>3Dモデリング・バーチャル空間のAI生成</li>
                    <li>AIモデルの継続的改善・運用保守</li>
                </ul>
                <ul class="business-tags">
                    <li>機械学習</li>
                    <li>画像認識</li>
                    <li>予測分析</li>
                </ul>
            </div>

            <div class="service-detail-card scroll-reveal">
                <div class="service-detail-number">03</div>
                <h3>自動化カスタマーサポートシステム</h3>
                <p>顧客からの問い合わせ管理を自動化するチケットシステムを構築します。AI分類・優先度判定・自動ルーティングにより、対応速度と顧客満足度を大幅に向上。CRM連携で顧客データの一元管理も実現します。</p>
                <ul class="service-detail-list">
                    <li>AI駆動の問い合わせ分類・優先度自動判定</li>
                    <li>チケット自動ルーティング・エスカレーション</li>
                    <li>顧客対応テンプレート・ナレッジベース構築</li>
                    <li>CRM・既存業務システムとのAPI連携</li>
                    <li>対応品質の分析ダッシュボード</li>
                </ul>
                <ul class="business-tags">
                    <li>自動化</li>
                    <li>チケットシステム</li>
                    <li>CRM連携</li>
                </ul>
            </div>

        </div>
    </div>
</section>

<?php get_footer(); ?>
