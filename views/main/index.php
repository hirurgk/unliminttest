<div class="col-sm-12">
    <h1>Задачи</h1>

    <button type="button" class="btn btn-new-task btn-primary float-right">Создать задачу</button>

    <!-- Форма новой задачи -->
    <div class="col-sm-12 new-task-form">
        <form id="create-task" action="/main/create" method="POST" enctype="multipart/form-data">
            <div class="errors"></div>
            <div class="success"></div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Имя</span>
                </div>
                <input type="text" name="username" class="form-control" placeholder="введите имя" aria-label="введите имя" aria-describedby="basic-addon1">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon2">E-Mail</span>
                </div>
                <input type="text" name="email" class="form-control" placeholder="введите E-Mail" aria-label="введите E-Mail" aria-describedby="basic-addon2">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Картинка</span>
                </div>
                <div class="custom-file">
                    <input type="file" name="image" accept=".jpg,.jpeg,.gif,.png" class="custom-file-input" id="inputGroupFile01">
                    <label class="custom-file-label" for="inputGroupFile01">выберите картинку</label>
                </div>
            </div>

            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Описание задачи</span>
                </div>
                <textarea class="form-control" name="description" aria-label="Описание задачи"></textarea>
            </div>

            <button type="button" class="btn btn-preview-task btn-secondary">Предпросмотр</button>
            <button type="submit" class="btn btn-create-task btn-primary">Создать</button>
        </form>
    </div>

    <div class="tasks-container">
        <div class="tasks-wrap">
            <div class="sort">
                Сортировать по:
                <a class="<?=$data['sort'] === 'username' ? 'active' : ''?>" href="?page=<?=$data['currentPage']?>&sort=username">имя</a> /
                <a class="<?=$data['sort'] === 'email' ? 'active' : ''?>" href="?page=<?=$data['currentPage']?>&sort=email">e-mail</a> /
                <a class="<?=$data['sort'] === 'done' ? 'active' : ''?>" href="?page=<?=$data['currentPage']?>&sort=done">статус</a>
            </div>
            <table class="table table-tasks table-hover table-pointer">
                <tbody>
                    <!-- Предпросмотр задачи -->
                    <tr class="task-preview">
                        <td>
                            <ul>
                                <li><b>Имя:</b> <span class="username"></span></li>
                                <li><b>E-Mail:</b> <span class="email"></span></li>
                            </ul>
                            <img src="" alt="">
                        </td>
                        <td><span class="description"></span></td>
                    </tr>

                    <!-- Список задач -->
                    <?foreach ($data['tasks'] as $task):?>
                        <tr data-id="<?=$task['id']?>" class="<?=$task['done'] ? 'done' : ''?>">
                            <td>
                                <ul>
                                    <li><b>Имя:</b> <?=$task['username']?></li>
                                    <li><b>E-Mail:</b> <?=$task['email']?></li>
                                    <li class="done <?=!$task['done'] ? 'hidden' : ''?>">Выполнено</li>
                                    <?if (!$task['done'] && Kernel\App::getInstance()->isAuth()):?>
                                        <li><button type="button" class="btn btn-done btn-primary">Выполнить</button></li>
                                    <?endif;?>
                                </ul>
                                <img src="/upload/<?=$task['image']?>" alt="">
                            </td>
                            <td>
                                <div class="task-description">
                                    <?=$task['description']?>
                                </div>
                                <?if (Kernel\App::getInstance()->isAuth()):?>
                                    <div class="task-description-update">
                                        <textarea rows="7" class="form-control" name="description" aria-label="Описание задачи"><?=$task['description']?></textarea>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-update-task btn-primary">Изменить</button>
                                    </div>
                                <?endif;?>
                            </td>
                        </tr>
                    <?endforeach;?>
                </tbody>
            </table>

            <!-- Пагинация -->
            <ul class="pagination justify-content-center">
                <?for ($i = 1; $i <= $data['countPages']; $i++):?>  
                    <li class="page-item <?=$i === $data['currentPage'] ? 'active' : ''?>">
                        <a class="page-link" href="?page=<?=$i?>&sort=<?=$data['sort']?>"><?=$i?></a>
                    </li>
                <?endfor;?>
            </ul>
        </div>
    </div>
</div>
