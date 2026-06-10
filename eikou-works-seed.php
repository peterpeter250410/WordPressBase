<?php
/**
 * EIKOU Works Content Seeder
 * 访问 http://127.0.0.1/eikou-works-seed.php 执行
 * 完成后请删除此文件
 */
require_once __DIR__ . '/wp-load.php';

if (!current_user_can('manage_options')) {
    wp_die('请先登录管理员账号');
}

$results = [];

// 确保分类存在
$categories = [
    'exhibition' => '展示会',
    'event'      => 'イベント',
    'space'      => '商業空間',
    'digital'    => 'デジタル',
];
foreach ($categories as $slug => $name) {
    if (!term_exists($name, 'work_category')) {
        wp_insert_term($name, 'work_category', ['slug' => $slug]);
    }
}

// 成功事例数据
$works = [
    [
        'title'    => '国際展示会ブースデザイン',
        'excerpt'  => '東京ビッグサイトで開催された国際展示会において、500㎡の大型ブースをデザイン・施工。来場者数前年比150%を達成。',
        'content'  => '<h2>プロジェクト概要</h2>
<p>東京ビッグサイトで開催された日本最大級の国際展示会において、クライアント様の大型ブース（500㎡）のデザインから施工、運営までをワンストップで担当しました。</p>

<h2>課題</h2>
<p>グローバル企業としてのブランドイメージを維持しつつ、来場者の注目を集める空間演出が求められました。限られた設営期間（3日間）の中で、高品質な仕上がりを実現する必要がありました。</p>

<h2>ソリューション</h2>
<ul>
<li>大型LEDウォール（横12m×高さ3m）による没入型映像演出</li>
<li>インタラクティブタッチパネルによる製品体験コーナー</li>
<li>VIPラウンジ＆商談スペースの設計</li>
<li>多言語対応の運営スタッフ配置</li>
</ul>

<h2>成果</h2>
<p>来場者数は前年比150%を達成。商談件数も大幅に増加し、クライアント様から高い評価をいただきました。本プロジェクトは業界誌にも取り上げられ、展示会デザインのベストプラクティスとして紹介されました。</p>',
        'image'    => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1200&q=80',
        'category' => 'exhibition',
        'location' => '東京ビッグサイト',
        'year'     => '2024',
    ],
    [
        'title'    => '新製品発表会プロデュース',
        'excerpt'  => '六本木ヒルズにて開催された新製品発表会。300名の招待客に向け、ブランドの世界観を体験として具現化。',
        'content'  => '<h2>プロジェクト概要</h2>
<p>大手家電メーカーの新製品発表会を六本木ヒルズにてプロデュース。メディア関係者・バイヤー・VIP顧客など300名を招待し、製品の革新性を体験を通じて伝えるイベントを企画・実施しました。</p>

<h2>課題</h2>
<p>新製品の技術的優位性を、専門知識のない一般消費者にも直感的に理解してもらえる演出が求められました。</p>

<h2>ソリューション</h2>
<ul>
<li>製品体験ゾーンの設計（5つのテーマ別エリア）</li>
<li>プロジェクションマッピングによるオープニング演出</li>
<li>ライブデモンストレーションの企画・進行</li>
<li>メディア向けフォトブースの設置</li>
<li>SNS連動型のデジタルサイネージ</li>
</ul>

<h2>成果</h2>
<p>メディア掲載数100件以上、SNSでの関連投稿は発表後1週間で5万件を超えました。製品の予約注文は目標の200%を達成しました。</p>',
        'image'    => 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=1200&q=80',
        'category' => 'event',
        'location' => '六本木ヒルズ',
        'year'     => '2024',
    ],
    [
        'title'    => '企業ショールームリニューアル',
        'excerpt'  => '銀座の企業ショールームを全面リニューアル。最新のインタラクティブ技術を導入し、ブランド体験を刷新。',
        'content'  => '<h2>プロジェクト概要</h2>
<p>銀座に位置する大手メーカーの企業ショールーム（300㎡）を全面リニューアル。10年ぶりの大規模改装として、最新のインタラクティブ技術を導入し、来館者の体験を根本から刷新しました。</p>

<h2>課題</h2>
<p>既存のショールームは製品陳列が中心で、来館者の滞在時間が短く、ブランドへの理解度向上につながっていませんでした。</p>

<h2>ソリューション</h2>
<ul>
<li>タッチレスセンサーによるインタラクティブ展示</li>
<li>AR（拡張現実）を活用した製品シミュレーション</li>
<li>空間全体の照明デザインリニューアル</li>
<li>ブランドストーリーを伝えるシアタールーム新設</li>
</ul>

<h2>成果</h2>
<p>来館者の平均滞在時間は改装前の2倍に増加。顧客満足度調査で95%以上が「非常に満足」と回答。リニューアル後3ヶ月で来館者数が40%増加しました。</p>',
        'image'    => 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=1200&q=80',
        'category' => 'space',
        'location' => '銀座',
        'year'     => '2023',
    ],
    [
        'title'    => 'ブランドポップアップストア',
        'excerpt'  => '表参道にて2週間限定のポップアップストアを企画・施工。SNS映えする空間デザインで大きな話題に。',
        'content'  => '<h2>プロジェクト概要</h2>
<p>海外ファッションブランドの日本初進出に合わせ、表参道にて2週間限定のポップアップストアを企画・施工。ブランドの世界観を凝縮した空間を創出しました。</p>

<h2>課題</h2>
<p>日本市場での認知度がゼロの状態から、短期間でブランド認知を最大化する必要がありました。</p>

<h2>ソリューション</h2>
<ul>
<li>フォトジェニックなインスタレーションアートの設置</li>
<li>限定商品の展示・販売スペース設計</li>
<li>インフルエンサー向けプレビューイベント開催</li>
<li>デジタルスタンプラリーによる回遊促進</li>
</ul>

<h2>成果</h2>
<p>2週間で来場者数15,000人を記録。Instagram関連投稿3万件以上。日本での正式展開が決定するきっかけとなりました。</p>',
        'image'    => 'https://images.unsplash.com/photo-1531058020387-3be344556be6?w=1200&q=80',
        'category' => 'event',
        'location' => '表参道',
        'year'     => '2024',
    ],
    [
        'title'    => '年次カンファレンス会場設営',
        'excerpt'  => 'パシフィコ横浜で開催された年次カンファレンス。2,000名規模の大型イベントの会場デザインから運営まで担当。',
        'content'  => '<h2>プロジェクト概要</h2>
<p>グローバルIT企業の日本法人年次カンファレンスをパシフィコ横浜にて開催。2,000名の参加者を迎える大型イベントの会場デザイン・設営・運営を一貫して担当しました。</p>

<h2>課題</h2>
<p>メインステージ、20以上のブレイクアウトセッション会場、展示エリア、ネットワーキングスペースを限られたフロアに効率的に配置する必要がありました。</p>

<h2>ソリューション</h2>
<ul>
<li>メインステージの大型LED演出（横16m×高さ4m）</li>
<li>各セッション会場の音響・映像システム構築</li>
<li>リアルタイム翻訳システムの導入</li>
<li>参加者動線を考慮したフロアレイアウト設計</li>
<li>100名体制の運営スタッフ配置</li>
</ul>

<h2>成果</h2>
<p>参加者満足度96%。「会場設営・運営が過去最高」との評価をいただき、翌年の開催も継続受注しました。</p>',
        'image'    => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=1200&q=80',
        'category' => 'event',
        'location' => 'パシフィコ横浜',
        'year'     => '2023',
    ],
    [
        'title'    => 'AIダッシュボード開発',
        'excerpt'  => '製造業向けAI搭載のリアルタイムダッシュボードを開発。生産効率の可視化と予測分析を実現。',
        'content'  => '<h2>プロジェクト概要</h2>
<p>大手製造業クライアント向けに、AI搭載のリアルタイム生産管理ダッシュボードを設計・開発。工場の生産データをリアルタイムで可視化し、AIによる予測分析機能を提供しました。</p>

<h2>課題</h2>
<p>複数工場のデータが分散管理されており、全体の生産状況をリアルタイムで把握できない状況でした。また、品質異常の検知が事後対応になっていました。</p>

<h2>ソリューション</h2>
<ul>
<li>リアルタイムデータ統合基盤の構築</li>
<li>AIによる異常検知・予測モデルの開発</li>
<li>直感的なダッシュボードUIの設計</li>
<li>モバイル対応のアラートシステム</li>
<li>段階的な導入計画と社内トレーニング</li>
</ul>

<h2>成果</h2>
<p>品質異常の早期検知率が80%向上。生産効率が15%改善。年間コスト削減効果は約2億円と試算されています。</p>',
        'image'    => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=1200&q=80',
        'category' => 'digital',
        'location' => 'オンライン',
        'year'     => '2024',
    ],
    [
        'title'    => '自動車技術展ブース',
        'excerpt'  => '幕張メッセで開催された自動車技術展にて、次世代モビリティを体感できる没入型ブースを制作。',
        'content'  => '<h2>プロジェクト概要</h2>
<p>幕張メッセで開催された自動車技術展において、大手自動車部品メーカーの次世代技術展示ブース（400㎡）をデザイン・施工しました。</p>

<h2>課題</h2>
<p>B2B向けの技術展示でありながら、来場者に技術の将来性と革新性を体感してもらう必要がありました。</p>

<h2>ソリューション</h2>
<ul>
<li>VRドライビングシミュレーターの設置</li>
<li>実物大カットモデルの展示台設計</li>
<li>技術解説用のインタラクティブ映像パネル</li>
<li>商談ブースの防音設計</li>
</ul>

<h2>成果</h2>
<p>ブース来場者数は出展企業中トップ3を記録。商談件数200件以上を獲得し、展示会の最優秀ブースデザイン賞を受賞しました。</p>',
        'image'    => 'https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?w=1200&q=80',
        'category' => 'exhibition',
        'location' => '幕張メッセ',
        'year'     => '2023',
    ],
    [
        'title'    => '旗艦店リニューアル',
        'excerpt'  => '青山の旗艦店を全面リニューアル。ブランドの新しいコンセプトを空間デザインで表現。',
        'content'  => '<h2>プロジェクト概要</h2>
<p>青山に位置するラグジュアリーブランドの旗艦店（250㎡）を全面リニューアル。ブランドリニューアルに合わせた新しいコンセプトを、空間デザインとして表現しました。</p>

<h2>課題</h2>
<p>ブランドイメージの刷新に合わせ、店舗空間を「商品販売の場」から「ブランド体験の場」へと転換する必要がありました。</p>

<h2>ソリューション</h2>
<ul>
<li>自然素材とテクノロジーを融合した内装デザイン</li>
<li>商品ごとの世界観を表現するゾーニング設計</li>
<li>デジタルミラーによるバーチャル試着システム</li>
<li>調光可能なアンビエント照明システム</li>
<li>香り演出による五感マーケティング</li>
</ul>

<h2>成果</h2>
<p>リニューアルオープン後、売上が前年比35%増加。平均客単価も20%向上。建築・デザイン専門誌3誌に掲載されました。</p>',
        'image'    => 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?w=1200&q=80',
        'category' => 'space',
        'location' => '青山',
        'year'     => '2024',
    ],
];

