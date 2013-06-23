<?php
class model_marker extends model
{
    // Получение маркера по имени
    public function get_by_name($marker_name) {
        $record = db::select_row('
            select * from marker where marker_name = :marker_name',
                array('marker_name' => $marker_name));
        return $this->get($record['marker_id'], $record);
    }
    
    // Получение список маркеров товара
    public function get_by_product($product) {
        $records = db::select_all('
            select marker.* from marker
                inner join product_marker on product_marker.marker_id = marker.marker_id
            where product_marker.product_id = :product_id
            order by marker_title',
                array('product_id' => $product->get_id()));
        return $this->get_batch($records);
    }
}