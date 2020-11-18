<?php

namespace Kernel;

class Router extends Singleton
{    
    /**
     * request
     * @var Request
     */
    private $request;

    public function __construct()
    {
        $this->request = Request::getInstance();
    }
    
    /**
     * route
     * Определяет контроллер и действие из пути запроса
     * @return Response
     */
    public function route()
    {
        // Получим контроллер и действие из пути
        $path = $this->request->path();
        list($controller, $action) = explode('/', trim($path, '/'));

        $controllerAction = $this->getControllerAction($controller, $action);

        // Если нет контроллера/действия - вызовим действие с 404
        if (!is_callable($controllerAction)) {
            $controllerAction = $this->getControllerAction('Main', 'notFound');
        }

        return call_user_func_array($controllerAction, []);
    }
    
    /**
     * getControllerAction
     * Возвращает массив контроллера и его действия
     * @param string $controller
     * @param string $action
     * @return array
     */
    public function getControllerAction($controller, $action)
    {
        // Составим путь до класса-контроллера
        $controller = "\\Controllers\\"
            . ($controller ? ucfirst($controller) : 'Main')
            . "Controller";

        $action = $action ?: 'index';

        if (!class_exists($controller)) return [];

        return [new $controller, $action];
    }
}
