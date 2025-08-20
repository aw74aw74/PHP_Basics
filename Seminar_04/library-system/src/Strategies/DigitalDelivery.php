<?php

namespace LibrarySystem\Strategies;

use LibrarySystem\Interfaces\BookDeliveryStrategy;

/**
 * Стратегия цифровой доставки книги
 * 
 * Предоставляет функционал для доступа к цифровой версии книги
 * через ссылку для скачивания.
 */
class DigitalDelivery implements BookDeliveryStrategy {
    /**
     * @var string Ссылка для скачивания цифровой версии книги
     */
    private string $downloadLink;

    /**
     * Конструктор стратегии цифровой доставки
     * 
     * @param string $downloadLink Ссылка для скачивания книги
     */
    public function __construct(string $downloadLink) {
        $this->downloadLink = $downloadLink;
    }

    /**
     * Возвращает информацию о способе получения цифровой книги
     * 
     * @return string Сообщение с ссылкой для скачивания
     */
    public function deliver(): string {
        return "Ссылка для скачивания: {$this->downloadLink}";
    }
}
