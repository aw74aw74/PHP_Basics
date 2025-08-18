<?php

// Получаем результаты от разных версий PHP
$raw_output82 = file_get_contents('http://nginx/php82/task1_output.php');
$raw_output74 = file_get_contents('http://nginx/php74/task1_output.php');

// Используем preg_split для надежного разделения вывода по строкам
$output82 = preg_split('/\R/', $raw_output82, -1, PREG_SPLIT_NO_EMPTY);
$output74 = preg_split('/\R/', $raw_output74, -1, PREG_SPLIT_NO_EMPTY);

$swap_output = file_get_contents('http://nginx/task3_swap.php');

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Демонстрация заданий по PHP</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; margin: 2em; background-color: #f4f4f4; color: #333; }
        .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1, h2 { color: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #007bff; color: white; }
        pre { background-color: #eee; padding: 10px; border-radius: 4px; white-space: pre-wrap; word-wrap: break-word; }
        .task { margin-bottom: 40px; }
    </style>
</head>
<body>

<div class="container">
    <h1>Демонстрация ДЗ семинара 1</h1>

    <div class="task">
        <h2>Задание 1 и 2: Сравнение вывода PHP 8.2 и PHP 7.4</h2>
        <p>Ниже показан результат выполнения одного и того же кода на разных версиях PHP.</p>
        <table>
            <thead>
                <tr>
                    <th>Код</th>
                    <th>Вывод в PHP 8.2</th>
                    <th>Вывод в PHP 7.4</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><pre><code>$a = 5;
$b = '05';
var_dump($a == $b);</code></pre></td>
                    <td colspan="2"><pre><?php echo $output82[0] ?? 'Ошибка'; ?></pre></td>
                </tr>
                <tr>
                    <td><pre><code>var_dump((int)'012345');</code></pre></td>
                    <td colspan="2"><pre><?php echo $output82[1] ?? 'Ошибка'; ?></pre></td>
                </tr>
                <tr>
                    <td><pre><code>var_dump((float)123.0 === (int)123.0);</code></pre></td>
                    <td colspan="2"><pre><?php echo $output82[2] ?? 'Ошибка'; ?></pre></td>
                </tr>
                <tr>
                    <td><pre><code>var_dump(0 == 'hello, world');</code></pre></td>
                    <td><pre><?php echo $output82[3] ?? 'Ошибка'; ?></pre></td>
                    <td><pre><?php echo $output74[3] ?? 'Ошибка'; ?></pre></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="task">
        <h2>Задание 3: Обмен значений переменных</h2>
        <p>Результат обмена значений двух переменных без использования третьей.</p>
        <pre><?php echo htmlspecialchars($swap_output); ?></pre>
    </div>

</div>

</body>
</html>
