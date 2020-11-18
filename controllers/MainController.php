<?php

namespace Controllers;

use Kernel\{App, Controller};
use Services\Image;
use Models\Task;

class MainController extends Controller
{    
    /**
     * index
     * GET
     * Вывод списка задач
     * @return Response
     */
    public function index()
    {
        $request = $this->request();

        $task = Task::getInstance();

        // Количество записей и страниц
        $countTasks = $task->count();
        $countPages = ceil($countTasks / Task::COUNT_ON_PAGE);

        // Вычисляем текущую страницу и порядковую запись в таблице
        $page = $request->get('page');
        $currentPage = 1;
        if (
            is_numeric($page) &&
            $page > 0 &&
            $page <= $countPages
        ) {
            $currentPage = (int) $page;
        }
        $from = ($currentPage - 1) * Task::COUNT_ON_PAGE;

        // Сортировка
        $sort = 'username';
        $order = 'ASC';
        if ($request->get('sort') === 'email')$sort = 'email';
        if ($request->get('sort') === 'done') {
            $sort = 'done';
            $order = 'DESC';
        }

        $tasks = $task->findAll($sort, $order, $from, Task::COUNT_ON_PAGE);

        return $this->view('main/index', [
            'tasks' => $tasks,
            'currentPage' => $currentPage,
            'countPages' => $countPages,
            'sort' => $sort,
        ]);
    }
    
    /**
     * create
     * POST
     * Создание и предпросмотр задачи
     * @return Response
     */
    public function create()
    {
        $request = $this->request();

        // Параметры запроса
        $username = $request->post('username') ?? '';
        $email = $request->post('email') ?? '';
        $file = $request->file('image') ?? [];
        $description = htmlspecialchars($request->post('description')) ?? '';
        $preview = $request->post('preview') ? true : false;

        // Валидация
        $errors = [];
        if (!preg_match("/^[0-9a-zа-я\s\-]+$/iu", $username)) {
            $errors[] = 'Имя некорректное';
        }
        if (!preg_match("/^[a-z0-9\-\.]+\@[a-z0-9\-\.]+\.[a-z]+$/i", $email)) {
            $errors[] = 'E-Mail некорректный';
        }
        if (empty($file['tmp_name'])) {
            $errors[] = 'Картинка некорректная';
        }
        if (empty($description)) {
            $errors[] = 'Описание некорректное';
        }
        if (count($errors)) {
            return $this->json([
                'success' => false,
                'errors' => $errors
            ]);
        }

        // Обработка изображения
        $image = new Image($file['tmp_name']);
        if ($image->over(Task::IMAGE_MAX_WIDTH, Task::IMAGE_MAX_HEIGHT)) {
            $image->resize(Task::IMAGE_MAX_WIDTH, Task::IMAGE_MAX_HEIGHT);
        }

        // Наполняем модель
        $task = new Task;
        $task->username = $username;
        $task->email = $email;
        $task->description = $description;

        // Предпросмотр
        if ($preview) {
            return $this->json([
                'success' => true,
                'username' => $task->username,
                'email' => $task->email,
                'description' => $task->description,
                'image' => $image->getBase64()
            ]);
        }

        // Сохраняем изображение и модель
        $imageName = $image->save();
        $task->image = $imageName;
        $saved = $task->save();

        return $this->json([
            'success' => $saved,
            'message' => 'Задача успешно добавлена'
        ]);
    }
    
    /**
     * update
     * POST
     * Обновление задачи
     * @return Response
     */
    public function update()
    {
        // Запрещаем неавторизованным юзерам
        if (!App::getInstance()->isAuth()) return $this->response();

        $request = $this->request();

        // Параметры запроса
        $id = $request->post('id') ?? 0;
        $done = $request->post('done') ?? 0;
        $description = htmlspecialchars($request->post('description')) ?? '';

        // Валидация
        if (!preg_match("/^[0-9]+$/", $id)) return $this->response();
        if ($done) $done = true;

        // Наполняем модель
        $task = new Task;
        $task->id = $id;
        if ($description) $task->description = $description;
        if ($done) $task->done = $done;

        $saved = $task->save();

        return $this->json([
            'success' => $saved,
            'task' => $task->findBy(['id' => $id])[0]
        ]);
    }
    
    /**
     * notFound
     * GET
     * Страница не найдена
     * @return Response
     */
    public function notFound()
    {
        $this->response()->status(404, "Not Found");
        return $this->view('main/404');
    }
}
