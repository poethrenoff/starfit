<?php
    /**
     * Пользовательские правила маршрутизации
     */
    $routes = array(
        // Путь к статьям
        '/article/@id' => array(
            'controller' => 'article',
            'action' => 'item',
        ),
        
        // Путь к товару
        '/product/@catalogue/@id' => array(
            'controller' => 'product',
            'catalogue' => '\d+',
            'action' => 'item',
        ),
   );
