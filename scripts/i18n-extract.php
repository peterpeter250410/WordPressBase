<?php
/**
 * EIKOU — 字符串提取器（基于 PHP 分词器，稳健）
 * 用 token_get_all 精确抓取所有「单/双引号字符串字面量」中含日文的串，生成 eikou.pot。
 * 优点：不受裸引号/HTML/大文件/正则回溯影响；自动跳过含变量插值的双引号串。
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
    $tokens = @token_get_all($code);
    if (!is_array($tokens)) continue;

    foreach ($tokens as $t) {
        if (!is_array($t) || $t[0] !== T_CONSTANT_ENCAPSED_STRING) continue;
        $raw = $t[1];
        $quote = $raw[0];
        $inner = substr($raw, 1, -1);
        // 还原字面量到真实字符串
        if ($quote === "'") {
            $val = strtr($inner, ["\\'" => "'", "\\\\" => "\\"]);
        } else {
            $val = stripcslashes($inner); // 双引号
        }

        // 只要含平假名/片假名/CJK 汉字
        if (!preg_match('/[\x{3040}-\x{30ff}\x{4e00}-\x{9fff}]/u', $val)) continue;
        // 过滤明显非文案
        if (preg_match('#https?://#', $val)) continue;
        if (preg_match('/\.(jpg|jpeg|png|gif|webp|svg|mp4|php|css|js)\b/i', $val)) continue;

        $msgids[$val] = true;
    }
}

ksort($msgids);

$pot  = "msgid \"\"\nmsgstr \"\"\n";
$pot .= "\"Project-Id-Version: EIKOU\\n\"\n";
$pot .= "\"Content-Type: text/plain; charset=UTF-8\\n\"\n";
$pot .= "\"Content-Transfer-Encoding: 8bit\\n\"\n";
$pot .= "\"Language: ja\\n\"\n\n";

foreach (array_keys($msgids) as $id) {
    $escaped = addcslashes($id, "\"\\");
    $escaped = str_replace("\n", '\n', $escaped);
    $pot .= "msgid \"{$escaped}\"\nmsgstr \"\"\n\n";
}

file_put_contents($outPot, $pot);
echo "提取完成: " . count($msgids) . " 条 -> {$outPot}\n";
