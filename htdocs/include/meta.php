<?php
class meta
{
    // Идентификатор
    protected $meta_id = '';

    // Объект
    protected $meta_object = '';

    // Поля таблицы
    protected $fields = array();

    // Описание полей таблицы
    protected $fields_desc = array();

    ////////////////////////////////////////////////////////////////////////////////////////////////
    
    public function __construct($meta_object)
    {
        $this->meta_object = $meta_object;
        $this->fields_desc = metadata::$objects['meta']['fields'];
    }

    // Диспетчер неявных аксессоров
    public function __call($method, $vars) {
        if (!preg_match("/^(get|set)_(\w+)/", $method, $matches)) {
            throw new AlarmException("Ошибка. Метод " . get_called_class() . "::{$method}() не найден.");
        }
        if (!(isset($this->fields_desc[$matches[2]]) && is_array($this->fields_desc[$matches[2]]))) {
            throw new AlarmException("Ошибка. Поле {$this->object}->{$matches[2]} не описано в метаданных.");
        }
        
        $field_name = $matches[2]; $field_desc = $this->fields_desc[$field_name];
        switch ($matches[1]) {
            case 'get':
                return isset($this->fields[$field_name]) ? $this->fields[$field_name] : null;
            case 'set':
                $this->fields[$field_name] = $vars[0];
                return $this;
        }
    }

    // Создание объекта модели
    public static final function factory($meta_object)
    {
        return new meta($meta_object);
    }

    // Заполнение полей объекта из БД
    public function get($meta_id, $record = null) {
        if (is_null($record)) {
            $record = db::select_row( '
                    select meta_title, meta_keywords, meta_description from meta
                    where meta_id = :meta_id and meta_object = :meta_object',
                array( 'meta_id' => $meta_id, 'meta_object' => $this->meta_object ) );
        }
        $this->meta_id = $meta_id;
        $this->fields = $record;
        return $this;
    }

    // Сохранение объекта в БД
    public function save() {
        if (!$this->meta_id){
            throw new AlarmException("Ошибка. Запись не можеть быть сохранена в БД, так как не имеет идентификатора.");
        }
        
        $record_pk = array(
            'meta_id' => $this->meta_id,
            'meta_object' => $this->meta_object,
        );
        
        db::delete('meta', $record_pk);
        if (!is_empty(join('', $this->fields))) {
            db::insert('meta', $record_pk + $this->fields);
        }
        
        return $this;
    }

    // Сохранение объекта в БД
    public function delete($meta_id) {
        $record_pk = array(
            'meta_id' => $meta_id,
            'meta_object' => $this->meta_object,
        );
        
        db::delete('meta', $record_pk);
    }

    // Клонирование объекта
    public function copy($meta_id) {
        return meta::factory($this->meta_object)->get($meta_id, $this->fields);
    }
}