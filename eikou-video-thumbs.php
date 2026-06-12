<?php
/**
 * EIKOU Video Thumbnails Seeder
 * 访问 http://127.0.0.1/eikou-video-thumbs.php 执行
 * 完成后请删除此文件
 */
require_once __DIR__ . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

if (!current_user_can('manage_options')) {
    wp_die('请先登录管理员账号');
}

$results = [];
$thumbs_dir = ABSPATH . 'wp-content/uploads/videos/thumbs/';

// Video title => thumbnail filename mapping
$video_thumbs = [
    'CATL巡回展示会 - マツダ'                              => 'catl-mazda.jpg',
    'CATL巡回展示会 - 日産'                                => 'catl-nissan.jpg',
    'CATL巡回展示会 - 三菱'                                => 'catl-mitsubishi.jpg',
    'CATL巡回展示会 - トヨタ'                              => 'catl-toyota.jpg',
    'CATL巡回展示会 - ホンダ'                              => 'catl-honda.jpg',
    '高景ソーラー 新エネルギー技術発表会'                  => 'gokin-solar.jpg',
    'Momentum Electric Marine @ JAPAN BOAT SHOW 2026'     => 'momentum-marine.jpg',
    '德擎光学 展示ブース'                                  => 'deqing-optics.jpg',
    'HELIX ゴルフブランド Japan Golf Fair'                 => 'helix-golf.jpg',
    '海辰エナジー 製品発表会'                              => 'hithium-energy.jpg',
];

// Compress image before importing (resize to max 1200px width, 75% JPEG quality)
function eikou_compress_thumb($file_path, $max_width = 1200, $quality = 75) {
    $info = getimagesize($file_path);
    if (!$info) return false;

    $orig_w = $info[0];
    $orig_h = $info[1];
    $mime   = $info['mime'];

    switch ($mime) {
        case 'image/jpeg':
            $src = imagecreatefromjpeg($file_path);
            break;
        case 'image/png':
            $src = imagecreatefrompng($file_path);
            break;
        default:
            return false;
    }

    if (!$src) return false;

    if ($orig_w > $max_width) {
        $new_w = $max_width;
        $new_h = intval($orig_h * ($max_width / $orig_w));
    } else {
        $new_w = $orig_w;
        $new_h = $orig_h;
    }

    $dst = imagecreatetruecolor($new_w, $new_h);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_w, $new_h, $orig_w, $orig_h);

    // Save as JPEG
    $output = preg_replace('/\.(jpg|JPG|jpeg|JPEG|png|PNG)$/', '.jpg', $file_path);
    imagejpeg($dst, $output, $quality);

    imagedestroy($src);
    imagedestroy($dst);

    return $output;
}

foreach ($video_thumbs as $video_title => $thumb_file) {
    $thumb_path = $thumbs_dir . $thumb_file;

    // Find the video post
    $video_query = new WP_Query([
        'post_type'      => 'eikou_video',
        'title'          => $video_title,
        'posts_per_page' => 1,
    ]);

    if (!$video_query->have_posts()) {
        $results[] = "✗ 動画が見つかりません: {$video_title}";
        continue;
    }

    $video_post = $video_query->posts[0];
    $video_id   = $video_post->ID;

    // Skip if already has a thumbnail
    if (has_post_thumbnail($video_id)) {
        $results[] = "✓ サムネイル設定済み: {$video_title} (ID: {$video_id})";
        continue;
    }

    // Check thumb file exists
    if (!file_exists($thumb_path)) {
        $results[] = "✗ 画像ファイルが見つかりません: {$thumb_file}";
        continue;
    }

    // Compress the image first
    $compressed = eikou_compress_thumb($thumb_path);
    if (!$compressed) {
        $results[] = "✗ 画像圧縮失敗: {$thumb_file}";
        continue;
    }

    // Copy to WP uploads directory
    $upload_dir = wp_upload_dir();
    $dest_path  = $upload_dir['path'] . '/' . basename($compressed);
    copy($compressed, $dest_path);

    // Create attachment
    $filetype = wp_check_filetype(basename($dest_path));
    $attachment_data = [
        'guid'           => $upload_dir['url'] . '/' . basename($dest_path),
        'post_mime_type' => $filetype['type'],
        'post_title'     => sanitize_file_name(pathinfo($thumb_file, PATHINFO_FILENAME)),
        'post_content'   => '',
        'post_status'    => 'inherit',
    ];

    $attach_id = wp_insert_attachment($attachment_data, $dest_path, $video_id);

    if (is_wp_error($attach_id)) {
        $results[] = "✗ Attachment作成失敗: {$thumb_file} - " . $attach_id->get_error_message();
        continue;
    }

    // Generate attachment metadata (thumbnails etc.)
    $attach_metadata = wp_generate_attachment_metadata($attach_id, $dest_path);
    wp_update_attachment_metadata($attach_id, $attach_metadata);

    // Set as featured image
    set_post_thumbnail($video_id, $attach_id);

    clearstatcache(true, $dest_path);
    $size_kb = round(filesize($dest_path) / 1024);

    $results[] = "✓ {$video_title} → {$thumb_file} (Attachment ID: {$attach_id}, {$size_kb}KB)";
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Video Thumbnails Setup</title>
    <style>
        body { font-family: -apple-system, sans-serif; max-width: 700px; margin: 40px auto; padding: 20px; background: #1a1a2e; color: #eee; }
        h1 { color: #c9a84c; border-bottom: 2px solid #c9a84c; padding-bottom: 10px; }
        .result { padding: 8px 12px; margin: 4px 0; border-radius: 4px; background: rgba(255,255,255,0.05); font-size: 13px; font-family: monospace; }
        .result:nth-child(odd) { background: rgba(255,255,255,0.08); }
        .warning { margin-top: 20px; padding: 15px; background: #c9a84c22; border: 1px solid #c9a84c; border-radius: 6px; color: #c9a84c; }
        a { color: #c9a84c; }
    </style>
</head>
<body>
    <h1>動画サムネイル設定</h1>
    <p>処理: <?php echo count($video_thumbs); ?> 件の動画</p>

    <?php foreach ($results as $r) : ?>
        <div class="result"><?php echo htmlspecialchars($r); ?></div>
    <?php endforeach; ?>

    <div class="warning">
        <strong>⚠</strong> 完成后请删除此文件（eikou-video-thumbs.php）<br><br>
        <a href="<?php echo esc_url(home_url('/video/')); ?>">→ 動画センター確認</a> ｜
        <a href="<?php echo esc_url(admin_url('edit.php?post_type=eikou_video')); ?>">→ 管理画面で確認</a>
    </div>
</body>
</html>
