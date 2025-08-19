<?php
/**
 * Функции для работы с шаблонами и вывода информации
 */

/**
 * Обрабатывает и форматирует сообщение об ошибке
 * 
 * @param string $errorText Текст ошибки
 * @return string Отформатированное сообщение об ошибке (красный цвет текста)
 */
function handleError(string $errorText) : string {
    return "\033[31m" . $errorText . " \r\n \033[97m";
}

/**
 * Генерирует текст справки по использованию приложения
 * 
 * @return string Текст справки с описанием доступных команд
 */
function handleHelp() : string {
    $help = "Программа работы с файловым хранилищем \r\n\r\n";
    $help .= "Использование:\r\n";
    $help .= "  php /code/app.php [КОМАНДА]\r\n\r\n";
    
    $help .= "Доступные команды:\r\n";
    $help .= "  read-all           - Просмотр всех записей из файла\r\n";
    $help .= "  add                - Добавление новой записи\r\n";
    $help .= "  clear              - Очистка файла с записями\r\n";
    $help .= "  delete             - Удаление записи по имени или дате\r\n";
    $help .= "  edit               - Редактирование существующей записи\r\n";
    $help .= "  find-birthdays     - Поиск именинников на сегодняшний день\r\n";
    $help .= "  read-profiles      - Просмотр списка профилей пользователей\r\n";
    $help .= "  read-profile       - Просмотр профиля выбранного пользователя\r\n";
    $help .= "  help               - Вывод этой справки\r\n\r\n";
    $help .= "Примеры использования:\r\n";
    $help .= "  php /code/app.php read-all\r\n";
    $help .= "  php /code/app.php add\r\n";
    $help .= "  php /code/app.php delete\r\n";

    return $help;
}