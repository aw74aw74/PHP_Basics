<?php

namespace Geekbrains\Application1;

/**
 * Класс-маршрутизатор.
 * Отвечает за разбор URI и определение контроллера и метода для выполнения.
 */
class Router {

    private const APP_NAMESPACE = 'Geekbrains\\Application1\\Controllers\\';

    private string $controllerName;
    private string $methodName;

    /**
     * Конструктор разбирает URI и инициализирует имена контроллера и метода.
     */
    public function __construct()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $path = parse_url($uri, PHP_URL_PATH);
        $routeArray = explode('/', $path);

        if (isset($routeArray[1]) && $routeArray[1] != '') {
            $controllerName = $routeArray[1];
        } else {
            $controllerName = "page";
        }

        $this->controllerName = self::APP_NAMESPACE . ucfirst($controllerName) . "Controller";

        if (isset($routeArray[2]) && $routeArray[2] != '') {
            $methodName = $routeArray[2];
        } else {
            $methodName = "index";
        }

        $this->methodName = "action" . ucfirst($methodName);
    }

    /**
     * Возвращает полное имя класса контроллера.
     * @return string
     */
    public function getControllerName(): string
    {
        return $this->controllerName;
    }

    /**
     * Возвращает имя метода контроллера.
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->methodName;
    }
}
