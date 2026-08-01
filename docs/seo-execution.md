# EIKOU SEO 执行手册

> 配套战略文档：[seo-strategy.md](seo-strategy.md)
> 本文按「文件 → 改动 → 代码 → 验证」粒度组织，可直接照做。

**执行前提：** 所有代码改动走 `claude/rg-seo-strategy-1ytuiv` 分支 → 服务器 `bash scripts/server-deploy.sh` 部署。

---

# P0 · 技术地基（第 1–2 周）

## 目录规划

新建 `wp-content/themes/wpbase-starter/inc/`：

```
inc/
├── seo-core.php        # canonical / hreflang / title / description / OG
├── seo-schema.php      # JSON-LD 结构化数据
├── seo-sitemap.php     # 多语言 sitemap
└── seo-internal-link.php  # 内链自动化（P1）
```

在 `functions.php` 末尾引入：

```php
/* ─── SEO ─── */
require_once get_template_directory() . '/inc/seo-core.php';
require_once get_template_directory() . '/inc/seo-schema.php';
require_once get_template_directory() . '/inc/seo-sitemap.php';
```

---

## E1. 语言与 URL 基础函数

> 这几个函数是后面所有 SEO 代码的地基。关键点：`home_url()` 已被 `eikou_localize_url()` 过滤会自动加语言前缀，**因此构造多语言 URL 时必须用 `get_option('home')` 拿未过滤的原始域名**，否则会出现 `/zh/zh/` 双前缀。

`inc/seo-core.php`：

```php
<?php
if (!defined('ABSPATH')) { exit; }

/** 未经语言前缀过滤的站点根 URL（重要：不要用 home_url()） */
function eikou_seo_raw_home() {
    return untrailingslashit(get_option('home'));
}

/** hreflang 语言码映射：内部 slug => hreflang 值 */
function eikou_seo_hreflang_map() {
    return [
        'ja' => 'ja',
        'zh' => 'zh-Hans',   // 简体中文，覆盖中国大陆+海外华人；如只面向大陆可改 zh-CN
        'en' => 'en',
    ];
}

/** 当前页面「剥离语言前缀后」的路径，如 /about/ */
function eikou_seo_base_path() {
    $url = '';
    if (is_front_page() || is_home()) {
        $url = '/';
    } elseif (is_singular()) {
        $url = parse_url((string) get_permalink(get_queried_object_id()), PHP_URL_PATH);
    } elseif (is_post_type_archive()) {
        $url = parse_url((string) get_post_type_archive_link(get_post_type()), PHP_URL_PATH);
    } elseif (is_tax() || is_category() || is_tag()) {
        $link = get_term_link(get_queried_object());
        $url  = is_wp_error($link) ? '/' : parse_url((string) $link, PHP_URL_PATH);
    }
    if (!$url) { $url = '/'; }

    // get_permalink 已被 eikou_localize_url 加过前缀，这里剥掉，得到纯路径
    $keys = implode('|', array_keys(eikou_lang_map()));
    $url  = preg_replace('#^/(' . $keys . ')(/|$)#', '/', $url);

    return '/' . ltrim($url, '/');
}

/** 指定语言下的绝对 URL */
function eikou_seo_url_for($lang, $path = null) {
    if ($path === null) { $path = eikou_seo_base_path(); }
    $home = eikou_seo_raw_home();
    return ($lang === 'ja') ? $home . $path : $home . '/' . $lang . $path;
}

/** 是否应该输出 SEO 标签（搜索页/404 不输出 canonical & hreflang） */
function eikou_seo_is_indexable() {
    return !(is_404() || is_search() || is_paged() && is_search());
}
```

---

## E2. canonical + hreflang（P0 最高优先级）

> **为什么必须自建 canonical：** `functions.php:63` 为避免重定向死循环，对次级语言整体关闭了 `redirect_canonical`。这个决定是对的、不要改回去，但副作用是 `/zh/about`、`/zh/about/`、`/zh/about/?utm=x` 都返回 200。canonical 是唯一的兜底。

追加到 `inc/seo-core.php`：

```php
/** canonical */
function eikou_seo_canonical() {
    if (!eikou_seo_is_indexable()) { return; }
    $url = eikou_seo_url_for(eikou_current_lang());

    // 分页保留页码
    $page = (int) get_query_var('paged');
    if ($page > 1) { $url = trailingslashit($url) . 'page/' . $page . '/'; }

    printf('<link rel="canonical" href="%s">' . "\n", esc_url($url));
}

/** hreflang 三语互指 + x-default */
function eikou_seo_hreflang() {
    if (!eikou_seo_is_indexable()) { return; }
    $path = eikou_seo_base_path();

    foreach (eikou_seo_hreflang_map() as $slug => $code) {
        printf('<link rel="alternate" hreflang="%s" href="%s">' . "\n",
            esc_attr($code), esc_url(eikou_seo_url_for($slug, $path)));
    }
    // x-default 指向日文（本社所在地语言）
    printf('<link rel="alternate" hreflang="x-default" href="%s">' . "\n",
        esc_url(eikou_seo_url_for('ja', $path)));
}

// WordPress 自带的 canonical 会输出未加语言前缀的 URL，必须先摘掉
remove_action('wp_head', 'rel_canonical');
add_action('wp_head', 'eikou_seo_canonical', 1);
add_action('wp_head', 'eikou_seo_hreflang', 2);
```

