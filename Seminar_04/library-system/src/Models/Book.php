<?php

namespace LibrarySystem\Models;

use LibrarySystem\Interfaces\BookDeliveryStrategy;
use LibrarySystem\Interfaces\ReadObserver;

/**
 * Абстрактный класс, представляющий книгу в библиотечной системе
 * 
 * Содержит общую логику для всех типов книг и определяет интерфейс
 * для работы с наблюдателями за событиями чтения.
 */
abstract class Book {
    /** @var string Название книги */
    protected string $title;
    
    /** @var string Автор книги */
    protected string $author;
    
    /** @var int Год издания */
    protected int $year;
    
    /** @var BookDeliveryStrategy Стратегия доставки книги */
    protected BookDeliveryStrategy $deliveryStrategy;
    
    /** @var ReadObserver[] Массив наблюдателей за событиями чтения */
    protected array $observers = [];

    /**
     * Конструктор книги
     * 
     * @param string $title Название книги
     * @param string $author Автор книги
     * @param int $year Год издания
     * @param BookDeliveryStrategy $deliveryStrategy Стратегия доставки книги
     */
    public function __construct(
        string $title, 
        string $author, 
        int $year,
        BookDeliveryStrategy $deliveryStrategy
    ) {
        $this->title = $title;
        $this->author = $author;
        $this->year = $year;
        $this->deliveryStrategy = $deliveryStrategy;
    }

    /**
     * Получить книгу в соответствии с выбранной стратегией доставки
     * 
     * @return string Информация о способе получения книги
     */
    public function getBook(): string {
        $this->notifyObservers();
        return $this->deliveryStrategy->deliver();
    }

    /**
     * Присоединяет наблюдателя к книге
     * 
     * @param ReadObserver $observer Наблюдатель за событиями чтения
     * @return void
     */
    public function attach(ReadObserver $observer): void {
        $this->observers[] = $observer;
    }

    /**
     * Уведомляет всех наблюдателей о событии чтения книги
     * 
     * @return void
     */
    protected function notifyObservers(): void {
        foreach ($this->observers as $observer) {
            $observer->onBookRead($this->title);
        }
    }

    /**
     * Получить название книги
     * 
     * @return string Название книги
     */
    public function getTitle(): string { 
        return $this->title; 
    }
    
    /**
     * Получить автора книги
     * 
     * @return string Автор книги
     */
    public function getAuthor(): string { 
        return $this->author; 
    }
    
    /**
     * Получить год издания книги
     * 
     * @return int Год издания
     */
    public function getYear(): int { 
        return $this->year; 
    }
}
