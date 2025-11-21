<?php
require_once '../session.php';
require_once '../connect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $postal_code = mysqli_real_escape_string($conn, $_POST['postal_code']);
    $password = $_POST['password'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $error = "First name, Last name, Email, and Password are required!";
    } else {
        // Check if email already exists
        $check_sql = "SELECT * FROM members WHERE email = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "s", $email);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = "Email already exists!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $membership_expiry = date('Y-m-d', strtotime('+1 year'));
            
            $sql = "INSERT INTO members 
            (first_name, last_name, email, phone, address, city, postal_code, password, status, membership_expiry) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssssssss", $first_name, $last_name, $email, $phone, $address, $city, $postal_code, $hashed_password, $status, $membership_expiry);
            
            if (mysqli_stmt_execute($stmt)) {
                header("Location: members.php?success=added");
                exit();
            } else {
                $error = "Error adding member: " . mysqli_stmt_error($stmt);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Member</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f3f4f6; }
        .navbar { background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { font-size: 24px; }
        .navbar a { color: white; text-decoration: none; padding: 8px 15px; background: rgba(255,255,255,0.2); border-radius: 6px; margin-left: 10px; }
        .container { max-width: 800px; margin: 30px auto; padding: 0 20px; }
        .form-card { background: white; padding: 35px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #374151; font-weight: 600; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 14px; font-family: inherit; }
        .form-group input:focus, .form-group select:focus { outline: none; border-color: #2563eb; }
        .required { color: #ef4444; }
        .btn-submit { background: #10b981; color: white; padding: 14px 35px; border: none; border-radius: 6px; font-size: 16px; font-weight: 600; cursor: pointer; }
        .btn-cancel { background: #6b7280; color: white; padding: 14px 35px; text-decoration: none; border-radius: 6px; font-size: 16px; font-weight: 600; display: inline-block; margin-left: 10px; }
        .error-message { background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 6px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>👥 Add New Member</h1>
        <div>
            <a href="members.php">← Back to Members</a>
            <a href="dashboard.php">Dashboard</a>
        </div>
    </div>
    
    <div class="container">
        <div class="form-card">
            <h2 style="margin-bottom: 25px; color: #1f2937;">Member Registration Form</h2>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label>First Name <span class="required">*</span></label>
                        <input type="text" name="first_name" required placeholder="John">
                    </div>
                    
                    <div class="form-group">
                        <label>Last Name <span class="required">*</span></label>
                        <input type="text" name="last_name" required placeholder="Doe">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Email <span class="required">*</span></label>
                        <input type="email" name="email" required placeholder="john@example.com">
                    </div>
                    
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="tel" name="phone" placeholder="+1234567890">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" placeholder="123 Main Street">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" placeholder="New York">
                    </div>
                    
                    <div class="form-group">
                        <label>Postal Code</label>
                        <input type="text" name="postal_code" placeholder="10001">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Password <span class="required">*</span></label>
                        <input type="password" name="password" required placeholder="Minimum 6 characters" minlength="6">
                    </div>
                    
                    <div class="form-group">
                        <label>Status <span class="required">*</span></label>
                        <select name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                </div>
                
                <div style="margin-top: 30px;">
                    <button type="submit" class="btn-submit">✅ Add Member</button>
                    <a href="members.php" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>