<?php
/**
 * Файл, содержащий функции для работы с файловым хранилищем
 */

/**
 * Читает и возвращает содержимое файла с данными
 * 
 * @param array $config Массив конфигурации, должен содержать ключ 'storage' с адресом файла
 * @return string Содержимое файла или сообщение об ошибке
 * @throws Exception Если файл не существует или недоступен для чтения
 */
function readAllFunction(array $config) : string {
    $address = $config['storage']['address'];
    
    if (file_exists($address) && is_readable($address)) {
        $file = fopen($address, "rb");
        
        $contents = ''; 
    
        while (!feof($file)) {
            $contents .= fread($file, 100);
        }
        
        fclose($file);
        return $contents;
    }
    else {
        return handleError("Файл не существует");
    }
}

/**
 * Добавляет новую запись в файл
 * 
 * Запрашивает у пользователя имя и дату рождения, проверяет их корректность
 * и добавляет запись в конец файла.
 * 
 * @param array $config Массив конфигурации с адресом файла для записи
 * @return string Сообщение о результате операции
 * @see isValidName() Проверка корректности имени
 * @see promptForBirthDate() Запрос и валидация даты рождения
 * @see isDuplicateEntry() Проверка на дубликаты
 */
function addFunction(array $config) : string {
    $address = $config['storage']['address'];

    $name = promptForName();
    $date = promptForBirthDate($address, $name);

    $data = $name . ", " . $date . "\r\n";

    // Дополнительная проверка на дубликат на случай, если данные изменились
    if (isDuplicateEntry($name, $date, $address)) {
        return handleError("Ошибка: запись с такими данными уже существует");
    }

    if (file_put_contents($address, $data, FILE_APPEND | LOCK_EX) !== false) {
        return "Запись успешно добавлена в файл.\n";
    }

    return handleError("Произошла ошибка записи. Данные не сохранены");
}

/**
 * Проверяет, является ли имя валидным.
 * Допустимы только буквы, пробелы и дефисы.
 *
 * @param string $name Имя для проверки
 * @return bool True если имя валидно, иначе false
 */
function isValidName(string $name): bool
{
    return preg_match('/^[\p{L} -]+$/u', $name) === 1;
}

/**
 * Проверяет, является ли дата валидной и реалистичной.
 *
 * @param string $dateStr Дата в формате 'ДД-ММ-ГГГГ'.
 * @return bool True, если дата корректна, иначе false.
 */
function isValidDate(string $dateStr): bool
{
    // Проверяем формат даты
    if (!preg_match('/^\d{2}-\d{2}-\d{4}$/', $dateStr)) {
        return false;
    }

    $dateParts = explode('-', $dateStr);
    if (count($dateParts) !== 3) {
        return false;
    }

    $day = (int)$dateParts[0];
    $month = (int)$dateParts[1];
    $year = (int)$dateParts[2];

    // Проверяем корректность даты
    if (!checkdate($month, $day, $year)) {
        return false;
    }

    // Проверяем, что дата не в будущем
    $today = new DateTime();
    $birthDate = DateTime::createFromFormat('d-m-Y', $dateStr);
    
    if ($birthDate > $today) {
        return false;
    }

    // Проверяем, что год рождения не слишком старый (например, до 1900)
    if ($year < 1900) {
        return false;
    }

    return true;
}

/**
 * Проверяет, существует ли уже запись с таким именем и датой.
 *
 * @param string $name Имя для проверки
 * @param string $date Дата для проверки
 * @param string $address Путь к файлу с данными
 * @return bool True если запись уже существует, иначе false
 */
function isDuplicateEntry(string $name, string $date, string $address): bool
{
    if (!file_exists($address) || !is_readable($address)) {
        return false;
    }

    $file = fopen($address, 'r');
    if (!$file) {
        return false;
    }

    $searchString = "$name, $date\r\n";
    
    while (!feof($file)) {
        $line = fgets($file);
        if ($line === $searchString) {
            fclose($file);
            return true;
        }
    }

    fclose($file);
    return false;
}

/**
 * Запрашивает у пользователя ввод имени и проверяет его корректность.
 *
 * @return string Введенное имя.
 */
function promptForName(): string
{
    while (true) {
        $name = trim(readline("Введите имя: "));
        
        if (empty($name)) {
            echo handleError("Имя не может быть пустым. Попробуйте еще раз.");
            continue;
        }
        
        if (!isValidName($name)) {
            echo handleError("Имя может содержать только буквы, пробелы и дефисы. Попробуйте еще раз.");
            continue;
        }
        
        return $name;
    }
}

