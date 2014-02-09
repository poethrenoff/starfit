<?php
class model_spyHistory extends model
{
    // Сохранение объекта в БД
    public function save() {
        if (!$this->get_history_date()) {
            $this->set_history_date(date::now());
        }
        return parent::save();
    }
    
    // Получение текущего статуса ссылки
    public function get_by_link($link) {
        $record = db::select_row('
            select * from spy_history where history_link = :history_link order by history_date desc limit 1',
                array('history_link' => $link->get_id()));
        if ($record) {
            return $this->get($record['history_id'], $record);
        }
        return false;
    }
}