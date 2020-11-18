<?php

namespace Kernel;

abstract class Singleton
{
    private static $instances = [];
    
    /**
     * getInstance
     * Возвращает единственный экземпляр класса
     * @return mixed
     */
    public static function getInstance()
    {
        $class = get_called_class();
        self::$instances[$class] ??= new $class;

        return self::$instances[$class];
    }
}
