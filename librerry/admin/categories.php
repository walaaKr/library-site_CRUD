<?php
require_once '../session.php';
require_once '../connect.php';

// Get all categories with book count
$sql = "SELECT c.*, COUNT(b.book_id) as book_count 
        FROM categories c 
        LEFT JOIN books b ON c.category_id = b.category_id 
        GROUP BY c.category_id 
        ORDER BY c.category_name";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - Library Admin</title>
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
        
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        .btn-add {
            background: #10b981;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
        }
        
        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #2563eb;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        tr:hover { background: #f9fafb; }
        
        .btn-action {
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 13px;
            margin-right: 5px;
            display: inline-block;
        }
        .btn-edit { background: #3b82f6; color: white; }
        .btn-delete { background: #ef4444; color: white; }
        
        .badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            background: #dbeafe;
            color: #1e40af;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>🏷️ Manage Categories</h1>
        <div>
            <a href="dashboard.php">← Dashboard</a>
            <a href="../logout.php">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <div class="header-section">
            <h2 style="color: #1f2937;">Book Categories</h2>
            <a href="add-category.php" class="btn-add">+ Add New Category</a>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
            <div style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <?php
                if ($_GET['success'] == 'added') echo "✅ Category added successfully!";
                if ($_GET['success'] == 'updated') echo "✅ Category updated successfully!";
                if ($_GET['success'] == 'deleted') echo "✅ Category deleted successfully!";
                ?>
            </div>
        <?php endif; ?>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category Name</th>
                        <th>Description</th>
                        <th>Books</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['category_id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['category_name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><span class="badge"><?php echo $row['book_count']; ?> books</span></td>
                        <td>
                            <a href="edit-category.php?id=<?php echo $row['category_id']; ?>" class="btn-action btn-edit">Edit</a>
                            <?php if ($row['book_count'] == 0): ?>
                                <a href="delete-category.php?id=<?php echo $row['category_id']; ?>" class="btn-action btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>