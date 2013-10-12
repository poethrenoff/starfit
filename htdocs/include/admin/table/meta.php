<?php
class admin_table_meta extends admin_table
{
    protected function get_meta($primary_field, $record)
    {
        return meta::factory($this -> object)->get($primary_field);
    }
    
    protected function action_meta()
    {
        $record = $this->get_record();
        $primary_field = $record[$this->primary_field];
        
        $meta = $this->get_meta($primary_field, $record);
        
        $form_fields = array();
        foreach( array( 'meta_title', 'meta_keywords', 'meta_description' ) as $field_name ) {
            $method = 'get_' . $field_name;
            $form_fields[$field_name] = metadata::$objects['meta']['fields'][$field_name];
            $form_fields[$field_name]['value'] = field::form_field( $meta->$method(),
                metadata::$objects['meta']['fields'][$field_name]['type'] );
        }
        
        $record_title = $record[$this->main_field];
        $action_title = 'Редактирование метатегов';
        
        $this->view->assign('record_title', $this->object_desc['title'] . ($record_title ? ' :: ' . $record_title : ''));
        $this->view->assign('action_title', $action_title);
        $this->view->assign('fields', $form_fields);
        
        $form_url = url_for(array('object' => $this->object, 'action' => 'meta_save', 'id' => $primary_field));
        $this->view->assign('form_url', $form_url);
        
        $prev_url = $this->restore_state();
        $this->view->assign('back_url', url_for($prev_url));
        
        $this->content = $this->view->fetch('admin/form');
        $this->output['meta_title'] .= ($record_title ? ' :: ' . $record_title : '') . ' :: ' . $action_title;
    }
    
    protected function action_meta_save( $redirect = true )
    {
        $record = $this->get_record();
        $primary_field = $record[$this->primary_field];
        
        $meta = $this->get_meta($primary_field, $record);
        
        foreach( array( 'meta_title', 'meta_keywords', 'meta_description' ) as $field_name ) {
            $method = 'set_' . $field_name;
            $meta->$method(field::set_field(init_string($field_name), metadata::$objects['meta']['fields'][$field_name]));
        }
        
        $meta->save();
        
        if ($redirect)
            $this->redirect();
    }
    
    protected function action_copy_save( $redirect = true )
    {
        $record = $this->get_record();
        $primary_field = parent::action_copy_save(false);
        
        $this->get_meta(id(), $record)->copy($primary_field)->save();
        
        if ( $redirect )
            $this -> redirect();
        
        return $primary_field;
    }
    
    protected function action_delete( $primary_field = '', $redirect = true )
    {
        $record = $this->get_record();
        $primary_field = $record[$this->primary_field];
        
        parent::action_delete(false);
        
        $meta = meta::factory($this -> object)->delete($primary_field);
        
        if ($redirect)
            $this->redirect();
    }
    
    protected function get_record_actions( $record )
    {
        $actions = parent::get_record_actions( $record );
        
        $actions['meta'] = array( 'title' => 'Метатеги', 'url' =>
            url_for( array( 'object' => $this -> object, 'action' => 'meta', 'id' => $record[$this -> primary_field] ) ) );
        
        return $actions;
    }
}
