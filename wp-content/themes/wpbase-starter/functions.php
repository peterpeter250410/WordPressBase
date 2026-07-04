<?php
/**
 * EIKOU Theme functions
 *
 * @package wpbase-starter
 */

if (!defined('ABSPATH')) {
    exit;
}

/* ============================================================
   轻量多语言路由：/zh /en 前缀，日文根目录
   同一套模板，按 locale 加载对应 .mo 实现日/中/英
   ============================================================ */

// 次级语言 => WordPress locale（日文为默认，无前缀）
function eikou_lang_map() {
    return ['zh' => 'zh_CN', 'en' => 'en_US'];
}

// 当前语言（从 URL 前缀判断）
function eikou_current_lang() {
    static $lang = null;
    if ($lang !== null) return $lang;
    $lang = 'ja';
    $uri  = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    $path = parse_url($uri, PHP_URL_PATH);
    $keys = implode('|', array_keys(eikou_lang_map()));
    if ($path && preg_match('#^/(' . $keys . ')(/|$)#', $path, $m)) {
        $lang = $m[1];
    }
    return $lang;
}

// 尽早剥离 URL 的语言前缀，让 WordPress 正常路由其余路径
(function () {
    if (empty($_SERVER['REQUEST_URI'])) return;
    $keys = implode('|', array_keys(eikou_lang_map()));
    $uri = $_SERVER['REQUEST_URI'];
    $q = '';
    if (($pos = strpos($uri, '?')) !== false) { $q = substr($uri, $pos); $uri = substr($uri, 0, $pos); }
    if (preg_match('#^/(' . $keys . ')(/.*)?$#', $uri, $m)) {
        $rest = (isset($m[2]) && $m[2] !== '') ? $m[2] : '/';
        $_SERVER['REQUEST_URI'] = $rest . $q;
    }
})();

// locale 过滤：按当前语言切换（驱动 .mo 翻译）
add_filter('locale', function ($locale) {
    $map = eikou_lang_map();
    $l = eikou_current_lang();
    return isset($map[$l]) ? $map[$l] : $locale;
});

// 次级语言页面禁用 canonical 重定向（前缀已剥离，避免重定向死循环）
add_filter('redirect_canonical', function ($redirect_url) {
    return (eikou_current_lang() !== 'ja') ? false : $redirect_url;
});

// 给本站前端 URL 加当前语言前缀，保持导航停留在当前语言
function eikou_localize_url($url) {
    $l = eikou_current_lang();
    if ($l === 'ja' || is_admin()) return $url;
    if (!is_string($url) || $url === '') return $url;
    $home = untrailingslashit(get_option('home'));
    if (strpos($url, $home) !== 0) return $url;                 // 非本站 URL
    $rest = substr($url, strlen($home));
    $path = parse_url($rest, PHP_URL_PATH);
    if ($path === null || $path === false) $path = '/';
    // 排除后台/接口/资源/feed 等
    if (preg_match('#^/(wp-admin|wp-login|wp-json|wp-content|wp-includes|xmlrpc|feed)#', $path)) return $url;
    $keys = implode('|', array_keys(eikou_lang_map()));
    if (preg_match('#^/(' . $keys . ')(/|$)#', $path)) return $url;   // 已带前缀
    if ($rest === '') $rest = '/';
    return $home . '/' . $l . $rest;
}
add_filter('page_link', 'eikou_localize_url', 20, 1);
add_filter('post_link', 'eikou_localize_url', 20, 1);
add_filter('post_type_link', 'eikou_localize_url', 20, 1);

