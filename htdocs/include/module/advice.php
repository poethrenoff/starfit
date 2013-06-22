<?php
class module_advice extends module
{
    protected function action_index()
    {
        $advice_id = $this->get_param('id');
        
        try {
            $advice_item = model::factory('advice')->get($this->get_param('id'));
        } catch (Exception $e) {
            not_found();
        }
        
        $this->view->assign($advice_item);
        $this->content = $this->view->fetch('module/advice/index');
    }
}