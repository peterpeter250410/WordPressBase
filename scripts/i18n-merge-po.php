<?php
/**
 * EIKOU — 合并 pot 到 po：以 pot 的 msgid 为准，保留 po 中已有的 msgstr，缺失补空。
 * 不依赖 gettext(msgmerge)。适用于本项目的短字符串（单行 msgid/msgstr）。
 *
 * 用法: php i18n-merge-po.php <pot> <po>
 */
if ($argc < 3) { fwrite(STDERR, "用法: php i18n-merge-po.php <pot> <po>\n"); exit(1); }
$pot = $argv[1];
$po  = $argv[2];
if (!file_exists($pot)) { fwrite(STDERR, "找不到 pot: $pot\n"); exit(1); }

// 读取现有 po 的 msgid => msgstr（保留已翻译）
$existing = [];
if (file_exists($po)) {
    foreach (preg_split("/\n\n+/", file_get_contents($po)) as $b) {
        if (!preg_match('/^msgid\s+"(.*)"\s*$/m', $b, $mid)) continue;
        if (preg_match('/^msgstr\s+"(.*)"\s*$/m', $b, $mst)) {
            $existing[$mid[1]] = $mst[1];
        }
    }
}

// 以 pot 为骨架重建 po，保留已有 msgstr
$out = [];
foreach (preg_split("/\n\n+/", file_get_contents($pot)) as $b) {
    if (!preg_match('/^msgid\s+"(.*)"\s*$/m', $b, $mid)) { $out[] = rtrim($b); continue; }
    $id = $mid[1];
    if ($id === '') { $out[] = rtrim($b); continue; } // header 原样保留
    $str = isset($existing[$id]) ? $existing[$id] : '';
    $out[] = "msgid \"{$id}\"\nmsgstr \"{$str}\"";
}
file_put_contents($po, implode("\n\n", $out) . "\n");
echo "  合并完成: " . basename($po) . "（保留 " . count($existing) . " 条已译）\n";
