<?php

namespace LibrarySystem\Strategies;

use LibrarySystem\Interfaces\BookDeliveryStrategy;

/**
 * Стратегия физической доставки книги
 * 
 * Предоставляет информацию о местоположении физической копии книги
 * в библиотеке, включая адрес и номер полки.
 */
class PhysicalDelivery implements BookDeliveryStrategy {
    /**
     * @var string Адрес библиотеки, где находится книга
     */
    private string $libraryAddress;
    
    /**
     * @var string Номер или обозначение полки, где находится книга
     */
    private string $shelf;

    /**
     * Конструктор стратегии физической доставки
     * 
     * @param string $libraryAddress Адрес библиотеки
     * @param string $shelf Номер или обозначение полки
     */
    public function __construct(string $libraryAddress, string $shelf) {
        $this->libraryAddress = $libraryAddress;
        $this->shelf = $shelf;
    }

    /**
     * Возвращает информацию о местоположении физической книги
     * 
     * @return string Сообщение с адресом библиотеки и номером полки
     */
    public function deliver(): string {
        return "Книга доступна в библиотеке по адресу: {$this->libraryAddress}, полка: {$this->shelf}";
    }
}
