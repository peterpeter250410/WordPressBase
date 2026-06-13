<?php
include get_template_directory() . '/h5/header.php';
$all_items = eikou_get_service_items();
$items = array_filter($all_items, function($v) { return $v['category'] === 'service-brand-event'; });
$hero_img = content_url('/uploads/services/gokin-presentation.jpg');
$cat_en = 'Brand Events'; $cat_jp = 'ブランドイベント';
$cat_desc = 'EIKOUは、企業の発表会・ロードショー・ポップアップストアなど、ブランド価値を最大化するイベントの企画・制作・運営をトータルサポートします。';
$work_cat_slug = 'event';
include get_template_directory() . '/h5/_service-category-template.php';
