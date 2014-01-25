<?php
include_once 'config/config.php';

$watermark_image = dirname(__FILE__) . '/image/watermark.png';

$catalogue_list = model::factory('catalogue')->get_list();
foreach ($catalogue_list as $catalogue) {
    if (!$catalogue->get_catalogue_image()) {
        continue;
    }
    $source_image = str_replace(UPLOAD_ALIAS, normalize_path(UPLOAD_DIR), $catalogue->get_catalogue_image());
    if (!file_exists($source_image)) {
        continue;
    }
    image::process('watermark', array(
        'source_image' => $source_image, 'watermark_image' => $watermark_image,
    ));    
}

$product_list = model::factory('product')->get_list();
foreach ($product_list as $product) {
    if (!$product->get_product_image()) {
        continue;
    }
    $source_image = str_replace(UPLOAD_ALIAS, normalize_path(UPLOAD_DIR), $product->get_product_image());
    if (!file_exists($source_image)) {
        continue;
    }
    image::process('watermark', array(
        'source_image' => $source_image, 'watermark_image' => $watermark_image,
    ));    
}

$picture_list = model::factory('product_picture')->get_list();
foreach ($picture_list as $picture) {
    if (!$picture->get_picture_image()) {
        continue;
    }
    $source_image = str_replace(UPLOAD_ALIAS, normalize_path(UPLOAD_DIR), $picture->get_picture_image());
    if (!file_exists($source_image)) {
        continue;
    }
    image::process('watermark', array(
        'source_image' => $source_image, 'watermark_image' => $watermark_image,
    ));    
}
