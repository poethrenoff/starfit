<?php
class model_article extends model
{
    // Возвращает объект статьи по системному имени
    public function get_by_name($article_name)
    {
        $record = db::select_row('select * from article where article_name = :article_name',
            array('article_name' => $article_name));
        if (!$record){
            throw new AlarmException("Ошибка. Запись {$this->object}({$article_name}) не найдена.");
        }
        return $this->get($record['article_id'], $record);
    }
    
    // Возвращает URL статьи
    public function get_article_url()
    {
        return url_for(array('controller' => 'article', 'action' => 'item', 'article' => $this->get_article_name()));
    }
}