**hreflang 的三条硬规则（违反任何一条整组失效）：**
1. **必须互指** —— 日文页声明中文版，中文页也必须声明日文版。上面的代码在三语共用模板下天然满足。
2. **必须绝对 URL** —— 含协议和域名。
3. **被指向的 URL 必须返回 200 且自身 canonical 指向自己** —— 不能指向一个 canonical 到别处的 URL，否则 Google 直接丢弃整组 hreflang。

**验证：**
```bash
for u in "" "zh/" "en/"; do
  echo "--- /$u"
  curl -s "https://eikoujp.net/$u" | grep -E 'rel="(canonical|alternate)"'
done
```
预期：三个 URL 各输出 1 条 canonical（指向自己）+ 4 条 alternate（ja/zh-Hans/en/x-default），且三组 alternate 的 href 完全一致。

---

## E3. title 与 meta description

`title-tag` 已启用，但需要按页型注入关键词。追加到 `inc/seo-core.php`：

```php
/** 标题模板：按页型注入关键词 */
add_filter('document_title_parts', function ($parts) {
    $brand = __('荣光株式会社', 'eikou');

    if (is_front_page()) {
        // 首页标题是最重要的一处关键词位
        $parts['title']  = __('展示会ブース制作・イベント企画・商業空間デザイン', 'eikou');
        $parts['tagline'] = $brand;
        unset($parts['site']);
    } elseif (is_singular('work')) {
        $parts['title'] = get_the_title() . __('｜施工事例', 'eikou');
    } elseif (is_page() && ($item = eikou_seo_current_service_item())) {
        // 24 个服务页：用已有数据里的 title + 类目做标题
        $parts['title'] = __($item['title'], 'eikou')
                        . '｜' . __($item['category_name'], 'eikou');
    }
    return $parts;
}, 20);

/** 分隔符改为日文站惯用的全角竖线 */
add_filter('document_title_separator', function () { return '｜'; });

/** 取当前页对应的服务条目（复用已有的 24 条数据） */
function eikou_seo_current_service_item() {
    if (!is_page()) { return null; }
    $slug  = get_post_field('post_name', get_queried_object_id());
    $items = eikou_get_service_items();
    return isset($items[$slug]) ? $items[$slug] : null;
}

/** meta description */
function eikou_seo_description() {
    $desc = '';

    if (is_front_page()) {
        $desc = __('荣光株式会社は展示会ブースのデザイン・設計・施工から、ブランドイベント、商業空間、デジタル・AIソリューションまでワンストップで提供します。東京ビッグサイト・幕張メッセなど全国主要会場で500件以上の実績。日本語・中国語・英語の三言語対応。', 'eikou');
    } elseif ($item = eikou_seo_current_service_item()) {
        $desc = __($item['description'], 'eikou');
    } elseif (is_singular()) {
        $post = get_queried_object();
        $desc = has_excerpt($post) ? get_the_excerpt($post) : wp_strip_all_tags($post->post_content);
    }

    // 允许后台逐页覆盖（见 E4）
    if (is_singular()) {
        $custom = get_post_meta(get_queried_object_id(), '_eikou_seo_desc', true);
        if ($custom) { $desc = $custom; }
    }

    $desc = trim(preg_replace('/\s+/u', ' ', wp_strip_all_tags($desc)));
    if ($desc === '') { return; }

    // 日文按字符数截断（非字节），120 字符 ≈ 搜索结果显示上限
    if (mb_strlen($desc, 'UTF-8') > 120) {
        $desc = mb_substr($desc, 0, 118, 'UTF-8') . '…';
    }
    printf('<meta name="description" content="%s">' . "\n", esc_attr($desc));
    return $desc;
}
add_action('wp_head', 'eikou_seo_description', 3);
```

> **注意 `mb_substr`：** 日文 description 用 `substr` 会切碎多字节字符产生乱码。必须用 `mb_*` 系列。

---

## E4. 后台逐页覆盖 SEO 字段

给页面/案例加一个 SEO 元框，让人工可以针对高价值页手写标题描述：

