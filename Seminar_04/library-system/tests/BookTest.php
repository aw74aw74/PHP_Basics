<?php

use PHPUnit\Framework\TestCase;
use LibrarySystem\Factories\BookFactory;

class BookTest extends TestCase {
    private BookFactory $bookFactory;

    protected function setUp(): void {
        $this->bookFactory = BookFactory::getInstance();
    }

    public function testDigitalBookCreation() {
        $book = $this->bookFactory->createDigitalBook(
            "Тестовая книга", 
            "Тестовый автор", 
            2023,
            "http://test.com/book"
        );
        
        $this->assertEquals("Тестовая книга", $book->getTitle());
        $this->assertEquals("Тестовый автор", $book->getAuthor());
        $this->assertEquals(2023, $book->getYear());
    }

    public function testPhysicalBookCreation() {
        $book = $this->bookFactory->createPhysicalBook(
            "Бумажная книга", 
            "Автор", 
            2022,
            "ул. Тестовая, 1", 
            "B2"
        );
        
        $this->assertEquals("Бумажная книга", $book->getTitle());
        $this->assertEquals("Автор", $book->getAuthor());
        $this->assertEquals(2022, $book->getYear());
    }
}
