<?php
/**
 * SEO Core — canonical / hreflang / title / description / OG / robots
 *
 * 依赖 functions.php 中的轻量多语言路由：
 *   eikou_current_lang() / eikou_lang_map() / eikou_localize_url()
 *
 * 重要：本文件构造多语言 URL 时一律使用 get_option('home')（未过滤），
 * 不能用 home_url()——后者已被 eikou_localize_url() 挂钩会自动追加语言
 * 前缀，再拼一次会产出 /zh/zh/ 这样的错误 URL。
 *
 * @package wpbase-starter
 */

if (!defined('ABSPATH')) {
    exit;
}

/* ============================================================
   基础：语言与 URL
   ============================================================ */

/** 未经语言前缀过滤的站点根 URL */
function eikou_seo_raw_home() {
    return untrailingslashit(get_option('home'));
}

/** hreflang 语言码映射：内部 slug => hreflang 值 */
function eikou_seo_hreflang_map() {
    return [
        'ja' => 'ja',
        'zh' => 'zh-Hans',
        'en' => 'en',
    ];
}

/** OG locale 映射 */
function eikou_seo_og_locale_map() {
    return [
        'ja' => 'ja_JP',
        'zh' => 'zh_CN',
        'en' => 'en_US',
    ];
}

/** 从任意路径剥掉语言前缀 */
function eikou_seo_strip_lang_prefix($path) {
    if (!is_string($path) || $path === '') {
        return '/';
    }
    $keys = implode('|', array_keys(eikou_lang_map()));
    $path = preg_replace('#^/(' . $keys . ')(/|$)#', '/', $path);
    return '/' . ltrim($path, '/');
}

/**
 * 当前页面「剥离语言前缀后」的路径，如 /about/
 * 用于构造三语平行 URL。
 */
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

    // get_permalink 等已被 eikou_localize_url 加过前缀，这里统一剥掉
    return eikou_seo_strip_lang_prefix($url ? $url : '/');
}

/** 指定语言下的绝对 URL */
function eikou_seo_url_for($lang, $path = null) {
    if ($path === null) {
        $path = eikou_seo_base_path();
    }
    $home = eikou_seo_raw_home();
    return ($lang === 'ja') ? $home . $path : $home . '/' . $lang . $path;
}

/** 该页是否应输出 canonical / hreflang（搜索结果与 404 不输出） */
function eikou_seo_is_indexable() {
    return !(is_404() || is_search());
}

/** 取当前页对应的服务条目（复用 functions.php 里已有的 24 条数据） */
function eikou_seo_current_service_item() {
    if (!is_page()) {
        return null;
    }
    $slug  = get_post_field('post_name', get_queried_object_id());
    $items = eikou_get_service_items();
    return isset($items[$slug]) ? $items[$slug] : null;
}

/* ============================================================
   canonical + hreflang
   ============================================================ */

/**
 * canonical
 *
 * 次级语言下 redirect_canonical 被整体关闭（functions.php），
 * 导致 /zh/about、/zh/about/、/zh/about/?x=1 都返回 200。
 * canonical 是唯一的重复内容兜底，不可省略。
 */
function eikou_seo_canonical() {
    if (!eikou_seo_is_indexable()) {
        return;
    }
    $url = eikou_seo_url_for(eikou_current_lang());

    $paged = (int) get_query_var('paged');
    if ($paged > 1) {
        $url = trailingslashit($url) . 'page/' . $paged . '/';
    }

    printf('<link rel="canonical" href="%s">' . "\n", esc_url($url));
}

/**
 * hreflang 三语互指 + x-default
 *
 * 三条硬规则，违反任何一条整组失效：
 *   1) 必须互指  2) 必须绝对 URL  3) 被指向的 URL 必须 200 且 canonical 指向自己
 * 本站三语共用同一套模板，天然满足 1、3。
 */
function eikou_seo_hreflang() {
    if (!eikou_seo_is_indexable()) {
        return;
    }
    $path = eikou_seo_base_path();

    foreach (eikou_seo_hreflang_map() as $slug => $code) {
        printf('<link rel="alternate" hreflang="%s" href="%s">' . "\n",
            esc_attr($code), esc_url(eikou_seo_url_for($slug, $path)));
    }

    // x-default 指向日文（本社所在地语言）
    printf('<link rel="alternate" hreflang="x-default" href="%s">' . "\n",
        esc_url(eikou_seo_url_for('ja', $path)));
}

// WordPress 自带的 rel_canonical 输出的是未加语言前缀的 URL，必须摘掉
remove_action('wp_head', 'rel_canonical');
add_action('wp_head', 'eikou_seo_canonical', 1);
add_action('wp_head', 'eikou_seo_hreflang', 2);

