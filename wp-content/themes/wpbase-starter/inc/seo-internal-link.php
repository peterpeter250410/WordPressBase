<?php
/**
 * SEO Internal Link — 内链权重雕刻
 *
 * 目的：把 6 个服务类目做成封闭的主题簇（topic cluster），
 * 权重在簇内循环、由簇心页向上汇聚，而不是散落在孤立页面上。
 *
 * 每个服务详情页自动链向：
 *   ① 同簇 3 个兄弟服务（精确匹配锚文本）
 *   ② 所属簇心＝类目页（已有的「一覧に戻る」按钮承担）
 *   ③ 联系页（泛锚文本，用于压低精确匹配占比）
 *
 * 锚文本策略：全站精确匹配锚文本占比控制在 30% 以内。
 * 100% 精确匹配是过度优化信号，反而有害。
 *
 * 排序策略：按服务编号固定排序。内链结构频繁变动会削弱权重传递，
 * 因此不使用随机或按时间排序。
 *
 * UI 文案与 seo-keywords.php 一致，按语种直接定义、不经过 .mo，
 * 部署即在三语生效。
 *
 * @package wpbase-starter
 */

if (!defined('ABSPATH')) {
    exit;
}

/** 内链区块的 UI 文案（按语种） */
function eikou_link_labels($lang = null) {
    if ($lang === null) {
        $lang = eikou_current_lang();
    }
    $labels = [
        'ja' => [
            'related_tag'   => 'Related Services',
            'related_title' => '関連サービス',
            'cta_title'     => 'お見積り・ご相談',
            'cta_text'      => '案件の規模や会場が決まっていない段階でもご相談いただけます。',
            'cta_button'    => 'お問い合わせはこちら',
            'view'          => '詳しく見る',
        ],
        'zh' => [
            'related_tag'   => 'Related Services',
            'related_title' => '相关服务',
            'cta_title'     => '报价与咨询',
            'cta_text'      => '项目规模与展馆尚未确定的阶段也欢迎咨询。',
            'cta_button'    => '联系我们',
            'view'          => '查看详情',
        ],
        'en' => [
            'related_tag'   => 'Related Services',
            'related_title' => 'Related Services',
            'cta_title'     => 'Quotes & Enquiries',
            'cta_text'      => 'Get in touch even if the venue and project scope are not yet fixed.',
            'cta_button'    => 'Contact us',
            'view'          => 'Learn more',
        ],
    ];
    return isset($labels[$lang]) ? $labels[$lang] : $labels['ja'];
}

/**
 * 取同簇兄弟服务（同一 category，排除自身）
 *
 * @param string $current_slug 当前服务 slug
 * @param int    $limit        返回数量
 * @return array slug => item
 */
function eikou_related_services($current_slug, $limit = 3) {
    $items = eikou_get_service_items();
    if (!isset($items[$current_slug])) {
        return [];
    }
    $category = $items[$current_slug]['category'];

    $siblings = [];
    foreach ($items as $slug => $item) {
        if ($slug !== $current_slug && $item['category'] === $category) {
            $siblings[$slug] = $item;
        }
    }

    // 按服务编号固定排序，保证每次渲染的内链结构完全一致
    uasort($siblings, function ($a, $b) {
        return strcmp($a['number'], $b['number']);
    });

    // 同簇不足时，从其他簇补齐，避免小类目页面内链过少
    if (count($siblings) < $limit) {
        foreach ($items as $slug => $item) {
            if (count($siblings) >= $limit) {
                break;
            }
            if ($slug !== $current_slug && !isset($siblings[$slug])) {
                $siblings[$slug] = $item;
            }
        }
    }

    return array_slice($siblings, 0, $limit, true);
}

/**
 * 渲染「関連サービス」区块 + 联系 CTA
 *
 * @param string $current_slug 当前服务 slug
 * @param bool   $mobile       true 输出 H5 版标记
 */
function eikou_render_related_services($current_slug, $mobile = false) {
    $related = eikou_related_services($current_slug, 3);
    if (!$related) {
        return;
    }
    $L = eikou_link_labels();

    if ($mobile) {
        ?>
        <section class="h5-section">
            <div class="h5-container">
                <div class="h5-section-header">
                    <h2 class="h5-section-title"><?php echo esc_html($L['related_title']); ?></h2>
                </div>
                <div class="h5-points-list">
                    <?php foreach ($related as $slug => $item) : ?>
                        <a class="h5-point-card" href="<?php echo esc_url(eikou_page_url($slug)); ?>">
                            <div class="h5-point-num"><?php echo esc_html($item['number']); ?></div>
                            <h3><?php echo esc_html(__($item['title'], 'eikou')); ?></h3>
                            <p><?php echo esc_html($L['view']); ?></p>
                        </a>
                    <?php endforeach; ?>
                </div>
                <div class="h5-section-action">
                    <a href="<?php echo esc_url(eikou_page_url('contact')); ?>" class="btn btn-primary">
                        <?php echo esc_html($L['cta_button']); ?>
                    </a>
                </div>
            </div>
        </section>
        <?php
        return;
    }
    ?>
    <section class="section">
        <div class="container">
            <div class="section-header">
                <span class="section-tag"><?php echo esc_html($L['related_tag']); ?></span>
                <h2 class="section-title"><?php echo esc_html($L['related_title']); ?></h2>
            </div>
            <div class="service-points-grid">
                <?php foreach ($related as $slug => $item) : ?>
                    <a class="service-point-card scroll-reveal" href="<?php echo esc_url(eikou_page_url($slug)); ?>">
                        <div class="service-point-num"><?php echo esc_html($item['number']); ?></div>
                        <h3><?php echo esc_html(__($item['title'], 'eikou')); ?></h3>
                        <p><?php echo esc_html($L['view']); ?> →</p>
                    </a>
                <?php endforeach; ?>
            </div>
            <div style="text-align:center; margin-top:2.5rem;">
                <p style="margin-bottom:1rem;"><?php echo esc_html($L['cta_text']); ?></p>
                <a href="<?php echo esc_url(eikou_page_url('contact')); ?>" class="btn-primary">
                    <?php echo esc_html($L['cta_button']); ?>
                </a>
            </div>
        </div>
    </section>
    <?php
}
