<?php
class module_subscribe extends module
{
    protected function action_index()
    {
        //
    }
    
    protected function action_registration()
    {
        $this->content = $this->view->fetch('module/subscribe/registration');
    }
}