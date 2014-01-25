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
        $primary_field = parent::action_add_save( false );
        
        if ((isset( $_FILES['product_image_file']['name']) && $_FILES['product_image_file']['name'])) {
            $this->apply_watermark($primary_field);
        }
        
        if ( $redirect )
            $this -> redirect();
        
        return $primary_field;
    }
    
    protected function action_edit_save( $redirect = true )
    {
        parent::action_edit_save( false );
        
        if (isset($_FILES['product_image_file']['name']) && $_FILES['product_image_file']['name']) {
            $this->apply_watermark(id());
        }
        
        if ( $redirect )
            $this -> redirect();
    }
    
    protected function action_copy_save($redirect = true)
    {
        $primary_field = parent::action_copy_save(false);
        
        // Копируем свойства товара
        $product_properties = db::select_all('
                select property_id, value from product_property where product_id = :product_id',
            array('product_id' => id()));
        foreach($product_properties as $product_property)
            db::insert('product_property', array('product_id' => $primary_field) + $product_property);
        
        if ($redirect)
            $this->redirect();
        
        return $primary_field;
    }
    
    protected function action_delete($redirect = true)
    {
        $record = $this->get_record();
        $primary_field = $record[$this->primary_field];
        
        parent::action_delete(false);
        
        db::delete('product_property', array('product_id' => $primary_field));
        
        if ($redirect)
            $this->redirect();
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
    
    protected function action_property()
    {
        $record = $this->get_record();
        $primary_field = $record[$this->primary_field];
        
        $properties = db::select_all('
                select
                    property.*, product_property.value
                from
                    property
                    inner join catalogue on catalogue.catalogue_type = property.property_type
                    inner join product on product.product_catalogue = catalogue.catalogue_id
                    left join product_property on product_property.property_id = property.property_id and
                        product_property.product_id = product.product_id
                where
                    product.product_id = :product_id
                order by
                    property.property_order',
            array('product_id' => $primary_field));
        
        $form_fields = array();
        foreach($properties as $property_index => $property_value)
        {
            $property_type = $property_value['property_kind'] == 'number' ? 'float' : $property_value['property_kind'];
            $property_errors = $property_type == 'float' ? 'float' : '';
            
            $form_fields['property[' . $property_value['property_id'] . ']'] = array(
                'title' => $property_value['property_title'] . ($property_value['property_unit'] ?
                    ' (' . $property_value['property_unit'] . ')' : ''),
                'type' => $property_type, 'errors' => $property_errors,
                'value' => field::form_field($property_value['value'], $property_type));
            
            if ($property_value['property_kind'] == 'select')
            {
                $values = db::select_all('
                        select * from property_value
                        where value_property = :value_property
                        order by value_title',
                    array('value_property' => $property_value['property_id']));
                    
                $value_records = array();
                foreach ($values as $value)
                    $value_records[] = array('value' => $value['value_id'], 'title' => $value['value_title']);
                
                $form_fields['property[' . $property_value['property_id'] . ']']['values'] = $value_records;
            }
        }
       
        $record_title = $record[$this->main_field];
        $action_title = 'Редактирование свойств';
        
        $this->view->assign('record_title', $this->object_desc['title'] . ($record_title ? ' :: ' . $record_title : ''));
        $this->view->assign('action_title', $action_title);
        $this->view->assign('fields', $form_fields);
        
        $form_url = url_for(array('object' => $this->object, 'action' => 'property_save', 'id' => $primary_field));
        $this->view->assign('form_url', $form_url);
        
        $prev_url = $this->restore_state();
        $this->view->assign('back_url', url_for($prev_url));
        
        $this->content = $this->view->fetch('admin/form');
        $this->output['meta_title'] .= ($record_title ? ' :: ' . $record_title : '') . ' :: ' . $action_title;
    }
    
    protected function action_property_save($redirect = true)
    {
        $record = $this->get_record();
        $primary_field = $record[$this->primary_field];
        
        $properties = db::select_all('
                select
                    property.*
                from
                    property
                    inner join catalogue on catalogue.catalogue_type = property.property_type
                    inner join product on product.product_catalogue = catalogue.catalogue_id
                where
                    product.product_id = :product_id',
            array('product_id' => $primary_field));
        
        $property_values = init_array('property');
        
        $insert_fields = array();
        foreach($properties as $property_index => $property_value)
        {
            $property_type = $property_value['property_kind'] == 'number' ? 'float' : $property_value['property_kind'];
            $property_errors_code = $property_type == 'float' ? field::$errors['float'] : 0;
            
            if (isset($property_values[$property_value['property_id']]))
                $insert_fields[$property_value['property_id']] =
                    field::set_field($property_values[$property_value['property_id']],
                array('title' => $property_value['property_title'],
                    'type' => $property_type, 'errors_code' => $property_errors_code));
        }
        
        db::delete('product_property', array('product_id' => $primary_field));
        foreach($insert_fields as $property_id => $property_value)
            if ($property_value !== null && $property_value !== '')
                db::insert('product_property', array(
                    'product_id' => $primary_field, 'property_id' => $property_id, 'value' => $property_value));
        
        if ($redirect)
            $this->redirect();
    }
    
    protected function get_record_actions($record)
    {
        $actions = parent::get_record_actions($record);
        
        $actions['property'] = array('title' => 'Свойства', 'url' =>
            url_for(array('object' => $this->object, 'action' => 'property',
                'id' => $record[$this->primary_field])));
        
        return $actions;
    }
    
    protected function apply_watermark($primary_field)
    {
        $product = model::factory('product')->get($primary_field);
        $source_image = str_replace(UPLOAD_ALIAS, normalize_path(UPLOAD_DIR), $product->get_product_image());
        $watermark_image = $_SERVER['DOCUMENT_ROOT'] . '/image/watermark.png';
        
        image::process('watermark', array(
            'source_image' => $source_image, 'watermark_image' => $watermark_image,
        ));
    }
}