/* ============================================================
   title
   ============================================================ */

/**
 * 标题模板：按页型注入关键词
 *
 * 优先级 20：晚于 functions.php 中翻译 title parts 的过滤器（默认 10），
 * 确保这里设置的值不被再次覆盖。
 */
add_filter('document_title_parts', function ($parts) {
    $lang = eikou_current_lang();

    // 首页与 24 个服务页：走逐语种关键词表（不经过 .mo，见 seo-keywords.php）
    if ($entry = eikou_seo_current_keyword_entry()) {
        // 自行拼接完整标题并丢弃其余部分，避免 WordPress 再追加一次站点名
        return ['title' => $entry['t'] . eikou_seo_brand_suffix($lang)];
    }

    if (is_singular('work')) {
        $suffix = ['ja' => '｜施工事例', 'zh' => '｜施工案例', 'en' => ' | Case Study'];
        $parts['title'] = get_the_title() . (isset($suffix[$lang]) ? $suffix[$lang] : $suffix['ja']);
    }
    return $parts;
}, 20);

/** 后台手写的 SEO 标题优先级最高 */
add_filter('document_title_parts', function ($parts) {
    if (is_singular()) {
        $custom = get_post_meta(get_queried_object_id(), '_eikou_seo_title', true);
        if ($custom) {
            $parts['title'] = $custom;
        }
    }
    return $parts;
}, 30);

/** 分隔符改为日文站惯用的全角竖线 */
add_filter('document_title_separator', function () {
    return '｜';
});

/* ============================================================
   meta description
   ============================================================ */

/** 生成当前页的 description 文本（不输出） */
function eikou_seo_get_description() {
    $desc = '';

    if (is_singular()) {
        $custom = get_post_meta(get_queried_object_id(), '_eikou_seo_desc', true);
        if ($custom) {
            $desc = $custom;
        }
    }

    if ($desc === '') {
        // 首页与 24 个服务页：走逐语种关键词表
        if ($entry = eikou_seo_current_keyword_entry()) {
            $desc = $entry['d'];
        } elseif ($item = eikou_seo_current_service_item()) {
            // 关键词表未覆盖时回退到服务数据里的日文简介（经 .mo 翻译）
            $desc = __($item['description'], 'eikou');
        } elseif (is_singular()) {
            $post = get_queried_object();
            $desc = has_excerpt($post) ? get_the_excerpt($post) : $post->post_content;
        }
    }

    $desc = wp_strip_all_tags(strip_shortcodes((string) $desc));
    $desc = trim(preg_replace('/\s+/u', ' ', $desc));

    // 截断长度按语种区分：全角字符占位宽，搜索结果显示上限比半角少得多。
    // 统一用一个数值会导致日文被 Google 二次截断、英文浪费关键词空间。
    $limits = ['ja' => 90, 'zh' => 90, 'en' => 155];
    $lang   = eikou_current_lang();
    $limit  = isset($limits[$lang]) ? $limits[$lang] : 90;

    // 必须用 mb_* 系列：substr 会切碎多字节字符产生乱码
    if (mb_strlen($desc, 'UTF-8') > $limit) {
        $desc = rtrim(mb_substr($desc, 0, $limit - 1, 'UTF-8')) . '…';
    }
    return $desc;
}

function eikou_seo_description() {
    $desc = eikou_seo_get_description();
    if ($desc === '') {
        return;
    }
    printf('<meta name="description" content="%s">' . "\n", esc_attr($desc));
}
add_action('wp_head', 'eikou_seo_description', 3);

/* ============================================================
   OG / Twitter Card
   ============================================================ */

/** 当前页的分享图 */
function eikou_seo_share_image() {
    if (is_singular() && has_post_thumbnail()) {
        $img = get_the_post_thumbnail_url(get_queried_object_id(), 'full');
        if ($img) {
            return $img;
        }
    }
    if ($item = eikou_seo_current_service_item()) {
        if (!empty($item['hero_image'])) {
            return $item['hero_image'];
        }
    }
    // 默认图：优先 og-default.jpg，缺失时回退到 logo
    $default = get_template_directory() . '/assets/images/og-default.jpg';
    if (file_exists($default)) {
        return get_template_directory_uri() . '/assets/images/og-default.jpg';
    }
    return get_template_directory_uri() . '/assets/images/logo-eikou.png';
}

