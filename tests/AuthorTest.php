<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    $DB = new PDO('pgsql:host=localhost;dbname=library_test');

    require_once "src/Author.php";

    class AuthorTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Author::deleteAll();
        }

        function test_getName()
        {
            //Arrange
            $name = "Natasha Korolenko";
            $id = 3;
            $test_author = new Author($name, $id);

            //Act
            $result = $test_author->getName();

            //Assert
            $this->assertEquals($name, $result);
        }

        function test_setName()
        {
            //Arrange
            $name = "Andy";
            $id = 3;
            $test_author = new Author($name, $id);
            $new_name = "Andy Dwyer";

            //Act
            $test_author->setName($new_name);

            //Assert
            $result = $test_author->getName();
            $this->assertEquals($new_name, $result);
        }

        function test_getId()
        {
            //Arrange
            $name = "Steve Jobs";
            $id = 4;
            $test_author = new Author($name, $id);

            //Act
            $result = $test_author->getId();

            //Assert
            $this->assertEquals($id, $result);
        }

        function test_setId()
        {
            //Arrange
            $name = "Reid Baldwin";
            $id = null;
            $test_author = new Author($name, $id);

            //Act
            $test_author->setId(5);

            //Assert
            $result = $test_author->getId();
            $this->assertEquals(5, $result);
        }

        function test_save()
        {
            //Arrange
            $name = "Sarah Connor";
            $test_author = new Author($name);
            $test_author->save();

            //Act
            $result = Author::getAll();

            //Arrange
            $this->assertEquals([$test_author], $result);
        }

        function test_getAll()
        {
            //Arrange
            $name = "Sarah Connor";
            $name2 = "John Connor";
            $test_author = new Author($name);
            $test_author2 = new Author($name2);
            $test_author->save();
            $test_author2->save();

            //Act
            $result = Author::getAll();

            //Assert
            $this->assertEquals([$test_author, $test_author2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $name = "Sarah Connor";
            $name2 = "John Connor";
            $test_author = new Author($name);
            $test_author2 = new Author($name2);
            $test_author->save();
            $test_author2->save();

            //Act
            Author::deleteAll();

            //Assert
            $result = Author::getAll();
            $this->assertEquals([], $result);
        }

        function test_update()
        {
            $name = "Sarah Connor";
            $test_author = new Author($name);
            $test_author->save();
            $new_name = "John Connor";

            //Act
            $test_author->update($new_name);

            //Assert
            $result = $test_author->getName();
            $this->assertEquals($new_name, $result);
        }

        function test_delete()
        {
            //Arrange
            $name = "Sarah Connor";
            $name2 = "John Connor";
            $test_author = new Author($name);
            $test_author2 = new Author($name2);
            $test_author->save();
            $test_author2->save();

            //Act
            $test_author->delete();

            //Assert
            $result = Author::getAll();
            $this->assertEquals([$test_author2], $result);

        }

        function test_getBooks()
        {
            //Arrange
            $name = "Jill Kuchman";
            $test_author = new Author($name);
            $test_author->save();

            $title = "How to Water Your Ficus on Mars";
            $test_book = new Book($title);
            $test_book->save();

            $title2 = "Whoops, Im on Mars";
            $test_book2 = new Book($title2);
            $test_book2->save();

            //Act
            $test_author->addBook($test_book);
            $test_author->addBook($test_book2);

            //Assert
            $result = $test_author->getBooks();
            $this->assertEquals([$test_book, $test_book2], $result);
        }

        function test_addBook()
        {
            //Arrange
            $name = "Jill Kuchman";
            $test_author = new Author($name);
            $test_author->save();

            $title = "How to Water Your Ficus on Mars";
            $test_book = new Book($title);
            $test_book->save();

            //Act
            $test_author->addBook($test_book);

            //Assert
            $result = $test_author->getBooks();
            $this->assertEquals([$test_book], $result);
        }
    }
?>
