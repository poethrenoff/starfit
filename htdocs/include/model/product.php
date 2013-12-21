<?php
class model_product extends model
{
    // Возвращает каталог товара
    public function get_catalogue()
    {
        return model::factory('catalogue')->get($this->get_product_catalogue());
    }
    
    // Возвращает URL товара
    public function get_product_url()
    {
        return url_for(array('controller' => 'product',
            'catalogue' => $this->get_catalogue()->get_catalogue_name(), 'action' => 'item', 'id' => $this->get_id()));
    }
    
    // Возвращает список маркеров товара
    public function get_marker_list()
    {
        return model::factory('marker')->get_by_product($this);
    }
    
    // Возвращает бренд товара
    public function get_brand()
    {
        if ($this->get_product_brand()) {
            return model::factory('brand')->get($this->get_product_brand());
        } else {
            return false;
        }
    }
    
    // Возвращает список товаров по маркеру
    public function get_by_marker($marker, $limit = 3)
    {
        $records = db::select_all('
            select product.* from product
                inner join catalogue on product_catalogue = catalogue_id
                inner join product_marker using(product_id)
            where marker_id = :marker_id and
                product_active = :product_active and catalogue_active = :catalogue_active
            order by rand() limit ' . $limit,
            array('marker_id' => $marker->get_id(), 'product_active' => 1, 'catalogue_active' => 1));
        
        return $this->get_batch($records);
    }
    
    // Возвращает список товаров по фильтру
    public function get_by_filter($filter)
    {
        $records = db::select_all('
            select product.* from product
                inner join catalogue on product_catalogue = catalogue_id
                inner join product_filter using(product_id)
            where filter_id = :filter_id and
                product_active = :product_active and catalogue_active = :catalogue_active
            order by product_order',
            array('filter_id' => $filter->get_id(), 'product_active' => 1, 'catalogue_active' => 1));
        
        return $this->get_batch($records);
    }
    
    // Возвращает изображения товара
    public function get_picture_list()
    {
        return model::factory('product_picture')->get_list(
            array('picture_product' => $this->get_id()), array('picture_order' => 'asc')
        );
    }
    
    // Возвращает список сопутствующих товаров
    public function get_product_link_list()
    {
        $product_link_list = db::select_all('
                select
                    product.*
                from
                    product
                    inner join product_link on product_link.link_product_id = product.product_id
                where
                    product_link.product_id = :product_id and product.product_active = :product_active
                order by
                    product.product_order',
            array('product_id' => $this->get_id(), 'product_active' => 1)
        );
        return $this->get_batch($product_link_list);
    }
    
    // Возвращает свойства товара, распределенные по группам
    public function get_property_list()
    {
        $product_property_list = db::select_all('
                select
                    property.*, ifnull(property_value.value_title, product_property.value) as property_value
                from
                    property
                    left join product_property on product_property.property_id = property.property_id
                    left join property_value on property_value.value_property = property.property_id and
                        property_value.value_id = product_property.value
                where
                    product_property.product_id = :product_id and property.property_active = :property_active
                order by
                    property.property_order',
            array('product_id' => $this->get_id(), 'property_active' => 1)
        );
        
        $property_list = array();
        foreach ($product_property_list as $product_property) {
            $property_list[] = model::factory('property')->get($product_property['property_id'], $product_property)
                ->set_property_value($product_property['property_value']);
        }
        return $property_list;
    }
    
    // Добавляет оценку товару
    public function add_mark($mark)
    {
        $voters = $this->get_product_voters();
        $rating = $this->get_product_rating();
        
        $this->set_product_voters($voters + 1);
        $this->set_product_rating(($rating * $voters + $mark) / ($voters + 1));
        
        return $this;
    }
}