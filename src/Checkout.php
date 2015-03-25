<?php

    class Checkout
    {
        private $patrons_id;
        private $id;
        private $books_id;
        private $due_date;

        function __construct($patrons_id = null, $id = null, $books_id = null, $due_date = "2015/01/01")
        {
            $this->patrons_id = $patrons_id;
            $this->id = $id;
            $this->books_id = $books_id;
            $this->due_date = $due_date;
        }

        function setPatronsId($new_patrons_id)
        {
            $this->patrons_id = $new_patrons_id;
        }

        function setId($new_id)
        {
            $this->id = $new_id;
        }

        function setBooksId($new_books_id)
        {
            $this->books_id = $new_books_id;
        }

        function setDueDate($new_due_date)
        {
            $this->due_date = $new_due_date;
        }

        function getPatronsId()
        {
            return $this->patrons_id;
        }

        function getId()
        {
            return $this->id;
        }

        function getBooksId()
        {
            return $this->books_id;
        }

        function getDueDate()
        {
            return $this->due_date;
        }

        function save()
        {
            $statement = $GLOBALS['DB']->query("INSERT INTO checkouts (books_id, due_date, patrons_id) VALUES ({$this->getBooksId()}, '{$this->getDueDate()}', {$this->getPatronsId()}) RETURNING id;");
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $this->setId($result['id']);
        }

        static function getAll()
        {
            $all_checkouts = $GLOBALS['DB']->query("SELECT * FROM checkouts;");
            $returned_checkouts = array();
            foreach ($all_checkouts as $checkout){
                $books_id = $checkout['books_id'];
                $id = $checkout['id'];
                $due_date = $checkout['due_date'];
                $patrons_id = $checkout['patrons_id'];
                $new_checkout = new Checkout($books_id, $id, $due_date, $patrons_id);
                array_push ($returned_checkouts, $new_checkout);
            }
            return $returned_checkouts;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM checkouts *;");
        }

        function update($new_due_date)
        {
            $GLOBALS['DB']->exec("UPDATE checkouts SET due_date = '{$new_due_date}' WHERE id = {$this->getId()};");
            $this->setDueDate($new_due_date);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM checkouts WHERE id = ({$this->getId()});");
        }
    }
?>
