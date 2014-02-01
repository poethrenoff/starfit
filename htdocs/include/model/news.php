<?php
class model_news extends model
{
    // Возвращает объект новости по системному имени
    public function get_by_name($news_name)
    {
        $record = db::select_row('select * from news where news_name = :news_name',
            array('news_name' => $news_name));
        if (!$record){
            throw new AlarmException("Ошибка. Запись {$this->object}({$news_name}) не найдена.");
        }
        return $this->get($record['news_id'], $record);
    }
    
    // Возвращает URL новости
    public function get_news_url()
    {
        return url_for(array('controller' => 'news', 'action' => 'item', 'news' => $this->get_news_name()));
    }
}