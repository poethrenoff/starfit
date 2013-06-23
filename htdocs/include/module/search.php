<?php
class module_search extends module
{
    protected function action_index()
    {
        $search_value = trim(init_string('search'));
        $result_list = $this->get_result($search_value);
        
        foreach ($result_list as $result_index => $result_item) {
            $result_list[$result_index] = model::factory('product')->get($result_item['product_id'], $result_item);
        }
        
        $this->view->assign('result_list', $result_list);
        $this->content = $this->view->fetch('module/search/result');
    }
    
    protected function action_form()
    {
        $this->content = $this->view->fetch('module/search/form');
    }
    
    protected function get_result($search_value)
    {
        $search_words = preg_split('/\s+/', $search_value);
            
        $table_filter_clause = array(); $search_table = 'product';
        foreach (array('product_title', 'product_description') as $field_name) {
            $field_filter_clause = array();
            foreach ($search_words as $search_index => $search_word) {
                $field_prefix = $field_name . '_' . $search_index;
                $field_filter_clause[] = 'lower(' . $field_name . ') like :' . $field_prefix;
                $filter_binds[$field_prefix] = '%' . mb_strtolower($search_word , 'utf-8') . '%';
            }
            $table_filter_clause[] = join(' and ', $field_filter_clause);
        }
        $result = db::select_all('
            select product.* from product
                inner join catalogue on product.product_catalogue = catalogue.catalogue_id
            where (' . join(' or ', $table_filter_clause) . ') and
                product_active = :product_active and catalogue_active = :catalogue_active',
            $filter_binds + array('product_active' => 1, 'catalogue_active' => 1)
        );
        return $result;
    }
}