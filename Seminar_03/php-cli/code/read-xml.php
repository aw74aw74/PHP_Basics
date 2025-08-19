<?php
/**
 * Пример чтения данных из XML-файла
 */

// Проверяем существование файла
$xmlFile = 'birthdays.xml';
if (!file_exists($xmlFile)) {
    die("Ошибка: Файл $xmlFile не найден");
}

// Загружаем XML-файл
$xml = simplexml_load_file($xmlFile);
if ($xml === false) {
    die("Ошибка загрузки XML-файла");
}

// Выводим данные
echo "Список дней рождения:\n";
foreach ($xml->person as $person) {
    echo $person->name . ': ' . $person->birthday . "\n";
}