<?php
require_once __DIR__ . '/../model/BookModel.php';

class BookController {
    public function create($data) {
        return insertBook(
            $data['title'] ?? '',
            $data['author'] ?? '',
            $data['category'] ?? '',
            $data['status'] ?? 'Available'
        );
    }

    public function readAll($filters) {
        $books = getAllBooks(
            $filters['search'] ?? '',
            $filters['category'] ?? '',
            $filters['status'] ?? ''
        );

        return ['success' => true, 'data' => $books];
    }

    public function readOne($id) {
        $book = getBookById($id);

        if (!$book) {
            return ['success' => false, 'message' => 'Book not found.'];
        }

        return ['success' => true, 'data' => $book];
    }

    public function update($data) {
        return updateBook(
            $data['id'] ?? 0,
            $data['title'] ?? '',
            $data['author'] ?? '',
            $data['category'] ?? '',
            $data['status'] ?? 'Available'
        );
    }

    public function delete($id) {
        return deleteBook($id);
    }

    public function categories() {
        return ['success' => true, 'data' => getCategories()];
    }
}
?>
