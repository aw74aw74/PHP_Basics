<?php

// Задача 1 и 2: Арифметические операции
echo "<h1>Задачи 1 и 2: Арифметические операции</h1>";

/**
 * Выполняет одну из четырех основных арифметических операций.
 *
 * @param float|int $arg1 Первое число.
 * @param float|int $arg2 Второе число.
 * @param string    $operation Операция ('+', '-', '*', '/').
 * @return float|int|string Результат операции или сообщение об ошибке.
 */
function mathOperation($arg1, $arg2, $operation)
{
    switch ($operation) {
        case '+':
            return $arg1 + $arg2;
        case '-':
            return $arg1 - $arg2;
        case '*':
            return $arg1 * $arg2;
        case '/':
            if ($arg2 == 0) {
                return "Ошибка: деление на ноль!";
            }
            return $arg1 / $arg2;
        default:
            return "Неизвестная операция";
    }
}

echo '15 + 5 = ' . mathOperation(15, 5, '+') . "<br>";
echo '15 - 5 = ' . mathOperation(15, 5, '-') . "<br>";
echo '15 * 5 = ' . mathOperation(15, 5, '*') . "<br>";
echo '15 / 5 = ' . mathOperation(15, 5, '/') . "<br>";
echo '15 / 0 = ' . mathOperation(15, 0, '/') . "<br>";

// Задача 3: Массив городов
echo "<h1>Задача 3: Массив городов</h1>";

$regions = [
    'Московская область' => ['Москва', 'Зеленоград', 'Клин'],
    'Ленинградская область' => ['Санкт-Петербург', 'Всеволожск', 'Павловск', 'Кронштадт'],
    'Рязанская область' => ['Рязань', 'Касимов', 'Скопин']
];

foreach ($regions as $region => $cities) {
    echo $region . ": " . implode(', ', $cities) . ".<br>";
}

// Задача 4: Транслитерация
echo "<h1>Задача 4: Транслитерация</h1>";

/**
 * Транслитерирует русскую строку в латиницу.
 *
 * @param string $text Исходный текст на русском.
 * @return string Транслитерированный текст.
 */
function transliterate($text)
{
    $map = [
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
        'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
        'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
        'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
        'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'ts', 'ч' => 'ch',
        'ш' => 'sh', 'щ' => 'shch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
        'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
    ];

    // Приводим строку к нижнему регистру для корректной замены
    $textLower = mb_strtolower($text);
    $result = '';
    $length = mb_strlen($text);

    for ($i = 0; $i < $length; $i++) {
        $char = mb_substr($textLower, $i, 1);
        // Проверяем, была ли исходная буква заглавной
        $originalChar = mb_substr($text, $i, 1);
        $isUpperCase = ($originalChar !== $char);

        if (isset($map[$char])) {
            $translitChar = $map[$char];
            // Если исходная буква была заглавной, делаем заглавной и первую букву транслита
            $result .= $isUpperCase ? ucfirst($translitChar) : $translitChar;
        } else {
            // Если символа нет в карте, оставляем его как есть
            $result .= $originalChar;
        }
    }
    return $result;
}

$testString = "Привет, Мир! Как дела?";
echo "Исходная строка: {$testString}<br>";
echo "Результат: " . transliterate($testString) . "<br>";

// Задача 5: Возведение в степень (рекурсия)
echo "<h1>Задача 5: Возведение в степень (рекурсия)</h1>";

/**
 * Возводит число в степень с помощью рекурсии.
 *
 * @param int|float $val Число.
 * @param int $pow Степень (целое, неотрицательное число).
 * @return int|float|string Результат или сообщение об ошибке.
 */
function power($val, $pow)
{
    if ($pow < 0) {
        return "Степень не может быть отрицательной";
    }
    if ($pow == 0) {
        return 1;
    }
    return $val * power($val, $pow - 1);
}

echo '2 в степени 5 = ' . power(2, 5) . "<br>";
echo '10 в степени 3 = ' . power(10, 3) . "<br>";

// Задача 6: Форматирование времени
echo "<h1>Задача 6: Форматирование времени</h1>";

/**
 * Возвращает строку с правильным склонением для числительного.
 *
 * @param int $number Число.
 * @param array $forms Массив из трех форм слова (например, ['час', 'часа', 'часов']).
 * @return string
 */
function declineWord($number, $forms)
{
    $cases = [2, 0, 1, 1, 1, 2];
    return $forms[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
}

/**
 * Возвращает текущее время в виде отформатированной строки.
 *
 * @return string
 */
function getCurrentTimeFormatted()
{
    $hours = (int)date('H');
    $minutes = (int)date('i');

    $hourWord = declineWord($hours, ['час', 'часа', 'часов']);
    $minuteWord = declineWord($minutes, ['минута', 'минуты', 'минут']);

    return "{$hours} {$hourWord} {$minutes} {$minuteWord}";
}

echo "Текущее время: " . getCurrentTimeFormatted() . "<br>";

?>