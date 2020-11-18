<?php

namespace Kernel;

class App extends Singleton
{    
    /**
     * router
     * @var Router
     */
    private $router;

    public function __construct()
    {
        session_start();
        $this->router = Router::getInstance();
    }
    
    /**
     * init
     * Инициализация приложения
     * @return void
     */
    public function init()
    {
        $response = $this->router->route();
        $response->print();
    }

    /**
     * auth
     * Авторизация
     * @param string $login
     * @return void
     */
    public function auth($login)
    {
        $_SESSION['auth'] = true;
        $_SESSION['login'] = $login;
    }
    
    /**
     * logout
     * Выход
     * @return void
     */
    public function logout()
    {
        $_SESSION['auth'] = false;
        $_SESSION['login'] = '';
    }
    
    /**
     * isAuth
     * Проверка, авторизован ли юзер
     * @return bool
     */
    public function isAuth()
    {
        return $_SESSION['auth'] ?? false;
    }
    
    /**
     * getLogin
     * Возвращает логин юзера
     * @return string
     */
    public function getLogin()
    {
        return $_SESSION['login'] ?? '';
    }
}
