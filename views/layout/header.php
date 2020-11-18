<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="//code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="//stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="/js/script.js"></script>
    <link rel="stylesheet" href="/css/style.css">

    <title>Задачник | Unlimint</title>
</head>
<body>
    <section class="container">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <a class="navbar-brand" href="/">Задачник Unlimint</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Задачи</a>
                    </li>
                    <li class="nav-item">
                        <?if (Kernel\App::getInstance()->isAuth()):?>
                            <a class="nav-link" href="/user/logout">Выход (<?=Kernel\App::getInstance()->getLogin()?>)</a>
                        <?else:?>
                            <a class="nav-link" href="/user/login">Вход</a>
                        <?endif;?>
                    </li>
                </ul>
            </div>
        </nav>

        <div id="content">
