<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    $DB = new PDO('pgsql:host=localhost;dbname=library_test');

    require_once "src/Patron.php";

    class PatronTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Patron::deleteAll();
        }

        function test_getName()
        {
            //Arrange
            $p_name = "Natasha Korolenko";
            $id = 3;
            $test_patron = new Patron($p_name, $id);

            //Act
            $result = $test_patron->getName();

            //Assert
            $this->assertEquals($p_name, $result);
        }

        function test_setName()
        {
            //Arrange
            $p_name = "Andy";
            $id = 3;
            $test_patron = new Patron($p_name, $id);
            $new_p_name = "Andy Dwyer";

            //Act
            $test_patron->setName($new_p_name);

            //Assert
            $result = $test_patron->getName();
            $this->assertEquals($new_p_name, $result);
        }

        function test_getId()
        {
            //Arrange
            $p_name = "Steve Jobs";
            $id = 4;
            $test_patron = new Patron($p_name, $id);

            //Act
            $result = $test_patron->getId();

            //Assert
            $this->assertEquals($id, $result);
        }

        function test_setId()
        {
            //Arrange
            $p_name = "Reid Baldwin";
            $id = null;
            $test_patron = new Patron($p_name, $id);

            //Act
            $test_patron->setId(5);

            //Assert
            $result = $test_patron->getId();
            $this->assertEquals(5, $result);
        }

        function test_save()
        {
            //Arrange
            $p_name = "Sarah Connor";
            $test_patron = new Patron($p_name);
            $test_patron->save();

            //Act
            $result = Patron::getAll();

            //Arrange
            $this->assertEquals([$test_patron], $result);
        }

        function test_getAll()
        {
            //Arrange
            $p_name = "Sarah Connor";
            $p_name2 = "John Connor";
            $test_patron = new Patron($p_name);
            $test_patron2 = new Patron($p_name2);
            $test_patron->save();
            $test_patron2->save();

            //Act
            $result = Patron::getAll();

            //Assert
            $this->assertEquals([$test_patron, $test_patron2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $p_name = "Sarah Connor";
            $p_name2 = "John Connor";
            $test_patron = new Patron($p_name);
            $test_patron2 = new Patron($p_name2);
            $test_patron->save();
            $test_patron2->save();

            //Act
            Patron::deleteAll();

            //Assert
            $result = Patron::getAll();
            $this->assertEquals([], $result);
        }

        function test_update()
        {
            $p_name = "Sarah Connor";
            $test_patron = new Patron($p_name);
            $test_patron->save();
            $new_p_name = "John Connor";

            //Act
            $test_patron->update($new_p_name);

            //Assert
            $result = $test_patron->getName();
            $this->assertEquals($new_p_name, $result);
        }

        function test_delete()
        {
            //Arrange
            $p_name = "Sarah Connor";
            $p_name2 = "John Connor";
            $test_patron = new Patron($p_name);
            $test_patron2 = new Patron($p_name2);
            $test_patron->save();
            $test_patron2->save();

            //Act
            $test_patron->delete();

            //Assert
            $result = Patron::getAll();
            $this->assertEquals([$test_patron2], $result);

        }

        function test_getCopies()
        {
            //Arrange
            $p_name = "Timmy McGibblets";
            $new_patron = new Patron($p_name);
            $new_patron->save();

            $books_id = 34;
            $new_copy = new Copy($books_id);
            $new_copy->save();

            $books_id2 = 234;
            $new_copy2 = new Copy($books_id2);
            $new_copy2->save();

            //Act
            $new_patron->addCopy($new_copy);
            $new_patron->addCopy($new_copy2);

            //Assert
            $result = $new_patron->getCopies();
            $this->assertEquals([$new_copy, $new_copy2], $result);
        }

        function test_addCopy()
        {
            //Arrange
            $p_name = "Timmy McGibblets";
            $new_patron = new Patron($p_name);
            $new_patron->save();

            $books_id = 34;
            $new_copy = new Copy($books_id);
            $new_copy->save();

            //Act
            $new_patron->addCopy($new_copy);

            //Assert
            $result = $new_patron->getCopies();
            $this->assertEquals([$new_copy], $result);
        }




    }
?>
