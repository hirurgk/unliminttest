<?php

namespace Kernel;

abstract class Controller
{
    private $request;
    private $response;

    public function __construct()
    {
        $this->request = Request::getInstance();
        $this->response = Response::getInstance();
    }
    
    /**
     * request
     * @return Request
     */
    public function request()
    {
        return $this->request;
    }
    
    /**
     * response
     * @return Response
     */
    public function response()
    {
        return $this->response;
    }
    
    /**
     * view
     * Возвращает Response с контентом из представления
     * @param string $name 
     * @param array $data данные, передаваемые в представление
     * @return Response
     */
    public function view($name, $data = [])
    {
        // Получим контент из представлений в переменную
        ob_start();
            include PATH_ROOT . "/views/layout/header.php";
            include PATH_ROOT . "/views/{$name}.php";
            include PATH_ROOT . "/views/layout/footer.php";

            $content = ob_get_contents();
        ob_end_clean();

        $this->response()->content($content);

        return $this->response();
    }
    
    /**
     * json
     * Возвращает Response с JSON
     * @param array $json
     * @return Response
     */
    public function json($json = [])
    {
        $this->response()->header('Content-Type', 'application/json');
        $this->response()->content(json_encode($json));

        return $this->response();
    }
}
