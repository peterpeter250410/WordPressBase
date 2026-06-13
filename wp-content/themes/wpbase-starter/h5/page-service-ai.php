<?php
include get_template_directory() . '/h5/header.php';
$all_items = eikou_get_service_items();
$items = array_filter($all_items, function($v) { return $v['category'] === 'service-ai'; });
$hero_img = eikou_mobile_img_url('https://images.unsplash.com/photo-1677442136019-21780ecad995?w=1920&q=80');
$cat_en = 'AI Solutions'; $cat_jp = 'AIソリューション';
$cat_desc = 'AIチャットボット・自動化システムの導入支援で、業務効率の向上と顧客体験の革新を実現します。';
$work_cat_slug = 'ai';
include get_template_directory() . '/h5/_service-category-template.php';
