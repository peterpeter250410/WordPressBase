<?php
include get_template_directory() . '/h5/header.php';
$all_items = eikou_get_service_items();
$items = array_filter($all_items, function($v) { return $v['category'] === 'service-digital'; });
$hero_img = eikou_mobile_img_url('https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=1920&q=80');
$cat_en = 'Digital & Web'; $cat_jp = 'デジタル・Web';
$cat_desc = 'Webサイト制作・最適化・アプリ開発で、お客様のデジタルプレゼンスを強化します。最新のテクノロジーを活用した効果的なソリューションを提供します。';
$work_cat_slug = 'digital';
include get_template_directory() . '/h5/_service-category-template.php';
