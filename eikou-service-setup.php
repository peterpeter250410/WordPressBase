<?php
/**
 * EIKOU Service Pages & Video Seeder
 * 访问 http://127.0.0.1/eikou-service-setup.php 执行
 * 完成后请删除此文件
 */
require_once __DIR__ . '/wp-load.php';

if (!current_user_can('manage_options')) {
    wp_die('请先登录管理员账号');
}

$results = [];

// ========== 1. 创建6个服务分类页面 ==========
$service_pages = [
    ['title' => '展示会・イベント',       'slug' => 'service-exhibition'],
    ['title' => 'ブランドイベント',        'slug' => 'service-brand-event'],
    ['title' => 'デジタル・Web',           'slug' => 'service-digital'],
    ['title' => 'AIソリューション',        'slug' => 'service-ai'],
    ['title' => 'ブランディング・デザイン', 'slug' => 'service-branding'],
    ['title' => 'メディア・映像',          'slug' => 'service-media'],
];

foreach ($service_pages as $sp) {
    $existing = get_page_by_path($sp['slug']);
    if ($existing) {
        $results[] = "✓ 页面「{$sp['title']}」已存在 (ID: {$existing->ID})";
        continue;
    }

    $page_id = wp_insert_post([
        'post_title'   => $sp['title'],
        'post_name'    => $sp['slug'],
        'post_content' => '',
        'post_status'  => 'publish',
        'post_type'    => 'page',
    ]);

    if (is_wp_error($page_id)) {
        $results[] = "✗ 创建页面「{$sp['title']}」失败: " . $page_id->get_error_message();
    } else {
        $results[] = "✓ 创建页面「{$sp['title']}」成功 (ID: {$page_id}, slug: {$sp['slug']})";
    }
}

// ========== 2. 创建10个视频 CPT 条目 ==========
$video_base_url = content_url('/uploads/videos/');

$video_entries = [
    ['title' => 'CATL巡回展示会 - マツダ',     'file' => '宁德时代马自达巡展.mp4'],
    ['title' => 'CATL巡回展示会 - 日産',        'file' => '宁德时代日产巡展.mp4'],
    ['title' => 'CATL巡回展示会 - 三菱',        'file' => '宁德时代三菱巡展.mp4'],
    ['title' => 'CATL巡回展示会 - トヨタ',      'file' => '宁德时代丰田巡展.mp4'],
    ['title' => 'CATL巡回展示会 - ホンダ',      'file' => '宁德时代本田巡展.mp4'],
    ['title' => '高景ソーラー 新エネルギー技術発表会', 'file' => '高景日本新能源技术发布会.mp4'],
    ['title' => 'Momentum Electric Marine @ JAPAN BOAT SHOW 2026', 'file' => 'Momentum Electric Marine 亮相 JAPAN BOAT SHOW 2026.mp4'],
    ['title' => '德擎光学 展示ブース',           'file' => '德擎光学.mp4'],
    ['title' => 'HELIX ゴルフブランド Japan Golf Fair', 'file' => 'HELIX 高尔夫品牌亮相日本高尔夫展.mp4'],
    ['title' => '海辰エナジー 製品発表会',       'file' => '海辰储能产品发布会暨日本分公司成立一周年庆典.mp4'],
];

foreach ($video_entries as $ve) {
    // 检查是否已存在
    $existing = get_page_by_title($ve['title'], OBJECT, 'eikou_video');
    if ($existing) {
        $results[] = "✓ 动画「{$ve['title']}」已存在 (ID: {$existing->ID})";
        continue;
    }

    $post_id = wp_insert_post([
        'post_title'  => $ve['title'],
        'post_status' => 'publish',
        'post_type'   => 'eikou_video',
    ]);

    if (is_wp_error($post_id)) {
        $results[] = "✗ 创建动画「{$ve['title']}」失败: " . $post_id->get_error_message();
        continue;
    }

    // 设置视频 URL
    $full_url = $video_base_url . rawurlencode($ve['file']);
    update_post_meta($post_id, '_video_url', $full_url);

    $results[] = "✓ 创建动画「{$ve['title']}」成功 (ID: {$post_id})";
}

