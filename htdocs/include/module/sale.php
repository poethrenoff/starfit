<?php
class module_sale extends module
{
    protected function action_index()
    {
        $product_list = model::factory('product')->get_list(
            array('product_active' => 1, 'product_sale' => 1), array('product_order' => 'asc')
        );
        $sale_text = module::factory('text')->get_by_tag('sale');

        $this->view->assign('sale_text', $sale_text);
        $this->view->assign('product_list', $product_list);
        $this->content = $this->view->fetch('module/product/sale');
    }
}