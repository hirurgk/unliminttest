<?php

namespace Kernel;

class Request extends Singleton
{
    public function get($name)
    {
        return $_GET[$name];
    }

    public function post($name)
    {
        return $_POST[$name];
    }

    public function file($name)
    {
        return $_FILES[$name];
    }

    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function path()
    {
        return stristr($_SERVER['REQUEST_URI'], '?', true) ?: $_SERVER['REQUEST_URI'];
    }
}
