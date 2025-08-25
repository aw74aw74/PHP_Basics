<?php

// Устанавливаем обработчик сессий в 'files', чтобы избежать ошибки с memcache
ini_set('session.save_handler', 'files');
// Указываем путь для сохранения сессий. '/tmp' обычно доступен для записи.
ini_set('session.save_path', '/tmp');

session_start();

require_once('./vendor/autoload.php');

use Geekbrains\Application1\Application;
use Geekbrains\Application1\Render;
use Geekbrains\Application1\Models\User;

// Автоматическая авторизация по токену
if (isset($_COOKIE['auth_token']) && !isset($_SESSION['user_name'])) {
    $user = User::getUserByToken($_COOKIE['auth_token']);
    if ($user) {
        $_SESSION['user_name'] = $user->getName();
    }
}

try{
    $app = new Application();
    echo $app->run();
}
catch(\Throwable $e){
    echo Render::renderExceptionPage($e);
}