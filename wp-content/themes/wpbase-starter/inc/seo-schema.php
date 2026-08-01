<?php
/**
 * SEO Schema — JSON-LD 结构化数据
 *
 * 原则：只输出真实可确认的数据。
 * 结构化数据填入虚假或占位值会导致 Google 取消该站的富媒体结果资格，
 * 因此资本金（page-about.php 中仍为占位符 XXXX万円）、法人番号等
 * 未确认字段一律不输出，待提供真实值后再补。
 *
 * @package wpbase-starter
 */

if (!defined('ABSPATH')) {
    exit;
}

/** 输出一段 JSON-LD */
function eikou_schema_output($data) {
    if (empty($data)) {
        return;
    }
    echo '<script type="application/ld+json">'
        . wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        . '</script>' . "\n";
}

/** 递归剔除空值，避免输出 "" / null / [] 字段 */
function eikou_schema_prune($data) {
    if (!is_array($data)) {
        return $data;
    }
    $out = [];
    foreach ($data as $k => $v) {
        if (is_array($v)) {
            $v = eikou_schema_prune($v);
        }
        if ($v === null || $v === '' || $v === []) {
            continue;
        }
        $out[$k] = $v;
    }
    return $out;
}

/**
 * 拆解日文地址为 schema.org PostalAddress 各字段
 * 例：東京都葛飾区柴又1丁目43-6MAC柴又コート 102
 *   → region=東京都  locality=葛飾区  street=柴又1丁目43-6MAC柴又コート 102
 */
function eikou_schema_address_parts() {
    $raw = trim((string) eikou_get('eikou_address', '東京都葛飾区柴又1丁目43-6MAC柴又コート 102'));
    $zip = trim((string) eikou_get('eikou_zip', '〒125-0052'));
    // schema 的 postalCode 不应带 〒 符号
    $zip = trim(str_replace('〒', '', $zip));

    $region   = '';
    $locality = '';
    $street   = $raw;

    // 都/道/府/県
    if (preg_match('/^(.+?[都道府県])(.*)$/u', $raw, $m)) {
        $region = $m[1];
        $street = $m[2];
    }
    // 市/区/郡/町/村
    if ($street !== '' && preg_match('/^(.+?[市区郡町村])(.*)$/u', $street, $m)) {
        $locality = $m[1];
        $street   = $m[2];
    }

    return [
        '@type'           => 'PostalAddress',
        'postalCode'      => $zip,
        'addressCountry'  => 'JP',
        'addressRegion'   => $region,
        'addressLocality' => $locality,
        'streetAddress'   => trim($street),
    ];
}

/**
 * Organization / LocalBusiness（全站输出）
 *
 * sameAs 是实体识别的关键：把官网与各商业目录、SNS 档案串成同一个实体。
 * 目前留空，待 Google Business Profile / 各目录档案建立后填入。
 */
function eikou_schema_organization() {
    $home = eikou_seo_raw_home();

    $data = [
        '@context'      => 'https://schema.org',
        '@type'         => 'LocalBusiness',
        '@id'           => $home . '/#organization',
        'name'          => eikou_get('eikou_company_name', '荣光株式会社'),
        'alternateName' => ['EIKOU Co., Ltd.', 'エイコー'],
        'url'           => $home . '/',
        'logo'          => get_template_directory_uri() . '/assets/images/logo-eikou.png',
        'image'         => get_template_directory_uri() . '/assets/images/logo-eikou.png',
        'foundingDate'  => '2009',
        'telephone'     => eikou_get('eikou_tel', '03-5876-9273'),
        'email'         => eikou_get('eikou_email', 'info@eikoujp.net'),
        'address'       => eikou_schema_address_parts(),
        'areaServed'    => ['@type' => 'Country', 'name' => 'Japan'],
        'knowsLanguage' => ['ja', 'zh', 'en'],
        // 待补：'sameAs' => ['https://...GBP', 'https://...Wantedly', ...]
        // 待补：'vatID' / 'taxID'（法人番号 13 桁）、'numberOfEmployees'
    ];

    eikou_schema_output(eikou_schema_prune($data));
}

/** WebSite（帮助 Google 建立站点实体，并声明三语） */
function eikou_schema_website() {
    $home = eikou_seo_raw_home();

    eikou_schema_output(eikou_schema_prune([
        '@context'   => 'https://schema.org',
        '@type'      => 'WebSite',
        '@id'        => $home . '/#website',
        'url'        => $home . '/',
        'name'       => __('荣光株式会社', 'eikou'),
        'inLanguage' => array_values(eikou_seo_hreflang_map()),
        'publisher'  => ['@id' => $home . '/#organization'],
    ]));
}

