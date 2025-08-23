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
}