// ========== 3. 更新导航菜单 ==========
// 检查并更新 primary 菜单，添加服务子菜单
$menu_name = 'primary';
$menu = wp_get_nav_menu_object($menu_name);
if (!$menu) {
    // 尝试获取已分配的菜单
    $locations = get_nav_menu_locations();
    if (isset($locations['primary'])) {
        $menu = wp_get_nav_menu_object($locations['primary']);
    }
}

if ($menu) {
    $results[] = "ℹ 导航菜单「{$menu->name}」已存在，请手动添加服务子页面到菜单中";
} else {
    $results[] = "ℹ 未找到主导航菜单，使用 fallback 导航（已包含服务子菜单）";
}

// ========== 4. 刷新固定链接 ==========
flush_rewrite_rules();
$results[] = "✓ 固定链接已刷新";

// 输出结果
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>EIKOU Service & Video Setup</title>
    <style>
        body { font-family: -apple-system, sans-serif; max-width: 700px; margin: 40px auto; padding: 20px; background: #1a1a2e; color: #eee; }
        h1 { color: #c9a84c; border-bottom: 2px solid #c9a84c; padding-bottom: 10px; }
        h2 { color: #c9a84c; font-size: 18px; margin-top: 30px; }
        .result { padding: 8px 12px; margin: 4px 0; border-radius: 4px; background: rgba(255,255,255,0.05); font-size: 14px; }
        .result:nth-child(odd) { background: rgba(255,255,255,0.08); }
        .warning { margin-top: 20px; padding: 15px; background: #c9a84c22; border: 1px solid #c9a84c; border-radius: 6px; color: #c9a84c; }
        a { color: #c9a84c; }
        .stats { margin: 20px 0; padding: 15px; background: rgba(201,168,76,0.1); border-radius: 6px; }
    </style>
</head>
<body>
    <h1>EIKOU サービス & 動画 セットアップ</h1>
    <div class="stats">
        <strong>処理項目:</strong> <?php echo count($service_pages); ?> 个服务页面 + <?php echo count($video_entries); ?> 个动画条目
    </div>

    <h2>📄 服务页面</h2>
    <?php
    $page_results = array_slice($results, 0, count($service_pages));
    foreach ($page_results as $r) : ?>
        <div class="result"><?php echo esc_html($r); ?></div>
    <?php endforeach; ?>

    <h2>🎬 动画条目</h2>
    <?php
    $video_results = array_slice($results, count($service_pages), count($video_entries));
    foreach ($video_results as $r) : ?>
        <div class="result"><?php echo esc_html($r); ?></div>
    <?php endforeach; ?>

    <h2>⚙ 系统设置</h2>
    <?php
    $system_results = array_slice($results, count($service_pages) + count($video_entries));
    foreach ($system_results as $r) : ?>
        <div class="result"><?php echo esc_html($r); ?></div>
    <?php endforeach; ?>

    <div class="warning">
        <strong>⚠ 重要：</strong>完成后请删除此文件（eikou-service-setup.php）。<br><br>
        <a href="<?php echo esc_url(home_url('/')); ?>">→ 首页查看效果</a> ｜
        <a href="<?php echo esc_url(home_url('/services/')); ?>">→ 服务总览页</a> ｜
        <a href="<?php echo esc_url(home_url('/video/')); ?>">→ 动画中心</a> ｜
        <a href="<?php echo esc_url(admin_url('edit.php?post_type=page')); ?>">→ 后台管理页面</a> ｜
        <a href="<?php echo esc_url(admin_url('edit.php?post_type=eikou_video')); ?>">→ 后台管理动画</a>
    </div>
</body>
</html>
