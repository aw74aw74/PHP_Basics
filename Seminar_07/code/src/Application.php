<?php

namespace Geekbrains\Application1;

use Geekbrains\Application1\Render;
use Geekbrains\Application1\Router;

/**
 * Основной класс приложения.
 * Отвечает за запуск и обработку запросов.
 */
class Application {

    /**
     * Запускает приложение.
     * Определяет контроллер и метод на основе URI, выполняет их и возвращает результат.
     * @return string HTML-код страницы.
     */
    public function run() : string {
        $router = new Router();
        $controllerName = $router->getControllerName();
        $methodName = $router->getMethodName();

        if(class_exists($controllerName)){
            // пытаемся вызвать метод
            if(method_exists($controllerName, $methodName)){
                $controllerInstance = new $controllerName();
                return call_user_func_array(
                    [$controllerInstance, $methodName],
                    []
                );
            }
            else {
                // Возвращаем HTTP-ответ 404 и страницу ошибки
                return $this->renderErrorPage();
            }
        }
        else{
            // Возвращаем HTTP-ответ 404 и страницу ошибки
            return $this->renderErrorPage();
        }
    }

    /**
     * Формирует страницу с ошибкой 404
     *
     * @return string
     */
    public function renderErrorPage(): string
    {
        // Устанавливаем HTTP-код 404
        header("HTTP/1.1 404 Not Found");

        $render = new Render();
        return $render->renderPage('error-404.tpl', ['title' => 'Ошибка 404']);
    }
}