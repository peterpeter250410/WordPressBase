<?php
/**
 * H5 Service - Exhibition & Events
 */
include get_template_directory() . '/h5/header.php';
$all_items = eikou_get_service_items();
$items = array_filter($all_items, function($v) { return $v['category'] === 'service-exhibition'; });
$hero_img = eikou_mobile_img_url('https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1920&q=80');
$cat_en = 'Exhibition & Events'; $cat_jp = '展示会・イベント';
$cat_desc = 'EIKOUは、展示会・イベントにおけるあらゆるニーズにお応えします。ブースの企画・デザインから、構造物の制作・施工、機材の手配、物流管理、そして当日の現場運営まで——。';
$work_cat_slug = 'exhibition';
include get_template_directory() . '/h5/_service-category-template.php';
