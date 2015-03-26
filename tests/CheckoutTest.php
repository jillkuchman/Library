<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    $DB = new PDO('pgsql:host=localhost;dbname=library_test');

    require_once "src/Checkout.php";

    class CheckoutTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Checkout::deleteAll();
        }

        function test_getCopiesId()
        {
            //Arrange
            $copies_id = 1;
            $id = 3;
            $due_date = "01/01/2015";
            $patrons_id = 1;
            $test_checkout = new Checkout($copies_id, $id, $due_date, $patrons_id);

            //Act
            $result = $test_checkout->getCopiesId();

            //Assert
            $this->assertEquals($copies_id, $result);
        }

        function test_setCopiesId()
        {
            //Arrange
            $copies_id = 1;
            $id = 3;
            $due_date = "01/01/2015";
            $patrons_id = 1;
            $test_checkout = new Checkout($copies_id, $id, $due_date, $patrons_id);
            $new_copies_id = 2;

            //Act
            $test_checkout->setCopiesId($new_copies_id);

            //Assert
            $result = $test_checkout->getCopiesId();
            $this->assertEquals($new_copies_id, $result);
        }

        function test_getId()
        {
            //Arrange
            $copies_id = 1;
            $id = 4;
            $due_date = "01/01/2015";
            $patrons_id = 1;
            $test_checkout = new Checkout($copies_id, $due_date, $patrons_id, $id);

            //Act
            $result = $test_checkout->getId();

            //Assert
            $this->assertEquals($id, $result);
        }

        function test_setId()
        {
            //Arrange
            $copies_id = 1;
            $id = null;
            $due_date = "01/01/2015";
            $patrons_id = 1;
            $test_checkout = new Checkout($copies_id, $due_date, $patrons_id, $id);

            //Act
            $test_checkout->setId(5);

            //Assert
            $result = $test_checkout->getId();
            $this->assertEquals(5, $result);
        }

        function test_save()
        {
            //Arrange
            $copies_id = 1;
            $due_date = "01/01/2015";
            $patrons_id = 1;
            $test_checkout = new Checkout($copies_id, $due_date, $patrons_id);
            $test_checkout->save();

            //Act
            $result = Checkout::getAll();

            //Arrange
            $this->assertEquals([$test_checkout], $result);
        }

        function test_getAll()
        {
            //Arrange
            $copies_id = 1;
            $copies_id2 = 2;
            $due_date = "01/01/2015";
            $patrons_id = 1;
            $due_date2 = "02/02/2012";
            $patrons_id2 = 4;
            $test_checkout = new Checkout($copies_id, $due_date, $patrons_id);
            $test_checkout2 = new Checkout($copies_id2, $due_date2, $patrons_id2);
            $test_checkout->save();
            $test_checkout2->save();

            //Act
            $result = Checkout::getAll();

            //Assert
            $this->assertEquals([$test_checkout, $test_checkout2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $copies_id = 1;
            $copies_id2 = 2;
            $due_date = "01/01/2015";
            $patrons_id = 1;
            $due_date2 = "02/02/2012";
            $patrons_id2 = 4;
            $test_checkout = new Checkout($copies_id, $due_date, $patrons_id);
            $test_checkout2 = new Checkout($copies_id2, $due_date2, $patrons_id2);
            $test_checkout->save();
            $test_checkout2->save();

            //Act
            Checkout::deleteAll();

            //Assert
            $result = Checkout::getAll();
            $this->assertEquals([], $result);
        }

        function test_update()
        {
            $copies_id = 1;
            $due_date = "01/01/2015";
            $patrons_id = 1;
            $test_checkout = new Checkout($copies_id, $due_date, $patrons_id);
            $test_checkout->save();
            $new_due_date = "02/31/2015";

            //Act
            $test_checkout->update($new_due_date);

            //Assert
            $result = $test_checkout->getDueDate();
            $this->assertEquals($new_due_date, $result);
        }

        function test_delete()
        {
            //Arrange
            $copies_id = 1;
            $due_date = "01/01/2015";
            $patrons_id = 1;
            $copies_id2 = 2;
            $due_date2 = "02/02/2012";
            $patrons_id2 = 4;
            $test_checkout = new Checkout($copies_id, $due_date, $patrons_id);
            $test_checkout2 = new Checkout($copies_id2, $due_date2, $patrons_id2);
            $test_checkout->save();
            $test_checkout2->save();

            //Act
            $test_checkout->delete();

            //Assert
            $result = Checkout::getAll();
            $this->assertEquals([$test_checkout2], $result);

        }
    }
?>
