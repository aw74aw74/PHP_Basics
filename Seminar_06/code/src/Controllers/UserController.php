<?php

namespace Geekbrains\Application1\Controllers;

use Geekbrains\Application1\Render;
use Geekbrains\Application1\Models\User;
use Geekbrains\Application1\Models\Db;

class UserController {

    public function actionIndex() {
        // Инициализируем соединение с БД, чтобы создалась таблица, если её нет
        Db::getInstance();

        $users = User::getAll();
        
        $render = new Render();

        if(!$users){
            return $render->renderPage(
                'user-empty.tpl', 
                [
                    'title' => 'Список пользователей',
                    'message' => "Пользователи не найдены"
                ]);
        }
        else{
            return $render->renderPage(
                'user-index.tpl', 
                [
                    'title' => 'Список пользователей',
                    'users' => $users
                ]);
        }
    }

    public function actionAdd(){
        if (isset($_GET['name'])) {
            $name = $_GET['name'];
            $birthday = null;

            if (!empty($_GET['birthday'])) {
                $date = \DateTime::createFromFormat('d-m-Y', $_GET['birthday']);
                if ($date) {
                    $birthday = $date->format('Y-m-d');
                }
            }
            User::add($name, $birthday);
        }

        header('Location: /user');
    }

    public function actionEdit(){
        if (isset($_GET['id'])) {
            $user = User::getById((int)$_GET['id']);

            if($user){
                $render = new Render();
                return $render->renderPage('user-edit.tpl', [
                    'title' => 'Редактировать пользователя',
                    'user' => $user
                ]);
            }
        }

        // Если пользователь не найден или ID не передан, перенаправляем на главную
        header('Location: /user');
    }

    public function actionUpdate(){
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
    }

    public function actionDelete(){
        if (isset($_GET['id'])) {
            User::delete((int)$_GET['id']);
        }

        header('Location: /user');
    }
}