<?php
require_once '../session.php';
require_once '../connect.php';

$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($category_id > 0) {
    // Check if category has books
    $check_sql = "SELECT COUNT(*) as count FROM books WHERE category_id = $category_id";
    $check_result = mysqli_query($conn, $check_sql);
    $check_row = mysqli_fetch_assoc($check_result);
    
    if ($check_row['count'] > 0) {
        // Category has books, cannot delete
        header("Location: categories.php?error=has_books");
        exit();
    }
    
    // Delete the category
    $delete_sql = "DELETE FROM categories WHERE category_id = $category_id";
    
    if (mysqli_query($conn, $delete_sql)) {
        header("Location: categories.php?success=deleted");
    } else {
        header("Location: categories.php?error=delete_failed");
    }
} else {
    header("Location: categories.php");
}

exit();
?>