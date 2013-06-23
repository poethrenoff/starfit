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
        try {
            $item = model::factory('article')->get(id());
        } catch (Exception $e) {
            not_found();
        }
        
        $this->view->assign($item);
        $this->content = $this->view->fetch('module/article/item');
    }
    
    ////////////////////////////////////////////////////////////////////////////////////////////////
    
    // Дополнительные параметры хэша модуля
    protected function ext_cache_key()
    {
        return parent::ext_cache_key() +
            ($this->action == 'item' ? array('_id' => id()) : array());
    }
}