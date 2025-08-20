<?php

namespace LibrarySystem\Factories;

use LibrarySystem\Models\Book;
use LibrarySystem\Services\StatisticsService;
use LibrarySystem\Strategies\DigitalDelivery;
use LibrarySystem\Strategies\PhysicalDelivery;

/**
 * Фабрика для создания книг с разными стратегиями доставки
 * 
 * Позволяет создавать как цифровые, так и физические книги.
 */
class BookFactory {
    /**
     * @var BookFactory|null Единственный экземпляр фабрики
     */
    private static ?BookFactory $instance = null;
    
    /**
     * @var StatisticsService Сервис сбора статистики по прочтениям
     */
    private StatisticsService $statisticsService;

    /**
     * Приватный конструктор для предотвращения прямого создания экземпляра
     */
    private function __construct() {
        $this->statisticsService = new StatisticsService();
    }

    /**
     * Возвращает единственный экземпляр фабрики
     * 
     * @return BookFactory Экземпляр фабрики книг
     */
    public static function getInstance(): BookFactory {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Создает цифровую книгу
     * 
     * @param string $title Название книги
     * @param string $author Автор книги
     * @param int $year Год издания
     * @param string $downloadLink Ссылка для скачивания книги
     * @return Book Созданный экземпляр цифровой книги
     */
    public function createDigitalBook(
        string $title, 
        string $author, 
        int $year,
        string $downloadLink
    ): Book {
        $book = new class($title, $author, $year, new DigitalDelivery($downloadLink)) extends Book {};
        $book->attach($this->statisticsService);
        return $book;
    }

    /**
     * Создает физическую книгу
     * 
     * @param string $title Название книги
     * @param string $author Автор книги
     * @param int $year Год издания
     * @param string $libraryAddress Адрес библиотеки
     * @param string $shelf Номер или обозначение полки
     * @return Book Созданный экземпляр физической книги
     */
    public function createPhysicalBook(
        string $title, 
        string $author, 
        int $year,
        string $libraryAddress,
        string $shelf
    ): Book {
        $book = new class($title, $author, $year, new PhysicalDelivery($libraryAddress, $shelf)) extends Book {};
        $book->attach($this->statisticsService);
        return $book;
    }

    /**
     * Возвращает статистику по прочтениям книг
     * 
     * @return array Ассоциативный массив с данными о прочтениях
     */
    public function getStatistics(): array {
        return $this->statisticsService->getStats();
    }
}
