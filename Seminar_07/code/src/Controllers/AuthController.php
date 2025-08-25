<?php

namespace Geekbrains\Application1\Controllers;

use Geekbrains\Application1\Render;
use Geekbrains\Application1\Models\User;

/**
 * Контроллер для аутентификации пользователей.
 * Отвечает за вход, выход и управление сессиями.
 */
class AuthController extends AbstractController {

    /**
     * Обрабатывает вход пользователя.
     * При GET-запросе отображает форму входа.
     * При POST-запросе проверяет данные, создает сессию и токен (если нужно), затем перенаправляет.
     * @return string HTML-код страницы или пустая строка при перенаправлении.
     */
    public function actionLogin()
    {
        // Если пользователь уже авторизован, перенаправляем на главную
        if ($this->user) {
            header('Location: /');
            return "";
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['username']) && isset($_POST['password'])) {
                $user = User::getByName($_POST['username']);

                // Простая проверка пароля. В реальном приложении используйте password_hash и password_verify.
                if ($user && $_POST['password'] === 'password') { // Предполагаем, что пароль 'password' для всех
                    $_SESSION['user_name'] = $user->getName();

                    if (isset($_POST['remember_me'])) {
                        $token = bin2hex(random_bytes(32));
                        User::addToken($user->getId(), $token);
                        // Устанавливаем cookie с флагами безопасности
                        setcookie('auth_token', $token, [
                            'expires' => time() + (86400 * 30), // 30 дней
                            'path' => '/',
                            // 'secure' => true,   // Включать на продакшене с HTTPS
                            'httponly' => true,    // Защита от XSS
                            'samesite' => 'Lax'    // Защита от CSRF
                        ]);
                    }

                    header('Location: /');
                    return "";
                }
            }
            $error = "Неверное имя пользователя или пароль";
        }

        return $this->render->renderPage('login.tpl', [
            'title' => 'Вход',
            'error' => $error ?? null,
            'user' => $this->user
        ]);
    }

    /**
     * Обрабатывает выход пользователя.
     * Уничтожает сессию, деактивирует токен и удаляет cookie.
     * @return string Пустая строка при перенаправлении.
     */
    public function actionLogout()
    {
        if (isset($_COOKIE['auth_token'])) {
            User::deactivateToken($_COOKIE['auth_token']);
            // Удаляем cookie, используя те же флаги безопасности
            setcookie('auth_token', '', [
                'expires' => time() - 3600,
                'path' => '/',
                // 'secure' => true,   // Включать на продакшене с HTTPS
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
        }

        session_destroy();
        header('Location: /');
        return "";
    }
}