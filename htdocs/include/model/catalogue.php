<?php
class model_catalogue extends hierarchy
{
    // ���������� ������ �������� �� ���������� �����
    public function get_by_name($catalogue_name)
    {
        $record = db::select_row('select * from catalogue where catalogue_name = :catalogue_name',
            array('catalogue_name' => $catalogue_name));
        if (!$record){
            throw new AlarmException("������. ������ {$this->object}({$catalogue_name}) �� �������.");
        }
        return $this->get($record['catalogue_id'], $record);
    }
    
    // ���������� URL ��������
    public function get_catalogue_url()
    {
        return url_for(array('controller' => 'product', 'catalogue' => $this->get_catalogue_name()));
    }
}