<?php
require_once '../session.php';
require_once '../connect.php';

$error = '';
$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM categories WHERE category_id = $category_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    header("Location: categories.php");
    exit();
}

$category = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    if (empty($category_name)) {
        $error = "Category name is required!";
    } else {
        $sql = "UPDATE categories SET category_name = ?, description = ? WHERE category_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $category_name, $description, $category_id);
        
        if (mysqli_stmt_execute($stmt)) {
            header("Location: categories.php?success=updated");
            exit();
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Category</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f3f4f6; }
        .navbar { background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color: white; padding: 15px 30px; }
        .navbar h1 { font-size: 24px; }
        .navbar a { color: white; text-decoration: none; padding: 8px 15px; background: rgba(255,255,255,0.2); border-radius: 6px; margin-left: 10px; }
        .container { max-width: 600px; margin: 30px auto; padding: 0 20px; }
        .form-card { background: white; padding: 35px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #374151; font-weight: 600; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 14px; font-family: inherit; }
        textarea { resize: vertical; min-height: 100px; }
        .btn-submit { background: #3b82f6; color: white; padding: 14px 35px; border: none; border-radius: 6px; font-size: 16px; font-weight: 600; cursor: pointer; }
        .btn-cancel { background: #6b7280; color: white; padding: 14px 35px; text-decoration: none; border-radius: 6px; font-size: 16px; font-weight: 600; display: inline-block; margin-left: 10px; }
        .error-message { background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 6px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>🏷️ Edit Category</h1>
        <a href="categories.php">← Back</a>
    </div>
    
    <div class="container">
        <div class="form-card">
            <h2 style="margin-bottom: 25px;">Edit Category</h2>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Category Name *</label>
                    <input type="text" name="category_name" required value="<?php echo htmlspecialchars($category['category_name']); ?>">
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description"><?php echo htmlspecialchars($category['description']); ?></textarea>
                </div>
                
                <button type="submit" class="btn-submit">💾 Update Category</button>
                <a href="categories.php" class="btn-cancel">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>