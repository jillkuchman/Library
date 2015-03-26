<?php

    class Copy
    {
        private $books_id;
        private $id;

        function __construct($books_id, $id = null)
        {
            $this->books_id = $books_id;
            $this->id = $id;
        }

        function setBooksId($new_books_id)
        {
            $this->books_id = (int) $new_books_id;
        }

        function getBooksId()
        {
            return $this->books_id;
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
            $statement = $GLOBALS['DB']->query("INSERT INTO copies (books_id) VALUES ('{$this->getBooksId()}') RETURNING id;");
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $this->setId($result['id']);
        }


        static function getAll()
        {
            $all_copies = $GLOBALS['DB']->query("SELECT * FROM copies;");
            $returned_copies = array();
            foreach ($all_copies as $copy){
                $books_id = $copy['books_id'];
                $id = $copy['id'];
                $new_copy = new Copy($books_id, $id);
                array_push ($returned_copies, $new_copy);
            }
            return $returned_copies;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM copies *;");
        }

        function update($new_books_id)
        {
            $GLOBALS['DB']->exec("UPDATE copies SET books_id = '{$new_books_id}' WHERE id = {$this->getId()};");
            $this->setBooksId($new_books_id);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM copies WHERE id = ({$this->getId()});");
        }

        function getPatrons()
        {
            $statement = $GLOBALS['DB']->query("SELECT patrons.* FROM copies
                                            JOIN checkouts ON (copies.id = checkouts.copies_id)
                                            JOIN patrons ON (checkouts.patrons_id = patrons.id)
                                            WHERE copies.id = {$this->getId()};");
            $all_patrons = $statement->fetchAll(PDO::FETCH_ASSOC);
            $patrons = array();
            foreach ($all_patrons as $patron) {
                $p_name = $patron['p_name'];
                $id = $patron['id'];
                $new_patron = new Patron($p_name, $id);
                array_push($patrons, $new_patron);
            }
            return $patrons;
        }

        function addPatron($new_patron)
        {
            $GLOBALS['DB']->exec("INSERT INTO checkouts (copies_id, patrons_id) VALUES ({$this->getId()}, {$new_patron->getId()});");
        }
    }
?>
