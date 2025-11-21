<?php
require_once '../session.php';
require_once '../connect.php';

$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($book_id > 0) {
    // Check if book has active loans
    $check_sql = "SELECT COUNT(*) as count FROM loans WHERE book_id = $book_id AND status = 'active'";
    $check_result = mysqli_query($conn, $check_sql);
    $check_row = mysqli_fetch_assoc($check_result);
    
    if ($check_row['count'] > 0) {
        // Book has active loans, cannot delete
        header("Location: books.php?error=has_loans");
        exit();
    }
    
    // Delete the book
    $delete_sql = "DELETE FROM books WHERE book_id = $book_id";
    
    if (mysqli_query($conn, $delete_sql)) {
        header("Location: books.php?success=deleted");
    } else {
        header("Location: books.php?error=delete_failed");
    }
} else {
    header("Location: books.php");
}

exit();
?>