```php
/** SEO 元框 */
add_action('add_meta_boxes', function () {
    foreach (['page', 'work', 'eikou_video'] as $pt) {
        add_meta_box('eikou_seo_box', 'SEO 設定', 'eikou_seo_box_html', $pt, 'normal', 'high');
    }
});

function eikou_seo_box_html($post) {
    wp_nonce_field('eikou_seo_meta', 'eikou_seo_nonce');
    $title = get_post_meta($post->ID, '_eikou_seo_title', true);
    $desc  = get_post_meta($post->ID, '_eikou_seo_desc', true);
    ?>
    <p><label><strong>SEO タイトル</strong>（空欄なら自動生成）</label><br>
    <input type="text" name="eikou_seo_title" value="<?php echo esc_attr($title); ?>" style="width:100%"></p>
    <p><label><strong>メタディスクリプション</strong>（120 文字以内推奨）</label><br>
    <textarea name="eikou_seo_desc" rows="3" style="width:100%"><?php echo esc_textarea($desc); ?></textarea></p>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['eikou_seo_nonce']) || !wp_verify_nonce($_POST['eikou_seo_nonce'], 'eikou_seo_meta')) { return; }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }
    if (!current_user_can('edit_post', $post_id)) { return; }

    foreach (['title' => '_eikou_seo_title', 'desc' => '_eikou_seo_desc'] as $k => $meta) {
        if (isset($_POST["eikou_seo_$k"])) {
            update_post_meta($post_id, $meta, sanitize_text_field(wp_unslash($_POST["eikou_seo_$k"])));
        }
    }
});

// 让自定义标题生效
add_filter('document_title_parts', function ($parts) {
    if (is_singular()) {
        $t = get_post_meta(get_queried_object_id(), '_eikou_seo_title', true);
        if ($t) { $parts['title'] = $t; }
    }
    return $parts;
}, 30);   // 优先级 30，晚于 E3 的 20，确保覆盖
```

---

## E5. OG / Twitter Card

```php
function eikou_seo_og() {
    $lang    = eikou_current_lang();
    $locales = ['ja' => 'ja_JP', 'zh' => 'zh_CN', 'en' => 'en_US'];

    $title = wp_get_document_title();
    $url   = eikou_seo_url_for($lang);
    $img   = '';

    if (is_singular() && has_post_thumbnail()) {
        $img = get_the_post_thumbnail_url(get_queried_object_id(), 'full');
    } elseif ($item = eikou_seo_current_service_item()) {
        $img = $item['hero_image'];
    }
    if (!$img) { $img = get_template_directory_uri() . '/assets/images/og-default.jpg'; }

    printf('<meta property="og:type" content="%s">' . "\n", is_singular() && !is_front_page() ? 'article' : 'website');
    printf('<meta property="og:title" content="%s">' . "\n", esc_attr($title));
    printf('<meta property="og:url" content="%s">' . "\n", esc_url($url));
    printf('<meta property="og:image" content="%s">' . "\n", esc_url($img));
    printf('<meta property="og:site_name" content="%s">' . "\n", esc_attr(__('荣光株式会社', 'eikou')));
    printf('<meta property="og:locale" content="%s">' . "\n", esc_attr($locales[$lang]));

    // 其余语言作为 alternate，强化 Google 对语言集群的识别
    foreach ($locales as $slug => $loc) {
        if ($slug !== $lang) {
            printf('<meta property="og:locale:alternate" content="%s">' . "\n", esc_attr($loc));
        }
    }
    printf('<meta name="twitter:card" content="summary_large_image">' . "\n");
}
add_action('wp_head', 'eikou_seo_og', 4);
```

> **需要素材：** `assets/images/og-default.jpg`，1200×630px。这是分享到 LINE / 微信 / X 时的默认缩略图，日本 B2B 场景下 LINE 分享很常见，务必准备。

---

## E6. JSON-LD 结构化数据

`inc/seo-schema.php`。**注意：所有字段必须是真实数据**，结构化数据造假是明确违规且会导致富媒体资格被永久取消。

