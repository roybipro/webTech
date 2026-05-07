<?php
require_once __DIR__ . '/../config/db.php';

function isValidStatus($status) {
    return in_array($status, ['Available', 'Checked Out'], true);
}

function insertBook($title, $author, $category, $status) {
    $title = trim($title);
    $author = trim($author);
    $category = trim($category);
    $status = trim($status);

    if ($title === '' || $author === '' || $category === '' || $status === '') {
        return ['success' => false, 'message' => 'All fields are required.'];
    }

    if (!isValidStatus($status)) {
        return ['success' => false, 'message' => 'Invalid availability status.'];
    }

    $conn = getConnection();
    $sql = "INSERT INTO books (title, author, category, status) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssss', $title, $author, $category, $status);
    $ok = mysqli_stmt_execute($stmt);

    if ($ok) {
        $id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return ['success' => true, 'message' => 'Book added successfully.', 'id' => $id];
    }

    $error = mysqli_error($conn);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return ['success' => false, 'message' => 'Failed to add book: ' . $error];
}

function getAllBooks($search = '', $category = '', $status = '') {
    $conn = getConnection();
    $search = trim($search);
    $category = trim($category);
    $status = trim($status);

    $sql = "SELECT id, title, author, category, status, created_at FROM books WHERE 1=1";
    $types = '';
    $params = [];

    if ($search !== '') {
        $like = '%' . $search . '%';
        $sql .= " AND (title LIKE ? OR author LIKE ?)";
        $types .= 'ss';
        $params[] = $like;
        $params[] = $like;
    }

    if ($category !== '') {
        $sql .= " AND category = ?";
        $types .= 's';
        $params[] = $category;
    }

    if ($status !== '') {
        $sql .= " AND status = ?";
        $types .= 's';
        $params[] = $status;
    }

    $sql .= " ORDER BY id DESC";
    $stmt = mysqli_prepare($conn, $sql);

    if ($types !== '') {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $books = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $books[] = $row;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $books;
}

function getBookById($id) {
    $conn = getConnection();
    $id = (int) $id;
    $sql = "SELECT id, title, author, category, status FROM books WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $book = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $book;
}

function updateBook($id, $title, $author, $category, $status) {
    $id = (int) $id;
    $title = trim($title);
    $author = trim($author);
    $category = trim($category);
    $status = trim($status);

    if ($id <= 0) {
        return ['success' => false, 'message' => 'Invalid book ID.'];
    }

    if ($title === '' || $author === '' || $category === '' || $status === '') {
        return ['success' => false, 'message' => 'All fields are required.'];
    }

    if (!isValidStatus($status)) {
        return ['success' => false, 'message' => 'Invalid availability status.'];
    }

    $conn = getConnection();
    $sql = "UPDATE books SET title = ?, author = ?, category = ?, status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssi', $title, $author, $category, $status, $id);
    $ok = mysqli_stmt_execute($stmt);

    if ($ok) {
        $affected = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        if ($affected === 0) {
            return ['success' => true, 'message' => 'No changes were made.'];
        }

        return ['success' => true, 'message' => 'Book updated successfully.'];
    }

    $error = mysqli_error($conn);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return ['success' => false, 'message' => 'Failed to update book: ' . $error];
}

function deleteBook($id) {
    $id = (int) $id;

    if ($id <= 0) {
        return ['success' => false, 'message' => 'Invalid book ID.'];
    }

    $conn = getConnection();
    $sql = "DELETE FROM books WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $ok = mysqli_stmt_execute($stmt);

    if ($ok) {
        $affected = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        if ($affected === 0) {
            return ['success' => false, 'message' => 'Book not found.'];
        }

        return ['success' => true, 'message' => 'Book deleted successfully.'];
    }

    $error = mysqli_error($conn);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return ['success' => false, 'message' => 'Failed to delete book: ' . $error];
}

function getCategories() {
    $conn = getConnection();
    $sql = "SELECT DISTINCT category FROM books ORDER BY category ASC";
    $result = mysqli_query($conn, $sql);
    $categories = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row['category'];
    }

    mysqli_close($conn);
    return $categories;
}
?>
