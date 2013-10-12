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
}