```php
<?php
if (!defined('ABSPATH')) { exit; }

function eikou_schema_output($data) {
    echo '<script type="application/ld+json">'
       . wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
       . '</script>' . "\n";
}

/** Organization + LocalBusiness（全站） */
function eikou_schema_organization() {
    $home = eikou_seo_raw_home();

    $org = [
        '@context'    => 'https://schema.org',
        '@type'       => 'LocalBusiness',
        '@id'         => $home . '/#organization',
        'name'        => eikou_get('eikou_company_name', '荣光株式会社'),
        'alternateName' => ['EIKOU Co., Ltd.', 'エイコー'],
        'url'         => $home . '/',
        'logo'        => get_template_directory_uri() . '/assets/images/logo-eikou.png',
        'foundingDate'=> '2009',
        'telephone'   => eikou_get('eikou_tel', '03-5876-9273'),
        'email'       => eikou_get('eikou_email', 'info@eikoujp.net'),
        'address'     => [
            '@type'           => 'PostalAddress',
            'postalCode'      => eikou_get('eikou_zip', '〒125-0052'),
            'addressCountry'  => 'JP',
            'addressRegion'   => '東京都',
            'addressLocality' => '葛飾区',
            'streetAddress'   => '柴又1丁目43-6 MAC柴又コート 102',
        ],
        'areaServed'  => ['@type' => 'Country', 'name' => 'Japan'],
        'availableLanguage' => ['ja', 'zh', 'en'],
        // sameAs 是实体识别的关键：把所有官方档案串起来（见 E13）
        'sameAs'      => array_filter([
            // 'https://www.wantedly.com/companies/xxxx',
            // 'https://www.facebook.com/xxxx',
            // Google Business Profile / Wikidata / 各目录页 URL 填这里
        ]),
    ];

    eikou_schema_output($org);
}

/** WebSite + 站内搜索（可拿 Sitelinks Searchbox） */
function eikou_schema_website() {
    $home = eikou_seo_raw_home();
    eikou_schema_output([
        '@context' => 'https://schema.org',
        '@type'    => 'WebSite',
        '@id'      => $home . '/#website',
        'url'      => $home . '/',
        'name'     => __('荣光株式会社', 'eikou'),
        'inLanguage' => ['ja', 'zh-Hans', 'en'],
        'publisher'  => ['@id' => $home . '/#organization'],
    ]);
}

/** 服务页 Service schema（复用已有的 24 条数据） */
function eikou_schema_service() {
    $item = eikou_seo_current_service_item();
    if (!$item) { return; }

    eikou_schema_output([
        '@context'    => 'https://schema.org',
        '@type'       => 'Service',
        'name'        => __($item['title'], 'eikou'),
        'description' => __($item['description'], 'eikou'),
        'serviceType' => __($item['category_name'], 'eikou'),
        'provider'    => ['@id' => eikou_seo_raw_home() . '/#organization'],
        'areaServed'  => ['@type' => 'Country', 'name' => 'Japan'],
        'url'         => eikou_seo_url_for(eikou_current_lang()),
    ]);
}

/** 面包屑 */
function eikou_schema_breadcrumb() {
    $items = [];
    $pos   = 1;
    $items[] = ['@type' => 'ListItem', 'position' => $pos++,
                'name' => __('ホーム', 'eikou'), 'item' => eikou_seo_url_for(eikou_current_lang(), '/')];

    if ($item = eikou_seo_current_service_item()) {
        $items[] = ['@type' => 'ListItem', 'position' => $pos++,
                    'name' => __('サービス', 'eikou'), 'item' => eikou_page_url('services')];
        $items[] = ['@type' => 'ListItem', 'position' => $pos++,
                    'name' => __($item['category_name'], 'eikou'), 'item' => eikou_page_url($item['category'])];
        $items[] = ['@type' => 'ListItem', 'position' => $pos++,
                    'name' => __($item['title'], 'eikou')];
    } elseif (is_singular('work')) {
        $items[] = ['@type' => 'ListItem', 'position' => $pos++,
                    'name' => __('成功事例', 'eikou'), 'item' => eikou_page_url('works')];
        $items[] = ['@type' => 'ListItem', 'position' => $pos++, 'name' => get_the_title()];
    } elseif (is_page()) {
        $items[] = ['@type' => 'ListItem', 'position' => $pos++, 'name' => get_the_title()];
    }

    if (count($items) < 2) { return; }
    eikou_schema_output([
        '@context' => 'https://schema.org',
        '@type'    => 'BreadcrumbList',
        'itemListElement' => $items,
    ]);
}

/** 视频页 VideoObject */
function eikou_schema_video() {
    if (!is_singular('eikou_video')) { return; }
    $id  = get_queried_object_id();
    $url = get_post_meta($id, '_video_url', true);
    if (!$url) { return; }

    eikou_schema_output([
        '@context'     => 'https://schema.org',
        '@type'        => 'VideoObject',
        'name'         => get_the_title(),
        'thumbnailUrl' => get_the_post_thumbnail_url($id, 'full'),
        'uploadDate'   => get_the_date('c', $id),
        'contentUrl'   => $url,
        'duration'     => eikou_schema_iso_duration(get_post_meta($id, '_video_duration', true)),
        'publisher'    => ['@id' => eikou_seo_raw_home() . '/#organization'],
    ]);
}

/** "03:24" → "PT3M24S" */
function eikou_schema_iso_duration($mmss) {
    if (!preg_match('/^(\d+):(\d{2})$/', trim((string) $mmss), $m)) { return null; }
    return sprintf('PT%dM%dS', (int) $m[1], (int) $m[2]);
}

add_action('wp_head', function () {
    eikou_schema_organization();
    eikou_schema_website();
    eikou_schema_service();
    eikou_schema_breadcrumb();
    eikou_schema_video();
}, 5);
```

**验证：**
- https://search.google.com/test/rich-results 逐页跑
- https://validator.schema.org/
- 三语各测一个 URL，确认 `inLanguage` 与内容语言一致

---

## E7. 多语言 sitemap

WP 默认 `wp-sitemap.xml` 不含 `/zh/` `/en/`（`functions.php:78` 明确排除）。自建一个带 `xhtml:link` 语言标注的 sitemap。

`inc/seo-sitemap.php`：

