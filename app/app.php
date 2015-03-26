<?php

    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Author.php";
    require_once __DIR__."/../src/Book.php";
    require_once __DIR__."/../src/Checkout.php";
    require_once __DIR__."/../src/Copy.php";
    require_once __DIR__."/../src/Patron.php";

    $app = new Silex\Application();
    $app['debug'] = true;

    use Symfony\Component\HttpFoundation\Request;
        Request::enableHttpMethodParameterOverride();

    $DB = new PDO('pgsql:host=localhost;dbname=library');

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    $app->get("/", function() use($app) {
        return $app['twig']->render('index.html.twig');
    });

    //BOOKS PAGE, list of books with links to each book.

    $app->get("/books", function() use ($app) {
        return $app['twig']->render('books.html.twig' array('books' => Books::getAll()));
    });

    $app->post(/"books", function() use ($app) {
        $new_book = new Book($_POST['title']);
        $new_book->save();
        return $app['twig']->render('books.html.twig' array('books' => Books::getAll()));
    });

    $app->get("/books/{id}", function($id) use ($app) {
        $current_book = Book::find($id);
        return $app['twig']->render('book.html.twig' array('book' => $current_book, 'authors' => $current_book->getAuthors(), 'all_authors' => Authors::getAll()));
    });

    $app->post("/books/{id}", function($id) use ($app) {
        $current_book = Book::find($id);
        $new_author = Author::find($_POST['author_id']);
        $current_book->addAuthor($new_author);
        return $app['twig']->render('book.html.twig' array('book' => $current_book, 'authors' => $current_book->getAuthors(), 'all_authors' => Authors::getAll()));
    });

    $app->get("/books/{id}/edit", function($id) use ($app) {
        $current_book = Book::find($id);
        return $app['twig']->render('book_edit.html.twig' array('book' => $current_book, 'authors' => $current_book->getAuthors()));
    });

    $app->patch("/books/{id}", function($id) use ($app) {
        $current_book = Book::find($id);
        $new_title = $_POST['new_title'];
        $current_book->update($new_title);
        return $app['twig']->render('book.html.twig' array('book' => $current_book, 'authors' => $current_book->getAuthors(), 'all_authors' => Authors::getAll()));
    });

    $app->delete("/books/{id}/delete", function($id) use ($app) {
        $current_book = Book::find($id);
        $current_book->delete();
        return $app['twig']->render('books.html.twig', array('books' => Book::getAll()));
    });


    return $app;




?>