// Unsplash画像をダウンロードしてWordPressに保存
function eikou_sideload_image($url, $post_id, $desc) {
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $tmp = download_url($url);
    if (is_wp_error($tmp)) {
        return $tmp;
    }

    $file_array = [
        'name'     => sanitize_file_name($desc) . '.jpg',
        'tmp_name' => $tmp,
    ];

    $id = media_handle_sideload($file_array, $post_id, $desc);
    if (is_wp_error($id)) {
        @unlink($tmp);
    }
    return $id;
}

// 创建事例文章
foreach ($works as $work) {
    // 检查是否已存在
    $existing = get_page_by_title($work['title'], OBJECT, 'work');
    if ($existing) {
        $results[] = "✓ 事例「{$work['title']}」已存在 (ID: {$existing->ID})";
        continue;
    }

    // 创建文章
    $post_id = wp_insert_post([
        'post_title'   => $work['title'],
        'post_content' => $work['content'],
        'post_excerpt' => $work['excerpt'],
        'post_status'  => 'publish',
        'post_type'    => 'work',
    ]);

    if (is_wp_error($post_id)) {
        $results[] = "✗ 创建事例「{$work['title']}」失败: " . $post_id->get_error_message();
        continue;
    }

    // 设置分类
    $term = get_term_by('slug', $work['category'], 'work_category');
    if ($term) {
        wp_set_object_terms($post_id, $term->term_id, 'work_category');
    }

    // 设置元数据
    update_post_meta($post_id, '_work_location', $work['location']);
    update_post_meta($post_id, '_work_year', $work['year']);

    // 下载并设置特色图片
    $img_id = eikou_sideload_image($work['image'], $post_id, $work['title']);
    if (!is_wp_error($img_id)) {
        set_post_thumbnail($post_id, $img_id);
        $results[] = "✓ 创建事例「{$work['title']}」成功 (ID: {$post_id}, 图片已下载)";
    } else {
        $results[] = "△ 创建事例「{$work['title']}」成功 (ID: {$post_id}, 图片下载失败: " . $img_id->get_error_message() . ")";
    }
}