```php
<?php
if (!defined('ABSPATH')) { exit; }

/** /sitemap-i18n.xml 路由 */
add_action('init', function () {
    add_rewrite_rule('^sitemap-i18n\.xml$', 'index.php?eikou_sitemap=i18n', 'top');
});
add_filter('query_vars', function ($v) { $v[] = 'eikou_sitemap'; return $v; });

add_action('template_redirect', function () {
    if (get_query_var('eikou_sitemap') !== 'i18n') { return; }

    header('Content-Type: application/xml; charset=UTF-8');
    header('X-Robots-Tag: noindex');   // sitemap 本身不需要被索引

    $paths = eikou_sitemap_collect_paths();

    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" '
       . 'xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";

    foreach ($paths as $path => $meta) {
        // 每个路径 × 3 语言 = 3 个 <url>，每个都列出全部 3 个 alternate
        foreach (array_keys(eikou_seo_hreflang_map()) as $lang) {
            echo "  <url>\n";
            printf("    <loc>%s</loc>\n", esc_url(eikou_seo_url_for($lang, $path)));
            if (!empty($meta['lastmod'])) {
                printf("    <lastmod>%s</lastmod>\n", esc_html($meta['lastmod']));
            }
            foreach (eikou_seo_hreflang_map() as $l => $code) {
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

/** 收集全站「无语言前缀」路径 */
function eikou_sitemap_collect_paths() {
    $paths = ['/' => ['priority' => '1.0', 'lastmod' => '']];
    $keys  = implode('|', array_keys(eikou_lang_map()));

    $strip = function ($url) use ($keys) {
        $p = parse_url((string) $url, PHP_URL_PATH);
        if (!$p) { return null; }
        return preg_replace('#^/(' . $keys . ')(/|$)#', '/', $p);
    };

    // 页面
    foreach (get_posts(['post_type' => 'page', 'numberposts' => -1, 'post_status' => 'publish']) as $p) {
        if ((int) get_option('page_on_front') === $p->ID) { continue; }
        if ($path = $strip(get_permalink($p))) {
            // 服务详情页权重更高——它们是长尾流量的主力
            $is_service = strpos($p->post_name, 'service-') === 0;
            $paths[$path] = [
                'priority' => $is_service ? '0.9' : '0.8',
                'lastmod'  => get_post_modified_time('c', true, $p),
            ];
        }
    }

    // CPT
    foreach (['work', 'eikou_video', 'partner'] as $pt) {
        foreach (get_posts(['post_type' => $pt, 'numberposts' => -1, 'post_status' => 'publish']) as $p) {
            if ($path = $strip(get_permalink($p))) {
                $paths[$path] = ['priority' => '0.7', 'lastmod' => get_post_modified_time('c', true, $p)];
            }
        }
    }

    // 分类法
    foreach (get_terms(['taxonomy' => 'work_category', 'hide_empty' => true]) as $t) {
        $link = get_term_link($t);
        if (!is_wp_error($link) && ($path = $strip($link))) {
            $paths[$path] = ['priority' => '0.6', 'lastmod' => ''];
        }
    }

    return $paths;
}
```

> **部署后必须刷新重写规则**，否则 `/sitemap-i18n.xml` 返回 404：
> ```bash
> wp rewrite flush --allow-root
> ```

**验证：**
```bash
curl -s https://eikoujp.net/sitemap-i18n.xml | head -40
curl -s https://eikoujp.net/sitemap-i18n.xml | grep -c "<loc>"   # 应为 页面数 × 3
```

---

## E8. robots.txt

```php
// 追加到 inc/seo-core.php
add_filter('robots_txt', function ($output, $public) {
    if (!$public) { return $output; }   // 站点设为「不允许搜索引擎」时不覆盖

    $home = eikou_seo_raw_home();
    $lines = [
        'User-agent: *',
        'Allow: /',
        'Disallow: /wp-admin/',
        'Allow: /wp-admin/admin-ajax.php',
        'Disallow: /wp-login.php',
        'Disallow: /?s=',
        'Disallow: /*?utm_',
        '',
        'Sitemap: ' . $home . '/sitemap-i18n.xml',
        'Sitemap: ' . $home . '/wp-sitemap.xml',
    ];
    return implode("\n", $lines) . "\n";
}, 10, 2);
```

> **前置检查：** 后台「設定 → 表示設定 → 検索エンジンがサイトをインデックスしないようにする」必须**取消勾选**。这个选项开着的话上面一切都是白做——这是企业站上线后最常见的低级致命错误，**第一件事就去确认**。
> ```bash
> wp option get blog_public --allow-root   # 必须返回 1
> ```

---

## E9. H5 / PC 双模板一致性核查（重要风险点）

`functions.php:223` 按 UA 切换到 `h5/` 目录下的模板，属于**动态服务（Dynamic Serving）**。这是 Google 允许的，但有两个前提：

1. **`Vary: User-Agent` 响应头** —— 已实现（`functions.php:233`），✅
2. **两套模板对同一 URL 必须返回等价的主要内容** —— **需要核查**

如果 PC 版和 H5 版的 title、description、结构化数据、主要正文出现实质差异，会被判定为 cloaking。由于 Googlebot 主要以移动 UA 抓取，**实际被索引的是 `h5/` 那套模板**。

**核查脚本：**

