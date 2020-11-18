<?php

class AutoLoader
{
    private $pathRoot = "";

    public function __construct($pathRoot = "")
    {
        $this->pathRoot = $pathRoot;
        spl_autoload_register([self::class, 'requireClass']);
    }

    public function requireClass(string $classname)
    {
        $arPath = explode("\\", $classname);
        $class = array_pop($arPath);
        $path = strtolower(implode('/', $arPath));

        $file = $this->pathRoot . "/{$path}/{$class}.php";
        if (file_exists($file)) require_once($file);
    }
}
