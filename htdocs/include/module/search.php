<?php
class module_search extends module
{
    protected function action_index()
    {
        //
    }
    
    protected function action_form()
    {
        $this->content = $this->view->fetch('module/search/form');
    }
}