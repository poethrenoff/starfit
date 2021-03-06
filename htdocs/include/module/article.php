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
        $count = max(1, intval($this->get_param('count')));
        
        $article_list = model::factory('article')->get_list(
            array('article_active' => 1), array('article_order' => 'asc'), $count);
        
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
        
        $meta = meta::factory('article')->get($article->get_id());
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
        $article_name = get_param('article');
        return parent::ext_cache_key() +
            ($this->action == 'item' ? array('_name' => $article_name) : array());
    }
}