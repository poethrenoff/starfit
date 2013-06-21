<?php
abstract class valid
{
    // ��� ��������
    private static $object_cache = array();
    
    abstract public function check($content);
    
    // �������� ������� ����������
    public static final function factory($type)
    {
        if (!isset(self::$object_cache[$type])) {
            $class_name = 'valid_' . $type;
            self::$object_cache[$type] = new $class_name();
        }
        return self::$object_cache[$type];
    }
}