function eikou_seo_og() {
    $lang    = eikou_current_lang();
    $locales = eikou_seo_og_locale_map();
    $desc    = eikou_seo_get_description();

    printf('<meta property="og:type" content="%s">' . "\n",
        (is_singular() && !is_front_page()) ? 'article' : 'website');
    printf('<meta property="og:title" content="%s">' . "\n", esc_attr(wp_get_document_title()));
    if ($desc !== '') {
        printf('<meta property="og:description" content="%s">' . "\n", esc_attr($desc));
    }
    printf('<meta property="og:url" content="%s">' . "\n", esc_url(eikou_seo_url_for($lang)));
    printf('<meta property="og:image" content="%s">' . "\n", esc_url(eikou_seo_share_image()));
    printf('<meta property="og:site_name" content="%s">' . "\n", esc_attr(__('荣光株式会社', 'eikou')));

    $current_locale = isset($locales[$lang]) ? $locales[$lang] : 'ja_JP';
    printf('<meta property="og:locale" content="%s">' . "\n", esc_attr($current_locale));

    // 其余语言作为 alternate，强化 Google 对语言集群的识别
    foreach ($locales as $slug => $loc) {
        if ($slug !== $lang) {
            printf('<meta property="og:locale:alternate" content="%s">' . "\n", esc_attr($loc));
        }
    }

    printf('<meta name="twitter:card" content="summary_large_image">' . "\n");
    printf('<meta name="twitter:title" content="%s">' . "\n", esc_attr(wp_get_document_title()));
    if ($desc !== '') {
        printf('<meta name="twitter:description" content="%s">' . "\n", esc_attr($desc));
    }
    printf('<meta name="twitter:image" content="%s">' . "\n", esc_url(eikou_seo_share_image()));
}
add_action('wp_head', 'eikou_seo_og', 4);

/* ============================================================
   资源提示（Core Web Vitals）
   ============================================================ */

/**
 * 为外部图床预建连接。
 *
 * 全站仍有大量图片指向 images.unsplash.com，首屏 LCP 图片走第三方域名时
 * 要额外付 DNS + TCP + TLS 三次往返。preconnect 能把这部分省掉。
 *
 * 注意：这只是缓解，不是修复。根治办法是把图片换成自有素材
 * （既是性能问题，也是内容原创性问题——见 docs/seo-p1-changes.md）。
 */
add_filter('wp_resource_hints', function ($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        $urls[] = ['href' => 'https://images.unsplash.com', 'crossorigin' => ''];
        $urls[] = ['href' => 'https://fonts.gstatic.com', 'crossorigin' => ''];
    }
    return $urls;
}, 10, 2);

/* ============================================================
   robots.txt
   ============================================================ */

add_filter('robots_txt', function ($output, $public) {
    // 站点被设为「不允许搜索引擎索引」时不覆盖，保留 WP 的全站 Disallow
    if (!$public) {
        return $output;
    }

    $home  = eikou_seo_raw_home();
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

/* ============================================================
   后台：逐页 SEO 覆盖字段
   ============================================================ */

add_action('add_meta_boxes', function () {
    foreach (['page', 'post', 'work', 'eikou_video'] as $pt) {
        add_meta_box('eikou_seo_box', 'SEO 設定', 'eikou_seo_box_html', $pt, 'normal', 'high');
    }
});

function eikou_seo_box_html($post) {
    wp_nonce_field('eikou_seo_meta', 'eikou_seo_nonce');
    $title = get_post_meta($post->ID, '_eikou_seo_title', true);
    $desc  = get_post_meta($post->ID, '_eikou_seo_desc', true);
    ?>
    <p>
        <label for="eikou_seo_title"><strong>SEO タイトル</strong>（空欄なら自動生成）</label><br>
        <input type="text" id="eikou_seo_title" name="eikou_seo_title"
               value="<?php echo esc_attr($title); ?>" style="width:100%;">
    </p>
    <p>
        <label for="eikou_seo_desc"><strong>メタディスクリプション</strong>（120 文字以内推奨）</label><br>
        <textarea id="eikou_seo_desc" name="eikou_seo_desc" rows="3" style="width:100%;"><?php echo esc_textarea($desc); ?></textarea>
    </p>
    <p style="color:#666;">
        ※ ここで設定した内容は日本語ページに適用されます。中国語・英語ページは翻訳ファイル（.mo）経由で出力されます。
    </p>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['eikou_seo_nonce']) || !wp_verify_nonce($_POST['eikou_seo_nonce'], 'eikou_seo_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    foreach (['eikou_seo_title' => '_eikou_seo_title', 'eikou_seo_desc' => '_eikou_seo_desc'] as $field => $meta) {
        if (isset($_POST[$field])) {
            $value = sanitize_text_field(wp_unslash($_POST[$field]));
            if ($value === '') {
                delete_post_meta($post_id, $meta);
            } else {
                update_post_meta($post_id, $meta, $value);
            }
        }
    }
});
