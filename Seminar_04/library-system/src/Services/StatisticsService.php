<?php

namespace LibrarySystem\Services;

use LibrarySystem\Interfaces\ReadObserver;

/**
 * Сервис сбора статистики по прочтениям книг
 * 
 * Отслеживает и сохраняет количество прочтений для каждой книги.
 * Реализует интерфейс ReadObserver для получения уведомлений о прочтениях.
 */
class StatisticsService implements ReadObserver {
    /**
     * @var array Ассоциативный массив, где ключ - название книги, 
     *            значение - количество прочтений
     */
    private array $readStats = [];

    /**
     * Обработчик события прочтения книги
     * 
     * @param string $bookTitle Название прочитанной книги
     * @return void
     */
    public function onBookRead(string $bookTitle): void {
        if (!isset($this->readStats[$bookTitle])) {
            $this->readStats[$bookTitle] = 0;
        }
        $this->readStats[$bookTitle]++;
        echo "Обновлена статистика: '{$bookTitle}' прочитана {$this->readStats[$bookTitle]} раз(а)\n";
    }

    /**
     * Возвращает текущую статистику прочтений
     * 
     * @return array Ассоциативный массив с данными о прочтениях
     */
    public function getStats(): array {
        return $this->readStats;
    }
}
