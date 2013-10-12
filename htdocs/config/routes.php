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
        
        // Путь к каталогу
        '/product/@catalogue' => array(
            'controller' => 'product',
            'catalogue' => '\w+',
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
