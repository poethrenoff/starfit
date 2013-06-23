<?php
class model_catalogue extends hierarchy
{
    // Возвращает URL каталога
    public function get_catalogue_url()
    {
        return url_for(array('controller' => 'product', 'id' => $this->get_id()));
    }
}