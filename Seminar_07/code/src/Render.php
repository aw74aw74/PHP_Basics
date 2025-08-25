<?php

namespace Geekbrains\Application1;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Render {

    private string $viewFolder = '/src/Views/';
    private FilesystemLoader $loader;
    private Environment $environment;


    public function __construct(){
        $this->loader = new FilesystemLoader($_SERVER['DOCUMENT_ROOT'] . $this->viewFolder);
        $this->environment = new Environment($this->loader, [
            //'cache' => $_SERVER['DOCUMENT_ROOT'].'/cache/',
            'cache' => false,
        ]);
    }

    public function renderPage(string $contentTemplateName = 'page-index.tpl', array $templateVariables = []) {
        $template = $this->environment->load('main.tpl');
        
        $templateVariables['content_template_name'] = $contentTemplateName;

        // Добавляем информацию о пользователе, если он авторизован
        if (isset($_SESSION['user_name'])) {
            $templateVariables['user_name'] = $_SESSION['user_name'];
        }
 
        return $template->render($templateVariables);
    }

    /**
     * Отображает страницу с ошибкой.
     * @param \Throwable $e Объект исключения.
     * @return string
     */
    public static function renderExceptionPage(\Throwable $e): string
    {
        $loader = new FilesystemLoader($_SERVER['DOCUMENT_ROOT'] . '/src/Views/');
        $environment = new Environment($loader, ['cache' => false]);
        $template = $environment->load('main.tpl');

        return $template->render([
            'content_template_name' => 'page-exception.tpl',
            'title' => 'Произошла ошибка',
            'exception_message' => $e->getMessage(),
            'exception_trace' => $e->getTraceAsString()
        ]);
    }
}