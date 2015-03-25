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

        function test_getCatNumber()
        {
            //Arrange
            $cat_number = 1;
            $id = 3;
            $test_copy = new Copy($cat_number, $id);

            //Act
            $result = $test_copy->getCatNumber();

            //Assert
            $this->assertEquals($cat_number, $result);
        }

        function test_setCatNumber()
        {
            //Arrange
            $cat_number = 1;
            $id = 3;
            $test_copy = new Copy($cat_number, $id);
            $new_cat_number = 2;

            //Act
            $test_copy->setCatNumber($new_cat_number);

            //Assert
            $result = $test_copy->getCatNumber();
            $this->assertEquals($new_cat_number, $result);
        }

        function test_getId()
        {
            //Arrange
            $cat_number = 1;
            $id = 4;
            $test_copy = new Copy($cat_number, $id);

            //Act
            $result = $test_copy->getId();

            //Assert
            $this->assertEquals($id, $result);
        }

        function test_setId()
        {
            //Arrange
            $cat_number = 1;
            $id = null;
            $test_copy = new Copy($cat_number, $id);

            //Act
            $test_copy->setId(5);

            //Assert
            $result = $test_copy->getId();
            $this->assertEquals(5, $result);
        }

        function test_save()
        {
            //Arrange
            $cat_number = 1;
            $test_copy = new Copy($cat_number);
            $test_copy->save();

            //Act
            $result = Copy::getAll();

            //Arrange
            $this->assertEquals([$test_copy], $result);
        }

        function test_getAll()
        {
            //Arrange
            $cat_number = 1;
            $cat_number2 = 2;
            $test_copy = new Copy($cat_number);
            $test_copy2 = new Copy($cat_number2);
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
            $cat_number = 1;
            $cat_number2 = 2;
            $test_copy = new Copy($cat_number);
            $test_copy2 = new Copy($cat_number2);
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
            $cat_number = 1;
            $test_copy = new Copy($cat_number);
            $test_copy->save();
            $new_cat_number = 2;

            //Act
            $test_copy->update($new_cat_number);

            //Assert
            $result = $test_copy->getCatNumber();
            $this->assertEquals($new_cat_number, $result);
        }

        function test_delete()
        {
            //Arrange
            $cat_number = 1;
            $cat_number2 = 2;
            $test_copy = new Copy($cat_number);
            $test_copy2 = new Copy($cat_number2);
            $test_copy->save();
            $test_copy2->save();

            //Act
            $test_copy->delete();

            //Assert
            $result = Copy::getAll();
            $this->assertEquals([$test_copy2], $result);

        }

        function test_getBook()
        {
            //Arrange
            $cat_number = 1;
            $test_copy = new Copy($cat_number);
            $test_copy->save();

            $title = "How to Water Your Ficus on Mars";
            $test_book = new Book($title);
            $test_book->save();

            $title2 = "Whoops, Im on Mars";
            $test_book2 = new Book($title2);
            $test_book2->save();

            //Act
            $test_copy->addBook($test_book);

            //Assert
            $result = $test_copy->getBook();
            $this->assertEquals([$test_book], $result);
        }

        function test_addBook()
        {
            //Arrange
            $cat_number = 1;
            $test_copy = new Copy($cat_number);
            $test_copy->save();

            $title = "How to Water Your Ficus on Mars";
            $test_book = new Book($title);
            $test_book->save();

            //Act
            $test_copy->addBook($test_book);

            //Assert
            $result = $test_copy->getBook();
            $this->assertEquals([$test_book], $result);
        }
    }
?>
