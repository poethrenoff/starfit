<?php
class admin_table_article extends admin_table_meta
{
    protected function action_add_save( $redirect = true )
    {
        if (!init_string('article_name')) {
            $_REQUEST['article_name'] = to_translit(init_string('article_title'));
        }
        unset( $this -> fields['article_name']['no_add'] );
        
        $primary_field = parent::action_add_save( false );
        
        if ( $redirect )
            $this -> redirect();
        
        return $primary_field;
    }
}