// 输出结果
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>EIKOU Works Seeder</title>
    <style>
        body { font-family: -apple-system, sans-serif; max-width: 700px; margin: 40px auto; padding: 20px; background: #1a1a2e; color: #eee; }
        h1 { color: #c9a84c; border-bottom: 2px solid #c9a84c; padding-bottom: 10px; }
        .result { padding: 8px 12px; margin: 4px 0; border-radius: 4px; background: rgba(255,255,255,0.05); }
        .result:nth-child(odd) { background: rgba(255,255,255,0.08); }
        .warning { margin-top: 20px; padding: 15px; background: #c9a84c22; border: 1px solid #c9a84c; border-radius: 6px; color: #c9a84c; }
        a { color: #c9a84c; }
        .stats { margin: 20px 0; padding: 15px; background: rgba(201,168,76,0.1); border-radius: 6px; }
    </style>
</head>
<body>
    <h1>成功事例コンテンツ生成結果</h1>
    <div class="stats">
        <strong>生成数:</strong> <?php echo count($works); ?> 件の成功事例
    </div>
    <?php foreach ($results as $r) : ?>
        <div class="result"><?php echo esc_html($r); ?></div>
    <?php endforeach; ?>
    <div class="warning">
        <strong>⚠ 重要：</strong>完成後、このファイル（eikou-works-seed.php）を削除してください。<br><br>
        <a href="<?php echo esc_url(home_url('/')); ?>">→ 首页查看效果</a> ｜
        <a href="<?php echo esc_url(home_url('/works/')); ?>">→ 成功事例一览</a> ｜
        <a href="<?php echo esc_url(admin_url('edit.php?post_type=work')); ?>">→ 后台管理事例</a>
    </div>
</body>
</html>
