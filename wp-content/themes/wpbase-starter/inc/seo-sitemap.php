<?php
/**
 * SEO Sitemap — 多语言站点地图
 *
 * WordPress 默认的 wp-sitemap.xml 只含日文 URL：functions.php 中的
 * eikou_localize_url() 明确把 wp-sitemap 排除在语言前缀之外，导致
 * /zh/ 与 /en/ 完全没有收录入口。
 *
 * 本文件提供 /sitemap-i18n.xml，每个路径输出三条 <url>（ja/zh/en），
 * 每条都带完整的 xhtml:link 语言标注，与页面上的 hreflang 互为佐证。
 *
 * 注意：新增了重写规则，部署后必须执行 wp rewrite flush，否则 404。
 *
 * @package wpbase-starter
 */

if (!defined('ABSPATH')) {
    exit;
}

/* ─── 路由：/sitemap-i18n.xml ─── */

add_action('init', function () {
    add_rewrite_rule('^sitemap-i18n\.xml$', 'index.php?eikou_sitemap=i18n', 'top');
});

add_filter('query_vars', function ($vars) {
    $vars[] = 'eikou_sitemap';
    return $vars;
});

/**
 * 收集全站「无语言前缀」的路径
 *
 * @return array path => ['priority' => string, 'lastmod' => string]
 */
function eikou_sitemap_collect_paths() {
    $paths = ['/' => ['priority' => '1.0', 'lastmod' => '']];

    $strip = function ($url) {
        $path = parse_url((string) $url, PHP_URL_PATH);
        if (!$path) {
            return null;
        }
        return eikou_seo_strip_lang_prefix($path);
    };

    $front_id = (int) get_option('page_on_front');

    // 页面
    $pages = get_posts([
        'post_type'      => 'page',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ]);
    foreach ($pages as $p) {
        if ($p->ID === $front_id) {
            continue;
        }
        $path = $strip(get_permalink($p));
        if (!$path) {
            continue;
        }
        // 服务详情页是长尾流量主力，权重高于普通页面
        $is_service = (strpos($p->post_name, 'service-') === 0);
        $paths[$path] = [
            'priority' => $is_service ? '0.9' : '0.8',
            'lastmod'  => get_post_modified_time('c', true, $p),
        ];
    }

    // 自定义文章类型
    foreach (['work', 'eikou_video', 'partner'] as $pt) {
        $items = get_posts([
            'post_type'      => $pt,
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ]);
        foreach ($items as $p) {
            $path = $strip(get_permalink($p));
            if (!$path) {
                continue;
            }
            $paths[$path] = [
                'priority' => '0.7',
                'lastmod'  => get_post_modified_time('c', true, $p),
            ];
        }
    }

    // 分类法归档
    $terms = get_terms(['taxonomy' => 'work_category', 'hide_empty' => true]);
    if (!is_wp_error($terms)) {
        foreach ($terms as $t) {
            $link = get_term_link($t);
            if (is_wp_error($link)) {
                continue;
            }
            $path = $strip($link);
            if ($path) {
                $paths[$path] = ['priority' => '0.6', 'lastmod' => ''];
            }
        }
    }

    return $paths;
}

/**
 * 关闭 sitemap 的规范化重定向
 *
 * 固定链接结构是 /%postname%/，WordPress 的 redirect_canonical 会给所有
 * URL 补尾斜杠，导致 /sitemap-i18n.xml 被 301 到 /sitemap-i18n.xml/。
 * robots.txt 声明的是不带斜杠的地址，每次抓取都要多跳一次，且部分
 * sitemap 校验工具会直接拒绝会跳转的地址。
 *
 * 优先级 5：必须早于核心的 redirect_canonical（template_redirect 上默认 10）。
 */
add_filter('redirect_canonical', function ($redirect_url) {
    return (get_query_var('eikou_sitemap') === 'i18n') ? false : $redirect_url;
}, 5);

/* ─── 输出 ─── */

add_action('template_redirect', function () {
    if (get_query_var('eikou_sitemap') !== 'i18n') {
        return;
    }

    header('Content-Type: application/xml; charset=UTF-8');
    header('X-Robots-Tag: noindex');   // sitemap 本身不需要被索引

    $paths     = eikou_sitemap_collect_paths();
    $lang_map  = eikou_seo_hreflang_map();

    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" '
       . 'xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";

    foreach ($paths as $path => $meta) {
        // 每个路径 × 3 语言，每条 <url> 都列出全部语言的 alternate
        foreach (array_keys($lang_map) as $lang) {
            echo "  <url>\n";
            printf("    <loc>%s</loc>\n", esc_url(eikou_seo_url_for($lang, $path)));

            if (!empty($meta['lastmod'])) {
                printf("    <lastmod>%s</lastmod>\n", esc_html($meta['lastmod']));
            }

            foreach ($lang_map as $l => $code) {
                printf("    <xhtml:link rel=\"alternate\" hreflang=\"%s\" href=\"%s\"/>\n",
                    esc_attr($code), esc_url(eikou_seo_url_for($l, $path)));
            }
            printf("    <xhtml:link rel=\"alternate\" hreflang=\"x-default\" href=\"%s\"/>\n",
                esc_url(eikou_seo_url_for('ja', $path)));

            printf("    <priority>%s</priority>\n", esc_html($meta['priority']));
            echo "  </url>\n";
        }
    }

    echo '</urlset>';
    exit;
});

/* ─── 主题切换时刷新重写规则（部署仍需手动 wp rewrite flush）─── */

add_action('after_switch_theme', function () {
    add_rewrite_rule('^sitemap-i18n\.xml$', 'index.php?eikou_sitemap=i18n', 'top');
    flush_rewrite_rules();
}, 20);
