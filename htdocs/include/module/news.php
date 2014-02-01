<?php
class module_news extends module
{
    protected function action_index()
    {
        $model_news = model::factory('news');
        
        $total = $model_news->get_count();
        $count = max(1, intval($this->get_param('count')));
        
        $pages = paginator::construct($total, array('by_page' => $count));
        
        $news_list = $model_news->get_list(
            array('news_active' => 1), array('news_date' => 'desc'), $pages['by_page'], $pages['offset']);
        
        $this->view->assign('news_list', $news_list);
        $this->view->assign('pages', paginator::fetch($pages));
        $this->content = $this->view->fetch('module/news/index');
    }
    
    protected function action_short()
    {
        $count = max(1, intval($this->get_param('count')));
        
        $news_list = model::factory('news')->get_list(
            array('news_active' => 1), array('news_date' => 'desc'), $count);
        
        $this->view->assign('news_list', $news_list);
        $this->content = $this->view->fetch('module/news/short');
    }
    
    protected function action_item()
    {
        $news_name = get_param('news');
        
        if (is_numeric($news_name)) {
            try {
                $news = model::factory('news')->get($news_name);
                
                header('HTTP/1.1 301 Moved Permanently');
                header('Location: '  . $news->get_news_url());
                exit;
            } catch (AlarmException $e) {
                not_found();
            }
        } else {
            try {
                $news = model::factory('news')->get_by_name($news_name);
            } catch (Exception $e) {
                not_found();
            }
            
            if (!$news->get_news_active()) {
                not_found();
            }
        }
        
        $this->view->assign($news);
        $this->content = $this->view->fetch('module/news/item');
        
        $meta = meta::factory('news')->get($news->get_id());
        if ($meta->get_meta_title()) {
            $this->output['meta_title'] = $meta->get_meta_title();
        }
        if ($meta->get_meta_keywords()) {
            $this->output['meta_keywords'] = $meta->get_meta_keywords();
        }
        if ($meta->get_meta_description()) {
            $this->output['meta_description'] = $meta->get_meta_description();
        }
    }
    
    ////////////////////////////////////////////////////////////////////////////////////////////////
    
    // Дополнительные параметры хэша модуля
    protected function ext_cache_key()
    {
        $news_name = get_param('news');
        return parent::ext_cache_key() +
            ($this->action == 'item' ? array('_name' => $news_name) : array());
    }
}