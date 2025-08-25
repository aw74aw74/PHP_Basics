<?php

namespace Geekbrains\Application1\Controllers;
use Geekbrains\Application1\Render;

/**
 * Контроллер для отображения основных страниц сайта.
 */
class PageController extends AbstractController {

    /**
     * Отображает главную страницу.
     * @return string HTML-код страницы.
     */
    public function actionIndex(): string
    {
        return $this->render->renderPage('page-index.tpl', [
            'title' => 'Главная страница',
            'user' => $this->user
        ]);
    }
}