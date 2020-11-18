<?php

namespace Controllers;

use Kernel\{App, Controller};
use Models\User;

class UserController extends Controller
{    
    /**
     * login
     * GET
     * Страница авторизации
     * @return Response
     */
    public function login()
    {
        return $this->view('user/login');
    }
    
    /**
     * logout
     * GET
     * Выход
     * @return Response
     */
    public function logout()
    {
        App::getInstance()->logout();
        $this->response()->header('Location', '/');
        return $this->response();
    }
    
    /**
     * auth
     * POST 
     * Авторизация
     * @return Response
     */
    public function auth()
    {
        $request = $this->request();

        $login = $request->post('login') ?? '';
        $password = $request->post('password') ?? '';

        $userModel = User::getInstance();
        $user = $userModel->findBy(['login' => $login])[0];

        // Если юзер найден в базе и пароль соответствует - авторизуем
        if ($user) {
            if ($user['password'] === $userModel->getPasswordHash($password)) {
                App::getInstance()->auth($user['login']);
                return $this->json(['success' => true]);
            }
        }

        return $this->json(['success' => false, 'errors' => ['Логин или пароль неверные']]);
    }
}
