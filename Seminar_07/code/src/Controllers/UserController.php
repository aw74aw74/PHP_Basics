<?php

namespace Geekbrains\Application1\Controllers;

use Geekbrains\Application1\Render;
use Geekbrains\Application1\Models\User;
use Geekbrains\Application1\Models\Db;

/**
 * Контроллер для управления пользователями (CRUD).
 */
class UserController extends AbstractController {

    /**
     * Отображает список всех пользователей или сообщение, если их нет.
     * @return string HTML-код страницы.
     */
    public function actionIndex(): string
    {
        $users = User::getAll();

        if(!$users){
            return $this->render->renderPage(
                'user-empty.tpl',
                [
                    'title' => 'Список пользователей',
                    'message' => "Пользователи не найдены",
                    'user' => $this->user
                ]);
        }
        else{
            return $this->render->renderPage(
                'user-index.tpl',
                [
                    'title' => 'Список пользователей',
                    'users' => $users,
                    'user' => $this->user
                ]);
        }
    }

    /**
     * Обрабатывает добавление нового пользователя.
     * При GET-запросе отображает форму добавления.
     * При POST-запросе добавляет пользователя в базу данных и перенаправляет на список.
     * @return string HTML-код страницы или пустая строка при перенаправлении.
     */
    public function actionAdd(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = $this->validateRequestData($_POST);

            if (isset($_POST['name'])) {
                $name = $_POST['name'];
                $birthday = null;

                if (!empty($_POST['birthday'])) {
                    $date = \DateTime::createFromFormat('d-m-Y', $_POST['birthday']);
                    if ($date) {
                        $birthday = $date->format('Y-m-d');
                    }
                }
                User::add($name, $birthday);
            }

            header('Location: /user');
            return "";
        } else {
            return $this->render->renderPage('user-form.tpl', [
                'title' => 'Добавить пользователя',
                'user' => $this->user
            ]);
        }
    }

    /**
     * Отображает форму для редактирования пользователя.
     * @return string HTML-код страницы или пустая строка при перенаправлении.
     */
    public function actionEdit(){
        if (isset($_GET['id'])) {
            $user = User::getById((int)$_GET['id']);

            if($user){
                return $this->render->renderPage('user-form.tpl', [
                    'title' => 'Редактировать пользователя',
                    'user_form' => $user,
                    'user' => $this->user
                ]);
            }
        }

        // Если пользователь не найден или ID не передан, перенаправляем на главную
        header('Location: /user');
        return "";
    }

    /**
     * Обрабатывает обновление данных пользователя.
     * Принимает POST-запрос с данными и обновляет запись в БД.
     * @return string Пустая строка при перенаправлении.
     */
    public function actionUpdate(){
        $_POST = $this->validateRequestData($_POST);

        if (isset($_POST['id'])) {
            $id = (int)$_POST['id'];
            $dataToUpdate = [];

            if (isset($_POST['name'])) {
                $dataToUpdate['name'] = $_POST['name'];
            }

            // Устанавливаем день рождения, даже если пришла пустая строка
            if (array_key_exists('birthday', $_POST)) {
                $birthday = null;
                if (!empty($_POST['birthday'])) {
                    $date = \DateTime::createFromFormat('d-m-Y', $_POST['birthday']);
                    if ($date) {
                        $birthday = $date->format('Y-m-d');
                    }
                }
                $dataToUpdate['birthday'] = $birthday;
            }

            if (!empty($dataToUpdate)) {
                User::update($id, $dataToUpdate);
            }
        }

        header('Location: /user');
        return "";
    }

    /**
     * Обрабатывает удаление пользователя.
     * Принимает POST-запрос с ID пользователя и удаляет его из БД.
     * @return string Пустая строка при перенаправлении.
     */
    public function actionDelete(){
        if (isset($_POST['id'])) {
            User::delete((int)$_POST['id']);
        }

        header('Location: /user');
        return "";
    }

    /**
     * Валидирует входящие данные, удаляя HTML-теги.
     * @param array $data Массив данных для валидации.
     * @return array Очищенный массив данных.
     */
    private function validateRequestData(array $data): array
    {
        $validatedData = [];
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                // Удаляем HTML-теги с помощью регулярного выражения
                $validatedData[$key] = preg_replace('/<[^>]*>/', '', $value);
            } else {
                $validatedData[$key] = $value;
            }
        }
        return $validatedData;
    }
}