<?php
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['librarian_id'])) {
    header("Location: admin/dashboard.php");
    exit();
}

require_once 'connect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    // Query to check credentials
    $sql = "SELECT * FROM librarians WHERE username = ? AND status = 'active'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        // Verify password
        if ($password === $row['password']) {
            // Set session variables
            $_SESSION['librarian_id'] = $row['librarian_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['role'] = $row['role'];
            
            // Redirect to dashboard
            header("Location: admin/dashboard.php");
            exit();
        } else {
            $error = "Invalid username or password!";
        }
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Library System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .login-container h2 {
            text-align: center;
            color: #2563eb;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-weight: 500;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .form-group input:focus {
            outline: none;
            border-color: #2563eb;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-login:hover {
            background: #1e40af;
        }
        .error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: #2563eb;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>📚 Library Admin Login</h2>
        
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn-login">Login</button>
        </form>
        
        <div class="back-link">
            <a href="index.php">← Back to Homepage</a>
        </div>
        
        <div style="margin-top: 20px; padding: 15px; background: #f3f4f6; border-radius: 6px; font-size: 13px; color: #6b7280;">
            <strong>Test Login:</strong><br>
            Username: <code>admin</code><br>
            Password: <code>admin123</code>
        </div>
    </div>
</body>
</html>