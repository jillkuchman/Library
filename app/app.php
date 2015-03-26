<?php

    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Author.php";
    require_once __DIR__."/../src/Book.php";
    require_once __DIR__."/../src/Checkout.php";
    require_once __DIR__."/../src/Copy.php";
    require_once __DIR__."/../src/Patron.php";

    $app = new Silex\Application();
    $app['debug']=true;

    use Symfony\Component\HttpFoundation\Request;
        Request::enableHttpMethodParameterOverride();

    $DB = new PDO('pgsql:host=localhost;dbname=library');

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    $app->get("/", function() use($app) {
        return $app['twig']->render('index.html.twig');
    });

    $app->get("/admin", function() use($app) {
        return $app['twig']->render('admin.html.twig');
    });

    $app->get("/public", function() use($app) {
        return $app['twig']->render('public.html.twig');
    });


//
//
    //BOOKS PAGE, list of books with links to each book.

    $app->get("/books", function() use ($app) {
        return $app['twig']->render('books.html.twig', array('books' => Book::getAll()));
    });

    $app->post("/books", function() use ($app) {
        $new_book = new Book($_POST['title']);
        $new_book->save();
        return $app['twig']->render('books.html.twig', array('books' => Book::getAll()));
    });

    $app->get("/books/{id}", function($id) use ($app) {
        $current_book = Book::find($id);
        return $app['twig']->render('book.html.twig', array('book' => $current_book, 'authors' => $current_book->getAuthors(), 'all_authors' => Author::getAll()));
    });

    $app->post("/books/{id}", function($id) use ($app) {
        $current_book = Book::find($id);
        $new_author = Author::find($_POST['author_id']);
        $current_book->addAuthor($new_author);
        return $app['twig']->render('book.html.twig', array('book' => $current_book, 'authors' => $current_book->getAuthors(), 'all_authors' => Author::getAll()));
    });

    $app->get("/books/{id}/edit", function($id) use ($app) {
        $current_book = Book::find($id);
        return $app['twig']->render('book_edit.html.twig', array('book' => $current_book, 'authors' => $current_book->getAuthors()));
    });

    $app->patch("/books/{id}", function($id) use ($app) {
        $current_book = Book::find($id);
        $new_title = $_POST['new_title'];
        $current_book->update($new_title);
        return $app['twig']->render('book.html.twig', array('book' => $current_book, 'authors' => $current_book->getAuthors(), 'all_authors' => Author::getAll()));
    });

    $app->post("/add_authors", function() use ($app) {
        $current_book = Book::find($_POST['book_id']);
        $author = Author::find($_POST['author_id']);
        $current_book->addAuthor($author);
        return $app['twig']->render('book.html.twig', array('book' => $current_book, 'authors' => $current_book->getAuthors(), 'all_authors' => Author::getAll()));
    });

    $app->delete("/delete_books", function() use($app) {
        $current_author = Author::find($_POST['author_id']);
        $book = Book::find($_POST['book_id']);
        $current_author->deleteBook($book);
        return $app['twig']->render('author.html.twig', array('author' => $current_author, 'books' => $current_author->getBooks(), 'all_books' => Book::getAll()));
    });


    $app->delete("/books/{id}/delete", function($id) use ($app) {
        $current_book = Book::find($id);
        $current_book->delete();
        return $app['twig']->render('books.html.twig', array('books' => Book::getAll()));
    });

    //AUTHORS PAGE, list of authors with link to individual author pages

    $app->get("/authors", function() use($app){
        return $app['twig']->render('authors.html.twig', array('authors' => Author::getAll()));
    });

    $app->post("/authors", function() use($app){
        $new_author = new Author($_POST['name']);
        $new_author->save();
        return $app['twig']->render('authors.html.twig', array('authors' => Author::getAll()));
    });

    $app->get("/authors/{id}", function($id) use($app){
        $current_author = Author::find($id);
        return $app['twig']->render('author.html.twig', array('author' => $current_author, 'books' => $current_author->getBooks(), 'all_books' => Book::getAll()));
    });

    $app->post("/authors/{id}", function($id) use($app){
        $current_author = Author::find($id);
        $new_book = Book::find($_POST['book_id']);
        $current_author->addBook($new_book);
        return $app['twig']->render('author.html.twig', array('author' => $current_author, 'books' => $current_author->getBooks(), 'all_books' => Book::getAll()));
    });

    $app->get("/authors/{id}/edit", function($id) use($app) {
        $current_author = Author::find($id);
        return $app['twig']->render('author_edit.html.twig', array('author' => $current_author, 'books' => $current_author->getBooks()));
    });

    $app->post("/add_books", function() use ($app) {
        $current_author = Author::find($_POST['author_id']);
        $book = Book::find($_POST['book_id']);
        $current_author->addBook($book);
        return $app['twig']->render('author.html.twig', array('author' => $current_author, 'books' => $current_author->getBooks(), 'all_books' => Book::getAll()));
    });

    $app->delete("/delete_books", function() use($app) {
        $current_author = Author::find($_POST['author_id']);
        $book = Book::find($_POST['book_id']);
        $current_author->deleteBook($book);
        return $app['twig']->render('author.html.twig', array('author' => $current_author, 'books' => $current_author->getBooks(), 'all_books' => Book::getAll()));
    });

    $app->patch("/authors/{id}", function($id) use($app){
        $current_author = Author::find($id);
        $new_name = $_POST['new_name'];
        $current_author->update($new_name);
        return $app['twig']->render('author.html.twig', array('author' => $current_author, 'books' => $current_author->getBooks(), 'all_books' => Book::getAll()));
    });

    $app->delete("/authors/{id}/delete", function($id) use($app){
        $current_author = Author::find($id);
        $current_author->delete();
        return $app['twig']->render('authors.html.twig', array('authors' => Author::getAll()));
    });


    return $app;




?>
