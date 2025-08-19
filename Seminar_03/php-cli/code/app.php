<?php
/**
 * Главный файл приложения для работы с файловым хранилищем
 */

// подключение файлов логики
// require_once('src/main.function.php');
// require_once('src/template.function.php');
// require_once('src/file.function.php');

// Подключение автозагрузчика Composer
require_once('vendor/autoload.php');

// Вызов корневой функции приложения с передачей пути к конфигурационному файлу
$result = main("/code/config.ini");

// Вывод результата выполнения
if ($result !== null) {
    echo $result;
}
