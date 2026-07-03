<?php
/**
 * EIKOU — 用 DeepL 翻译 .po 文件中「未翻译」的条目（源语言=日文）
 *
 * 用法:  php i18n-translate-po.php <po文件路径> <目标语言: ZH | EN-US>
 * Key:   环境变量 DEEPL_API_KEY（免费版 key 以 ":fx" 结尾）
 *
 * 特性：只翻译 msgstr 为空的条目；可重复运行；跳过 header 和复数条目。
 */

if ($argc < 3) {
    fwrite(STDERR, "用法: php i18n-translate-po.php <po> <ZH|EN-US>\n");
    exit(1);
}
$poFile = $argv[1];
$target = strtoupper($argv[2]);
$key    = getenv('DEEPL_API_KEY');

if (!$key)               { fwrite(STDERR, "[ERROR] 缺少 DEEPL_API_KEY\n"); exit(1); }
if (!file_exists($poFile)) { fwrite(STDERR, "[ERROR] 找不到 $poFile\n"); exit(1); }

$endpoint = (substr($key, -3) === ':fx')
    ? 'https://api-free.deepl.com/v2/translate'
    : 'https://api.deepl.com/v2/translate';

function po_extract($block, $field) {
    // 提取 field 拼接值（支持续行 "..."），返回 null 表示无该字段
    $lines = explode("\n", $block);
    $val = null; $capturing = false;
    foreach ($lines as $line) {
        if (preg_match('/^' . preg_quote($field, '/') . '\s+"(.*)"\s*$/', $line, $m)) {
            $val = $m[1]; $capturing = true;
        } elseif ($capturing && preg_match('/^"(.*)"\s*$/', $line, $m)) {
            $val .= $m[1];
        } else {
            $capturing = false;
        }
    }
    return $val;
}
function po_unescape($s) { return strtr($s, ['\\n' => "\n", '\\t' => "\t", '\\"' => '"', '\\\\' => '\\']); }
function po_escape($s)   { return strtr($s, ['\\' => '\\\\', '"' => '\\"', "\n" => '\\n', "\t" => '\\t']); }

$content = file_get_contents($poFile);
$blocks  = preg_split("/\n\n+/", $content);

$pending = []; // blockIndex => 源文(已 unescape)
foreach ($blocks as $i => $block) {
    if (strpos($block, 'msgid') === false) continue;
    if (strpos($block, 'msgid_plural') !== false) continue;  // 跳过复数
    $msgid  = po_extract($block, 'msgid');
    $msgstr = po_extract($block, 'msgstr');
    if ($msgid === null || $msgid === '') continue;           // 跳过 header/空
    if ($msgstr !== null && $msgstr !== '') continue;         // 已翻译
    $pending[$i] = po_unescape($msgid);
}

if (!$pending) { echo "  [$target] 无未翻译条目: " . basename($poFile) . "\n"; exit(0); }
echo "  [$target] 待翻译 " . count($pending) . " 条...\n";

$keys = array_keys($pending);
$batch = 40;
for ($b = 0; $b < count($keys); $b += $batch) {
    $slice = array_slice($keys, $b, $batch);
    // DeepL 2025-11 起弃用 body 里的 auth_key，改用请求头 Authorization
    $post = http_build_query(['source_lang' => 'JA', 'target_lang' => $target]);
    foreach ($slice as $k) { $post .= '&text=' . urlencode($pending[$k]); }

    $ch = curl_init($endpoint);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $post,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTPHEADER => [
            'Authorization: DeepL-Auth-Key ' . $key,
            'Content-Type: application/x-www-form-urlencoded',
        ],
    ]);
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err  = curl_error($ch);
    curl_close($ch);

    if ($code !== 200) { fwrite(STDERR, "[ERROR] DeepL HTTP $code $err: $resp\n"); exit(1); }
    $json = json_decode($resp, true);
    if (!isset($json['translations'])) { fwrite(STDERR, "[ERROR] DeepL 响应异常: $resp\n"); exit(1); }

    foreach ($slice as $idx => $k) {
        $translated = $json['translations'][$idx]['text'];
        // 用字符串拼接替换 msgstr（避免正则替换里的 $ / \ 问题）
        $block = $blocks[$k];
        $pos = strpos($block, "\nmsgstr");
        $head = ($pos !== false) ? substr($block, 0, $pos) : preg_replace('/\n?msgstr.*$/s', '', $block);
        $blocks[$k] = rtrim($head, "\n") . "\nmsgstr \"" . po_escape($translated) . "\"";
    }
    echo "    " . min($b + $batch, count($keys)) . "/" . count($keys) . "\n";
    usleep(200000);
}

file_put_contents($poFile, implode("\n\n", $blocks));
echo "  [$target] 写回完成: " . basename($poFile) . "\n";
