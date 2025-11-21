<?php
require_once '../session.php';
require_once '../connect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    if (empty($category_name)) {
        $error = "Category name is required!";
    } else {
        $sql = "INSERT INTO categories (category_name, description) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $category_name, $description);
        
        if (mysqli_stmt_execute($stmt)) {
            header("Location: categories.php?success=added");
            exit();
        } else {
            $error = "Error adding category: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Category</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f3f4f6; }
        .navbar {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            padding: 15px 30px;
        }
        .navbar h1 { font-size: 24px; }
        .navbar a { color: white; text-decoration: none; padding: 8px 15px; background: rgba(255,255,255,0.2); border-radius: 6px; margin-left: 10px; }
        .container { max-width: 600px; margin: 30px auto; padding: 0 20px; }
        .form-card { background: white; padding: 35px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #374151; font-weight: 600; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 14px; font-family: inherit; }
        textarea { resize: vertical; min-height: 100px; }
        .btn-submit { background: #10b981; color: white; padding: 14px 35px; border: none; border-radius: 6px; font-size: 16px; font-weight: 600; cursor: pointer; }
        .btn-cancel { background: #6b7280; color: white; padding: 14px 35px; text-decoration: none; border-radius: 6px; font-size: 16px; font-weight: 600; display: inline-block; margin-left: 10px; }
        .error-message { background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 6px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>🏷️ Add New Category</h1>
        <a href="categories.php">← Back</a>
    </div>
    
    <div class="container">
        <div class="form-card">
            <h2 style="margin-bottom: 25px;">New Category</h2>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Category Name *</label>
                    <input type="text" name="category_name" required placeholder="e.g., Science Fiction">
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" placeholder="Optional description"></textarea>
                </div>
                
                <button type="submit" class="btn-submit">✅ Add Category</button>
                <a href="categories.php" class="btn-cancel">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>