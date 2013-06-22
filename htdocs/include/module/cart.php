<?php
class module_cart extends module
{
    protected function action_index()
    {
        //
    }
    
    protected function action_info()
    {
        $this->content = $this->view->fetch('module/cart/info');
    }
}