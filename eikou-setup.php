<?php
/**
 * EIKOU WordPress Setup Script
 *
 * 通过浏览器访问 http://127.0.0.1/eikou-setup.php 执行
 * 完成后请删除此文件
 */

require_once __DIR__ . '/wp-load.php';

// 仅管理员可执行
if (!current_user_can('manage_options')) {
    wp_die('请先登录管理员账号再访问此页面');
}

$results = [];

// ─── 1. 创建页面 ───
$pages = [
    ['title' => 'ホーム',         'slug' => 'home'],
    ['title' => '荣光について',    'slug' => 'about'],
    ['title' => 'サービス',       'slug' => 'services'],
    ['title' => '成功事例',       'slug' => 'works'],
    ['title' => '動画',           'slug' => 'video'],
    ['title' => 'パートナー',     'slug' => 'partners'],
    ['title' => 'お問い合わせ',   'slug' => 'contact'],
];

foreach ($pages as $page) {
    $existing = get_page_by_path($page['slug']);
    if ($existing) {
        $results[] = "✓ 页面「{$page['title']}」已存在 (ID: {$existing->ID})";
    } else {
        $id = wp_insert_post([
            'post_title'  => $page['title'],
            'post_name'   => $page['slug'],
            'post_status' => 'publish',
            'post_type'   => 'page',
        ]);
        if (is_wp_error($id)) {
            $results[] = "✗ 创建页面「{$page['title']}」失败: " . $id->get_error_message();
        } else {
            $results[] = "✓ 创建页面「{$page['title']}」成功 (ID: {$id}, slug: {$page['slug']})";
        }
    }
}

// ─── 2. 设置静态首页 ───
$home_page = get_page_by_path('home');
if ($home_page) {
    update_option('show_on_front', 'page');
    update_option('page_on_front', $home_page->ID);
    $results[] = "✓ 静态首页设置为「ホーム」(ID: {$home_page->ID})";
} else {
    $results[] = "✗ 未找到 slug 为 home 的页面";
}

// ─── 3. 设置固定链接为投稿名 ───
update_option('permalink_structure', '/%postname%/');
flush_rewrite_rules();
$results[] = "✓ 固定链接设置为「投稿名」(/%postname%/)";

// ─── 4. 创建事例分类 ───
$work_categories = [
    ['name' => '展示会',   'slug' => 'exhibition'],
    ['name' => 'イベント', 'slug' => 'event'],
    ['name' => '商業空間', 'slug' => 'space'],
    ['name' => 'デジタル', 'slug' => 'digital'],
];

foreach ($work_categories as $cat) {
    $existing = term_exists($cat['name'], 'work_category');
    if ($existing) {
        $results[] = "✓ 事例分类「{$cat['name']}」已存在";
    } else {
        $term = wp_insert_term($cat['name'], 'work_category', ['slug' => $cat['slug']]);
        if (is_wp_error($term)) {
            $results[] = "✗ 创建分类「{$cat['name']}」失败: " . $term->get_error_message();
        } else {
            $results[] = "✓ 创建事例分类「{$cat['name']}」成功 (slug: {$cat['slug']})";
        }
    }
}

// ─── 5. 创建导航菜单 ───
// Primary Menu
$primary_menu_name = 'Primary Menu';
$primary_menu = wp_get_nav_menu_object($primary_menu_name);
if (!$primary_menu) {
    $primary_menu_id = wp_create_nav_menu($primary_menu_name);
    $menu_pages = ['home' => 'ホーム', 'services' => 'サービス', 'works' => '成功事例', 'video' => '動画', 'partners' => 'パートナー', 'contact' => 'お問い合わせ'];
    $position = 0;
    foreach ($menu_pages as $slug => $title) {
        $page = get_page_by_path($slug);
        if ($page) {
            wp_update_nav_menu_item($primary_menu_id, 0, [
                'menu-item-title'     => $title,
                'menu-item-object'    => 'page',
                'menu-item-object-id' => $page->ID,
                'menu-item-type'      => 'post_type',
                'menu-item-status'    => 'publish',
                'menu-item-position'  => $position++,
            ]);
        }
    }
    $locations = get_theme_mod('nav_menu_locations', []);
    $locations['primary'] = $primary_menu_id;
    set_theme_mod('nav_menu_locations', $locations);
    $results[] = "✓ 创建主导航菜单「Primary Menu」并分配到 Header";
} else {
    $results[] = "✓ 主导航菜单「Primary Menu」已存在";
}

