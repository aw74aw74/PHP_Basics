<?php

namespace LibrarySystem\Interfaces;

/**
 * Интерфейс наблюдателя за событиями чтения книг
 * 
 * Позволяет отслеживать события, связанные с чтением книг,
 * такие как получение книги читателем.
 */
interface ReadObserver {
    /**
     * Вызывается при каждом прочтении книги
     * 
     * @param string $bookTitle Название прочитанной книги
     * @return void
     */
    public function onBookRead(string $bookTitle): void;
}
