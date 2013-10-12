<?php
class module_article extends module
{
    protected function action_index()
    {
        $article_list = model::factory('article')->get_list(
            array('article_active' => 1), array('article_order' => 'asc'));
        
        $this->view->assign('article_list', $article_list);
        $this->content = $this->view->fetch('module/article/index');
    }
    
    protected function action_short()
    {
        $article_list = model::factory('article')->get_list(
            array('article_active' => 1), array('article_order' => 'asc'));
        
        $this->view->assign('article_list', $article_list);
        $this->content = $this->view->fetch('module/article/short');
    }
    
    protected function action_item()
    {
        $article_name = get_param('article');
        
        if (is_numeric($article_name)) {
            try {
                $article = model::factory('article')->get($article_name);
                
                header('HTTP/1.1 301 Moved Permanently');
                header('Location: '  . $article->get_article_url());
                exit;
            } catch (AlarmException $e) {
                not_found();
            }
        } else {
            try {
                $article = model::factory('article')->get_by_name($article_name);
            } catch (Exception $e) {
                not_found();
            }
            
            if (!$article->get_article_active()) {
                not_found();
            }
        }
        
        $this->view->assign($article);
        $this->content = $this->view->fetch('module/article/item');
    }
    
    ////////////////////////////////////////////////////////////////////////////////////////////////
    
    // Дополнительные параметры хэша модуля
    protected function ext_cache_key()
    {
        $article_name = get_param('article');
        return parent::ext_cache_key() +
            ($this->action == 'item' ? array('_name' => $article_name) : array());
    }
}