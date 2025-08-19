<?php
/**
 * Основные функции приложения для работы с файловым хранилищем
 * 
 * @category CLI_Application
 * @package  BirthdayReminder
 * @author   Ваше имя
 * @version  1.0.0
 * @license  MIT
 */

/**
 * Главная функция приложения
 * 
 * Инициализирует приложение, загружает конфигурацию и вызывает соответствующую команду
 * 
 * @param string $configFileAddress Путь к файлу конфигурации
 * @return string Результат выполнения команды
 * @throws Exception Если не удалось загрузить конфигурацию
 */
function main(string $configFileAddress) : string {
    $config = readConfig($configFileAddress);

    if(!$config){
        return handleError("Невозможно подключить файл настроек");
    }

    $functionName = parseCommand();

    if(function_exists($functionName)) {
        $result = $functionName($config);
    }
    else {
        $result = handleError("Вызываемая функция не существует");
    }

    return $result;
}

/**
 * Разбирает аргументы командной строки и возвращает имя соответствующей функции
 * 
 * @return string Имя функции для выполнения команды
 */
function parseCommand() : string {
    // По умолчанию показываем справку
    $functionName = 'helpFunction';
    
    if(isset($_SERVER['argv'][1])) {
        $functionName = match($_SERVER['argv'][1]) {
            'read-all' => 'readAllFunction',        // Просмотр всех записей
            'add' => 'addFunction',                // Добавление новой записи
            'clear' => 'clearFunction',            // Очистка файла с записями
            'delete' => 'deleteFunction',          // Удаление записи
            'edit' => 'editFunction',              // Редактирование записи
            'find-birthdays' => 'findBirthdaysFunction', // Поиск именинников
            'read-profiles' => 'readProfilesDirectory', // Просмотр директории профилей
            'read-profile' => 'readProfile',       // Чтение профиля
            'help' => 'helpFunction',              // Справка
            default => 'helpFunction'               // По умолчанию показываем справку
        };
    }

    return $functionName;
}