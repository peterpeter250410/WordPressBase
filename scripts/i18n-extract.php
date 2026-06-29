<?php
/**
 * EIKOU — 自定义字符串提取器
 * 扫描主题 PHP 中所有「含日文的带引号字面量」，生成 eikou.pot。
 * 这样 functions.php 的服务数据数组等无需逐条包裹 __()，
 * 只要运行时输出点经过 __($var,'eikou')，即可按 .mo 翻译。
 *
 * 用法: php i18n-extract.php <主题目录> <输出pot路径>
 */
if ($argc < 3) {
    fwrite(STDERR, "用法: php i18n-extract.php <theme_dir> <out.pot>\n");
    exit(1);
}
$themeDir = rtrim($argv[1], '/\\');
$outPot   = $argv[2];

$rii = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($themeDir, FilesystemIterator::SKIP_DOTS)
);

$msgids = [];
foreach ($rii as $file) {
    if ($file->getExtension() !== 'php') continue;
    $path = $file->getPathname();
    if (strpos($path, DIRECTORY_SEPARATOR . 'languages') !== false) continue;
    if (strpos($path, DIRECTORY_SEPARATOR . 'node_modules') !== false) continue;

    $code = file_get_contents($path);
    // 匹配单引号 '...' 和双引号 "..." 字面量
    if (preg_match_all('/\'((?:[^\'\\\\]|\\\\.)*)\'|"((?:[^"\\\\]|\\\\.)*)"/', $code, $m, PREG_SET_ORDER)) {
        foreach ($m as $set) {
            $raw = isset($set[2]) && $set[2] !== '' ? $set[2] : (isset($set[1]) ? $set[1] : '');
            if ($raw === '') {
                // 也可能是空字符串匹配，跳过
                if (!isset($set[1]) && !isset($set[2])) continue;
            }
            $val = $raw;
            // 只要含平假名/片假名/CJK 汉字
            if (!preg_match('/[\x{3040}-\x{30ff}\x{4e00}-\x{9fff}]/u', $val)) continue;
            // 过滤明显非文案：纯URL、含 .jpg/.png/.php 等
            if (preg_match('#https?://#', $val)) continue;
            if (preg_match('/\.(jpg|jpeg|png|gif|webp|svg|mp4|php|css|js)\b/i', $val)) continue;
            $msgids[$val] = true;
        }
    }
}

ksort($msgids);

$pot  = "msgid \"\"\nmsgstr \"\"\n";
$pot .= "\"Project-Id-Version: EIKOU\\n\"\n";
$pot .= "\"Content-Type: text/plain; charset=UTF-8\\n\"\n";
$pot .= "\"Content-Transfer-Encoding: 8bit\\n\"\n";
$pot .= "\"Language: ja\\n\"\n\n";

foreach (array_keys($msgids) as $id) {
    // id 已是源码中的转义形式（单/双引号内），统一规整为 PO 转义
    $unescaped = stripcslashes($id);
    $escaped   = addcslashes($unescaped, "\"\\");
    $escaped   = str_replace("\n", '\n', $escaped);
    $pot .= "msgid \"$escaped\"\nmsgstr \"\"\n\n";
}

file_put_contents($outPot, $pot);
echo "提取完成: " . count($msgids) . " 条 -> $outPot\n";
