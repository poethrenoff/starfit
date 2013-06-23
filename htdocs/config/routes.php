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
   );
