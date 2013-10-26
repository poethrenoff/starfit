<?php
class metadata
{
    public static $objects = array(
        /**
         * Таблица "Тексты"
         */
        'text' => array(
            'title' => 'Тексты',
            'fields' => array(
                'text_id' => array('title' => 'Идентификатор', 'type' => 'pk'),
                'text_tag' => array('title' => 'Метка', 'type' => 'string', 'show' => 1, 'sort' => 'asc', 'errors' => 'require|alpha', 'group' => array()),
                'text_title' => array('title' => 'Заголовок', 'type' => 'string', 'show' => 1, 'main' => 1, 'errors' => 'require'),
                'text_content' => array('title' => 'Текст', 'type' => 'text', 'editor' => 1, 'errors' => 'require'),
            )
        ),
        
        /**
         * Таблица "Меню"
         */
        'menu' => array(
            'title' => 'Меню',
            'fields' => array(
                'menu_id' => array('title' => 'Идентификатор', 'type' => 'pk'),
                'menu_parent' => array('title' => 'Родительский элемент', 'type' => 'parent'),
                'menu_title' => array('title' => 'Заголовок', 'type' => 'string', 'show' => 1, 'main' => 1, 'errors' => 'require'),
                'menu_page' => array('title' => 'Раздел', 'type' => 'table', 'table' => 'page'),
                'menu_url' => array('title' => 'URL', 'type' => 'string', 'show' => 1),
                'menu_order' => array('title' => 'Порядок', 'type' => 'order', 'group' => array('menu_parent')),
                'menu_active' => array('title' => 'Видимость', 'type' => 'active')
            )
        ),
        
        /**
         * Таблица "Статьи"
         */
        'article' => array(
            'title' => 'Статьи',
            'class' => 'article',
            'fields' => array(
                'article_id' => array('title' => 'Идентификатор', 'type' => 'pk'),
                'article_title' => array('title' => 'Заголовок', 'type' => 'string', 'show' => 1, 'main' => 1, 'errors' => 'require'),
                'article_name' => array( 'title' => 'Ссылка', 'type' => 'string', 'errors' => 'require', 'no_add' => 1, 'group' => array() ),
                'article_content' => array('title' => 'Текст', 'type' => 'text', 'editor' => 1, 'errors' => 'require'),
                'article_order' => array('title' => 'Порядок', 'type' => 'order'),
                'article_active' => array('title' => 'Видимость', 'type' => 'active'),
            ),
        ),
        
        /**
         * Таблица "Советы эксперта"
         */
        'advice' => array(
            'title' => 'Советы эксперта',
            'fields' => array(
                'advice_id' => array('title' => 'Идентификатор', 'type' => 'pk'),
                'advice_title' => array('title' => 'Заголовок', 'type' => 'string', 'show' => 1, 'main' => 1, 'errors' => 'require'),
                'advice_content' => array('title' => 'Текст', 'type' => 'text', 'editor' => 1, 'errors' => 'require'),
            ),
        ),
        
        /**
         * Таблица "Тизеры"
         */
        'teaser' => array(
            'title' => 'Тизеры',
            'fields' => array(
                'teaser_id' => array('title' => 'Идентификатор', 'type' => 'pk'),
                'teaser_title' => array('title' => 'Заголовок', 'type' => 'string', 'show' => 1, 'main' => 1, 'errors' => 'require'),
                'teaser_image' => array('title' => 'Изображение', 'type' => 'image', 'upload_dir' => 'teaser', 'errors' => 'require'),
                'teaser_url' => array('title' => 'URL', 'type' => 'string', 'errors' => 'require'),
                'teaser_order' => array('title' => 'Порядок', 'type' => 'order'),
                'teaser_active' => array('title' => 'Видимость', 'type' => 'active'),
            ),
        ),
        
        /**
         * Таблица "Каталог"
         */
        'catalogue' => array(
            'title' => 'Каталог',
            'class' => 'catalogue',
            'fields' => array(
                'catalogue_id' => array( 'title' => 'Идентификатор', 'type' => 'pk' ),
                'catalogue_parent' => array( 'title' => 'Родительский раздел', 'type' => 'parent' ),
                'catalogue_title' => array( 'title' => 'Название', 'type' => 'string', 'show' => 1, 'main' => 1, 'errors' => 'require' ),
                'catalogue_short_title' => array( 'title' => 'Краткое название', 'type' => 'string', 'errors' => 'require' ),
                'catalogue_name' => array( 'title' => 'Ссылка', 'type' => 'string', 'errors' => 'require', 'no_add' => 1, 'group' => array() ),
                'catalogue_description' => array( 'title' => 'Описание', 'type' => 'text', 'editor' => 1 ),
                'catalogue_image' => array( 'title' => 'Изображение', 'type' => 'image', 'upload_dir' => 'catalogue' ),
                'catalogue_order' => array( 'title' => 'Порядок', 'type' => 'order', 'group' => array( 'catalogue_parent' ) ),
                'catalogue_active' => array( 'title' => 'Видимость', 'type' => 'active' ),
            ),
            'links' => array(
                'product' => array( 'table' => 'product', 'field' => 'product_catalogue' ),
                'filter' => array( 'table' => 'filter', 'field' => 'filter_catalogue' ),
            ),
        ),
        
        /**
         * Таблица "Товары"
         */
        'product' => array(
            'title' => 'Товары',
            'class' => 'product',
            'fields' => array(
                'product_id' => array( 'title' => 'Идентификатор', 'type' => 'pk' ),
                'product_catalogue' => array( 'title' => 'Каталог', 'type' => 'table', 'table' => 'catalogue', 'errors' => 'require' ),
                'product_title' => array( 'title' => 'Название', 'type' => 'string', 'main' => 1, 'errors' => 'require' ),
                'product_description' => array( 'title' => 'Описание', 'type' => 'text', 'editor' => 1, 'errors' => 'require' ),
                'product_price' => array( 'title' => 'Цена', 'type' => 'float', 'errors' => 'require' ),
                'product_price_old' => array( 'title' => 'Старая цена', 'type' => 'float' ),
                'product_image' => array( 'title' => 'Изображение', 'type' => 'image', 'upload_dir' => 'image', 'errors' => 'require' ),
                'product_instruction' => array( 'title' => 'Инструкция', 'type' => 'file', 'upload_dir' => 'instruction'),
                'product_order' => array( 'title' => 'Порядок', 'type' => 'order', 'group' => array( 'product_catalogue' ) ),
                'product_active' => array( 'title' => 'Видимость', 'type' => 'active' ),
            ),
            'relations' => array(
                'marker' => array( 'secondary_table' => 'marker', 'relation_table' => 'product_marker',
                    'primary_field' => 'product_id', 'secondary_field' => 'marker_id', 'title' => 'Маркеры' ),
                'filter' => array( 'secondary_table' => 'filter', 'relation_table' => 'product_filter',
                    'primary_field' => 'product_id', 'secondary_field' => 'filter_id', 'title' => 'Фильтры' ),
            ),
        ),
        
        /**
         * Таблица "Маркеры"
         */
        'marker' => array(
            'title' => 'Маркеры',
            'fields' => array(
                'marker_id' => array( 'title' => 'Идентификатор', 'type' => 'pk' ),
                'marker_title' => array( 'title' => 'Название', 'type' => 'string', 'show' => 1, 'main' => 1, 'sort' => 'asc', 'errors' => 'require' ),
                'marker_name' => array( 'title' => 'Системное имя', 'type' => 'string', 'show' => 1, 'errors' => 'require', 'group' => array() ),
                'marker_picture' => array( 'title' => 'Картинка', 'type' => 'image', 'upload_dir' => 'marker' ),
            ),
            'relations' => array(
                'product' => array( 'secondary_table' => 'product', 'relation_table' => 'product_marker',
                    'primary_field' => 'marker_id', 'secondary_field' => 'product_id', 'title' => 'Товары' ),
            ),
        ),
        
        /**
         * Таблица "Связь маркеров с товарами"
         */
        'product_marker' => array(
            'title' => 'Связь маркеров с товарами',
            'internal' => true,
            'fields' => array(
                'product_id' => array( 'title' => 'Товар', 'type' => 'table', 'table' => 'product', 'errors' => 'require' ),
                'marker_id' => array( 'title' => 'Маркер', 'type' => 'table', 'table' => 'marker', 'errors' => 'require' ),
            ),
        ),
        
        /**
         * Таблица "Фильтры"
         */
        'filter' => array(
            'title' => 'Фильтры',
            'class' => 'filter',
            'fields' => array(
                'filter_id' => array( 'title' => 'Идентификатор', 'type' => 'pk' ),
                'filter_catalogue' => array( 'title' => 'Каталог', 'type' => 'table', 'table' => 'catalogue', 'errors' => 'require' ),
                'filter_title' => array( 'title' => 'Название', 'type' => 'string', 'show' => 1, 'main' => 1, 'errors' => 'require' ),
                'filter_name' => array( 'title' => 'Ссылка', 'type' => 'string', 'errors' => 'require', 'no_add' => 1, 'group' => array( 'filter_catalogue' ) ),
                'filter_header' => array( 'title' => 'Заголовок', 'type' => 'string' ),
                'filter_description' => array( 'title' => 'Описание', 'type' => 'text', 'editor' => 1 ),
                'filter_order' => array( 'title' => 'Порядок', 'type' => 'order', 'group' => array( 'filter_catalogue' ) ),
            ),
        ),
        
        /**
         * Таблица "Связь фильтров с товарами"
         */
        'product_filter' => array(
            'title' => 'Связь фильтров с товарами',
            'internal' => true,
            'fields' => array(
                'product_id' => array( 'title' => 'Товар', 'type' => 'table', 'table' => 'product', 'errors' => 'require' ),
                'filter_id' => array( 'title' => 'Фильтр', 'type' => 'table', 'table' => 'filter', 'errors' => 'require' ),
            ),
        ),
        
        /**
         * Таблица "Заказы"
         */
        'orders' => array(
            'title' => 'Заказы',
            'fields' => array(
                'order_id' => array('title' => 'Идентификатор', 'type' => 'pk'),
                'order_client_name' => array('title' => 'Клиент', 'type' => 'string', 'main' => 1, 'errors' => 'require'),
                'order_client_phone' => array('title' => 'Телефон', 'type' => 'string'),
                'order_client_email' => array('title' => 'Email', 'type' => 'string', 'errors' => 'email'),
                'order_client_address' => array('title' => 'Адрес', 'type' => 'text'),
                'order_client_comment' => array('title' => 'Комментарий', 'type' => 'text'),
                'order_date' => array('title' => 'Дата заказа', 'type' => 'datetime', 'show' => 1, 'sort' => 'desc', 'errors' => 'require'),
                'order_sum' => array('title' => 'Сумма заказа', 'type' => 'float', 'show' => 1, 'errors' => 'require'),
            ),
            'links' => array(
                'order_item' => array('table' => 'order_item', 'field' => 'item_order', 'ondelete' => 'cascade'),
            )
        ),
        
        'order_item' => array(
            'title' => 'Позиции заказов',
            'fields' => array(
                'item_id' => array('title' => 'Идентификатор', 'type' => 'pk'),
                'item_order' => array('title' => 'Заказ', 'type' => 'table', 'table' => 'orders', 'errors' => 'require'),
                'item_title' => array('title' => 'Товар', 'type' => 'string', 'main' => 1, 'errors' => 'require'),
                'item_price' => array('title' => 'Цена', 'type' => 'float', 'show' => 1, 'errors' => 'require'),
                'item_quantity' => array('title' => 'Количество', 'type' => 'int', 'show' => 1, 'errors' => 'require')
            )
        ),
        
        'meta' => array(
            'title' => 'Метатеги',
            'internal' => true,
            'fields' => array(
                'meta_id' => array( 'title' => 'Идентификатор', 'type' => 'int' ),
                'meta_object' => array('title' => 'Объект', 'type' => 'select', 'values' => '__OBJECT__' ),
                'meta_title' => array( 'title' => 'Заголовок', 'type' => 'string' ),
                'meta_keywords' => array( 'title' => 'Ключевые слова', 'type' => 'text' ),
                'meta_description' => array( 'title' => 'Описание', 'type' => 'text' ),
            )
        ),
        
        
        ////////////////////////////////////////////////////////////////////////////////////////
        
        /**
         * Таблица "Настройки"
         */
        'preference' => array(
            'title' => 'Настройки',
            'class' => 'builder',
            'fields' => array(
                'preference_id' => array('title' => 'Идентификатор', 'type' => 'pk'),
                'preference_title' => array('title' => 'Название', 'type' => 'string', 'show' => 1, 'main' => 1, 'errors' => 'require'),
                'preference_name' => array('title' => 'Имя', 'type' => 'string', 'show' => 1, 'filter' => 1, 'errors' => 'require|alpha', 'group' => array()),
                'preference_value' => array('title' => 'Значение', 'type' => 'string', 'show' => 1),
            )
        ),
        
        /**
         * Таблица "Разделы"
         */
        'page' => array(
            'title' => 'Разделы',
            'class' => 'page',
            'fields' => array(
                'page_id' => array('title' => 'Идентификатор', 'type' => 'pk'),
                'page_parent' => array('title' => 'Родительский раздел', 'type' => 'parent'),
                'page_layout' => array('title' => 'Шаблон', 'type' => 'table', 'table' => 'layout', 'errors' => 'require'),
                'page_title' => array('title' => 'Название', 'type' => 'string', 'main' => 1, 'errors' => 'require'),
                'page_name' => array('title' => 'Каталог', 'type' => 'string', 'show' => 1, 'errors' => 'alpha', 'group' => array('page_parent')),
                'page_folder' => array('title' => 'Папка', 'type' => 'boolean'),
                'meta_title' => array('title' => 'Заголовок', 'type' => 'text'),
                'meta_keywords' => array('title' => 'Ключевые слова', 'type' => 'text'),
                'meta_description' => array('title' => 'Описание', 'type' => 'text'),
                'page_order' => array('title' => 'Порядок', 'type' => 'order', 'group' => array('page_parent')),
                'page_active' => array('title' => 'Видимость', 'type' => 'active'),
            ),
            'links' => array(
                'block' => array('table' => 'block', 'field' => 'block_page', 'ondelete' => 'cascade'),
            ),
        ),
        
        /**
         * Таблица "Блоки"
         */
        'block' => array(
            'title' => 'Блоки',
            'class' => 'block',
            'fields' => array(
                'block_id' => array('title' => 'Идентификатор', 'type' => 'pk'),
                'block_page' => array('title' => 'Раздел', 'type' => 'table', 'table' => 'page', 'errors' => 'require'),
                'block_module' => array('title' => 'Модуль', 'type' => 'table', 'table' => 'module', 'errors' => 'require'),
                'block_title' => array('title' => 'Название', 'type' => 'string', 'main' => 1, 'errors' => 'require'),
                'block_area' => array('title' => 'Область шаблона', 'type' => 'table', 'table' => 'layout_area', 'errors' => 'require'),
            ),
            'links' => array(
                'block_param' => array('table' => 'block_param', 'field' => 'block', 'ondelete' => 'cascade'),
            ),
        ),
        
        /**
         * Таблица "Шаблоны"
         */
        'layout' => array(
            'title' => 'Шаблоны',
            'class' => 'layout',
            'fields' => array(
                'layout_id' => array('title' => 'Идентификатор', 'type' => 'pk'),
                'layout_title' => array('title' => 'Название', 'type' => 'string', 'main' => 1, 'errors' => 'require'),
                'layout_name' => array('title' => 'Системное имя', 'type' => 'string', 'show' => 1, 'errors' => 'require|alpha'),
            ),
            'links' => array(
                'page' => array('table' => 'page', 'field' => 'page_layout', 'hidden' => 1),
                'area' => array('table' => 'layout_area', 'field' => 'area_layout', 'title' => 'Области'),
            ),
        ),
        
        /**
         * Таблица "Области шаблона"
         */
        'layout_area' => array(
            'title' => 'Области шаблона',
            'class' => 'builder',
            'fields' => array(
                'area_id' => array('title' => 'Идентификатор', 'type' => 'pk'),
                'area_layout' => array('title' => 'Шаблон', 'type' => 'table', 'table' => 'layout', 'errors' => 'require'),
                'area_title' => array('title' => 'Название', 'type' => 'string', 'main' => 1, 'errors' => 'require'),
                'area_name' => array('title' => 'Системное имя', 'type' => 'string', 'show' => 1, 'errors' => 'require|alpha'),
                'area_main' => array('title' => 'Главная область', 'type' => 'default', 'show' => 1, 'group' => array('area_layout')),
                'area_order' => array('title' => 'Порядок', 'type' => 'order', 'group' => array('area_layout')),
            ),
            'links' => array(
                'bloсk' => array('table' => 'block', 'field' => 'block_area'),
            ),
        ),
        
        /**
         * Таблица "Модули"
         */
        'module' => array(
            'title' => 'Модули',
            'class' => 'module',
            'fields' => array(
                'module_id' => array('title' => 'Идентификатор', 'type' => 'pk'),
                'module_title' => array('title' => 'Название', 'type' => 'string', 'main' => 1, 'errors' => 'require'),
                'module_name' => array('title' => 'Системное имя', 'type' => 'string', 'show' => 1, 'group' => array(), 'errors' => 'require|alpha'),
            ),
            'links' => array(
                'block' => array('table' => 'block', 'field' => 'block_module'),
                'module_param' => array('table' => 'module_param', 'field' => 'param_module', 'title' => 'Параметры', 'ondelete' => 'cascade'),
            ),
        ),
        
        /**
         * Таблица "Параметры модулей"
         */
        'module_param' => array(
            'title' => 'Параметры модулей',
            'class' => 'moduleParam',
            'fields' => array(
                'param_id' => array('title' => 'Идентификатор', 'type' => 'pk'),
                'param_module' => array('title' => 'Модуль', 'type' => 'table', 'table' => 'module', 'errors' => 'require'),
                'param_title' => array('title' => 'Название', 'type' => 'string', 'main' => 1, 'errors' => 'require'),
                'param_type' => array('title' => 'Тип параметра', 'type' => 'select', 'filter' => 1, 'values' => array(
                        array('value' => 'string', 'title' => 'Строка'),
                        array('value' => 'int', 'title' => 'Число'),
                        array('value' => 'text', 'title' => 'Текст'),
                        array('value' => 'select', 'title' => 'Список'),
                        array('value' => 'table', 'title' => 'Таблица'),
                        array('value' => 'boolean', 'title' => 'Флаг')), 'show' => 1, 'errors' => 'require'),
                'param_name' => array('title' => 'Системное имя', 'type' => 'string', 'show' => 1, 'group' => array('param_module'), 'errors' => 'require|alpha'),
                'param_table' => array('title' => 'Имя таблицы', 'type' => 'select', 'values' => '__OBJECT__', 'show' => 1),
                'param_default' => array('title' => 'Значение по умолчанию', 'type' => 'string'),
                'param_require' => array('title' => 'Обязательное', 'type' => 'boolean'),
                'param_order' => array('title' => 'Порядок', 'type' => 'order', 'group' => array('param_module')),
            ),
            'links' => array(
                'param_value' => array('table' => 'param_value', 'field' => 'value_param', 'show' => array('param_type' => array('select')), 'title' => 'Значения', 'ondelete' => 'cascade'),
                'block_param' => array('table' => 'block_param', 'field' => 'param', 'ondelete' => 'cascade'),
            ),
        ),
        
        /**
         * Таблица "Значения параметров модулей"
         */
        'param_value' => array(
            'title' => 'Значения параметров модулей',
            'class' => 'paramValue',
            'fields' => array(
                'value_id' => array('title' => 'Идентификатор', 'type' => 'pk'),
                'value_param' => array('title' => 'Параметр', 'type' => 'table', 'table' => 'module_param', 'errors' => 'require'),
                'value_title' => array('title' => 'Название', 'type' => 'string', 'main' => 1, 'errors' => 'require'),
                'value_content' => array('title' => 'Значение', 'type' => 'string', 'show' => 1, 'group' => array('value_param'), 'errors' => 'require'),
                'value_default' => array('title' => 'По умолчанию', 'type' => 'default', 'show' => 1, 'group' => array('value_param')),
            ),
        ),
        
        /**
         * Таблица "Параметры блоков"
         */
        'block_param' => array(
            'title' => 'Параметры блоков',
            'internal' => true,
            'fields' => array(
                'block' => array('title' => 'Блок', 'type' => 'table', 'table' => 'block'),
                'param' => array('title' => 'Параметр', 'type' => 'table', 'table' => 'module_param'),
                'value' => array('title' => 'Значение', 'type' => 'text'),
            ),
        ),
        
        /**
         * Таблицы управления правами доступа
         */
        
        'admin' => array(
            'title' => 'Администраторы',
            'fields' => array(
                'admin_id' => array('title' => 'Идентификатор', 'type' => 'pk'),
                'admin_title' => array('title' => 'Имя', 'type' => 'string', 'show' => 1, 'main' => 1, 'errors' => 'require'),
                'admin_login' => array('title' => 'Логин', 'type' => 'string', 'show' => 1, 'errors' => 'require|alpha', 'group' => array()),
                'admin_password' => array('title' => 'Пароль', 'type' => 'password'),
                'admin_email' => array('title' => 'Email', 'type' => 'string', 'errors' => 'email'),
                'admin_active' => array('title' => 'Активный', 'type' => 'active'),
            ),
            'relations' => array(
                'admin_role' => array('secondary_table' => 'role', 'relation_table' => 'admin_role',
                    'primary_field' => 'admin_id', 'secondary_field' => 'role_id'),
            ),
        ),
        
        'admin_role' => array(
            'title' => 'Роли администраторов',
            'internal' => true,
            'fields' => array(
                'admin_id' => array('title' => 'Администратор', 'type' => 'table', 'table' => 'admin', 'errors' => 'require'),
                'role_id' => array('title' => 'Роль', 'type' => 'table', 'table' => 'role', 'errors' => 'require'),
            ),
        ),
        
        'role' => array(
            'title' => 'Роли',
            'fields' => array(
                'role_id' => array('title' => 'Идентификатор', 'type' => 'pk'),
                'role_title' => array('title' => 'Название', 'type' => 'string', 'show' => 1, 'main' => 1, 'errors' => 'require'),
                'role_default' => array('title' => 'Главный администратор', 'type' => 'default', 'show' => 1),
            ),
            'relations' => array(
                'role_object' => array('secondary_table' => 'object', 'relation_table' => 'role_object',
                    'primary_field' => 'role_id', 'secondary_field' => 'object_id'),
            ),
        ),
        
        'role_object' => array(
            'title' => 'Права на системные разделы',
            'internal' => true,
            'fields' => array(
                'role_id' => array('title' => 'Роль', 'type' => 'table', 'table' => 'role', 'errors' => 'require'),
                'object_id' => array('title' => 'Системный раздел', 'type' => 'table', 'table' => 'object', 'errors' => 'require'),
            ),
        ),
        
        'object' => array(
            'title' => 'Системные разделы',
            'fields' => array(
                'object_id' => array('title' => 'Идентификатор', 'type' => 'pk'),
                'object_parent' => array('title' => 'Родительский раздел', 'type' => 'parent'),
                'object_title' => array('title' => 'Название', 'type' => 'string', 'show' => 1, 'main' => 1, 'errors' => 'require'),
                'object_name' => array('title' => 'Объект', 'type' => 'select', 'values' => '__OBJECT__'),
                'object_order' => array('title' => 'Порядок', 'type' => 'order', 'group' => array('object_parent')),
                'object_active' => array('title' => 'Видимость', 'type' => 'active'),
            )
        ),
        
        /**
         * Утилита "Файл-менеджер"
         */
        'fm' => array(
            'title' => 'Файл-менеджер',
            'class' => 'fm'
        ),
   );
}

//db::create();
