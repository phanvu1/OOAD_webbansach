<?php
require_once __DIR__ . '/../../model/BookModel.php';

class BookController {
    private $bookModel;

    public function __construct() {
        $this->bookModel = new BookModel();
    }

    public function detail() {
        $bookId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($bookId <= 0) {
            header('Location: index.php');
            exit();
        }

        $book = $this->bookModel->getBookById($bookId);

        if (!$book) {
            header('Location: index.php');
            exit();
        }

        $images = $this->bookModel->getBookImages($bookId);
        $relatedBooks = $this->bookModel->getRelatedBooks($book->idchitietdanhmuc, $bookId);

        require_once __DIR__ . '/../view/chi-tiet-sp.php';
    }
}
?>