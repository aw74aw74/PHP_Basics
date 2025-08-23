<?php

namespace Geekbrains\Application1\Models;

use PDO;
use PDOException;

/**
 * Класс для работы с базой данных (реализация Singleton).
 */
class Db
{
    private static ?Db $_instance = null;
    private PDO $_connection;

    private const DB_HOST = 'mysql';
    private const DB_NAME = 'geekbrains';
    private const DB_USER = 'root';
    private const DB_PASSWORD = 'root';

    /**
     * Конструктор класса.
     * Устанавливает соединение с базой данных и проверяет/создает таблицу пользователей.
     * @throws PDOException если не удалось подключиться к базе данных.
     */
    private function __construct()
    {
        try {
            $this->_connection = new PDO(
                'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME,
                self::DB_USER,
                self::DB_PASSWORD
            );
            $this->checkAndCreateTable();
        } catch (PDOException $e) {
            // В реальном приложении здесь должно быть логирование ошибки
            throw $e;
        }
    }

    /**
     * Получает единственный экземпляр класса Db (Singleton).
     * @return Db
     */
    public static function getInstance(): Db
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Выполняет SQL-запрос без возврата результата (INSERT, UPDATE, DELETE).
     * @param string $sql SQL-запрос с плейсхолдерами.
     * @param array $params Параметры для подготовленного запроса.
     * @return bool true в случае успеха, false в случае ошибки.
     */
    public function execute(string $sql, array $params = []): bool
    {
        $stmt = $this->_connection->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Выполняет SQL-запрос и возвращает массив результатов.
     * @param string $sql SQL-запрос с плейсхолдерами.
     * @param array $params Параметры для подготовленного запроса.
     * @return array|null Массив с результатами или null в случае ошибки.
     */
    public function query(string $sql, array $params = []): ?array
    {
        $stmt = $this->_connection->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Проверяет наличие таблицы 'users' и, если она отсутствует, создает её и заполняет данными.
     */
    private function checkAndCreateTable(): void
    {
        $result = $this->query("SHOW TABLES LIKE 'users'");

        if (!empty($result)) {
            return;
        }

        $sql = "
            CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                birthday DATE
            );
        ";
        $this->execute($sql);

        $this->populateUsersFromTxt();
    }

    /**
     * Заполняет таблицу 'users' данными из файла birthdays.txt.
     */
    private function populateUsersFromTxt(): void
    {
        $filePath = __DIR__ . '/../../storage/birthdays.txt';
        if (!file_exists($filePath)) {
            return;
        }

        $content = file_get_contents($filePath);
        // Используем регулярное выражение для поиска всех совпадений "Имя, дата"
        preg_match_all('/([\p{L}\s]+),\s*(\d{2}-\d{2}-\d{4})/u', $content, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $name = trim($match[1]);
            $birthday = \DateTime::createFromFormat('d-m-Y', $match[2]);

            if ($name && $birthday) {
                $sql = "INSERT INTO users (name, birthday) VALUES (:name, :birthday)";
                $this->execute($sql, [
                    'name' => $name,
                    'birthday' => $birthday->format('Y-m-d')
                ]);
            }
        }
    }
}
