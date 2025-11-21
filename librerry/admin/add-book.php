<?php
require_once '../session.php';
require_once '../connect.php';

$success = '';
$error = '';

// Get all categories for dropdown
$categories_result = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_name");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $publisher = mysqli_real_escape_string($conn, $_POST['publisher']);
    $publication_year = mysqli_real_escape_string($conn, $_POST['publication_year']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $shelf_location = mysqli_real_escape_string($conn, $_POST['shelf_location']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    // Validation
    if (empty($title) || empty($author)) {
        $error = "Title and Author are required!";
    } else {
        // Insert into database
        $sql = "INSERT INTO books (isbn, title, author, publisher, publication_year, category_id, quantity, available_quantity, shelf_location, description) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssiiiiss", $isbn, $title, $author, $publisher, $publication_year, $category_id, $quantity, $quantity, $shelf_location, $description);
        
        if (mysqli_stmt_execute($stmt)) {
            // Redirect with success message
            header("Location: books.php?success=added");
            exit();
        } else {
            $error = "Error adding book: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book - Library Admin</title>
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
        .form-group.full-width {
            grid-column: 1 / -1;
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
            background: #10b981;
            color: white;
            padding: 14px 35px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-submit:hover { background: #059669; }
        
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
        .btn-cancel:hover { background: #4b5563; }
        
        .error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>📚 Add New Book</h1>
        <div>
            <a href="books.php">← Back to Books</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="../logout.php">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <div class="form-card">
            <h2 style="margin-bottom: 25px; color: #1f2937;">Book Registration Form</h2>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="isbn">ISBN</label>
                        <input type="text" id="isbn" name="isbn" placeholder="978-0-545-01022-1">
                    </div>
                    
                    <div class="form-group">
                        <label for="shelf_location">Shelf Location</label>
                        <input type="text" id="shelf_location" name="shelf_location" placeholder="A1-01">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="title">Book Title <span class="required">*</span></label>
                    <input type="text" id="title" name="title" required placeholder="Enter book title">
                </div>
                
                <div class="form-group">
                    <label for="author">Author <span class="required">*</span></label>
                    <input type="text" id="author" name="author" required placeholder="Enter author name">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="publisher">Publisher</label>
                        <input type="text" id="publisher" name="publisher" placeholder="Enter publisher">
                    </div>
                    
                    <div class="form-group">
                        <label for="publication_year">Publication Year</label>
                        <input type="number" id="publication_year" name="publication_year" min="1800" max="<?php echo date('Y'); ?>" placeholder="2024">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="category_id">Category <span class="required">*</span></label>
                        <select id="category_id" name="category_id" required>
                            <option value="">-- Select Category --</option>
                            <?php while ($cat = mysqli_fetch_assoc($categories_result)): ?>
                                <option value="<?php echo $cat['category_id']; ?>">
                                    <?php echo $cat['category_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="quantity">Quantity <span class="required">*</span></label>
                        <input type="number" id="quantity" name="quantity" min="1" value="1" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Enter book description (optional)"></textarea>
                </div>
                
                <div style="margin-top: 30px;">
                    <button type="submit" class="btn-submit">✅ Add Book</button>
                    <a href="books.php" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>