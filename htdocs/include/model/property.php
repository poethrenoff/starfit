<?php
class model_property extends model
{
    // Значение свойства товара
    protected $property_value = null;
    
    // Устанавливает значение свойства товара
    public function set_property_value($property_value)
    {
        $this->property_value = $property_value;
        return $this;
    }
    
    // Возвращает значение свойства товара
    public function get_property_value()
    {
        return $this->property_value;
    }
}