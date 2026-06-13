<?php
include get_template_directory() . '/h5/header.php';
$all_items = eikou_get_service_items();
$items = array_filter($all_items, function($v) { return $v['category'] === 'service-branding'; });
$hero_img = eikou_mobile_img_url('https://images.unsplash.com/photo-1561070791-2526d30994b5?w=1920&q=80');
$cat_en = 'Branding & Design'; $cat_jp = 'ブランディング・デザイン';
$cat_desc = 'ブランド戦略の立案からパッケージ・販促ツールまで、統一されたビジュアルアイデンティティを構築します。';
$work_cat_slug = 'branding';
include get_template_directory() . '/h5/_service-category-template.php';
