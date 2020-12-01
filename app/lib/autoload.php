<?php

namespace TODOS\LIB;

namespace TODOS\LIB;


class Autoload
{
    public static function autoload($className)
    {
        $classFile = APP_PATH . strtolower(str_replace('\\', DS, str_replace('TODOS\\', '', $className))) . '.php';
        if (file_exists($classFile)){
            require_once $classFile;
        }
    }
}

spl_autoload_register(__NAMESPACE__ . '\Autoload::autoload');