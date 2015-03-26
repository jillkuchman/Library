<?php

    class Patron
    {
        private $p_name;
        private $id;

        function __construct($p_name, $id = null)
        {
            $this->p_name = $p_name;
            $this->id = $id;
        }

        function setName($new_p_name)
        {
            $this->p_name = (string) $new_p_name;
        }

        function getName()
        {
            return $this->p_name;
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
            $statement = $GLOBALS['DB']->query("INSERT INTO patrons (p_name) VALUES ('{$this->getName()}') RETURNING id;");
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $this->setId($result['id']);
        }

        static function getAll()
        {
            $all_patrons = $GLOBALS['DB']->query("SELECT * FROM patrons;");
            $returned_patrons = array();
            foreach ($all_patrons as $patron){
                $p_name = $patron['p_name'];
                $id = $patron['id'];
                $new_patron = new Patron($p_name, $id);
                array_push ($returned_patrons, $new_patron);
            }
            return $returned_patrons;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM patrons *;");
        }

        static function find($search_id)
        {
            $all_patrons = Patron::getAll();
            $found_patron = null;
            foreach($all_patrons as $patron){
                if ($patron->getId() == $search_id){
                    $found_patron = $patron;
                }
            }
            return $found_patron;
        }

        function update($new_p_name)
        {
            $GLOBALS['DB']->exec("UPDATE patrons SET p_name = '{$new_p_name}' WHERE id = {$this->getId()};");
            $this->setName($new_p_name);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM patrons WHERE id = ({$this->getId()});");
        }

        function getCopies()
        {
            $statement = $GLOBALS['DB']->query("SELECT copies.* FROM patrons
                                            JOIN checkouts ON (patrons.id = checkouts.patrons_id)
                                            JOIN copies ON  (checkouts.copies_id = copies.id)
                                            WHERE patrons.id = {$this->getId()};");
            $all_copies = $statement->fetchAll(PDO::FETCH_ASSOC);
            $copies = array();
            foreach($all_copies as $copy){
                $id = $copy['id'];
                $books_id = $copy['books_id'];
                $new_copy = new Copy($books_id, $id);
                array_push($copies, $new_copy);
            }
            return $copies;
        }

        function addCopy($new_copy)
        {
            $GLOBALS['DB']->exec("INSERT INTO checkouts (copies_id, patrons_id) VALUES ({$new_copy->getId()},{$this->getId()});");
        }
    }
?>