```bash
#!/bin/bash
# scripts/seo-check-parity.sh —— 对比 PC 与 H5 输出的 SEO 关键字段
PC_UA="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120 Safari/537.36"
MB_UA="Mozilla/5.0 (Linux; Android 13) AppleWebKit/537.36 Chrome/120 Mobile Safari/537.36"

for path in "/" "/zh/" "/en/" "/service-booth-design/" "/works/"; do
  echo "===== $path"
  for ua_name in PC MB; do
    ua=$([ "$ua_name" = "PC" ] && echo "$PC_UA" || echo "$MB_UA")
    html=$(curl -s -A "$ua" "https://eikoujp.net${path}")
    echo "[$ua_name] title:  $(echo "$html" | grep -oP '<title>\K[^<]*')"
    echo "[$ua_name] desc:   $(echo "$html" | grep -oP 'name="description" content="\K[^"]*' | cut -c1-60)"
    echo "[$ua_name] canon:  $(echo "$html" | grep -oP 'rel="canonical" href="\K[^"]*')"
    echo "[$ua_name] ldjson: $(echo "$html" | grep -c 'application/ld+json') 个"
  done
done
```

**要求：** 同一 path 下 PC 与 MB 的 title / desc / canonical 必须**完全一致**，ld+json 数量一致。任何不一致都要修到一致。

> 由于 SEO 标签全部由 `wp_head()` 输出、而 `h5/header.php` 与 PC `header.php` 都调用 `wp_head()`，本方案的实现天然一致。但**上线后必须实测确认**，不能假设。

---

## E10. Google Search Console 配置

1. **验证方式**：用 DNS TXT 记录验证**域名属性**（`eikoujp.net`），一次覆盖全站含所有语言前缀。不要用 URL 前缀属性逐个验证，会漏数据。
2. **额外建 3 个 URL 前缀属性**：`https://eikoujp.net/`、`https://eikoujp.net/zh/`、`https://eikoujp.net/en/` —— 域名属性看整体，这三个用来**分语言看数据**，是判断三语策略是否奏效的唯一方法。
3. **提交 sitemap**：`sitemap-i18n.xml`
4. **国际定位报告**：上线 hreflang 后 1–2 周查「国際ターゲティング」，确认无 hreflang 错误。
5. **Bing Webmaster Tools** 同步配置 —— 可直接从 GSC 导入，5 分钟的事，日本市场 Bing 份额虽小但是白捡的。

---

## E11. 清理生产环境 debug 日志

`functions.php:1168-1172` 有一段留在生产环境的调试输出：

```php
error_log('REST API 请求进来啦！路径: ' . $_SERVER['REQUEST_URI']);
```

每个 REST 请求都写一行日志，会持续撑大 `error_log` 文件。与 SEO 无直接关系但属于同批清理项，**直接删除**。

---

# P1 · 页面级优化（第 3–4 周）

## E12. 24 个服务页的关键词映射表

按此表逐页填写 E4 的 SEO 元框（人工校对资源优先投这里）：

| slug | 主关键词（JA） | 主关键词（EN） |
|---|---|---|
| service-booth-design | 展示会 ブース デザイン 制作 | exhibition booth design Japan |
| service-display-fixtures | 展示什器 オーダーメイド 制作 | custom display fixtures Japan |
| service-steel-structure | 展示会 鉄骨 アルミ 構造 加工 | steel structure exhibition Japan |
| service-woodwork | 展示会 木工 造作 塗装 | woodwork painting exhibition |
| service-led-av | LEDディスプレイ レンタル 展示会 | LED display rental Tokyo |
| service-logistics | 展示会 輸送 搬入 物流 | exhibition logistics Japan |
| service-onsite-ops | 展示会 現場 運営 スタッフ | on-site event operation Japan |
| service-launch-event | 新製品 発表会 企画 運営 | product launch event Japan |
| service-popup-store | ポップアップストア 制作 施工 | pop-up store production Japan |
| service-brand-promotion | ブランド プロモーション イベント | brand promotion event Japan |
| service-nationwide | 全国 イベント 展開 対応 | nationwide event rollout Japan |
| service-japan-entry | 日本 市場 進出 支援 イベント | Japan market entry support |
| service-web-design | 企業 サイト 制作 東京 | corporate web design Tokyo |
| service-web-marketing | Web マーケティング 支援 | web marketing agency Japan |
| service-app-dev | アプリ 開発 会社 東京 | app development Tokyo |
| service-ai-chatbot | AI チャットボット 導入 | AI chatbot implementation Japan |
| service-ai-modeling | AI 3Dモデリング 制作 | AI 3D modeling service |
| service-automation | 業務 自動化 AI 導入 | business automation AI Japan |
| service-package-design | パッケージ デザイン 制作 | package design Japan |
| service-brand-pr | ブランディング PR 支援 | branding PR agency Japan |
| service-print-design | 印刷物 デザイン 制作 | print design service Japan |
| service-media-ops | SNS 運用 代行 | social media management Japan |
| service-video-production | 動画 制作 会社 東京 | video production Tokyo |
| service-signage | サイネージ 制作 設置 | digital signage Japan |

> 中文关键词由 `/zh/` 页承接，统一走「日本 + 服务词」句式（如「日本展会搭建」「日本展台设计」），不逐条列表——中文侧的重点是 §2.4 的渠道组合，不是关键词密度。

