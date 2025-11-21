<?php
require_once '../session.php';

require_once '../connect.php';

// Get statistics
$total_books = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM books"))['count'];
$available_books = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(available_quantity) as count FROM books"))['count'];
$total_members = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM members WHERE status='active'"))['count'];
$active_loans = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM loans WHERE status='active'"))['count'];
$overdue_loans = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM loans WHERE status='overdue'"))['count'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Library Admin</title>
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
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar h1 { font-size: 24px; }
        .navbar .user-info { display: flex; align-items: center; gap: 20px; }
        .navbar a { color: white; text-decoration: none; padding: 8px 15px; background: rgba(255,255,255,0.2); border-radius: 6px; }
        .navbar a:hover { background: rgba(255,255,255,0.3); }
        
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-left: 4px solid #2563eb;
        }
        .stat-card.warning { border-left-color: #f59e0b; }
        .stat-card.danger { border-left-color: #ef4444; }
        .stat-card.success { border-left-color: #10b981; }
        .stat-number { font-size: 36px; font-weight: bold; color: #2563eb; }
        .stat-label { color: #6b7280; font-size: 14px; margin-top: 5px; }
        
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        .action-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.2s;
        }
        .action-card:hover { transform: translateY(-5px); box-shadow: 0 4px 8px rgba(0,0,0,0.15); }
        .action-card h3 { color: #1f2937; margin-bottom: 15px; }
        .action-card a {
            display: inline-block;
            padding: 12px 30px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: background 0.3s;
        }
        .action-card a:hover { background: #1e40af; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>📚 Library Admin Panel</h1>
        <div class="user-info">
            <span>Welcome, <strong><?php echo $full_name; ?></strong></span>
            <a href="../logout.php">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <h2 style="margin-bottom: 20px; color: #1f2937;">📊 Dashboard Overview</h2>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_books; ?></div>
                <div class="stat-label">Total Books</div>
            </div>
            
            <div class="stat-card success">
                <div class="stat-number"><?php echo $available_books; ?></div>
                <div class="stat-label">Available Books</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_members; ?></div>
                <div class="stat-label">Active Members</div>
            </div>
            
            <div class="stat-card warning">
                <div class="stat-number"><?php echo $active_loans; ?></div>
                <div class="stat-label">Active Loans</div>
            </div>
            
            <?php if ($overdue_loans > 0): ?>
            <div class="stat-card danger">
                <div class="stat-number"><?php echo $overdue_loans; ?></div>
                <div class="stat-label">Overdue Loans</div>
            </div>
            <?php endif; ?>
        </div>
        
        <h2 style="margin: 40px 0 20px; color: #1f2937;">⚡ Quick Actions</h2>
        
        <div class="actions-grid">
            <div class="action-card">
                <h3>📖 Manage Books</h3>
                <p style="color: #6b7280; margin-bottom: 15px;">View, add, edit, and delete books</p>
                <a href="books.php">Go to Books</a>
            </div>
            
            <div class="action-card">
                <h3>👥 Manage Members</h3>
                <p style="color: #6b7280; margin-bottom: 15px;">View and manage library members</p>
                <a href="members.php">Go to Members</a>
            </div>
            
            <div class="action-card">
                <h3>🏷️ Manage Categories</h3>
                <p style="color: #6b7280; margin-bottom: 15px;">Add and edit book categories</p>
                <a href="categories.php">Go to Categories</a>
            </div>
            
            <div class="action-card">
                <h3>📤 Issue Book</h3>
                <p style="color: #6b7280; margin-bottom: 15px;">Issue a book to a member</p>
                <a href="issue-book.php">Issue Book</a>
            </div>
            
            <div class="action-card">
                <h3>📥 Return Book</h3>
                <p style="color: #6b7280; margin-bottom: 15px;">Process book returns</p>
                <a href="return-book.php">Return Book</a>
            </div>
            
            <div class="action-card">
                <h3>📋 View Loans</h3>
                <p style="color: #6b7280; margin-bottom: 15px;">View all active and past loans</p>
                <a href="loans.php">View Loans</a>
            </div>
        </div>
    </div>
</body>
</html>