/* ─── Theme Setup ─── */
function eikou_setup() {
    // 多语言：加载主题翻译文件（languages/eikou-{locale}.mo）
    load_theme_textdomain('eikou', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    add_theme_support('custom-logo', [
        'height'      => 80,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    // Image sizes
    add_image_size('work-large', 1000, 700, true);
    add_image_size('work-thumb', 600, 400, true);
    add_image_size('video-thumb', 600, 340, true);
    add_image_size('h5-thumb', 400, 267, true);
    add_image_size('h5-hero', 800, 450, true);

    // Navigation menus
    register_nav_menus([
        'primary'      => 'Primary Menu (Header)',
        'footer-nav'   => 'Footer Navigation',
        'footer-other' => 'Footer Other Links',
    ]);
}
add_action('after_setup_theme', 'eikou_setup');

/* ─── 资源版本号：用文件修改时间，文件一变即自动刷新缓存 ─── */
function eikou_ver($rel) {
    $path = get_template_directory() . $rel;
    return file_exists($path) ? filemtime($path) : '2.0.0';
}

/* ─── Enqueue Styles & Scripts ─── */
function eikou_scripts() {
    // Google Fonts (shared)
    wp_enqueue_style('eikou-google-fonts',
        'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Inter:wght@300;400;500;600;700&display=swap',
        [], null
    );

    if (eikou_is_mobile_device()) {
        // ── H5 path: base.css + h5.css + h5.js ──
        wp_enqueue_style('eikou-base',
            get_template_directory_uri() . '/assets/css/base.css',
            ['eikou-google-fonts'], eikou_ver('/assets/css/base.css')
        );
        wp_enqueue_style('eikou-h5',
            get_template_directory_uri() . '/h5/assets/css/h5.css',
            ['eikou-base'], eikou_ver('/h5/assets/css/h5.css')
        );
        wp_enqueue_script('eikou-h5',
            get_template_directory_uri() . '/h5/assets/js/h5.js',
            [], eikou_ver('/h5/assets/js/h5.js'), true
        );

        // Pass current tab state to JS
        $current_tab = 'home';
        if (is_page('services') || (is_page() && strpos(get_post_field('post_name'), 'service-') === 0)) {
            $current_tab = 'services';
        } elseif (is_page('works') || is_singular('work')) {
            $current_tab = 'works';
        } elseif (is_page('video')) {
            $current_tab = 'videos';
        } elseif (is_page('contact')) {
            $current_tab = 'contact';
        }
        wp_localize_script('eikou-h5', 'eikouMobile', [
            'currentTab' => $current_tab,
        ]);
    } else {
        // ── PC path: eikou.css + main.js ──
        wp_enqueue_style('eikou-style',
            get_template_directory_uri() . '/assets/css/eikou.css',
            ['eikou-google-fonts'], eikou_ver('/assets/css/eikou.css')
        );
        wp_enqueue_style('eikou-theme', get_stylesheet_uri(), ['eikou-style'], eikou_ver('/style.css'));
        wp_enqueue_script('eikou-main',
            get_template_directory_uri() . '/assets/js/main.js',
            [], eikou_ver('/assets/js/main.js'), true
        );
    }
}
add_action('wp_enqueue_scripts', 'eikou_scripts');

/* ─── H5 Template Routing ─── */
function eikou_h5_template_redirect($template) {
    if (!eikou_is_mobile_device()) {
        return $template;
    }
    $h5_template = get_template_directory() . '/h5/' . basename($template);
    return file_exists($h5_template) ? $h5_template : $template;
}
add_filter('template_include', 'eikou_h5_template_redirect', 99);

/* ─── Vary: User-Agent header for caching ─── */
function eikou_vary_header() {
    if (!is_admin()) {
        header('Vary: User-Agent');
    }
}
add_action('send_headers', 'eikou_vary_header');

/* ─── Mobile Device Detection ─── */
function eikou_is_mobile_device() {
    if (function_exists('wp_is_mobile') && wp_is_mobile()) {
        // wp_is_mobile() includes tablets; refine by checking for 'Mobile' keyword
        $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        // Tablets (iPad) typically don't have 'Mobile' in UA (except iPhone)
        // We want phones only for H5 layout
        $mobile_keywords = ['iPhone', 'iPod', 'Android', 'webOS', 'BlackBerry',
                            'Windows Phone', 'Opera Mini', 'IEMobile'];
        foreach ($mobile_keywords as $keyword) {
            if (stripos($ua, $keyword) !== false) {
                // Android tablets have 'Android' but not 'Mobile'
                if ($keyword === 'Android' && stripos($ua, 'Mobile') === false) {
                    return false; // Android tablet
                }
                return true;
            }
        }
    }
    return false;
}

function eikou_mobile_body_class($classes) {
    if (eikou_is_mobile_device()) {
        $classes[] = 'is-mobile';
    }
    return $classes;
}
add_filter('body_class', 'eikou_mobile_body_class');

/* ─── Custom Post Type: Work (成功事例) ─── */
function eikou_register_work_cpt() {
    register_post_type('work', [
        'labels' => [
            'name'               => __('成功事例', 'eikou'),
            'singular_name'      => __('成功事例', 'eikou'),
            'add_new'            => __('新規追加', 'eikou'),
            'add_new_item'       => __('新しい事例を追加', 'eikou'),
            'edit_item'          => __('事例を編集', 'eikou'),
            'view_item'          => __('事例を表示', 'eikou'),
            'all_items'          => __('すべての事例', 'eikou'),
            'search_items'       => __('事例を検索', 'eikou'),
            'not_found'          => __('事例が見つかりません', 'eikou'),
        ],
        'public'       => true,
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-portfolio',
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt'],
        'rewrite'      => ['slug' => 'work'],
        'show_in_rest' => true,
    ]);

    register_taxonomy('work_category', 'work', [
        'labels' => [
            'name'          => __('事例カテゴリ', 'eikou'),
            'singular_name' => __('事例カテゴリ', 'eikou'),
            'add_new_item'  => __('新しいカテゴリを追加', 'eikou'),
        ],
        'public'       => true,
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'work-category'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'eikou_register_work_cpt');

/* ─── Custom Post Type: Video (動画) ─── */
function eikou_register_video_cpt() {
    register_post_type('eikou_video', [
        'labels' => [
            'name'               => __('動画', 'eikou'),
            'singular_name'      => __('動画', 'eikou'),
            'add_new'            => __('新規追加', 'eikou'),
            'add_new_item'       => __('新しい動画を追加', 'eikou'),
            'edit_item'          => __('動画を編集', 'eikou'),
            'view_item'          => __('動画を表示', 'eikou'),
            'all_items'          => __('すべての動画', 'eikou'),
            'search_items'       => __('動画を検索', 'eikou'),
            'not_found'          => __('動画が見つかりません', 'eikou'),
        ],
        'public'       => true,
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-video-alt3',
        'supports'     => ['title', 'thumbnail'],
        'rewrite'      => ['slug' => 'video-item'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'eikou_register_video_cpt');

/* ─── Custom Post Type: Partner ─── */
function eikou_register_partner_cpt() {
    register_post_type('partner', [
        'labels' => [
            'name'               => __('パートナー', 'eikou'),
            'singular_name'      => __('パートナー', 'eikou'),
            'add_new'            => __('新規追加', 'eikou'),
            'add_new_item'       => __('新しいパートナーを追加', 'eikou'),
            'edit_item'          => __('パートナーを編集', 'eikou'),
            'all_items'          => __('すべてのパートナー', 'eikou'),
        ],
        'public'       => true,
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-groups',
        'supports'     => ['title', 'thumbnail'],
        'rewrite'      => ['slug' => 'partner'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'eikou_register_partner_cpt');

/* ─── Custom Post Type: Testimonial ─── */
function eikou_register_testimonial_cpt() {
    register_post_type('testimonial', [
        'labels' => [
            'name'               => __('お客様の声', 'eikou'),
            'singular_name'      => __('お客様の声', 'eikou'),
            'add_new'            => __('新規追加', 'eikou'),
            'add_new_item'       => __('新しい声を追加', 'eikou'),
            'edit_item'          => __('声を編集', 'eikou'),
            'all_items'          => __('すべてのお客様の声', 'eikou'),
        ],
        'public'       => false,
        'show_ui'      => true,
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-format-quote',
        'supports'     => ['title', 'editor'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'eikou_register_testimonial_cpt');

/* ─── Meta Boxes: Video ─── */
function eikou_video_meta_boxes() {
    add_meta_box('eikou_video_details', '動画詳細', 'eikou_video_meta_html', 'eikou_video', 'normal', 'high');
}
add_action('add_meta_boxes', 'eikou_video_meta_boxes');

function eikou_video_meta_html($post) {
    wp_nonce_field('eikou_video_meta', 'eikou_video_meta_nonce');
    $duration  = get_post_meta($post->ID, '_video_duration', true);
    $video_url = get_post_meta($post->ID, '_video_url', true);
    ?>
    <p>
        <label for="video_duration"><strong>再生時間</strong></label><br>
        <input type="text" id="video_duration" name="video_duration" value="<?php echo esc_attr($duration); ?>" placeholder="03:24" style="width:200px;">
    </p>
    <p>
        <label for="video_url"><strong>動画URL（YouTube等）</strong></label><br>
        <input type="url" id="video_url" name="video_url" value="<?php echo esc_attr($video_url); ?>" placeholder="https://www.youtube.com/watch?v=..." style="width:100%;">
    </p>
    <?php
}

function eikou_save_video_meta($post_id) {
    if (!isset($_POST['eikou_video_meta_nonce']) || !wp_verify_nonce($_POST['eikou_video_meta_nonce'], 'eikou_video_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['video_duration'])) {
        update_post_meta($post_id, '_video_duration', sanitize_text_field($_POST['video_duration']));
    }
    if (isset($_POST['video_url'])) {
        update_post_meta($post_id, '_video_url', esc_url_raw($_POST['video_url']));
    }
}
add_action('save_post_eikou_video', 'eikou_save_video_meta');

/* ─── Meta Boxes: Testimonial ─── */
function eikou_testimonial_meta_boxes() {
    add_meta_box('eikou_testimonial_details', '回答者情報', 'eikou_testimonial_meta_html', 'testimonial', 'normal', 'high');
}
add_action('add_meta_boxes', 'eikou_testimonial_meta_boxes');

function eikou_testimonial_meta_html($post) {
    wp_nonce_field('eikou_testimonial_meta', 'eikou_testimonial_meta_nonce');
    $author_title   = get_post_meta($post->ID, '_testimonial_author_title', true);
    $author_company = get_post_meta($post->ID, '_testimonial_author_company', true);
    ?>
    <p>
        <label for="testimonial_author_title"><strong>肩書き</strong></label><br>
        <input type="text" id="testimonial_author_title" name="testimonial_author_title" value="<?php echo esc_attr($author_title); ?>" placeholder="マーケティング部長" style="width:100%;">
    </p>
    <p>
        <label for="testimonial_author_company"><strong>会社名</strong></label><br>
        <input type="text" id="testimonial_author_company" name="testimonial_author_company" value="<?php echo esc_attr($author_company); ?>" placeholder="大手電機メーカー" style="width:100%;">
    </p>
    <?php
}

function eikou_save_testimonial_meta($post_id) {
    if (!isset($_POST['eikou_testimonial_meta_nonce']) || !wp_verify_nonce($_POST['eikou_testimonial_meta_nonce'], 'eikou_testimonial_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['testimonial_author_title'])) {
        update_post_meta($post_id, '_testimonial_author_title', sanitize_text_field($_POST['testimonial_author_title']));
    }
    if (isset($_POST['testimonial_author_company'])) {
        update_post_meta($post_id, '_testimonial_author_company', sanitize_text_field($_POST['testimonial_author_company']));
    }
}
add_action('save_post_testimonial', 'eikou_save_testimonial_meta');

/* ─── Meta Boxes: Work (事例の場所・年) ─── */
function eikou_work_meta_boxes() {
    add_meta_box('eikou_work_details', '事例詳細', 'eikou_work_meta_html', 'work', 'normal', 'high');
}
add_action('add_meta_boxes', 'eikou_work_meta_boxes');

function eikou_work_meta_html($post) {
    wp_nonce_field('eikou_work_meta', 'eikou_work_meta_nonce');
    $location = get_post_meta($post->ID, '_work_location', true);
    $year     = get_post_meta($post->ID, '_work_year', true);
    ?>
    <p>
        <label for="work_location"><strong>場所</strong></label><br>
        <input type="text" id="work_location" name="work_location" value="<?php echo esc_attr($location); ?>" placeholder="東京ビッグサイト" style="width:100%;">
    </p>
    <p>
        <label for="work_year"><strong>年</strong></label><br>
        <input type="text" id="work_year" name="work_year" value="<?php echo esc_attr($year); ?>" placeholder="2024" style="width:200px;">
    </p>
    <?php
}

function eikou_save_work_meta($post_id) {
    if (!isset($_POST['eikou_work_meta_nonce']) || !wp_verify_nonce($_POST['eikou_work_meta_nonce'], 'eikou_work_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['work_location'])) {
        update_post_meta($post_id, '_work_location', sanitize_text_field($_POST['work_location']));
    }
    if (isset($_POST['work_year'])) {
        update_post_meta($post_id, '_work_year', sanitize_text_field($_POST['work_year']));
    }
}
add_action('save_post_work', 'eikou_save_work_meta');

/* ─── Customizer: Company Info ─── */
function eikou_customizer($wp_customize) {
    $wp_customize->add_section('eikou_company_info', [
        'title'    => '会社情報',
        'priority' => 30,
    ]);

    $fields = [
        'eikou_company_name' => ['label' => '会社名', 'default' => '荣光株式会社｜EIKOU Co., Ltd.'],
        'eikou_zip'          => ['label' => '郵便番号', 'default' => '〒XXX-XXXX'],
        'eikou_address'      => ['label' => '住所', 'default' => '東京都XX区XX X-X-X'],
        'eikou_tel'          => ['label' => '電話番号', 'default' => '03-XXXX-XXXX'],
        'eikou_email'        => ['label' => 'メールアドレス', 'default' => 'info@eikou.co.jp'],
    ];

    foreach ($fields as $id => $args) {
        $wp_customize->add_setting($id, [
            'default'           => $args['default'],
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control($id, [
            'label'   => $args['label'],
            'section' => 'eikou_company_info',
            'type'    => 'text',
        ]);
    }
}
add_action('customize_register', 'eikou_customizer');

/* ─── Customizer: Contact Forms (Contact Form 7) ─── */
function eikou_contact_form_customizer($wp_customize) {
    $wp_customize->add_section('eikou_contact_forms', [
        'title'    => 'お問い合わせフォーム',
        'priority' => 31,
    ]);

    $fields = [
        'eikou_cf7_full'  => 'フルフォーム shortcode（お問い合わせページ）',
        'eikou_cf7_quick' => 'クイックフォーム shortcode（H5フローティング）',
    ];
    foreach ($fields as $id => $label) {
        $wp_customize->add_setting($id, [
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control($id, [
            'label'       => $label,
            'section'     => 'eikou_contact_forms',
            'type'        => 'text',
            'description' => 'Contact Form 7 で作成したフォームの [contact-form-7 ...] を貼り付けてください。未入力の場合は静的フォームを表示します。',
        ]);
    }
}
add_action('customize_register', 'eikou_contact_form_customizer');

/**
 * CF7 フォームを描画。shortcode 未設定 or CF7 未導入なら空文字を返す（テンプレ側で静的フォールバック）。
 *
 * @param string $key  Customizer setting key（eikou_cf7_full / eikou_cf7_quick）
 * @return string      レンダリング済み HTML、なければ ''
 */
function eikou_render_contact_form($key) {
    $shortcode = eikou_get($key, '');
    if (!empty($shortcode) && shortcode_exists('contact-form-7')) {
        return do_shortcode($shortcode);
    }
    return '';
}

/* ─── 多语言：语言切换助手（接入 Polylang，未启用时回退占位）─── */
function eikou_get_languages() {
    if (function_exists('pll_the_languages')) {
        $langs = pll_the_languages(['raw' => 1, 'hide_if_empty' => 0]);
        if (is_array($langs) && $langs) {
            return $langs;
        }
    }
    return []; // Polylang 未启用 → 模板使用静态占位
}

function eikou_current_language_name() {
    if (function_exists('pll_current_language')) {
        $name = pll_current_language('name');
        if ($name) {
            return $name;
        }
    }
    return '日本語';
}

/* ─── Customizer: Hero Slideshow ─── */
function eikou_hero_customizer($wp_customize) {
    $wp_customize->add_section('eikou_hero_slides', [
        'title'    => 'ヒーロースライドショー',
        'priority' => 25,
    ]);

    for ($i = 1; $i <= 5; $i++) {
        // Slide image
        $wp_customize->add_setting("eikou_hero_slide_{$i}", [
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ]);
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "eikou_hero_slide_{$i}", [
            'label'   => "スライド {$i} 画像",
            'section' => 'eikou_hero_slides',
        ]));

        // Slide link (optional)
        $wp_customize->add_setting("eikou_hero_slide_{$i}_link", [
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ]);
        $wp_customize->add_control("eikou_hero_slide_{$i}_link", [
            'label'   => "スライド {$i} リンクURL",
            'section' => 'eikou_hero_slides',
            'type'    => 'url',
        ]);
    }
}
add_action('customize_register', 'eikou_hero_customizer');

/**
 * Get hero slides array
 */
function eikou_get_hero_slides() {
    $slides = [];
    $defaults = [
        'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1920&q=80',
        'https://images.unsplash.com/photo-1511578314322-379afb476865?w=1920&q=80',
        'https://images.unsplash.com/photo-1497366216548-37526070297c?w=1920&q=80',
        'https://images.unsplash.com/photo-1531058020387-3be344556be6?w=1920&q=80',
    ];

    for ($i = 1; $i <= 5; $i++) {
        $img = get_theme_mod("eikou_hero_slide_{$i}", '');
        if ($img) {
            $slides[] = [
                'image' => $img,
                'link'  => get_theme_mod("eikou_hero_slide_{$i}_link", ''),
            ];
        }
    }

    // Fallback to defaults if no slides configured
    if (empty($slides)) {
        foreach ($defaults as $url) {
            $slides[] = ['image' => $url, 'link' => ''];
        }
    }

    // Compress images for mobile
    foreach ($slides as &$slide) {
        $slide['image'] = eikou_mobile_img_url($slide['image']);
    }
    unset($slide);

    return $slides;
}

/* ─── Helper: Mobile-optimized image URL ─── */
function eikou_mobile_img_url($url, $mobile_width = 800, $mobile_quality = 60) {
    if (!eikou_is_mobile_device()) {
        return $url;
    }
    // Unsplash URLs: replace w= and q= params
    if (strpos($url, 'images.unsplash.com') !== false) {
        $url = preg_replace('/([?&])w=\d+/', '${1}w=' . $mobile_width, $url);
        $url = preg_replace('/([?&])q=\d+/', '${1}q=' . $mobile_quality, $url);
    }
    return $url;
}

/* ─── Mobile: Use smaller WordPress image sizes ─── */
function eikou_mobile_thumbnail_size($size) {
    if (!function_exists('eikou_is_mobile_device') || !eikou_is_mobile_device()) {
        return $size;
    }
    // Downgrade large sizes to thumb sizes on mobile
    $mobile_map = [
        'work-large' => 'work-thumb',
        'full'       => 'large',
        'large'      => 'medium_large',
    ];
    if (is_string($size) && isset($mobile_map[$size])) {
        return $mobile_map[$size];
    }
    return $size;
}
add_filter('post_thumbnail_size', 'eikou_mobile_thumbnail_size');

/* ─── Mobile: Add loading=lazy to all images ─── */
function eikou_mobile_lazy_load($attr) {
    if (function_exists('eikou_is_mobile_device') && eikou_is_mobile_device()) {
        $attr['loading'] = 'lazy';
        $attr['decoding'] = 'async';
    }
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'eikou_mobile_lazy_load');

/* ─── Helper: Get Theme Mod ─── */
function eikou_get($key, $default = '') {
    return get_theme_mod($key, $default);
}

/* ─── Helper: Get Page URL by Slug ─── */
function eikou_page_url($slug) {
    $page = get_page_by_path($slug);
    if ($page) {
        return get_permalink($page);
    }
    // Fallback: try home_url with slug
    return home_url('/' . $slug . '/');
}

/* ─── Menu Fallback Callbacks ─── */
function eikou_fallback_menu() {
    ?>
    <ul>
        <li><a href="<?php echo esc_url(home_url('/')); ?>"<?php if (is_front_page()) echo ' class="active"'; ?>><?php esc_html_e('ホーム', 'eikou'); ?></a></li>
        <li>
            <a href="<?php echo esc_url(eikou_page_url('services')); ?>"<?php if (is_page('services') || (is_page() && strpos(get_post_field('post_name'), 'service-') === 0)) echo ' class="active"'; ?>><?php esc_html_e('サービス', 'eikou'); ?></a>
            <ul class="submenu">
                <li><a href="<?php echo esc_url(eikou_page_url('service-exhibition')); ?>"><?php esc_html_e('展示会・イベント', 'eikou'); ?></a></li>
                <li><a href="<?php echo esc_url(eikou_page_url('service-brand-event')); ?>"><?php esc_html_e('ブランドイベント', 'eikou'); ?></a></li>
                <li><a href="<?php echo esc_url(eikou_page_url('service-digital')); ?>"><?php esc_html_e('デジタル・Web', 'eikou'); ?></a></li>
                <li><a href="<?php echo esc_url(eikou_page_url('service-ai')); ?>"><?php esc_html_e('AIソリューション', 'eikou'); ?></a></li>
                <li><a href="<?php echo esc_url(eikou_page_url('service-branding')); ?>"><?php esc_html_e('ブランディング・デザイン', 'eikou'); ?></a></li>
                <li><a href="<?php echo esc_url(eikou_page_url('service-media')); ?>"><?php esc_html_e('メディア・映像', 'eikou'); ?></a></li>
            </ul>
        </li>
        <li><a href="<?php echo esc_url(eikou_page_url('works')); ?>"<?php if (is_page('works') || is_singular('work')) echo ' class="active"'; ?>><?php esc_html_e('成功事例', 'eikou'); ?></a></li>
        <li><a href="<?php echo esc_url(eikou_page_url('video')); ?>"<?php if (is_page('video')) echo ' class="active"'; ?>><?php esc_html_e('動画', 'eikou'); ?></a></li>
        <li><a href="<?php echo esc_url(eikou_page_url('partners')); ?>"<?php if (is_page('partners')) echo ' class="active"'; ?>><?php esc_html_e('パートナー', 'eikou'); ?></a></li>
        <li><a href="<?php echo esc_url(eikou_page_url('contact')); ?>"<?php if (is_page('contact')) echo ' class="active"'; ?>><?php esc_html_e('お問い合わせ', 'eikou'); ?></a></li>
    </ul>
    <?php
}

function eikou_footer_nav_fallback() {
    ?>
    <ul>
        <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('ホーム', 'eikou'); ?></a></li>
        <li><a href="<?php echo esc_url(eikou_page_url('about')); ?>"><?php esc_html_e('荣光について', 'eikou'); ?></a></li>
        <li><a href="<?php echo esc_url(eikou_page_url('services')); ?>"><?php esc_html_e('サービス', 'eikou'); ?></a></li>
        <li><a href="<?php echo esc_url(eikou_page_url('works')); ?>"><?php esc_html_e('成功事例', 'eikou'); ?></a></li>
        <li><a href="<?php echo esc_url(eikou_page_url('service-exhibition')); ?>"><?php esc_html_e('展示会・イベント', 'eikou'); ?></a></li>
        <li><a href="<?php echo esc_url(eikou_page_url('service-brand-event')); ?>"><?php esc_html_e('ブランドイベント', 'eikou'); ?></a></li>
    </ul>
    <?php
}

function eikou_footer_other_fallback() {
    ?>
    <ul>
        <li><a href="<?php echo esc_url(eikou_page_url('service-digital')); ?>"><?php esc_html_e('デジタル・Web', 'eikou'); ?></a></li>
        <li><a href="<?php echo esc_url(eikou_page_url('service-ai')); ?>"><?php esc_html_e('AIソリューション', 'eikou'); ?></a></li>
        <li><a href="<?php echo esc_url(eikou_page_url('video')); ?>"><?php esc_html_e('動画センター', 'eikou'); ?></a></li>
        <li><a href="<?php echo esc_url(eikou_page_url('partners')); ?>"><?php esc_html_e('パートナー', 'eikou'); ?></a></li>
        <li><a href="<?php echo esc_url(eikou_page_url('contact')); ?>"><?php esc_html_e('お問い合わせ', 'eikou'); ?></a></li>
    </ul>
    <?php
}

/* ─── Service Items Data (24 services) ─── */
function eikou_get_service_items() {
    $img_base = content_url('/uploads/services/');
    return [
        // A. 展示会・イベント (7 items)
        'service-booth-design' => [
            'number' => '01', 'category' => 'service-exhibition', 'category_name' => '展示会・イベント',
            'title' => '展示会ブースのデザイン・設計・施工',
            'title_en' => 'Booth Design & Construction',
            'hero_image' => $img_base . 'catl-booth.jpg',
            'description' => '企業のブランドイメージを最大限に引き出すブースデザインを企画・設計し、高品質な施工で具現化します。東京ビッグサイト、幕張メッセをはじめとする日本全国の主要展示会場での豊富な実績を活かし、来場者の動線設計から体験演出まで、展示効果を最大化するトータルソリューションを提供いたします。',
            'features' => [
                'ブランドコンセプトに基づくブースデザイン企画',
                '3Dパース・CGによる完成イメージの可視化',
                'ブースレイアウト・来場者動線の最適設計',
                '高品質な施工・設営・撤去の一貫管理',
                'インタラクティブ展示・体験型コンテンツの設計',
                'VIP商談スペース・ミーティングエリアの設計',
            ],
            'points' => [
                ['title' => '豊富な実績', 'desc' => '東京ビッグサイト、幕張メッセ、パシフィコ横浜など日本全国の主要会場で500件以上のブース施工実績'],
                ['title' => 'ワンストップ対応', 'desc' => 'デザイン・設計・施工・撤去まで一貫した品質管理で、お客様の負担を最小限に'],
                ['title' => '短納期対応', 'desc' => '限られた設営期間内での高品質な仕上がりを実現する独自のプロジェクト管理体制'],
            ],
            'tags' => ['ブースデザイン', '3D設計', '施工管理', 'ワンストップ'],
        ],
        'service-display-fixtures' => [
            'number' => '02', 'category' => 'service-exhibition', 'category_name' => '展示会・イベント',
            'title' => '展示什器・展示架・ディスプレイ道具の制作',
            'title_en' => 'Display Fixtures & Props',
            'hero_image' => $img_base . 'catl-display.jpg',
            'description' => '製品の魅力を最大限に引き出すオーダーメイドの展示什器・展示架・ディスプレイ道具を制作します。素材の選定からデザイン、精密加工、仕上げまで、用途と予算に応じた最適なソリューションをご提案。展示会での繰り返し使用にも耐える高耐久性と、ブランドイメージを反映した美しい仕上がりを両立します。',
            'features' => [
                'オーダーメイド什器・展示台の設計・制作',
                '可動式・組立式展示架の開発・製作',
                'アクリル・金属・木材など多素材対応',
                'リユース可能な展示システムの開発',
                '製品サイズに合わせた精密なフィッティング',
                '量産対応・小ロット制作いずれも可能',
            ],
            'points' => [
                ['title' => '多素材対応', 'desc' => 'アクリル、ステンレス、アルミ、木材、MDF等あらゆる素材に対応した制作体制'],
                ['title' => 'カスタム設計', 'desc' => '製品の形状・重量・展示目的に合わせた完全オーダーメイドの設計'],
                ['title' => 'コスト最適化', 'desc' => '使い捨てではなくリユース可能な設計で、長期的なコスト削減を実現'],
            ],
            'tags' => ['什器制作', 'ディスプレイ', 'オーダーメイド', '多素材対応'],
        ],
        'service-steel-structure' => [
            'number' => '03', 'category' => 'service-exhibition', 'category_name' => '展示会・イベント',
            'title' => '鉄骨構造・アルミ合金構造の加工制作',
            'title_en' => 'Steel & Aluminum Structure',
            'hero_image' => $img_base . 'catl-stage.jpg',
            'description' => '大型展示ブースや特殊構造物に不可欠な鉄骨・アルミ合金構造の設計・加工・制作を行います。構造計算に基づく安全設計と、美観を両立した高精度な金属加工で、大規模プロジェクトの基盤を支えます。自社工場での一貫生産により、品質とコストの最適化を実現。',
            'features' => [
                '鉄骨フレーム・トラス構造の設計加工',
                '軽量アルミフレームの制作・組立',
                '構造計算・強度検証・安全性認証',
                '吊り構造・大型看板フレーム制作',
                'CNC加工・レーザーカットによる高精度加工',
                '表面処理（溶融亜鉛メッキ・粉体塗装等）',
            ],
            'points' => [
                ['title' => '安全第一', 'desc' => '構造計算書の作成と第三者機関による安全性検証で、大規模構造物も安心'],
                ['title' => '高精度加工', 'desc' => 'CNC加工機・レーザーカッターによるミリ単位の精密な金属加工'],
                ['title' => '自社工場', 'desc' => '自社工場での一貫生産体制により、品質管理と納期厳守を実現'],
            ],
            'tags' => ['鉄骨加工', 'アルミ構造', '安全設計', 'CNC加工'],
        ],
        'service-woodwork' => [
            'number' => '04', 'category' => 'service-exhibition', 'category_name' => '展示会・イベント',
            'title' => '木工制作・塗装・焼付塗装加工',
            'title_en' => 'Woodwork & Painting',
            'hero_image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85f82e?w=1200&q=80',
            'description' => '展示ブースや什器に温かみと高級感を与える木工制作と、耐久性・美観に優れた塗装加工を提供します。無垢材から合板・MDFまで多様な木材に対応し、CNC加工による精密カット、職人による手仕上げ、各種塗装（ウレタン・ラッカー・焼付）まで一貫して対応します。',
            'features' => [
                '無垢材・合板・MDFの精密加工',
                'CNC加工による複雑形状のカット',
                'ウレタン塗装・ラッカー塗装・焼付塗装',
                '特殊仕上げ（エイジング・メタリック・鏡面等）',
                '突板貼り・化粧シート仕上げ',
                '防火・防水処理対応',
            ],
            'points' => [
                ['title' => '職人技術', 'desc' => '熟練職人による手仕上げで、高級感のある美しい仕上がりを実現'],
                ['title' => '多彩な仕上げ', 'desc' => 'マット・グロス・テクスチャー・メタリック等、あらゆる仕上げに対応'],
                ['title' => '品質管理', 'desc' => '温度・湿度管理された塗装ブースでの作業で、均一な品質を保証'],
            ],
            'tags' => ['木工制作', '塗装加工', 'CNC加工', '焼付塗装'],
        ],
        'service-led-av' => [
            'number' => '05', 'category' => 'service-exhibition', 'category_name' => '展示会・イベント',
            'title' => 'LEDディスプレイ・照明・音響・ステージ機材のレンタル・設置',
            'title_en' => 'LED, Lighting & AV Equipment',
            'hero_image' => $img_base . 'catl-tech.jpg',
            'description' => '展示会・イベントを華やかに演出するLEDディスプレイ、照明、音響、ステージ機材のレンタルおよびプロフェッショナルな設置サービスを提供します。会場規模や演出プランに合わせた最適な機材構成をご提案し、技術スタッフによる確実なオペレーションで、イベントの成功を支えます。',
            'features' => [
                '高精細LEDビジョン・大型スクリーンのレンタル・設置',
                'ムービングライト・スポットライト・アンビエント照明演出',
                'PA音響システム・ワイヤレスマイクの設置・運用',
                'ステージ・演台・トラスの組立施工',
                'プロジェクションマッピング機材の手配',
                '映像スイッチング・ライブ配信機材',
            ],
            'points' => [
                ['title' => '最新機材', 'desc' => '高精細P2.5以下のLEDパネルから4Kプロジェクターまで、最新の映像機材を保有'],
                ['title' => '技術スタッフ', 'desc' => '経験豊富な照明・音響・映像技術スタッフが現場でのオペレーションを担当'],
                ['title' => '柔軟な対応', 'desc' => '小規模セミナーから大型コンベンションまで、規模に応じた機材プランを提案'],
            ],
            'tags' => ['LED演出', '音響照明', '機材レンタル', 'ステージ'],
        ],
        'service-logistics' => [
            'number' => '06', 'category' => 'service-exhibition', 'category_name' => '展示会・イベント',
            'title' => '展示会資材の倉庫保管・輸送・搬入搬出・現場管理',
            'title_en' => 'Logistics & On-site Management',
            'hero_image' => $img_base . 'catl-venue.jpg',
            'description' => '展示資材の保管から会場への輸送、搬入・搬出作業、現場での資材管理まで、物流面をトータルでサポートします。温度・湿度管理された自社倉庫での保管サービスに加え、全国各地の会場への効率的な配送体制を構築。複数会場の巡回展示にも対応し、お客様の物流負担を大幅に軽減します。',
            'features' => [
                '展示資材の倉庫保管・在庫管理',
                '全国の展示会場への配送・輸送手配',
                '搬入・搬出作業の計画策定と実行',
                'フォークリフト・クレーン等の重機手配',
                '巡回展示の物流コーディネート',
                '資材のメンテナンス・補修対応',
            ],
            'points' => [
                ['title' => '自社倉庫', 'desc' => '温度・湿度管理された倉庫で大切な展示資材を安全に保管'],
                ['title' => '全国配送', 'desc' => '北海道から沖縄まで、日本全国の会場への配送ネットワーク'],
                ['title' => '巡回対応', 'desc' => '複数会場をまわる巡回展示の物流計画・実行を一括コーディネート'],
            ],
            'tags' => ['物流管理', '倉庫保管', '搬入搬出', '全国配送'],
        ],
        'service-onsite-ops' => [
            'number' => '07', 'category' => 'service-exhibition', 'category_name' => '展示会・イベント',
            'title' => '展示会の現場運営・プロジェクト管理・実行サービス',
            'title_en' => 'On-site Operations & PM',
            'hero_image' => $img_base . 'catl-crowd.jpg',
            'description' => '展示会当日の現場運営からプロジェクト全体のマネジメントまで、経験豊富なプロフェッショナルチームが対応します。スケジュール管理、スタッフ配置、来場者対応、各種トラブルシューティングを包括的にサポートし、お客様は本業に集中していただける環境を構築します。',
            'features' => [
                'プロジェクトマネージャーによる全体統括',
                '運営スタッフ・コンパニオン・通訳の手配',
                '来場者受付・誘導・案内システムの運用',
                'リアルタイム進行管理・タイムキーピング',
                '緊急時対応・リスクマネジメント',
                '来場者データ集計・効果測定レポート作成',
            ],
            'points' => [
                ['title' => '経験豊富なPM', 'desc' => '年間100件以上の展示会を管理する経験豊富なプロジェクトマネージャーが統括'],
                ['title' => '多言語対応', 'desc' => '日本語・中国語・英語対応のスタッフ配置で、国際的なイベントにも対応'],
                ['title' => '効果測定', 'desc' => '来場者データの集計・分析による効果測定レポートで、次回改善に活用'],
            ],
            'tags' => ['現場運営', 'PM管理', 'スタッフ配置', '多言語対応'],
        ],
        // B. ブランドイベント (5 items)
        'service-launch-event' => [
            'number' => '01', 'category' => 'service-brand-event', 'category_name' => 'ブランドイベント',
            'title' => '企業発表会・ブランドイベント・ロードショーの企画実行',
            'title_en' => 'Launch Events & Roadshows',
            'hero_image' => $img_base . 'gokin-presentation.jpg',
            'description' => '新製品発表会、プレスカンファレンス、プロモーションイベント、全国巡回ロードショーなど、企業のブランドメッセージを効果的に伝えるイベントを企画・実行します。コンセプト設計からシナリオ作成、会場選定、演出プラン、当日運営まで一括対応。メディア露出の最大化と、参加者に深い印象を残すブランド体験を創出します。',
            'features' => ['イベントコンセプト企画・シナリオ設計', 'プレスカンファレンス・メディア向けイベント', '全国巡回ロードショーの企画運営', 'ステージ演出・映像・照明・音響プロデュース', 'MC・モデル・通訳スタッフの手配', 'メディア対応・プレスリリース配信サポート'],
            'points' => [
                ['title' => 'メディア戦略', 'desc' => 'プレスリリース配信からメディアリレーション構築まで、露出最大化をサポート'],
                ['title' => '全国展開', 'desc' => '東京を拠点に日本全国でのロードショー・巡回イベントに対応'],
                ['title' => '演出力', 'desc' => 'プロジェクションマッピング・LED演出・ライブパフォーマンスまで多彩な演出'],
            ],
            'tags' => ['発表会', 'ロードショー', 'イベント企画', 'メディア対応'],
        ],
        'service-popup-store' => [
            'number' => '02', 'category' => 'service-brand-event', 'category_name' => 'ブランドイベント',
            'title' => 'ポップアップストア・商業空間・ブランド展示空間の施工',
            'title_en' => 'Pop-up Stores & Brand Spaces',
            'hero_image' => $img_base . 'hithium-performance.jpg',
            'description' => '期間限定のポップアップストアや常設のブランド展示空間を、コンセプト設計から施工・撤去まで一貫してサポートします。ブランドの世界観を空間全体で表現し、来場者に記憶に残る体験を創出。SNS映えする空間演出からインタラクティブな体験設計まで、現代の消費者に響くブランド空間を実現します。',
            'features' => ['ポップアップストアの空間デザイン・施工', 'ショールーム・展示スペースの内装設計', '什器・ディスプレイ・照明の一括手配', 'SNS映えする空間演出・フォトスポット設計', 'デジタルサイネージ・インタラクティブ展示', '期間限定スペースの短期施工・撤去'],
            'points' => [
                ['title' => 'SNS効果', 'desc' => 'フォトジェニックな空間設計で、来場者の自発的なSNS投稿を促進'],
                ['title' => '短期施工', 'desc' => '期間限定スペースに求められるスピーディーな施工と確実な撤去対応'],
                ['title' => 'ブランド表現', 'desc' => 'ブランドの世界観を空間で体現し、深い顧客エンゲージメントを実現'],
            ],
            'tags' => ['ポップアップ', '商業空間', '空間デザイン', 'SNS演出'],
        ],
        'service-brand-promotion' => [
            'number' => '03', 'category' => 'service-brand-event', 'category_name' => 'ブランドイベント',
            'title' => '企業ブランドプロモーション・マーケティング活動の実施',
            'title_en' => 'Brand Promotion & Marketing',
            'hero_image' => $img_base . 'gokin-audience.jpg',
            'description' => 'ブランド認知の向上や顧客エンゲージメントの強化を目的としたプロモーション施策を企画・実施します。オフラインイベントとデジタル施策を組み合わせた統合マーケティングアプローチで、ターゲット層への効果的なアプローチを実現します。',
            'features' => ['ブランドプロモーション戦略の立案', 'サンプリング・体験型イベントの企画運営', 'インフルエンサー・KOLとの連携企画', 'オンライン×オフライン統合キャンペーン', 'キャンペーンの効果測定・ROI分析', 'CRMデータ連携によるターゲティング'],
            'points' => [
                ['title' => '統合アプローチ', 'desc' => 'オフラインイベントとデジタル施策を連動させた統合マーケティング'],
                ['title' => 'データドリブン', 'desc' => '効果測定・ROI分析に基づく継続的な施策改善サイクル'],
                ['title' => 'KOL連携', 'desc' => '影響力のあるインフルエンサー・KOLとの連携でリーチを最大化'],
            ],
            'tags' => ['プロモーション', 'マーケティング', 'ブランディング', 'KOL'],
        ],
        'service-nationwide' => [
            'number' => '04', 'category' => 'service-brand-event', 'category_name' => 'ブランドイベント',
            'title' => '日本全国の展示会・イベント・商業空間の施工実行',
            'title_en' => 'Nationwide Execution',
            'hero_image' => $img_base . 'catl-exterior.jpg',
            'description' => '東京・大阪・名古屋をはじめ、北海道から沖縄まで日本全国どこでも対応可能な施工実行サービスです。各地域の会場特性を熟知したスタッフと、全国ネットワークのパートナー企業との連携により、安定した品質の施工を全国規模で提供します。',
            'features' => ['日本全国主要会場での施工対応', '各地域のパートナーネットワーク活用', '遠隔地プロジェクトの物流コーディネート', '複数拠点の同時施工管理', '地域特有の規制・ルールへの対応', '全国統一品質基準の維持'],
            'points' => [
                ['title' => '全国ネットワーク', 'desc' => '全国47都道府県にパートナーネットワークを構築し、地域密着のサービスを提供'],
                ['title' => '品質統一', 'desc' => '独自の品質基準と施工マニュアルにより、全国どこでも同じ高品質を保証'],
                ['title' => '同時多拠点', 'desc' => '複数会場での同時施工も、中央管理体制でスムーズに実行'],
            ],
            'tags' => ['全国対応', '施工実行', 'ネットワーク', '品質統一'],
        ],
        'service-japan-entry' => [
            'number' => '05', 'category' => 'service-brand-event', 'category_name' => 'ブランドイベント',
            'title' => '海外企業の日本市場参入プロジェクト支援',
            'title_en' => 'Japan Market Entry Support',
            'hero_image' => $img_base . 'catl-event.jpg',
            'description' => '海外企業が日本市場に参入する際のイベント・展示会出展を包括的にサポートします。言語・文化・ビジネス慣習の壁を越え、日本市場での効果的なブランド展開を実現。中国語・英語・日本語のトリリンガルチームが、企画段階から現場運営まで一貫して伴走します。',
            'features' => ['日本市場向けブランディング戦略アドバイス', '展示会出展の全工程マネジメント', '日中英トリリンガル対応（通訳・翻訳）', '日本のビジネス慣習・規制対応サポート', '現地パートナー・メディアとの関係構築', '市場調査・競合分析レポート'],
            'points' => [
                ['title' => 'トリリンガル', 'desc' => '日本語・中国語・英語に精通したチームが、コミュニケーションの壁を解消'],
                ['title' => '文化理解', 'desc' => '日本と海外双方のビジネス文化を理解し、スムーズなプロジェクト進行を実現'],
                ['title' => 'ワンストップ', 'desc' => '市場調査から展示会出展・メディア対応まで、参入に必要なすべてを一括サポート'],
            ],
            'tags' => ['海外企業支援', '日本参入', '多言語対応', '市場調査'],
        ],
        // C. デジタル・Web (3 items)
        'service-web-design' => [
            'number' => '01', 'category' => 'service-digital', 'category_name' => 'デジタル・Web',
            'title' => 'Webサイトのデザイン・開発',
            'title_en' => 'Web Design & Development',
            'hero_image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=1200&q=80',
            'description' => '企業の個性とブランド価値を反映したWebサイトを、企画・デザイン・開発・公開まで一貫して対応します。レスポンシブデザイン、CMS構築、ECサイト開発、多言語対応など、あらゆるWeb制作ニーズに対応。最新のフロントエンド技術とSEOベストプラクティスを取り入れた、成果につながるWebサイトを構築します。',
            'features' => ['コーポレートサイト・ブランドサイトの企画制作', 'WordPress・ヘッドレスCMSによるサイト構築', 'ECサイト・予約システムの開発', 'レスポンシブ・モバイルファーストデザイン', '多言語サイト対応（日本語・中国語・英語）', 'SSL・セキュリティ対策・保守運用サポート'],
            'points' => [
                ['title' => 'デザイン力', 'desc' => 'ブランドの世界観を反映した美しいUIデザインと、使いやすいUX設計'],
                ['title' => '技術力', 'desc' => 'React/Next.js/WordPress等、最新技術を活用した高パフォーマンスなサイト構築'],
                ['title' => '成果主義', 'desc' => 'ビジネス目標を達成するためのKPI設計とコンバージョン最適化'],
            ],
            'tags' => ['Web制作', 'CMS構築', 'レスポンシブ', 'EC開発'],
        ],
        'service-web-marketing' => [
            'number' => '02', 'category' => 'service-digital', 'category_name' => 'デジタル・Web',
            'title' => 'Webサイト最適化・デジタルマーケティング',
            'title_en' => 'SEO & Digital Marketing',
            'hero_image' => 'https://images.unsplash.com/photo-1432888498266-38ffec3eaf0a?w=1200&q=80',
            'description' => '既存Webサイトのパフォーマンス向上と、検索エンジン・SNS・広告を活用した集客施策を提供します。データ分析に基づく戦略立案で、Webサイトからのコンバージョンを最大化。SEO内部施策からリスティング広告運用、コンテンツマーケティングまで、デジタルプレゼンスの総合的な強化を支援します。',
            'features' => ['SEO対策（内部施策・コンテンツ最適化）', 'Google広告・SNS広告の運用代行', 'アクセス解析・コンバージョン改善（CRO）', 'ページ表示速度・Core Web Vitals最適化', 'コンテンツマーケティング戦略の立案実行', 'MEO（ローカルSEO）対策'],
            'points' => [
                ['title' => 'データドリブン', 'desc' => 'Google Analytics・Search Console等のデータに基づく科学的なアプローチ'],
                ['title' => 'ROI重視', 'desc' => '広告費用対効果を最大化する継続的な最適化と透明性のあるレポーティング'],
                ['title' => '総合的支援', 'desc' => 'SEO・広告・SNS・コンテンツを横断した統合デジタルマーケティング'],
            ],
            'tags' => ['SEO対策', '広告運用', 'アクセス解析', 'CRO'],
        ],
        'service-app-dev' => [
            'number' => '03', 'category' => 'service-digital', 'category_name' => 'デジタル・Web',
            'title' => 'アプリケーション開発',
            'title_en' => 'Application Development',
            'hero_image' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=1200&q=80',
            'description' => 'iOS・Androidネイティブアプリからクロスプラットフォームアプリ、業務用Webアプリケーションまで、多様なアプリ開発に対応します。ユーザビリティを重視したUI/UX設計と、堅牢なバックエンド構築を両立。要件定義から設計・開発・テスト・公開・運用保守まで、ライフサイクル全体をサポートします。',
            'features' => ['iOS/Androidネイティブアプリ開発', 'React Native・Flutterクロスプラットフォーム開発', '業務効率化Webアプリケーション開発', 'API設計・バックエンドシステム構築', 'UI/UXデザイン・プロトタイピング', 'アプリ公開・ストア申請・運用保守サポート'],
            'points' => [
                ['title' => 'UI/UX設計', 'desc' => 'ユーザーリサーチに基づくUI/UX設計で、使いやすく満足度の高いアプリを開発'],
                ['title' => 'クロスプラットフォーム', 'desc' => 'Flutter/React Nativeで一つのコードからiOS・Android両対応アプリを効率開発'],
                ['title' => '運用保守', 'desc' => '公開後のバグ修正・機能追加・サーバー監視まで継続的にサポート'],
            ],
            'tags' => ['アプリ開発', 'UI/UX', 'クロスプラットフォーム', 'API'],
        ],
        // D. AIソリューション (3 items)
        'service-ai-chatbot' => [
            'number' => '01', 'category' => 'service-ai', 'category_name' => 'AIソリューション',
            'title' => 'AIチャットボット導入',
            'title_en' => 'AI Chatbot Solutions',
            'hero_image' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=1200&q=80',
            'description' => '最新のLLM（大規模言語モデル）技術を活用したインテリジェントなAIチャットボットを開発・導入します。Webサイト、LINE、WeChat等のプラットフォームに統合し、24時間365日の自動顧客対応を実現。日本語・中国語・英語の多言語対応で、グローバルな顧客サポートも可能です。',
            'features' => ['GPT/LLM技術によるインテリジェントチャットボット', 'FAQ自動応答・問い合わせ分類システム', 'LINE・WeChat・Webサイトへの統合', '多言語対応（日本語・中国語・英語）', '社内ナレッジベースとの連携', 'チャット履歴分析・改善レポート'],
            'points' => [
                ['title' => '最新AI技術', 'desc' => 'GPT-4o等の最新LLMを活用し、自然で的確な対話を実現するチャットボット'],
                ['title' => '多プラットフォーム', 'desc' => 'Webサイト・LINE・WeChat・Slack等、主要プラットフォームへの統合に対応'],
                ['title' => '継続改善', 'desc' => '対話ログの分析に基づく回答精度の継続的な改善と、月次レポートの提供'],
            ],
            'tags' => ['チャットボット', 'LLM', 'NLP', '多言語対応'],
        ],
        'service-ai-modeling' => [
            'number' => '02', 'category' => 'service-ai', 'category_name' => 'AIソリューション',
            'title' => 'AIモデリング・AIアプリケーション',
            'title_en' => 'AI Modeling & Applications',
            'hero_image' => 'https://images.unsplash.com/photo-1555255707-c07966088b7b?w=1200&q=80',
            'description' => '機械学習・ディープラーニングを活用したカスタムAIモデルの設計・構築・運用を提供します。画像認識、需要予測、レコメンデーション、異常検知など、ビジネス課題に応じたAIソリューションを開発。データ収集・前処理からモデル学習・評価・デプロイまで、AI導入の全工程をサポートします。',
            'features' => ['カスタムAIモデルの設計・学習・デプロイ', '画像認識・物体検出システム開発', '需要予測・売上分析AIの構築', '3Dモデリング・バーチャル空間のAI生成', 'RAG（検索拡張生成）システムの構築', 'AIモデルの継続的改善・運用保守'],
            'points' => [
                ['title' => 'カスタム開発', 'desc' => '業界・業務特化のカスタムAIモデルを開発し、汎用ツールでは得られない精度を実現'],
                ['title' => 'エンドツーエンド', 'desc' => 'データ収集・前処理からモデル開発・デプロイ・運用まで一貫してサポート'],
                ['title' => 'ROI検証', 'desc' => 'PoC（概念実証）による効果検証を経て本格導入することで、投資リスクを最小化'],
            ],
            'tags' => ['機械学習', '画像認識', '予測分析', 'RAG'],
        ],
        'service-automation' => [
            'number' => '03', 'category' => 'service-ai', 'category_name' => 'AIソリューション',
            'title' => '自動化カスタマーサポートシステム',
            'title_en' => 'Automated Support Systems',
            'hero_image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=1200&q=80',
            'description' => '顧客からの問い合わせ管理を自動化するチケットシステムを構築します。AI分類・優先度判定・自動ルーティングにより、対応速度と顧客満足度を大幅に向上。CRM連携による顧客データの一元管理と、ダッシュボードによる対応品質の可視化も実現します。',
            'features' => ['AI駆動の問い合わせ分類・優先度自動判定', 'チケット自動ルーティング・エスカレーション', '顧客対応テンプレート・ナレッジベース構築', 'CRM・既存業務システムとのAPI連携', '対応品質の分析ダッシュボード', 'SLA管理・レポート自動生成'],
            'points' => [
                ['title' => '業務効率化', 'desc' => 'AI自動分類により、問い合わせ対応時間を平均50%短縮'],
                ['title' => 'システム連携', 'desc' => 'Salesforce・Zendesk・kintone等の既存システムとのスムーズなAPI連携'],
                ['title' => '品質可視化', 'desc' => 'リアルタイムダッシュボードで対応状況・品質指標を常時モニタリング'],
            ],
            'tags' => ['自動化', 'チケットシステム', 'CRM連携', 'ダッシュボード'],
        ],
        // E. ブランディング・デザイン (3 items)
        'service-package-design' => [
            'number' => '01', 'category' => 'service-branding', 'category_name' => 'ブランディング・デザイン',
            'title' => 'ブランドパッケージ・デザイン',
            'title_en' => 'Brand & Package Design',
            'hero_image' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=1200&q=80',
            'description' => '製品パッケージ、ギフトボックス、ショッピングバッグなど、ブランドの世界観を反映したパッケージデザインを制作します。素材選定から印刷仕様の監修、プロトタイプ制作まで、製品の価値を最大限に引き出す高品質なパッケージの実現をサポートします。',
            'features' => ['商品パッケージのコンセプト設計・デザイン', 'ギフトパッケージ・限定パッケージの企画', 'VI（ビジュアルアイデンティティ）設計', '素材・印刷仕様の選定アドバイス', 'パッケージプロトタイプの制作・検証', 'サステナブルパッケージの提案'],
            'points' => [
                ['title' => 'ブランド一貫性', 'desc' => 'VI設計に基づく統一されたビジュアル言語で、ブランド認知を強化'],
                ['title' => '素材知識', 'desc' => '紙・プラスチック・木材・金属等の素材特性を熟知し、最適な素材を提案'],
                ['title' => 'サステナビリティ', 'desc' => '環境に配慮したエコフレンドリーなパッケージソリューションの提案'],
            ],
            'tags' => ['パッケージ', 'VI設計', 'ブランドデザイン', 'エコ'],
        ],
        'service-brand-pr' => [
            'number' => '02', 'category' => 'service-branding', 'category_name' => 'ブランディング・デザイン',
            'title' => 'ブランド・製品プロモーション',
            'title_en' => 'Brand & Product Promotion',
            'hero_image' => $img_base . 'hithium-ceremony.jpg',
            'description' => 'ブランドや製品の認知拡大・販売促進のための統合プロモーション戦略を立案・実行します。POP制作、ノベルティ企画、キャンペーン設計など、オフライン施策とデジタル施策を組み合わせ、ターゲット層への効果的なアプローチを実現します。',
            'features' => ['ブランドプロモーション戦略の立案', 'キャンペーン企画・クリエイティブ制作', 'POP・店頭販促ツールのデザイン制作', 'ノベルティグッズの企画・制作', '新商品ローンチキャンペーンの設計', 'プロモーション効果の測定・改善提案'],
            'points' => [
                ['title' => '戦略的アプローチ', 'desc' => 'ターゲット分析に基づく戦略的なプロモーション設計で、費用対効果を最大化'],
                ['title' => 'クリエイティブ力', 'desc' => 'インパクトのあるビジュアルとコピーで、消費者の心に残るプロモーションを展開'],
                ['title' => 'ワンストップ', 'desc' => '企画・デザイン・制作・配布・効果測定まで一貫対応でスムーズな進行'],
            ],
            'tags' => ['プロモーション', '販促', 'キャンペーン', 'ノベルティ'],
        ],
        'service-print-design' => [
            'number' => '03', 'category' => 'service-branding', 'category_name' => 'ブランディング・デザイン',
            'title' => 'ポスター・パンフレットデザイン',
            'title_en' => 'Poster & Brochure Design',
            'hero_image' => 'https://images.unsplash.com/photo-1586717791821-3f44a563fa4c?w=1200&q=80',
            'description' => '展示会・イベント・店頭で使用するポスター、パンフレット、カタログなどの販促印刷物をデザイン・制作します。ブランドガイドラインに沿った統一感のあるデザインで、企業のメッセージを効果的に伝えます。企画・デザインから入稿データ作成・印刷手配まで対応。',
            'features' => ['ポスター・バナーのデザイン制作', '会社案内・製品カタログの企画編集', 'パンフレット・リーフレットのデザイン', '名刺・封筒・レターヘッドの統一デザイン', '展示会用パネル・バックパネルのデザイン', '印刷入稿データの作成・品質管理'],
            'points' => [
                ['title' => 'デザイン品質', 'desc' => 'グラフィックデザイナーによるプロフェッショナルなレイアウトとタイポグラフィ'],
                ['title' => 'ブランド統一', 'desc' => 'ブランドガイドラインに準拠した統一感のあるデザインで、ブランド価値を強化'],
                ['title' => '印刷知識', 'desc' => '印刷工程を熟知したデータ作成で、仕上がりイメージ通りの高品質な印刷物を実現'],
            ],
            'tags' => ['印刷物', 'カタログ', 'グラフィック', 'パンフレット'],
        ],
        // F. メディア・映像 (3 items)
        'service-media-ops' => [
            'number' => '01', 'category' => 'service-media', 'category_name' => 'メディア・映像',
            'title' => 'メディア運営・プロモーション',
            'title_en' => 'Media Operations & Promotion',
            'hero_image' => $img_base . 'hithium-mc.jpg',
            'description' => '企業のSNSアカウント、Webメディア、メールマガジンなどの運営を代行します。コンテンツ企画・制作・投稿スケジュール管理・効果分析まで、一貫したメディア運営で継続的なブランド露出と顧客エンゲージメントの向上を実現します。',
            'features' => ['SNSアカウント運用代行（Instagram・X・WeChat・RED等）', 'コンテンツ企画・ライティング・デザイン', '投稿スケジュール管理・自動投稿設定', 'エンゲージメント分析・月次レポート作成', 'インフルエンサーマーケティング施策', 'クロスボーダーSNS運用（日本・中国・東南アジア）'],
            'points' => [
                ['title' => 'クロスボーダー', 'desc' => '日本のSNSと中国のWeChat・RED・Douyin等、クロスボーダーのSNS運営に対応'],
                ['title' => 'コンテンツ力', 'desc' => '写真・動画・テキストのトータルコンテンツ制作で、魅力的な投稿を継続発信'],
                ['title' => 'データ分析', 'desc' => 'エンゲージメント率・リーチ・フォロワー推移等のKPIを月次で分析レポート'],
            ],
            'tags' => ['SNS運用', 'コンテンツ', 'メディア', 'クロスボーダー'],
        ],
        'service-video-production' => [
            'number' => '02', 'category' => 'service-media', 'category_name' => 'メディア・映像',
            'title' => '動画・アニメーション制作',
            'title_en' => 'Video & Animation Production',
            'hero_image' => $img_base . 'hithium-stage.jpg',
            'description' => '企業PR動画、製品紹介映像、イベント記録、SNS向けショート動画、モーショングラフィックス、3Dアニメーションなど、多様な映像コンテンツを企画・制作します。企画構成・撮影・編集・ナレーション収録・音楽選曲まで一貫対応。',
            'features' => ['企業PR・ブランド動画の企画制作', '製品紹介・使い方説明動画', 'イベント記録・ダイジェスト映像', 'モーショングラフィックス・3Dアニメーション', 'SNS向けショート動画・リール制作', 'ドローン空撮・タイムラプス撮影'],
            'points' => [
                ['title' => 'ワンストップ', 'desc' => '企画・シナリオ・撮影・編集・MA（音声収録）まで社内で一貫対応'],
                ['title' => '多彩な表現', 'desc' => '実写・アニメーション・モーショングラフィックス・3DCGなど多彩な映像表現'],
                ['title' => 'マルチ展開', 'desc' => 'Web・SNS・展示会・デジタルサイネージなど、用途に最適化した映像フォーマット'],
            ],
            'tags' => ['動画制作', 'アニメーション', '映像編集', 'モーショングラフィックス'],
        ],
        'service-signage' => [
            'number' => '03', 'category' => 'service-media', 'category_name' => 'メディア・映像',
            'title' => '大判印刷・UVプリント・切り文字・サイン看板制作',
            'title_en' => 'Large Format Print & Signage',
            'hero_image' => 'https://images.unsplash.com/photo-1504868584819-f8e8b4b6d7e3?w=1200&q=80',
            'description' => '展示会・店舗・屋外広告で使用する大判印刷物、UVプリント、切り文字（チャンネル文字）、サイン看板、標識などを制作・設置します。最新のインクジェットプリンターとレーザー加工機を活用し、高品質な仕上がりを実現。デザインから制作・現場施工まで一貫対応。',
            'features' => ['大型バナー・タペストリー・バックパネルの印刷', 'UVインクジェットプリント（アクリル・木材・金属対応）', 'ステンレス・アクリル切り文字の制作設置', 'LEDサイン・内照式看板の設計制作', '車両ラッピング・ウインドウフィルム施工', 'デジタルサイネージコンテンツ制作'],
            'points' => [
                ['title' => '高品質印刷', 'desc' => '最新の大判インクジェットプリンターにより、高解像度・高彩度の印刷物を制作'],
                ['title' => '多素材対応', 'desc' => '紙・布・アクリル・アルミ・ガラスなど、あらゆる素材への印刷・加工に対応'],
                ['title' => '施工込み', 'desc' => 'デザイン・制作だけでなく、現場での看板設置・ラッピング施工まで一括対応'],
            ],
            'tags' => ['大判印刷', '看板制作', 'UVプリント', '切り文字'],
        ],
    ];
}

add_filter('rest_url_prefix', function() {
    return 'api';  // 改成你想要的路径，比如 'api' 或 'json-api'
});

add_action('init', function() {
    if (defined('REST_REQUEST') && REST_REQUEST) {
        error_log('REST API 请求进来啦！路径: ' . $_SERVER['REQUEST_URI']);
    }
});

/* ─── Flush Rewrite Rules on Activation ─── */
function eikou_rewrite_flush() {
    eikou_register_work_cpt();
    eikou_register_video_cpt();
    eikou_register_partner_cpt();
    eikou_register_testimonial_cpt();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'eikou_rewrite_flush');
