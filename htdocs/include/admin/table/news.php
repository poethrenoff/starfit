<?php
class admin_table_news extends admin_table_meta
{
    protected function action_add_save( $redirect = true )
    {
        if (!init_string('news_name')) {
            $_REQUEST['news_name'] = to_translit(init_string('news_title'));
        }
        unset( $this -> fields['news_name']['no_add'] );
        
        $primary_field = parent::action_add_save( false );
        
        if ( $redirect )
            $this -> redirect();
        
        return $primary_field;
    }
}