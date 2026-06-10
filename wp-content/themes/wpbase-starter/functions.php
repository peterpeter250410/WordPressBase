<?php
/**
 * EIKOU Theme functions
 *
 * @package wpbase-starter
 */

if (!defined('ABSPATH')) {
    exit;
}

/* ─── Theme Setup ─── */
function eikou_setup() {
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

    // Navigation menus
    register_nav_menus([
        'primary'      => 'Primary Menu (Header)',
        'footer-nav'   => 'Footer Navigation',
        'footer-other' => 'Footer Other Links',
    ]);
}
add_action('after_setup_theme', 'eikou_setup');

/* ─── Enqueue Styles & Scripts ─── */
function eikou_scripts() {
    // Google Fonts
    wp_enqueue_style('eikou-google-fonts',
        'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Inter:wght@300;400;500;600;700&display=swap',
        [], null
    );

    // Main stylesheet
    wp_enqueue_style('eikou-style',
        get_template_directory_uri() . '/assets/css/eikou.css',
        ['eikou-google-fonts'], '2.0.0'
    );

    // Theme stylesheet (metadata only)
    wp_enqueue_style('eikou-theme', get_stylesheet_uri(), ['eikou-style'], '2.0.0');

    // Main script
    wp_enqueue_script('eikou-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [], '2.0.0', true
    );
}
add_action('wp_enqueue_scripts', 'eikou_scripts');

/* ─── Custom Post Type: Work (成功事例) ─── */
function eikou_register_work_cpt() {
    register_post_type('work', [
        'labels' => [
            'name'               => '成功事例',
            'singular_name'      => '成功事例',
            'add_new'            => '新規追加',
            'add_new_item'       => '新しい事例を追加',
            'edit_item'          => '事例を編集',
            'view_item'          => '事例を表示',
            'all_items'          => 'すべての事例',
            'search_items'       => '事例を検索',
            'not_found'          => '事例が見つかりません',
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
            'name'          => '事例カテゴリ',
            'singular_name' => '事例カテゴリ',
            'add_new_item'  => '新しいカテゴリを追加',
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
            'name'               => '動画',
            'singular_name'      => '動画',
            'add_new'            => '新規追加',
            'add_new_item'       => '新しい動画を追加',
            'edit_item'          => '動画を編集',
            'view_item'          => '動画を表示',
            'all_items'          => 'すべての動画',
            'search_items'       => '動画を検索',
            'not_found'          => '動画が見つかりません',
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
            'name'               => 'パートナー',
            'singular_name'      => 'パートナー',
            'add_new'            => '新規追加',
            'add_new_item'       => '新しいパートナーを追加',
            'edit_item'          => 'パートナーを編集',
            'all_items'          => 'すべてのパートナー',
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
            'name'               => 'お客様の声',
            'singular_name'      => 'お客様の声',
            'add_new'            => '新規追加',
            'add_new_item'       => '新しい声を追加',
            'edit_item'          => '声を編集',
            'all_items'          => 'すべてのお客様の声',
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

    return $slides;
}

/* ─── Helper: Get Theme Mod ─── */
function eikou_get($key, $default = '') {
    return get_theme_mod($key, $default);
}

/* ─── Menu Fallback Callbacks ─── */
function eikou_fallback_menu() {
    ?>
    <ul>
        <li><a href="<?php echo esc_url(home_url('/')); ?>"<?php if (is_front_page()) echo ' class="active"'; ?>>ホーム</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>"<?php if (is_page('services')) echo ' class="active"'; ?>>サービス</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('works'))); ?>"<?php if (is_page('works')) echo ' class="active"'; ?>>成功事例</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('video'))); ?>"<?php if (is_page('video')) echo ' class="active"'; ?>>動画</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('partners'))); ?>"<?php if (is_page('partners')) echo ' class="active"'; ?>>パートナー</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>"<?php if (is_page('contact')) echo ' class="active"'; ?>>お問い合わせ</a></li>
    </ul>
    <?php
}

function eikou_footer_nav_fallback() {
    ?>
    <ul>
        <li><a href="<?php echo esc_url(home_url('/')); ?>">ホーム</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('about'))); ?>">荣光について</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>">サービス</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('works'))); ?>">成功事例</a></li>
    </ul>
    <?php
}

function eikou_footer_other_fallback() {
    ?>
    <ul>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('video'))); ?>">動画センター</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('partners'))); ?>">パートナー</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>">お問い合わせ</a></li>
    </ul>
    <?php
}

/* ─── Flush Rewrite Rules on Activation ─── */
function eikou_rewrite_flush() {
    eikou_register_work_cpt();
    eikou_register_video_cpt();
    eikou_register_partner_cpt();
    eikou_register_testimonial_cpt();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'eikou_rewrite_flush');
