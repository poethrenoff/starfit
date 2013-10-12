<?php
class model_filter extends model
{
    // Возвращает объект фильтра по системному имени
    public function get_by_name($catalogue, $filter_name)
    {
        $record = db::select_row('select * from filter where filter_catalogue = :filter_catalogue and filter_name = :filter_name',
            array('filter_catalogue' => $catalogue->get_id(), 'filter_name' => $filter_name));
        if (!$record){
            throw new AlarmException("Ошибка. Запись {$this->object}({$filter_name}) не найдена.");
        }
        return $this->get($record['filter_id'], $record);
    }
    
    // Возвращает каталог фильтра
    public function get_catalogue()
    {
        return model::factory('catalogue')->get($this->get_filter_catalogue());
    }
    
    // Возвращает URL фильтра
    public function get_filter_url()
    {
        return url_for(array('controller' => 'product',
            'catalogue' => $this->get_catalogue()->get_catalogue_name(),
            'action' => 'filter', 'filter' => $this->get_filter_name()));
    }
}