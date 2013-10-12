<?php
class admin_table_filter extends admin_table_meta
{
    protected function action_add_save( $redirect = true )
    {
        if (!init_string('filter_name')) {
            $_REQUEST['filter_name'] = to_translit(init_string('filter_title'));
        }
        unset( $this -> fields['filter_name']['no_add'] );
        
        $primary_field = parent::action_add_save( false );
        
        if ( $redirect )
            $this -> redirect();
        
        return $primary_field;
    }
}