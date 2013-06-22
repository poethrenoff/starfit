<?php
class module_article extends module
{
    protected function action_index()
    {
        //
    }
    
    protected function action_short()
    {
        $this->content = $this->view->fetch('module/article/short');
    }
}