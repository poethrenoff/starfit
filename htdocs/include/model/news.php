<?php
class model_news extends model
{
    // ���������� ������ ������� �� ���������� �����
    public function get_by_name($news_name)
    {
        $record = db::select_row('select * from news where news_name = :news_name',
            array('news_name' => $news_name));
        if (!$record){
            throw new AlarmException("������. ������ {$this->object}({$news_name}) �� �������.");
        }
        return $this->get($record['news_id'], $record);
    }
    
    // ���������� URL �������
    public function get_news_url()
    {
        return url_for(array('controller' => 'news', 'action' => 'item', 'news' => $this->get_news_name()));
    }
}