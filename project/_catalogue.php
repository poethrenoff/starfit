<?php
include_once 'config/config.php';

$cats = model::factory('catalogue')->get_list();
foreach ($cats as $cat) {
    $cat->set_catalogue_name(to_translit($cat->get_catalogue_title()))->save();
}

