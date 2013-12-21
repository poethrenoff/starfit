<?php
class admin_table_propertyValue extends admin_table
{
    protected function action_delete($redirect = true)
    {
        $record = $this->get_record();
        $primary_field = $record[$this->primary_field];
        
        $value_property = db::select_cell('
                select value_property from property_value where value_id = :value_id',
            array('value_id' => $primary_field));
        
        $records_count = db::select_cell('
                select count(*) from product_property where property_id = :property_id and value = :value',
            array('property_id' => $value_property, 'value' => $primary_field));
        
        if ($records_count)
            throw new Exception('Ошибка. Невозможно удалить запись, так как у нее есть зависимые записи в таблице "Свойства товаров".');
        
        parent::action_delete($redirect);
    }
}
