<?php

    class Author
    {
        private $name;
        private $id;

        function __construct($name, $id = null)
        {
            $this->name = $name;
            $this->id = $id;
        }

        function setName($new_name)
        {
            $this->name = (string) $new_name;
        }

        function getName()
        {
            return $this->name;
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
            $statement = $GLOBALS['DB']->query("INSERT INTO authors (name) VALUES ('{$this->getName()}') RETURNING id;");
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $this->setId($result['id']);
        }

        static function getAll()
        {
            $all_authors = $GLOBALS['DB']->query("SELECT * FROM authors;");
            $returned_authors = array();
            foreach ($all_authors as $author){
                $name = $author['name'];
                $id = $author['id'];
                $new_author = new Author($name, $id);
                array_push ($returned_authors, $new_author);
            }
            return $returned_authors;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM authors *;");
        }

        function update($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE authors SET name = '{$new_name}' WHERE id = {$this->getId()};");
            $this->setName($new_name);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM authors WHERE id = ({$this->getId()});");
        }

        function getBooks()
        {
            $statement = $GLOBALS['DB']->query("SELECT books.* FROM authors
                                            JOIN authors_books ON (authors.id = authors_books.authors_id)
                                            JOIN books ON (authors_books.books_id = books.id)
                                        WHERE authors.id = {$this->getId()};");
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
            $GLOBALS['DB']->exec("INSERT INTO authors_books (authors_id, books_id) VALUES ({$this->getId()}, {$book->getId()});");
        }

    }


?>
