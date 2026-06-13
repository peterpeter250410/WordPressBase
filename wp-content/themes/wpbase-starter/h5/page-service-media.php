<?php
include get_template_directory() . '/h5/header.php';
$all_items = eikou_get_service_items();
$items = array_filter($all_items, function($v) { return $v['category'] === 'service-media'; });
$hero_img = content_url('/uploads/services/hithium-mc.jpg');
$cat_en = 'Media & Production'; $cat_jp = 'メディア・映像';
$cat_desc = '動画制作・メディア運営・サイン看板まで、あらゆるメディアニーズに対応します。高品質な映像コンテンツでブランドの魅力を伝えます。';
$work_cat_slug = 'media';
include get_template_directory() . '/h5/_service-category-template.php';
