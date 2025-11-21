<?php
require_once '../session.php';
require_once '../connect.php';

// Get all books with category names
$sql = "SELECT b.*, c.category_name 
        FROM books b 
        LEFT JOIN categories c ON b.category_id = c.category_id 
        ORDER BY b.book_id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books - Library Admin</title>
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
        
        .container { max-width: 1400px; margin: 30px auto; padding: 0 20px; }
        
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
        .btn-add:hover { background: #059669; }
        
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
        .btn-edit:hover { background: #2563eb; }
        .btn-delete { background: #ef4444; color: white; }
        .btn-delete:hover { background: #dc2626; }
        
        .badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>📚 Manage Books</h1>
        <div>
            <a href="dashboard.php">← Dashboard</a>
            <a href="../logout.php">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <div class="header-section">
            <h2 style="color: #1f2937;">All Books</h2>
            <a href="add-book.php" class="btn-add">+ Add New Book</a>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
            <div style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <?php
                if ($_GET['success'] == 'added') echo "✅ Book added successfully!";
                if ($_GET['success'] == 'updated') echo "✅ Book updated successfully!";
                if ($_GET['success'] == 'deleted') echo "✅ Book deleted successfully!";
                ?>
            </div>
        <?php endif; ?>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ISBN</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Available</th>
                        <th>Shelf</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['book_id']; ?></td>
                        <td><?php echo $row['isbn']; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['author']); ?></td>
                        <td><?php echo $row['category_name']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td>
                            <?php if ($row['available_quantity'] > 0): ?>
                                <span class="badge badge-success"><?php echo $row['available_quantity']; ?></span>
                            <?php else: ?>
                                <span class="badge badge-danger">0</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $row['shelf_location']; ?></td>
                        <td>
                            <?php if ($row['available_quantity'] > 0): ?>
                                <span class="badge badge-success">Available</span>
                            <?php else: ?>
                                <span class="badge badge-warning">Borrowed</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit-book.php?id=<?php echo $row['book_id']; ?>" class="btn-action btn-edit">Edit</a>
                            <a href="delete-book.php?id=<?php echo $row['book_id']; ?>" class="btn-action btn-delete" onclick="return confirm('Are you sure you want to delete this book?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    
                    <?php if (mysqli_num_rows($result) == 0): ?>
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 40px; color: #6b7280;">
                            No books found. Click "Add New Book" to add your first book.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>