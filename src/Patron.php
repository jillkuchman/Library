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

    }
?>