/**
 * Запрашивает у пользователя дату рождения и проверяет её корректность.
 *
 * @param string $address Путь к файлу с данными (для проверки дубликатов)
 * @param string $currentName Текущее имя (для проверки дубликатов, если редактируется запись)
 * @return string Корректная дата рождения.
 */
function promptForBirthDate(string $address, string $currentName = ''): string
{
    while (true) {
        $date = trim(readline("Введите дату рождения в формате ДД-ММ-ГГГГ: "));
        
        if (!isValidDate($date)) {
            echo handleError("Некорректная дата. Убедитесь, что дата существует, не в будущем и год не раньше 1900. Попробуйте еще раз.");
            continue;
        }
        
        // Если имя уже введено, проверяем на дубликат
        if (!empty($currentName) && isDuplicateEntry($currentName, $date, $address)) {
            echo handleError("Запись с таким именем и датой уже существует. Попробуйте снова.");
            continue;
        }
        
        return $date;
    }
}

/**
 * Запрашивает у пользователя дату рождения и проверяет её корректность.
 *
 * @return string Корректная дата рождения.
 */
function promptForBirthDateWithoutCheck(string $address): string
{
    while (true) {
        $date = trim(readline("Введите дату рождения в формате ДД-ММ-ГГГГ: "));
        
        if (!isValidDate($date)) {
            echo handleError("Некорректная дата. Убедитесь, что дата существует, не в будущем и год не раньше 1900. Попробуйте еще раз.");
            continue;
        }
        
        return $date;
    }
}

/**
 * Очищает файл с данными.
 * 
 * @param array $config Массив конфигурации с адресом файла для очистки
 * @return string Сообщение о результате операции
 */
function clearFunction(array $config) : string {
    $address = $config['storage']['address'];

    if (file_exists($address) && is_readable($address)) {
        $file = fopen($address, "w");
        
        fwrite($file, '');
        
        fclose($file);
        return "Файл очищен";
    }
    else {
        return handleError("Файл не существует");
    }
}

/**
 * Выводит справку по командам.
 * 
 * @return string Текст справки
 */
function helpFunction() {
    return handleHelp();
}

/**
 * Читает конфигурацию из файла.
 * 
 * @param string $configAddress Путь к файлу конфигурации
 * @return array|false Массив конфигурации или false в случае ошибки
 */
function readConfig(string $configAddress): array|false{
    return parse_ini_file($configAddress, true);
}

/**
 * Выводит список профилей в директории.
 * 
 * @param array $config Массив конфигурации с адресом директории профилей
 * @return string Список профилей или сообщение об ошибке
 */
function readProfilesDirectory(array $config): string {
    $profilesDirectoryAddress = $config['profiles']['address'];

    if(!is_dir($profilesDirectoryAddress)){
        mkdir($profilesDirectoryAddress);
    }

    $files = scandir($profilesDirectoryAddress);

    $result = "";

    if(count($files) > 2){
        foreach($files as $file){
            if(in_array($file, ['.', '..']))
                continue;
            
            $result .= $file . "\r\n";
        }
    }
    else {
        $result .= "Директория пуста \r\n";
    }

    return $result;
}

/**
 * Выводит информацию о профиле.
 * 
 * @param array $config Массив конфигурации с адресом директории профилей
 * @return string Информация о профиле или сообщение об ошибке
 */
function readProfile(array $config): string {
    $profilesDirectoryAddress = $config['profiles']['address'];

    if(!isset($_SERVER['argv'][2])){
        return handleError("Не указан файл профиля");
    }

    $profileFileName = $profilesDirectoryAddress . $_SERVER['argv'][2] . ".json";

    if(!file_exists($profileFileName)){
        return handleError("Файл $profileFileName не существует");
    }

    $contentJson = file_get_contents($profileFileName);
    $contentArray = json_decode($contentJson, true);

    $info = "Имя: " . $contentArray['name'] . "\r\n";
    $info .= "Фамилия: " . $contentArray['lastname'] . "\r\n";

    return $info;
}

/**
 * Ищет пользователей с днем рождения сегодня и выводит их имена.
 *
 * @param array $config Конфигурация приложения.
 * @return string Результат поиска.
 */
