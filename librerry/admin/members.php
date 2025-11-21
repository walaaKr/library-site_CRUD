<?php
require_once '../session.php';
require_once '../connect.php';

$sql = "SELECT * FROM members ORDER BY member_id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Members - Library Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f3f4f6; }
        .navbar { background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { font-size: 24px; }
        .navbar a { color: white; text-decoration: none; padding: 8px 15px; background: rgba(255,255,255,0.2); border-radius: 6px; margin-left: 10px; }
        .container { max-width: 1400px; margin: 30px auto; padding: 0 20px; }
        .header-section { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .btn-add { background: #10b981; color: white; padding: 12px 25px; text-decoration: none; border-radius: 6px; font-weight: 600; }
        .table-container { background: white; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #2563eb; color: white; padding: 15px; text-align: left; font-weight: 600; }
        td { padding: 12px 15px; border-bottom: 1px solid #e5e7eb; }
        tr:hover { background: #f9fafb; }
        .btn-action { padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 13px; margin-right: 5px; display: inline-block; }
        .btn-edit { background: #3b82f6; color: white; }
        .btn-delete { background: #ef4444; color: white; }
        .badge { padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; }
        .badge-active { background: #d1fae5; color: #065f46; }
        .badge-inactive { background: #fef3c7; color: #92400e; }
        .badge-suspended { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>👥 Manage Members</h1>
        <div>
            <a href="dashboard.php">← Dashboard</a>
            <a href="../logout.php">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <div class="header-section">
            <h2 style="color: #1f2937;">Library Members</h2>
            <a href="add-member.php" class="btn-add">+ Add New Member</a>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
            <div style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <?php
                if ($_GET['success'] == 'added') echo "✅ Member added successfully!";
                if ($_GET['success'] == 'updated') echo "✅ Member updated successfully!";
                if ($_GET['success'] == 'deleted') echo "✅ Member deleted successfully!";
                ?>
            </div>
        <?php endif; ?>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Member Since</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['member_id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['city']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($row['membership_date'])); ?></td>
                        <td>
                            <?php 
                            if ($row['status'] == 'active') {
                                echo '<span class="badge badge-active">Active</span>';
                            } elseif ($row['status'] == 'inactive') {
                                echo '<span class="badge badge-inactive">Inactive</span>';
                            } else {
                                echo '<span class="badge badge-suspended">Suspended</span>';
                            }
                            ?>
                        </td>
                        <td>
                            <a href="edit-member.php?id=<?php echo $row['member_id']; ?>" class="btn-action btn-edit">Edit</a>
                            <a href="delete-member.php?id=<?php echo $row['member_id']; ?>" class="btn-action btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    
                    <?php if (mysqli_num_rows($result) == 0): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px; color: #6b7280;">
                            No members found. Click "Add New Member" to register your first member.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>