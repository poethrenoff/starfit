<?php
    /**
     * Пользовательские правила маршрутизации
     */
    $routes = array(
        // Путь к статьям
        '/article/@article' => array(
            'controller' => 'article',
            'article' => '\w+',
            'action' => 'item',
        ),
        
        // Путь к новостям
        '/news/@news' => array(
            'controller' => 'news',
            'news' => '\w+',
            'action' => 'item',
        ),
        
        // Путь к каталогу
        '/product/@catalogue' => array(
            'controller' => 'product',
            'catalogue' => '\w+',
        ),
        
        // Путь для голосования
        '/product/vote/@id' => array(
            'controller' => 'product',
            'action' => 'vote',
        ),
        
        // Путь к фильтру
        '/product/marker/@marker' => array(
            'controller' => 'product',
            'marker' => '\w+',
            'action' => 'marker_list',
        ),
        
        // Путь к товару
        '/product/@catalogue/@id' => array(
            'controller' => 'product',
            'catalogue' => '\w+',
            'action' => 'item',
        ),
        
        // Путь к фильтру
        '/product/@catalogue/@filter' => array(
            'controller' => 'product',
            'catalogue' => '\w+',
            'filter' => '\w+',
            'action' => 'filter',
        ),
   );
