<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Render;
use Geekbrains\Application1\Domain\Models\User;

class UserController {

    public function actionIndex(): string {
        $users = User::getAllUsersFromStorage();
        
        $render = new Render();

        if(!$users){
            return $render->renderPage(
                'user-empty.tpl', 
                [
                    'title' => 'Список пользователей в хранилище',
                    'message' => "Список пуст или не найден"
                ]);
        }
        else{
            return $render->renderPage(
                'user-index.tpl', 
                [
                    'title' => 'Список пользователей в хранилище',
                    'users' => $users
                ]);
        }
    }

    public function actionCreate(): string
    {
        $render = new Render();
        return $render->renderPage('user-create.tpl', [
            'title' => 'Создание нового пользователя',
        ]);
    }

    public function actionSave(): string {
        if(User::validateRequestData()) {
            $user = new User();
            $user->setParamsFromRequestData();
            $user->saveToStorage();

            $render = new Render();

            return $render->renderPage(
                'user-created.tpl', 
                [
                    'title' => 'Пользователь создан',
                    'message' => "Создан пользователь " . $user->getUserName() . " " . $user->getUserLastName()
                ]);
        }
        else {
            throw new \Exception("Переданные данные некорректны");
        }
    }

    public function actionUpdate(): string {
        if(!isset($_GET['id'])){
            throw new \Exception("Не передан id пользователя");
        }

        $userId = $_GET['id'];

        if(User::exists($userId)) {
            $user = new User();
            $user->setUserId($userId);

            $arrayData = [];

            if(isset($_GET['name'])){
                $arrayData['user_name'] = $_GET['name'];
            }

            if(isset($_GET['lastname'])) {
                $arrayData['user_lastname'] = $_GET['lastname'];
            }

            if(isset($_GET['birthday'])){
                $time = strtotime($_GET['birthday']);
                if($time){
                    $arrayData['user_birthday_timestamp'] = $time;
                }
            }

            if(!empty($arrayData)){
                $user->updateUser($arrayData);
            }

            $render = new Render();
            return $render->renderPage(
                'user-updated.tpl',
                [
                    'title' => 'Пользователь обновлен',
                    'message' => "Обновлен пользователь id=" . $user->getUserId()
                ]);
        }
        else {
            throw new \Exception("Пользователь не существует");
        }
    }

    public function actionDelete(): string {
        if(!isset($_GET['id'])){
            throw new \Exception("Не передан id пользователя");
        }

        $userId = $_GET['id'];

        if(User::exists($userId)) {
            $user = User::getById($userId);
            User::deleteFromStorage($userId);

            $render = new Render();
            
            return $render->renderPage(
                'user-removed.tpl', [
                    'title' => 'Пользователь удален',
                    'user' => $user
                ]
            );
        }
        else {
            throw new \Exception("Пользователь не существует");
        }
    }

    public function actionEdit(): string
    {
        if (!isset($_GET['id'])) {
            throw new \Exception('Не передан id пользователя');
        }

        $userId = $_GET['id'];
        $user = User::getById($userId);

        if (!$user) {
            throw new \Exception('Пользователь не найден');
        }

        $render = new Render();
        return $render->renderPage('user-edit.tpl', [
            'title' => 'Редактирование пользователя',
            'user' => $user,
        ]);
    }
}