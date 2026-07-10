<?php
/**
 * EIKOU — 采集数据库内容并追加到 .pot
 * 作品/视频/合作/客户评价 的标题、work_category 分类名、作品地点(_work_location)
 * 使得这些"录入内容"也能被 DeepL 翻译（配合 the_title / get_the_terms 过滤器生效）
 *
 * 用法: wp eval-file scripts/i18n-db-strings.php <pot路径> --allow-root
 */
$pot = isset($args[0]) ? $args[0] : '';
if (!$pot || !file_exists($pot)) { echo "[db-strings] pot 不存在: $pot\n"; return; }

$strings = [];

$posts = get_posts([
    'post_type'   => ['work', 'eikou_video', 'partner', 'testimonial'],
    'numberposts' => -1,
    'post_status' => 'publish',
]);
foreach ($posts as $p) {
    if (trim($p->post_title) !== '') $strings[$p->post_title] = 1;
    $loc = get_post_meta($p->ID, '_work_location', true);
    if ($loc) $strings[$loc] = 1;
    // testimonial 正文 + 职衔/公司
    if ($p->post_type === 'testimonial') {
        if (trim($p->post_content) !== '') $strings[wp_strip_all_tags($p->post_content)] = 1;
        $at = get_post_meta($p->ID, '_testimonial_author_title', true);
        $ac = get_post_meta($p->ID, '_testimonial_author_company', true);
        if ($at) $strings[$at] = 1;
        if ($ac) $strings[$ac] = 1;
    }
}

$terms = get_terms(['taxonomy' => 'work_category', 'hide_empty' => false]);
if (!is_wp_error($terms)) {
    foreach ($terms as $t) $strings[$t->name] = 1;
}

$content = file_get_contents($pot);
$append = '';
$added = 0;
foreach (array_keys($strings) as $s) {
    // 仅含日文（平/片假名或汉字）的才需要翻译
    if (!preg_match('/[\x{3040}-\x{30ff}\x{4e00}-\x{9fff}]/u', $s)) continue;
    $esc = addcslashes($s, "\"\\");
    $esc = str_replace("\n", '\n', $esc);
    if (strpos($content, "msgid \"{$esc}\"") !== false) continue; // 已存在
    $append .= "msgid \"{$esc}\"\nmsgstr \"\"\n\n";
    $added++;
}
if ($append !== '') {
    file_put_contents($pot, rtrim($content) . "\n\n" . $append);
}
echo "[db-strings] 追加 {$added} 条数据库内容到 pot\n";