## E13. 内链自动化（灰帽 G1，零风险高收益）

`inc/seo-internal-link.php` 核心逻辑：

```php
/** 服务页底部：同簇兄弟服务 + 簇心回链 */
function eikou_related_services($current_slug, $limit = 3) {
    $items = eikou_get_service_items();
    if (!isset($items[$current_slug])) { return []; }

    $category = $items[$current_slug]['category'];
    $siblings = array_filter($items, function ($i, $slug) use ($category, $current_slug) {
        return $i['category'] === $category && $slug !== $current_slug;
    }, ARRAY_FILTER_USE_BOTH);

    // 固定顺序（按 number），保证每页内链稳定——内链结构频繁变动会削弱权重传递
    uasort($siblings, fn($a, $b) => strcmp($a['number'], $b['number']));
    return array_slice($siblings, 0, $limit, true);
}
```

**内链规则（写死在模板里，不靠人工）：**

| 位置 | 链向 | 锚文本 |
|---|---|---|
| 服务详情页底部 | 同簇 3 个兄弟服务 | 服务全称（精确匹配）|
| 服务详情页面包屑 | 簇心（类目页）| 类目名 |
| 服务详情页 CTA | `/contact/` | 「〇〇のご相談はこちら」（含服务词）|
| 案例详情页 | 对应服务页 + 场馆页 | 服务全称 / 场馆名 |
| 类目页 | 全部下属服务 | 服务全称 |
| 首页 | 6 个簇心 | 类目名 |

**锚文本分布控制：** 精确匹配锚文本占比控制在 30% 以内，其余用品牌词、泛词（「詳しくはこちら」）、长尾变体。全站锚文本 100% 精确匹配是过度优化信号。

## E14. 图片与 Core Web Vitals

- 全站 `alt` 补齐：服务页用 `__($item['title'])`，案例页用「案例名 + 场馆名 + 施工内容」
- 现有代码已有 `eikou_mobile_img_url()` 和 `eikou_mobile_lazy_load()`，✅
- **`service-woodwork` 的 `hero_image` 指向 Unsplash 外链**（`functions.php` 内），换成自有图片：既是 CWV 问题（外部请求阻塞 LCP），也是内容原创性问题
- 补 `width`/`height` 属性防 CLS
- Google Fonts 已有 `preconnect`，补 `&display=swap`（已有 ✅）

---

# P2 · 内容规模化（第 2 个月）

## E15. CPT 多语言译文入库（战略文档 §3.3 方案 A）

```php
/** CPT 多语言字段 */
add_action('add_meta_boxes', function () {
    foreach (['work', 'eikou_video', 'partner'] as $pt) {
        add_meta_box('eikou_i18n_box', '多言語コンテンツ', 'eikou_i18n_box_html', $pt, 'normal', 'default');
    }
});

/** 前端按语言取标题 */
add_filter('the_title', function ($title, $post_id = 0) {
    if (is_admin() || !$post_id) { return $title; }
    $lang = eikou_current_lang();
    if ($lang === 'ja') { return $title; }
    $t = get_post_meta($post_id, "_title_{$lang}", true);
    return $t ?: $title;
}, 8, 2);   // 优先级 8，早于已有的 9（.mo 翻译），元字段优先
```

**批量填充：** 写 `scripts/i18n-cpt-translate.php`，复用已有的 `scripts/i18n-translate-po.php` 的 DeepL 调用逻辑，遍历 CPT 生成 `_title_zh` / `_title_en` / `_content_zh` / `_content_en`，再人工校对。

## E16. 场馆页生成（15 × 3）

新建 CPT `venue`，字段：场馆名、地址、总展示面积、小間規格、搬入口尺寸、搬入搬出规则、最寄駅、荣光实绩数。

**质量门槛（写进生成脚本）：**
```
if (实绩案例数 == 0)          → 只生成场馆信息页，不生成 服务×场馆 交叉页
if (正文字数 < 800)           → 不发布，标记为草稿待人工补充
if (与其他页重复率 > 40%)     → 不发布
if (本批已发布 >= 20)         → 停止，等待 2 周观察收录
```

**首批 15 个场馆：** 東京ビッグサイト / 幕張メッセ / パシフィコ横浜 / インテックス大阪 / ポートメッセなごや / 東京国際フォーラム / 神戸国際展示場 / マリンメッセ福岡 / 札幌ドーム / 名古屋国際会議場 / グランキューブ大阪 / 広島産業会館 / 静岡ツインメッセ / 仙台国際センター / 沖縄コンベンションセンター

## E17. 资源中心（转化漏斗上游）

| 页面 | 目标关键词 | 转化设计 |
|---|---|---|
| 展示会出展 費用相場ガイド | 展示会 出展 費用 相場 | 内嵌简易报价计算器 → 收邮箱 |
| 展示会出展 準備チェックリスト | 展示会 出展 準備 | PDF 下载 → 收邮箱 |
| よくあるご質問（FAQ）| 长尾问句词 | `FAQPage` schema 抢富媒体位 |