// Footer Nav
$footer_nav_name = 'Footer Nav';
$footer_nav = wp_get_nav_menu_object($footer_nav_name);
if (!$footer_nav) {
    $footer_nav_id = wp_create_nav_menu($footer_nav_name);
    $footer_pages = ['home' => 'ホーム', 'about' => '荣光について', 'services' => 'サービス', 'works' => '成功事例'];
    $position = 0;
    foreach ($footer_pages as $slug => $title) {
        $page = get_page_by_path($slug);
        if ($page) {
            wp_update_nav_menu_item($footer_nav_id, 0, [
                'menu-item-title'     => $title,
                'menu-item-object'    => 'page',
                'menu-item-object-id' => $page->ID,
                'menu-item-type'      => 'post_type',
                'menu-item-status'    => 'publish',
                'menu-item-position'  => $position++,
            ]);
        }
    }
    $locations = get_theme_mod('nav_menu_locations', []);
    $locations['footer-nav'] = $footer_nav_id;
    set_theme_mod('nav_menu_locations', $locations);
    $results[] = "✓ 创建 Footer 导航菜单并分配";
} else {
    $results[] = "✓ Footer 导航菜单已存在";
}

// Footer Other
$footer_other_name = 'Footer Other';
$footer_other = wp_get_nav_menu_object($footer_other_name);
if (!$footer_other) {
    $footer_other_id = wp_create_nav_menu($footer_other_name);
    $other_pages = ['video' => '動画センター', 'partners' => 'パートナー', 'contact' => 'お問い合わせ'];
    $position = 0;
    foreach ($other_pages as $slug => $title) {
        $page = get_page_by_path($slug);
        if ($page) {
            wp_update_nav_menu_item($footer_other_id, 0, [
                'menu-item-title'     => $title,
                'menu-item-object'    => 'page',
                'menu-item-object-id' => $page->ID,
                'menu-item-type'      => 'post_type',
                'menu-item-status'    => 'publish',
                'menu-item-position'  => $position++,
            ]);
        }
    }
    $locations = get_theme_mod('nav_menu_locations', []);
    $locations['footer-other'] = $footer_other_id;
    set_theme_mod('nav_menu_locations', $locations);
    $results[] = "✓ 创建 Footer Other 菜单并分配";
} else {
    $results[] = "✓ Footer Other 菜单已存在";
}

// ─── 输出结果 ───
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>EIKOU Setup Complete</title>
    <style>
        body { font-family: -apple-system, sans-serif; max-width: 700px; margin: 40px auto; padding: 20px; background: #1a1a2e; color: #eee; }
        h1 { color: #c9a84c; border-bottom: 2px solid #c9a84c; padding-bottom: 10px; }
        .result { padding: 8px 12px; margin: 4px 0; border-radius: 4px; background: rgba(255,255,255,0.05); }
        .result:nth-child(odd) { background: rgba(255,255,255,0.08); }
        .warning { margin-top: 20px; padding: 15px; background: #c9a84c22; border: 1px solid #c9a84c; border-radius: 6px; color: #c9a84c; }
        a { color: #c9a84c; }
    </style>
</head>
<body>
    <h1>EIKOU Setup Results</h1>
    <?php foreach ($results as $r) : ?>
        <div class="result"><?php echo esc_html($r); ?></div>
    <?php endforeach; ?>
    <div class="warning">
        <strong>⚠ 重要：</strong>设置完成后请立即删除此文件（eikou-setup.php），避免安全风险。<br><br>
        <a href="<?php echo esc_url(home_url('/')); ?>">→ 访问前台首页</a> ｜
        <a href="<?php echo esc_url(admin_url()); ?>">→ 进入后台</a>
    </div>
</body>
</html>
