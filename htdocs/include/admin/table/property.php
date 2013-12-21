<?php
class admin_table_property extends admin_table
{
    protected function action_copy_save($redirect = true)
    {
        $primary_field = parent::action_copy_save(false);
        
        $values = db::select_all('select * from property_value where value_property = :value_property',
            array('value_property' => id()));
        
        foreach($values as $value)
            db::insert('property_value', array('value_property' => $primary_field, 'value_title' => $value['value_title']));
        
        if ($redirect)
            $this->redirect();
        
        return $primary_field;
    }
    
    protected function action_delete($redirect = true)
    {
        $record = $this->get_record();
        $primary_field = $record[$this->primary_field];
        
        $records_count = db::select_cell('
                select count(*) from product_property where property_id = :property_id',
            array('property_id' => $primary_field));
        
        if ($records_count)
            throw new Exception('Ошибка. Невозможно удалить запись, так как у нее есть зависимые записи в таблице "Свойства товаров".');
        
        parent::action_delete($redirect);
    }
}