/** 服务详情页 Service（复用 functions.php 里已有的 24 条服务数据） */
function eikou_schema_service() {
    $item = eikou_seo_current_service_item();
    if (!$item) {
        return;
    }

    eikou_schema_output(eikou_schema_prune([
        '@context'    => 'https://schema.org',
        '@type'       => 'Service',
        'name'        => __($item['title'], 'eikou'),
        'description' => eikou_seo_get_description(),
        'serviceType' => __($item['category_name'], 'eikou'),
        'provider'    => ['@id' => eikou_seo_raw_home() . '/#organization'],
        'areaServed'  => ['@type' => 'Country', 'name' => 'Japan'],
        'url'         => eikou_seo_url_for(eikou_current_lang()),
    ]));
}

/** 面包屑 */
function eikou_schema_breadcrumb() {
    if (is_front_page()) {
        return;
    }

    $items = [];
    $pos   = 1;

    $items[] = [
        '@type'    => 'ListItem',
        'position' => $pos++,
        'name'     => __('ホーム', 'eikou'),
        'item'     => eikou_seo_url_for(eikou_current_lang(), '/'),
    ];

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
    } elseif (is_singular('eikou_video')) {
        $items[] = ['@type' => 'ListItem', 'position' => $pos++,
                    'name' => __('動画', 'eikou'), 'item' => eikou_page_url('video')];
        $items[] = ['@type' => 'ListItem', 'position' => $pos++, 'name' => get_the_title()];
    } elseif (is_singular() || is_page()) {
        $items[] = ['@type' => 'ListItem', 'position' => $pos++, 'name' => get_the_title()];
    }

    if (count($items) < 2) {
        return;
    }

    eikou_schema_output([
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $items,
    ]);
}

/** "03:24" → "PT3M24S"（ISO 8601 duration） */
function eikou_schema_iso_duration($mmss) {
    $mmss = trim((string) $mmss);
    if (preg_match('/^(\d+):(\d{2}):(\d{2})$/', $mmss, $m)) {
        return sprintf('PT%dH%dM%dS', (int) $m[1], (int) $m[2], (int) $m[3]);
    }
    if (preg_match('/^(\d+):(\d{2})$/', $mmss, $m)) {
        return sprintf('PT%dM%dS', (int) $m[1], (int) $m[2]);
    }
    return null;
}

/** 视频详情页 VideoObject */
function eikou_schema_video() {
    if (!is_singular('eikou_video')) {
        return;
    }
    $id  = get_queried_object_id();
    $url = get_post_meta($id, '_video_url', true);
    if (!$url) {
        return;
    }

    eikou_schema_output(eikou_schema_prune([
        '@context'     => 'https://schema.org',
        '@type'        => 'VideoObject',
        'name'         => get_the_title(),
        'description'  => eikou_seo_get_description(),
        'thumbnailUrl' => get_the_post_thumbnail_url($id, 'full'),
        'uploadDate'   => get_the_date('c', $id),
        'contentUrl'   => $url,
        'duration'     => eikou_schema_iso_duration(get_post_meta($id, '_video_duration', true)),
        'publisher'    => ['@id' => eikou_seo_raw_home() . '/#organization'],
        'inLanguage'   => eikou_seo_hreflang_map()[eikou_current_lang()],
    ]));
}

/** 案例详情页 Article */
function eikou_schema_work() {
    if (!is_singular('work')) {
        return;
    }
    $id = get_queried_object_id();

    eikou_schema_output(eikou_schema_prune([
        '@context'         => 'https://schema.org',
        '@type'            => 'Article',
        'headline'         => get_the_title(),
        'description'      => eikou_seo_get_description(),
        'image'            => get_the_post_thumbnail_url($id, 'full'),
        'datePublished'    => get_the_date('c', $id),
        'dateModified'     => get_the_modified_date('c', $id),
        'author'           => ['@id' => eikou_seo_raw_home() . '/#organization'],
        'publisher'        => ['@id' => eikou_seo_raw_home() . '/#organization'],
        'inLanguage'       => eikou_seo_hreflang_map()[eikou_current_lang()],
        'mainEntityOfPage' => eikou_seo_url_for(eikou_current_lang()),
    ]));
}

add_action('wp_head', function () {
    if (is_404()) {
        return;
    }
    eikou_schema_organization();
    eikou_schema_website();
    eikou_schema_service();
    eikou_schema_breadcrumb();
    eikou_schema_video();
    eikou_schema_work();
}, 5);
