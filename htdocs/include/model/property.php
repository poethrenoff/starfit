<?php
class model_property extends model
{
    // �������� �������� ������
    protected $property_value = null;
    
    // ������������� �������� �������� ������
    public function set_property_value($property_value)
    {
        $this->property_value = $property_value;
        return $this;
    }
    
    // ���������� �������� �������� ������
    public function get_property_value()
    {
        return $this->property_value;
    }
}