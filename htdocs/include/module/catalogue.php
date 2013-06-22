<?php
class module_catalogue extends module
{
    protected function action_index()
    {
        //
    }
    
    protected function action_menu()
    {
        $this->content = $this->view->fetch('module/catalogue/menu');
    }
    
    protected function action_marker()
    {
        $this->content = $this->view->fetch('module/catalogue/marker');
    }
}