FAQPage schema 是当前性价比最高的富媒体类型之一，能在搜索结果里直接占据更大版面。

---

# P3 · 站外与实体建设（第 2–3 个月）

## E18. 日本行业目录 Citation 清单（灰帽 G3，零风险最高 ROI）

**NAP 必须逐字一致**（含全角半角、`〒` 符号、番地写法）。不一致的 citation 不但无效，还会削弱实体识别。

**统一 NAP 模板（复制这段，不要改动任何字符）：**
```
荣光株式会社（EIKOU Co., Ltd.）
〒125-0052 東京都葛飾区柴又1丁目43-6 MAC柴又コート 102
TEL: 03-5876-9273
https://eikoujp.net/
```

| # | 平台 | 类型 | 费用 |
|---|---|---|---|
| 1 | **Google Business Profile** | 本地包（最重要）| 免费 |
| 2 | iタウンページ | 综合目录 | 免费 |
| 3 | エキテン | 店铺目录 | 免费 |
| 4 | Baseconnect | 企业数据库 | 免费 |
| 5 | Musubu | 企业数据库 | 免费 |
| 6 | Wantedly | 招聘/企业档案 | 免费 |
| 7 | 日本 BtoB 総合検索「イプロス」| 行业 B2B（展会搭建高度相关）| 免费/付费 |
| 8 | Yahoo!ロコ | 本地 | 免费 |
| 9 | Bizloop / 求人ボックス企業 | 企业档案 | 免费 |
| 10 | 日本の展示会・イベント業界団体名簿 | 行业协会 | 会费 |
| 11–30 | 各都道府県商工会議所名簿、展示会主催者の出展社サービス一覧、地域ビジネス目録 等 | | 多为免费 |

**关键动作：** 每建一个档案，就把 URL 加进 E6 `eikou_schema_organization()` 的 `sameAs` 数组。`sameAs` 是把这些散落档案串成一个「实体」的粘合剂，不串等于白建。

## E19. 合作伙伴互链（最优质的免费外链）

已有 12 家合作企业（见 commit `46a5cc7`）。这是**现成的高相关性外链池**，且完全合规——真实业务关系产生的链接正是 Google 想要的那种。

执行：逐家沟通「相互リンク」，在对方「協力会社」「取引先」页面加链接。目标转化率 50%（6 家），换到 6 个高相关日本企业域的反链，价值远超任何付费外链。

## E20. PR 与行业媒体

- **PR TIMES** 发布：新案例、技术投入、三语服务上线等。日本 B2B 记者的主要选题来源，且被大量媒体转载 = 一次投入多个反链。
- **展示会業界メディア**（見本市展示会通信 等）投稿
- **Wikidata 实体条目**：建立后写回 `sameAs`，是 Google 知识图谱的重要输入

---

# 灰帽项的执行门槛（需你拍板后才动）

| 项 | 前置条件 | 停止条件 |
|---|---|---|
| **G6 老域名 301** | Wayback 历史干净 + 反链 profile 无垃圾锚文本 + 主题相关 | 301 后 3 个月内主站任何核心词下滑 → 立即解除 301 |
| **G7 行业媒体站** | 只建 1 个，独立选题、独立运营、真实编辑价值 | 若沦为纯反链工具 → 停止并 noindex |
| **G8 付费外链** | 仅限 PR TIMES / 行业协会 / 带 `sponsored` 标记的赞助稿 | 任何「外链套餐」「包收录」一律不采购 |

---

# 执行顺序总表

| 序 | 任务 | 文件 | 阻塞关系 |
|---|---|---|---|
| 1 | 确认 `blog_public=1` | 后台设置 | **阻塞全部** |
| 2 | E1 基础函数 | `inc/seo-core.php` | 阻塞 3–7 |
| 3 | E2 canonical + hreflang | `inc/seo-core.php` | — |
| 4 | E3 title + description | `inc/seo-core.php` | — |
| 5 | E5 OG（需 og-default.jpg）| `inc/seo-core.php` | 需素材 |
| 6 | E6 结构化数据 | `inc/seo-schema.php` | 需法人番号/资本金 |
| 7 | E7 多语言 sitemap | `inc/seo-sitemap.php` | 需 `wp rewrite flush` |
| 8 | E8 robots.txt | `inc/seo-core.php` | 依赖 7 |
| 9 | E11 清理 debug 日志 | `functions.php` | — |
| 10 | E9 PC/H5 一致性核查 | `scripts/seo-check-parity.sh` | 依赖 3–7 上线 |
| 11 | E10 GSC 配置 | 运维 | 依赖 7 |
| 12 | E4 SEO 元框 | `inc/seo-core.php` | — |
| 13 | E12 24 页关键词填写 | 后台 | 依赖 12 |
| 14 | E13 内链自动化 | `inc/seo-internal-link.php` | — |
| 15 | E14 图片与 CWV | 模板 + `functions.php` | 需替换 Unsplash 外链图 |
| 16+ | P2 / P3 | — | 依赖 P0 验收通过 |

**第 1–10 项是一个完整可交付批次，可立即开工。**
