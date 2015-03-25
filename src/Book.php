<?php

class Book
{
    private $title;
    private $id;

    function __construct($title, $id = null)
    {
        $this->title = $title;
        $this->id = $id;
    }

    function setTitle($new_title)
    {
        $this->title = (string) $new_title;
    }

    function getTitle()
    {
        return $this->title;
    }

    function setId($new_id)
    {
        $this->id = (int) $new_id;
    }

    function getId()
    {
        return $this->id;
    }

    function save()
    {
        $statement = $GLOBALS['DB']->query("INSERT INTO books (title) VALUES ('{$this->getTitle()}') RETURNING id;");
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $this->setId($result['id']);
    }

    static function getAll()
    {
        $all_books = $GLOBALS['DB']->query("SELECT*FROM books");
        $returned_books = array();

        foreach($all_books as $book){
            $title = $book['title'];
            $id = $book['id'];
            $new_book = new Book($title, $id);
            array_push($returned_books, $new_book);
        }
        return $returned_books;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM books *;");
    }

    static function find($search_id)
    {
        $all_books = Book::getAll();
        $found_book = null;
        foreach($all_books as $book){
            if ($book->getId() == $search_id){
                $found_book = $book;
            }
        }
        return $found_book;
    }

    function delete()
    {
        $GLOBALS['DB']->exec("DELETE FROM books WHERE  id = {$this->getId()};");
    }

    function update($new_title)
    {
        $GLOBALS['DB']->exec("UPDATE books SET title = '{$new_title}' WHERE id = {$this->getId()};");
        $this->setTitle($new_title);
    }
}
?>
