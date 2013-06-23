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
            'catalogue' => $this->get_product_catalogue(),
            'action' => 'item', 'id' => $this->get_id()));
    }
    
    // Возвращает список маеркеров товара
    public function get_marker_list()
    {
        return model::factory('marker')->get_by_product($this);
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
}