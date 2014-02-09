<?php
class admin_table_spyLink extends admin_table
{
    protected function action_parse()
    {
        $record = $this->get_record();
        $primary_field = $record[$this->primary_field];
        
        $record_title = $record[$this->main_field];
        $action_title = 'Отслеживание цен';
        
        $this->view->assign('record_title', $this->object_desc['title'] . ($record_title ? ' :: ' . $record_title : ''));
        $this->view->assign('action_title', $action_title);
        
        $prev_url = $this->restore_state();
        $this->view->assign('back_url', url_for($prev_url));
        
        $link = model::factory('spy_link')->get($primary_field);
        
        $pricespy = new pricespy();
        $pricespy->parse_link($link);
        $report = $pricespy->get_report_link($link);
        
        $this->view->assign('report', $report);
        $this->content = $this->view->fetch('admin/pricespy/result');
        $this->output['meta_title'] .= ($record_title ? ' :: ' . $record_title : '') . ' :: ' . $action_title;
    }
    
    protected function get_record_actions($record)
    {
        $actions = parent::get_record_actions($record);
        
        $actions['parse'] = array('title' => 'Проверить цены', 'url' =>
            url_for(array('object' => $this->object, 'action' => 'parse',
                'id' => $record[$this->primary_field])));
        
        return $actions;
    }
}