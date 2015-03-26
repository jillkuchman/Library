<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    $DB = new PDO('pgsql:host=localhost;dbname=library_test');

    require_once "src/Copy.php";

    class CopyTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Copy::deleteAll();
        }

        function test_getBooksId()
        {
            //Arrange
            $books_id = 1;
            $id = 3;
            $test_copy = new Copy($books_id, $id);

            //Act
            $result = $test_copy->getBooksId();

            //Assert
            $this->assertEquals($books_id, $result);
        }

        function test_setBooksId()
        {
            //Arrange
            $books_id = 1;
            $id = 3;
            $test_copy = new Copy($books_id, $id);
            $new_books_id = 2;

            //Act
            $test_copy->setBooksId($new_books_id);

            //Assert
            $result = $test_copy->getBooksId();
            $this->assertEquals($new_books_id, $result);
        }

        function test_getId()
        {
            //Arrange
            $books_id = 1;
            $id = 4;
            $test_copy = new Copy($books_id, $id);

            //Act
            $result = $test_copy->getId();

            //Assert
            $this->assertEquals($id, $result);
        }

        function test_setId()
        {
            //Arrange
            $books_id = 1;
            $id = null;
            $test_copy = new Copy($books_id, $id);

            //Act
            $test_copy->setId(5);

            //Assert
            $result = $test_copy->getId();
            $this->assertEquals(5, $result);
        }

        function test_save()
        {
            //Arrange
            $books_id = 1;
            $test_copy = new Copy($books_id);
            $test_copy->save();

            //Act
            $result = Copy::getAll();

            //Arrange
            $this->assertEquals([$test_copy], $result);
        }

        function test_getAll()
        {
            //Arrange
            $books_id = 1;
            $books_id2 = 2;
            $test_copy = new Copy($books_id);
            $test_copy2 = new Copy($books_id2);
            $test_copy->save();
            $test_copy2->save();

            //Act
            $result = Copy::getAll();

            //Assert
            $this->assertEquals([$test_copy, $test_copy2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $books_id = 1;
            $books_id2 = 2;
            $test_copy = new Copy($books_id);
            $test_copy2 = new Copy($books_id2);
            $test_copy->save();
            $test_copy2->save();

            //Act
            Copy::deleteAll();

            //Assert
            $result = Copy::getAll();
            $this->assertEquals([], $result);
        }

        function test_update()
        {
            $books_id = 1;
            $test_copy = new Copy($books_id);
            $test_copy->save();
            $new_books_id = 2;

            //Act
            $test_copy->update($new_books_id);

            //Assert
            $result = $test_copy->getBooksId();
            $this->assertEquals($new_books_id, $result);
        }

        function test_delete()
        {
            //Arrange
            $books_id = 1;
            $books_id2 = 2;
            $test_copy = new Copy($books_id);
            $test_copy2 = new Copy($books_id2);
            $test_copy->save();
            $test_copy2->save();

            //Act
            $test_copy->delete();

            //Assert
            $result = Copy::getAll();
            $this->assertEquals([$test_copy2], $result);

        }

        function test_getPatrons()
        {
            //Arrange
            $books_id = 34;
            $new_copy = new Copy($books_id);
            $new_copy->save();

            $p_name = "Timmy McGibblets";
            $new_patron = new Patron($p_name);
            $new_patron->save();

            $p_name2 = "Jimmy Neutron";
            $new_patron2 = new Patron($p_name2);
            $new_patron2->save();

            //Act
            $new_copy->addPatron($new_patron);
            $new_copy->addPatron($new_patron2);

            //Assert
            $result = $new_copy->getPatrons();
            $this->assertEquals([$new_patron, $new_patron2], $result);
        }

        function test_addPatron()
        {
            $books_id = 34;
            $new_copy = new Copy($books_id);
            $new_copy->save();

            $p_name = "Timmy McGibblets";
            $new_patron = new Patron($p_name);
            $new_patron->save();

            //Act
            $new_copy->addPatron($new_patron);

            //Assert
            $result = $new_copy->getPatrons();
            $this->assertEquals([$new_patron], $result);
        }
    }
?>
