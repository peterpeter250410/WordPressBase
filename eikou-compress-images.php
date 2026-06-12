<?php
/**
 * Image Compression Script
 * 访问 http://127.0.0.1/eikou-compress-images.php 执行
 * 完成后请删除此文件
 */

$dir = __DIR__ . '/wp-content/uploads/services/';
$max_width  = 1200;
$quality    = 75;
$results    = [];

if (!is_dir($dir)) {
    die("Directory not found: $dir");
}

$files = glob($dir . '*.{jpg,JPG,jpeg,JPEG,png,PNG}', GLOB_BRACE);

foreach ($files as $file) {
    $info = getimagesize($file);
    if (!$info) {
        $results[] = "✗ 跳过（非图片）: " . basename($file);
        continue;
    }

    $orig_size = filesize($file);
    $orig_w = $info[0];
    $orig_h = $info[1];
    $mime   = $info['mime'];

    // Load image
    switch ($mime) {
        case 'image/jpeg':
            $src = imagecreatefromjpeg($file);
            break;
        case 'image/png':
            $src = imagecreatefrompng($file);
            break;
        default:
            $results[] = "✗ 不支持的格式: " . basename($file);
            continue 2;
    }

    if (!$src) {
        $results[] = "✗ 无法加载: " . basename($file);
        continue;
    }

    // Calculate new dimensions
    if ($orig_w > $max_width) {
        $new_w = $max_width;
        $new_h = intval($orig_h * ($max_width / $orig_w));
    } else {
        $new_w = $orig_w;
        $new_h = $orig_h;
    }

    // Resize
    $dst = imagecreatetruecolor($new_w, $new_h);

    // Preserve PNG transparency
    if ($mime === 'image/png') {
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
    }

    imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_w, $new_h, $orig_w, $orig_h);

    // Save as JPEG for consistency
    $output_file = preg_replace('/\.(jpg|JPG|jpeg|JPEG|png|PNG)$/', '.jpg', $file);
    imagejpeg($dst, $output_file, $quality);

    // Clean up original if different extension
    if ($output_file !== $file && file_exists($output_file)) {
        unlink($file);
    }

    clearstatcache(true, $output_file);
    $new_size = filesize($output_file);

    imagedestroy($src);
    imagedestroy($dst);

    $reduction = round((1 - $new_size / $orig_size) * 100);
    $results[] = sprintf(
        "✓ %s: %dx%d → %dx%d | %s → %s (-%d%%)",
        basename($output_file),
        $orig_w, $orig_h,
        $new_w, $new_h,
        round($orig_size / 1024) . 'KB',
        round($new_size / 1024) . 'KB',
        $reduction
    );
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Image Compression</title>
    <style>
        body { font-family: -apple-system, sans-serif; max-width: 700px; margin: 40px auto; padding: 20px; background: #1a1a2e; color: #eee; }
        h1 { color: #c9a84c; }
        .result { padding: 8px 12px; margin: 4px 0; border-radius: 4px; background: rgba(255,255,255,0.05); font-size: 13px; font-family: monospace; }
        .warning { margin-top: 20px; padding: 15px; background: #c9a84c22; border: 1px solid #c9a84c; border-radius: 6px; color: #c9a84c; }
    </style>
</head>
<body>
    <h1>画像圧縮結果</h1>
    <p>処理: <?php echo count($results); ?> ファイル | Max: <?php echo $max_width; ?>px | Quality: <?php echo $quality; ?>%</p>
    <?php foreach ($results as $r) : ?>
        <div class="result"><?php echo htmlspecialchars($r); ?></div>
    <?php endforeach; ?>
    <div class="warning">
        <strong>⚠</strong> 完成后请删除此文件（eikou-compress-images.php）
    </div>
</body>
</html>