function findBirthdaysFunction(array $config): string
{
    $address = $config['storage']['address'];

    if (!file_exists($address) || !is_readable($address)) {
        return handleError("Файл с данными не найден или недоступен для чтения.");
    }

    $lines = file($address, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return handleError("Не удалось прочитать файл.");
    }

    $currentDate = date('d-m');
    $foundUsers = [];

    foreach ($lines as $line) {
        $parts = explode(',', $line);
        if (count($parts) < 2) {
            continue;
        }

        $name = trim($parts[0]);
        $birthDateStr = trim($parts[1]);

        if (substr($birthDateStr, 0, 5) === $currentDate) {
            $foundUsers[] = $name;
        }
    }

    if (empty($foundUsers)) {
        return "Сегодня ни у кого из пользователей нет дня рождения.\n";
    }

    $result = "🎉 Сегодня день рождения у следующих пользователей:\n";
    foreach ($foundUsers as $user) {
        $result .= "- $user\n";
    }
    return $result;
}

/**
 * Удаляет запись из файла данных.
 *
 * @param array $config Конфигурация приложения.
 * @return string Результат операции.
 */
function deleteFunction(array $config): string
{
    $address = $config['storage']['address'];

    if (!file_exists($address) || !is_readable($address)) {
        return handleError("Файл с данными не найден или недоступен.");
    }

    $searchTerm = readline("Введите имя или дату для удаления: ");
    if (empty(trim($searchTerm))) {
        return handleError("Поисковый запрос не может быть пустым.");
    }

    $lines = file($address, FILE_IGNORE_NEW_LINES);
    if ($lines === false) {
        return handleError("Не удалось прочитать файл.");
    }

    $foundLines = [];
    foreach ($lines as $index => $line) {
        if (str_contains(strtolower($line), strtolower($searchTerm))) {
            $foundLines[$index] = $line;
        }
    }

    if (empty($foundLines)) {
        return "Записи, соответствующие запросу '$searchTerm', не найдены.\n";
    }

    if (count($foundLines) === 1) {
        $indexToDelete = key($foundLines);
        unset($lines[$indexToDelete]);
        $line = current($foundLines);
    } else {
        echo "Найдено несколько записей:\n";
        $i = 1;
        foreach ($foundLines as $line) {
            echo "[$i] $line\n";
            $i++;
        }

        $choice = (int)readline("Введите номер строки для удаления (или 0 для отмены): ");

        if ($choice === 0 || $choice > count($foundLines)) {
            return "Удаление отменено.\n";
        }

        $keys = array_keys($foundLines);
        $indexToDelete = $keys[$choice - 1];
        $line = $lines[$indexToDelete];
        unset($lines[$indexToDelete]);
    }

    if (file_put_contents($address, implode("\r\n", $lines) . "\r\n", LOCK_EX) !== false) {
        return "Запись '$line' была успешно удалена.\n";
    }

    return handleError("Произошла ошибка при удалении записи.");
}

/**
 * Редактирует существующую запись в файле.
 *
 * @param array $config Конфигурация приложения.
 * @return string Результат операции.
 */
function editFunction(array $config): string
{
    $address = $config['storage']['address'];

    if (!file_exists($address) || !is_readable($address)) {
        return handleError("Файл с данными не найден или недоступен.");
    }

    $searchTerm = readline("Введите имя или дату для редактирования: ");
    if (empty(trim($searchTerm))) {
        return handleError("Поисковый запрос не может быть пустым.");
    }

    $lines = file($address, FILE_IGNORE_NEW_LINES);
    if ($lines === false) {
        return handleError("Не удалось прочитать файл.");
    }

    $foundLines = [];
    foreach ($lines as $index => $line) {
        if (str_contains(strtolower($line), strtolower($searchTerm))) {
            $foundLines[$index] = $line;
        }
    }

    if (empty($foundLines)) {
        return "Записи, соответствующие запросу '$searchTerm', не найдены.\n";
    }

    if (count($foundLines) === 1) {
        $indexToEdit = key($foundLines);
    } else {
        echo "Найдено несколько записей:\n";
        $i = 1;
        foreach ($foundLines as $line) {
            echo "[$i] $line\n";
            $i++;
        }

        $choice = (int)readline("Введите номер строки для редактирования (или 0 для отмены): ");

        if ($choice === 0 || $choice > count($foundLines)) {
            return "Редактирование отменено.\n";
        }

        $keys = array_keys($foundLines);
        $indexToEdit = $keys[$choice - 1];
    }

    echo "Вы редактируете запись: '" . $lines[$indexToEdit] . "'\n";
    $newName = promptForName();
    $newDate = promptForBirthDate($address, $newName);
    $newLine = $newName . ", " . $newDate;

    $lines[$indexToEdit] = $newLine;

    if (file_put_contents($address, implode("\r\n", $lines) . "\r\n", LOCK_EX) !== false) {
        return "Запись успешно отредактирована.\n";
    }

    return handleError("Произошла ошибка при редактировании записи.");
}