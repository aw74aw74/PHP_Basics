<?php

namespace Geekbrains\Application1\Models;

/**
 * Модель пользователя
 */
class User
{
    private ?int $id;
    private string $name;
    private ?string $birthday;

    /**
     * Конструктор модели пользователя.
     * @param string $name Имя пользователя.
     * @param string|null $birthday Дата рождения в формате Y-m-d.
     * @param int|null $id ID пользователя.
     */
    public function __construct(string $name, ?string $birthday, ?int $id = null)
    {
        $this->name = $name;
        $this->birthday = $birthday;
        $this->id = $id;
    }

    /**
     * Получает ID пользователя.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Получает имя пользователя.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Получает дату рождения пользователя.
     * @return string|null
     */
    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    /**
     * Получает всех пользователей из базы данных.
     * @return array|null Массив объектов User или null, если пользователей нет.
     */
    public static function getAll(): ?array
    {
        $db = Db::getInstance();
        $data = $db->query("SELECT * FROM users");

        if (!$data) {
            return null;
        }

        $users = [];
        foreach ($data as $row) {
            $users[] = new self($row['name'], $row['birthday'], $row['id']);
        }

        return $users;
    }

    /**
     * Получает пользователя по ID.
     * @param int $id ID пользователя.
     * @return User|null Объект User или null, если пользователь не найден.
     */
    public static function getById(int $id): ?User
    {
        $db = Db::getInstance();
        $data = $db->query("SELECT * FROM users WHERE id = :id", ['id' => $id]);

        if (!$data) {
            return null;
        }

        return new self($data[0]['name'], $data[0]['birthday'], $data[0]['id']);
    }

    /**
     * Получает пользователя по имени.
     * @param string $name Имя пользователя.
     * @return User|null Объект User или null, если пользователь не найден.
     */
    public static function getByName(string $name): ?User
    {
        $db = Db::getInstance();
        $data = $db->query("SELECT * FROM users WHERE name = :name", ['name' => $name]);

        if (!$data) {
            return null;
        }

        return new self($data[0]['name'], $data[0]['birthday'], $data[0]['id']);
    }

    /**
     * Добавляет нового пользователя.
     * @param string $name Имя пользователя.
     * @param string|null $birthday Дата рождения.
     * @return bool Результат выполнения операции.
     */
    public static function add(string $name, ?string $birthday): bool
    {
        $db = Db::getInstance();
        $sql = "INSERT INTO users (name, birthday) VALUES (:name, :birthday)";
        return $db->execute($sql, ['name' => $name, 'birthday' => $birthday]);
    }

    /**
     * Обновляет данные пользователя.
     * @param int $id ID пользователя.
     * @param array $data Ассоциативный массив с новыми данными ('name' => 'новое имя').
     * @return bool Результат выполнения операции.
     */
    public static function update(int $id, array $data): bool
    {
        if (empty($data)) {
            return false;
        }

        $db = Db::getInstance();
        $params = [];
        $setParts = [];

        foreach ($data as $key => $value) {
            if ($key === 'name' || $key === 'birthday') {
                $setParts[] = "`$key` = :$key";
                $params[$key] = $value;
            }
        }

        if (empty($setParts)) {
            return false;
        }

        $sql = "UPDATE users SET " . implode(', ', $setParts) . " WHERE id = :id";
        $params['id'] = $id;

        return $db->execute($sql, $params);
    }

    /**
     * Удаляет пользователя по ID.
     * @param int $id ID пользователя.
     * @return bool Результат выполнения операции.
     */
    public static function delete(int $id): bool
    {
        $db = Db::getInstance();
        $sql = "DELETE FROM users WHERE id = :id";
        return $db->execute($sql, ['id' => $id]);
    }

    /**
     * Добавляет токен для пользователя.
     * @param int $userId ID пользователя.
     * @param string $token Токен.
     * @return bool
     */
    public static function addToken(int $userId, string $token): bool
    {
        $db = Db::getInstance();
        $sql = "INSERT INTO user_tokens (user_id, token) VALUES (:user_id, :token)";
        return $db->execute($sql, ['user_id' => $userId, 'token' => $token]);
    }

    /**
     * Деактивирует токен.
     * @param string $token Токен.
     * @return bool
     */
    public static function deactivateToken(string $token): bool
    {
        $db = Db::getInstance();
        $sql = "UPDATE user_tokens SET is_active = FALSE WHERE token = :token";
        return $db->execute($sql, ['token' => $token]);
    }

    /**
     * Получает пользователя по токену.
     * @param string $token Токен.
     * @return User|null
     */
    public static function getUserByToken(string $token): ?User
    {
        $db = Db::getInstance();
        $data = $db->query(
            "SELECT u.* FROM users u JOIN user_tokens ut ON u.id = ut.user_id WHERE ut.token = :token AND ut.is_active = TRUE",
            ['token' => $token]
        );

        if (!$data) {
            return null;
        }

        return new self($data[0]['name'], $data[0]['birthday'], $data[0]['id']);
    }
}