<?php
include_once 'config/config.php';

$arts = model::factory('article')->get_list();
foreach ($arts as $art) {
    $art->set_article_name(to_translit($art->get_article_title()))->save();
}

