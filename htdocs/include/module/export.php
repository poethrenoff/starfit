<?php
class module_export extends module
{
    protected function action_index()
    {
        $catalogue_list = model::factory('catalogue')->get_list(
            array('catalogue_active' => 1), array('catalogue_id' => 'asc')
        );
        $product_list = model::factory('product')->get_list(
            array('product_active' => 1, 'product_stock' => 1), array('product_id' => 'asc')
        );
        
		header( 'Content-type: text/xml; charset: UTF-8' );
        
        $this->view->assign('catalogue_list', $catalogue_list);
        $this->view->assign('product_list', $product_list);
        $this->content = $this->view->fetch('module/export/export');
    }
}