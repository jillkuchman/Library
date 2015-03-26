<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    $DB = new PDO('pgsql:host=localhost;dbname=library_test');

    require_once "src/Book.php";

    class BookTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Book::deleteAll();
        }

        function test_getTitle()
        {
            //Arrange
            $title = "Beowulf";
            $id = 1;
            $test_book = new Book($title, $id);

            //Act
            $result = $test_book->getTitle();

            //Assert
            $this->assertEquals($title, $result);
        }

        function test_setTitle()
        {
            //Arrange
            $title = "Beowulf";
            $id = 1;
            $test_book = new Book($title, $id);
            $new_title = "Grendall";

            //Act
            $test_book->setTitle($new_title);
            $result = $test_book->getTitle();

            //Assert
            $this->assertEquals($new_title, $result);
        }

        function test_getId()
        {
            //Arrange
            $title = "Grapes of Wrath";
            $id = 1;
            $test_book = new Book($title, $id);

            //Act
            $result = $test_book->getId();

            //Assert
            $this->assertEquals($id, $result);
        }

        function test_setId()
        {
            //Arrange
            $title = "Romeo and Juliet";
            $id = null;
            $test_book = new Book($title, $id);
            $new_id = 2;

            //Act
            $test_book->setId($new_id);
            $result = $test_book->getId();

            //Assert
            $this->assertEquals($new_id, $result);
        }

        function test_save()
        {
            //Arrange
            $title = "The Belljar";
            $test_book = new Book($title);
            $test_book->save();

            //Act
            $result = Book::getAll();

            //Assert
            $this->assertEquals([$test_book], $result);
        }

        function test_getAll()
        {
            //Arrange
            $title = "Watership Down";
            $title2 = "Zoolander: The Book";
            $test_book = new Book($title);
            $test_book->save();
            $test_book2 = new Book($title2);
            $test_book2->save();

            //Act
            $result = Book::getAll();

            //Assert
            $this->assertEquals([$test_book, $test_book2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $title = "Watership Down";
            $title2 = "Zoolander: The Book";
            $test_book = new Book($title);
            $test_book->save();
            $test_book2 = new Book($title2);
            $test_book2->save();

            //Act
            Book::deleteAll();
            $result = Book::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function test_find()
        {
            //Arrange
            $title = "Bossypants";
            $test_book = new Book($title);
            $test_book->save();

            $title2 = "Yes Please";
            $test_book2 = new Book($title2);
            $test_book2->save();

            //Act
            $result = Book::find($test_book2->getId());

            //Assert
            $this->assertEquals($test_book2, $result);
        }

        function test_delete()
        {
            //Arrange
            $title = "Gone with the Wind";
            $test_book = new Book($title);
            $test_book->save();

            $title2 = "Shiloh";
            $test_book2 = new Book($title2);
            $test_book2->save();

            //Act
            $test_book2->delete();

            //Assert
            $result = Book::getAll();
            $this->assertEquals([$test_book], $result);
        }

        function test_update()
        {
            //Arrange
            $title = "Lord of the Rings";
            $test_book = new Book($title);
            $test_book->save();
            $new_title = "The Fellowship of the Ring";

            //Act
            $test_book->update($new_title);

            //Assert
            $result = $test_book->getTitle();
            $this->assertEquals($new_title, $result);
        }

        function test_getAuthors()
        {
            //Arrange
            $title = "How to Water Your Succulents on Venus";
            $test_book = new Book($title);
            $test_book->save();

            $name1 = "John Franti";
            $test_author1 = new Author($name1);
            $test_author1->save();

            $name2 = "David Bowie";
            $test_author2 = new Author($name2);
            $test_author2->save();

            //Act
            $test_book->addAuthor($test_author1);
            $test_book->addAuthor($test_author2);

            //Assert
            $result = $test_book->getAuthors();
            $this->assertEquals([$test_author1, $test_author2], $result);
        }

        function test_addAuthor()
        {
            //Arrange
            $title = "How to Water Your Succulents on Venus";
            $test_book = new Book($title);
            $test_book->save();

            $name1 = "John Franti";
            $test_author1 = new Author($name1);
            $test_author1->save();

            //Act
            $test_book->addAuthor($test_author1);

            //Assert
            $result = $test_book->getAuthors();
            $this->assertEquals([$test_author1], $result);

        }

        function test_addCopy()
        {
            //Arrange
            $title = "How to Water Your Succulents on Venus";
            $test_book = new Book($title);
            $test_book->save();

            // $books_id = null;
            // $test_copy = new Copy($books_id);
            // $test_copy->save();


            //Act
            $test_book->addCopy();


            //Assert
            $result = $test_book->getCopies();
            $this->assertEquals([$test_copy], $result);

        }

    }



?>
