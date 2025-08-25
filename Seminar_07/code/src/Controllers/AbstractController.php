<?php

namespace Geekbrains\Application1\Controllers;

use Geekbrains\Application1\Render;
use Geekbrains\Application1\Models\User;

/**
 * Абстрактный базовый контроллер.
 * Содержит общую логику для всех контроллеров приложения.
 */
abstract class AbstractController
{
    protected Render $render;
    protected ?User $user = null;

    /**
     * Конструктор.
     * Инициализирует рендерер и проверяет аутентификацию пользователя.
     */
    public function __construct()
    {
        $this->render = new Render();

        // Проверяем, авторизован ли пользователь через сессию или cookie
        if (isset($_SESSION['user_name'])) {
            $this->user = User::getByName($_SESSION['user_name']);
        }
        elseif (isset($_COOKIE['auth_token'])) {
            $user = User::getUserByToken($_COOKIE['auth_token']);
            if ($user) {
                $this->user = $user;
                $_SESSION['user_name'] = $user->getName();
            }
        }
    }
}
