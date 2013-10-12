<?php
class admin_table_product extends admin_table_meta
{
    protected function get_meta($primary_field, $record)
    {
        $meta = meta::factory($this -> object)->get($primary_field);
        
        if (!$meta->get_meta_title()) {
            $meta->set_meta_title($record['product_title']);
        }
        if (!$meta->get_meta_keywords()) {
            $meta->set_meta_keywords($record['product_title']);
        }
        if (!$meta->get_meta_description()) {
            $meta->set_meta_description($record['product_title']);
        }
        
        return $meta;
    }
    
    protected function action_add_save( $redirect = true )
    {
        if (!init_string('catalogue_name')) {
            $_REQUEST['catalogue_name'] = to_translit(init_string('catalogue_title'));
        }
        unset( $this -> fields['catalogue_name']['no_add'] );
        
        $primary_field = parent::action_add_save( false );
        
        if ( $redirect )
            $this -> redirect();
        
        return $primary_field;
    }
    
    protected function get_record_relations($record)
    {
        $relations = parent::get_record_relations($record);
        
        $product = model::factory('product')->get($record[$this->primary_field]);
        $relations['filter']['url'] = url_for(array('object' => $this->object, 'action' => 'relation', 'relation' => 'filter',
            'id' => $record[$this->primary_field], 'filter_catalogue' => $product->get_product_catalogue()));
        
        return $relations;
    }
    
    
    protected function action_relation()
    {
        $relation_name = init_string('relation');
        if ($relation_name == 'filter' ) {
            $filter_catalogue = init_string('filter_catalogue');
            if (!$filter_catalogue) {
                $record = $this->get_record();
                $primary_field = $record[$this->primary_field];
                $product = model::factory('product')->get($primary_field);
                $_REQUEST['filter_catalogue'] = $product->get_product_catalogue();
            }
        }
        
        parent::action_relation();
    }
}