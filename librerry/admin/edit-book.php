<?php
require_once '../session.php';
require_once '../connect.php';

$error = '';
$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get book data
$book_sql = "SELECT * FROM books WHERE book_id = $book_id";
$book_result = mysqli_query($conn, $book_sql);

if (mysqli_num_rows($book_result) == 0) {
    header("Location: books.php");
    exit();
}

$book = mysqli_fetch_assoc($book_result);

// Get all categories
$categories_result = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_name");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $publisher = mysqli_real_escape_string($conn, $_POST['publisher']);
    $publication_year = mysqli_real_escape_string($conn, $_POST['publication_year']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $quantity = intval($_POST['quantity']);
    $shelf_location = mysqli_real_escape_string($conn, $_POST['shelf_location']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    // Calculate new available quantity
    $borrowed = $book['quantity'] - $book['available_quantity'];
    $new_available = $quantity - $borrowed;
    
    if ($new_available < 0) {
        $error = "Cannot reduce quantity below currently borrowed books!";
    } else {
        $sql = "UPDATE books SET 
                isbn = ?, 
                title = ?, 
                author = ?, 
                publisher = ?, 
                publication_year = ?, 
                category_id = ?, 
                quantity = ?, 
                available_quantity = ?,
                shelf_location = ?,
                description = ?
                WHERE book_id = ?";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssiiiissi", $isbn, $title, $author, $publisher, $publication_year, $category_id, $quantity, $new_available, $shelf_location, $description, $book_id);
        
        if (mysqli_stmt_execute($stmt)) {
            header("Location: books.php?success=updated");
            exit();
        } else {
            $error = "Error updating book: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book - Library Admin</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f3f4f6; }
        
        .navbar {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h1 { font-size: 24px; }
        .navbar a { color: white; text-decoration: none; padding: 8px 15px; background: rgba(255,255,255,0.2); border-radius: 6px; margin-left: 10px; }
        
        .container { max-width: 800px; margin: 30px auto; padding: 0 20px; }
        
        .form-card {
            background: white;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-weight: 600;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #2563eb;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .required { color: #ef4444; }
        
        .btn-submit {
            background: #3b82f6;
            color: white;
            padding: 14px 35px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-submit:hover { background: #2563eb; }
        
        .btn-cancel {
            background: #6b7280;
            color: white;
            padding: 14px 35px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            display: inline-block;
            margin-left: 10px;
        }
        
        .error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .info-box {
            background: #dbeafe;
            color: #1e40af;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>📚 Edit Book</h1>
        <div>
            <a href="books.php">← Back to Books</a>
            <a href="dashboard.php">Dashboard</a>
        </div>
    </div>
    
    <div class="container">
        <div class="form-card">
            <h2 style="margin-bottom: 25px; color: #1f2937;">Edit Book Information</h2>
            
            <div class="info-box">
                📖 Currently Borrowed: <strong><?php echo $book['quantity'] - $book['available_quantity']; ?></strong> copies
            </div>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="isbn">ISBN</label>
                        <input type="text" id="isbn" name="isbn" value="<?php echo htmlspecialchars($book['isbn']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="shelf_location">Shelf Location</label>
                        <input type="text" id="shelf_location" name="shelf_location" value="<?php echo htmlspecialchars($book['shelf_location']); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="title">Book Title <span class="required">*</span></label>
                    <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($book['title']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="author">Author <span class="required">*</span></label>
                    <input type="text" id="author" name="author" required value="<?php echo htmlspecialchars($book['author']); ?>">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="publisher">Publisher</label>
                        <input type="text" id="publisher" name="publisher" value="<?php echo htmlspecialchars($book['publisher']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="publication_year">Publication Year</label>
                        <input type="number" id="publication_year" name="publication_year" value="<?php echo $book['publication_year']; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select id="category_id" name="category_id" required>
                            <?php while ($cat = mysqli_fetch_assoc($categories_result)): ?>
                                <option value="<?php echo $cat['category_id']; ?>" 
                                    <?php echo ($cat['category_id'] == $book['category_id']) ? 'selected' : ''; ?>>
                                    <?php echo $cat['category_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="quantity">Quantity <span class="required">*</span></label>
                        <input type="number" id="quantity" name="quantity" min="<?php echo $book['quantity'] - $book['available_quantity']; ?>" value="<?php echo $book['quantity']; ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"><?php echo htmlspecialchars($book['description']); ?></textarea>
                </div>
                
                <div style="margin-top: 30px;">
                    <button type="submit" class="btn-submit">💾 Update Book</button>
                    <a href="books.php" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>