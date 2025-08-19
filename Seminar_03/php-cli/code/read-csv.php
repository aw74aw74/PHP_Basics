<?php
/**
 * Пример чтения CSV-файла
 */

// Проверяем существование файла
$csvFile = '/code/birthdays.txt';
if (!file_exists($csvFile)) {
    die("Ошибка: Файл $csvFile не найден");
}

// Открываем файл для чтения
$file = fopen($csvFile, "r");
if ($file === false) {
    die("Ошибка открытия файла");
}

// Пропускаем заголовок, если он есть
$headers = fgetcsv($file);

// Читаем и выводим данные
echo "Содержимое CSV-файла:\n";
while (($data = fgetcsv($file)) !== false) {
    // Выводим каждую строку как массив
    print_r($data);
    // Или выводим как строку
    // echo implode(", ", $data) . "\n";
}

// Закрываем файл
fclose($file);
