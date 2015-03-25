<?php

    class Copy
    {
        private $cat_number;
        private $id;

        function __construct($cat_number, $id = null)
        {
            $this->cat_number = $cat_number;
            $this->id = $id;
        }

        function setCatNumber($new_cat_number)
        {
            $this->cat_number = (int) $new_cat_number;
        }

        function getCatNumber()
        {
            return $this->cat_number;
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
            $statement = $GLOBALS['DB']->query("INSERT INTO copies (cat_number) VALUES ('{$this->getCatNumber()}') RETURNING id;");
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $this->setId($result['id']);
        }

        static function getAll()
        {
            $all_copies = $GLOBALS['DB']->query("SELECT * FROM copies;");
            $returned_copies = array();
            foreach ($all_copies as $copy){
                $cat_number = $copy['cat_number'];
                $id = $copy['id'];
                $new_copy = new Copy($cat_number, $id);
                array_push ($returned_copies, $new_copy);
            }
            return $returned_copies;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM copies *;");
        }

        function update($new_cat_number)
        {
            $GLOBALS['DB']->exec("UPDATE copies SET cat_number = '{$new_cat_number}' WHERE id = {$this->getId()};");
            $this->setCatNumber($new_cat_number);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM copies WHERE id = ({$this->getId()});");
        }

        function getBook()
        {
            $statement = $GLOBALS['DB']->query("SELECT books.* FROM copies
                                            JOIN books_copies ON (copies.id = books_copies.copies_id)
                                            JOIN books ON (books_copies.books_id = books.id)
                                        WHERE copies.id = {$this->getId()};");
            $book_id = $statement->fetchAll(PDO::FETCH_ASSOC);
            $books = array();
            foreach($book_id as $book){
                $title = $book['title'];
                $id = $book['id'];
                $new_book = new Book($title, $id);
                array_push($books, $new_book);
            }
            return $books;
        }

        function addBook($book)
        {
            $GLOBALS['DB']->exec("INSERT INTO books_copies (copies_id, books_id) VALUES ({$this->getId()}, {$book->getId()});");
        }
    }
?>
