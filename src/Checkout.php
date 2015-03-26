<?php

    class Checkout
    {
        private $patrons_id;
        private $id;
        private $copies_id;
        private $due_date;

        function __construct($copies_id = null, $due_date, $patrons_id = null, $id = null)
        {
            $this->copies_id = $copies_id;
            $this->due_date = $due_date;
            $this->patrons_id = $patrons_id;
            $this->id = $id;
        }

        function setPatronsId($new_patrons_id)
        {
            $this->patrons_id = (int) $new_patrons_id;
        }

        function setId($new_id)
        {
            $this->id = (int) $new_id;
        }

        function setCopiesId($new_copies_id)
        {
            $this->copies_id = (int) $new_copies_id;
        }

        function setDueDate($new_due_date)
        {
            $this->due_date = (string) $new_due_date;
        }

        function getPatronsId()
        {
            return $this->patrons_id;
        }

        function getId()
        {
            return $this->id;
        }

        function getCopiesId()
        {
            return $this->copies_id;
        }

        function getDueDate()
        {
            return $this->due_date;
        }

        function save()
        {
            $statement = $GLOBALS['DB']->query("INSERT INTO checkouts (copies_id, due_date, patrons_id) VALUES ({$this->getCopiesId()}, '{$this->getDueDate()}', {$this->getPatronsId()}) RETURNING id;");
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $this->setId($result['id']);
        }

        static function getAll()
        {
            $all_checkouts = $GLOBALS['DB']->query("SELECT * FROM checkouts;");
            $returned_checkouts = array();
            foreach ($all_checkouts as $checkout){
                $copies_id = $checkout['copies_id'];
                $id = $checkout['id'];
                $due_date = $checkout['due_date'];
                $patrons_id = $checkout['patrons_id'];
                $new_checkout = new Checkout($copies_id, $due_date, $patrons_id, $id);
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
