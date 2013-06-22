<?php
class module_teaser extends module
{
    protected function action_index()
    {
        $this->content = $this->view->fetch('module/teaser/index');
    }
}