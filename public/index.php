<?php

define('PATH_ROOT', dirname(__DIR__));

require PATH_ROOT . '/kernel/AutoLoader.php';
$autoLoader = new AutoLoader(PATH_ROOT);

Kernel\App::getInstance()->init();
