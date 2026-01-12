<?php
session_start();
require_once 'config/database.php';

$message = '';
$success = false;

// Check if admin already exists
$check = $conn->query("SELECT * FROM users WHERE username = 'admin' AND user_type = 'admin'");

if ($check && $check->num_rows > 0) {
    $message = "Admin user already exists!";
    $admin = $check->fetch_assoc();
    $existingAdmin = true;
} else {
    $existingAdmin = false;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_admin'])) {
    $username = 'admin';
    $password = 'admin123';
    $email = 'admin@desiroutes.com';
    $phone = '9999999999';
    $city = 'Admin City';
    $gender = 'male';
    $user_type = 'admin';
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert admin user
    $sql = "INSERT INTO users (username, email, password, phone, city, gender, user_type, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssss", $username, $email, $hashed_password, $phone, $city, $gender, $user_type);
        if ($stmt->execute()) {
            $message = "Admin user created successfully!<br>Username: admin<br>Password: admin123";
            $success = true;
            $existingAdmin = false;
            // Refresh to show new admin
            $check = $conn->query("SELECT * FROM users WHERE username = 'admin' AND user_type = 'admin'");
            if ($check && $check->num_rows > 0) {
                $admin = $check->fetch_assoc();
                $existingAdmin = true;
            }
        } else {
            $message = "Error creating admin: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Handle update password
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_password'])) {
    $new_password = 'admin123';
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    $sql = "UPDATE users SET password = ? WHERE username = 'admin' AND user_type = 'admin'";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $hashed_password);
        if ($stmt->execute()) {
            $message = "Admin password updated to: admin123";
            $success = true;
        } else {
            $message = "Error updating password: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Admin - Desi Routes</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 10px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        .message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .admin-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .admin-details h3 {
            color: #333;
            margin-bottom: 15px;
        }
        .admin-details p {
            margin: 10px 0;
            font-size: 14px;
        }
        .admin-details strong {
            color: #667eea;
        }
        button {
            width: 100%;
            padding: 15px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background: #5568d3;
        }
        .links {
            margin-top: 30px;
            text-align: center;
        }
        .links a {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .links a:hover {
            background: #218838;
        }
        .warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Admin Setup</h1>
        
        <?php if ($message): ?>
            <div class="message <?php echo $success ? 'success' : 'info'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($existingAdmin): ?>
            <div class="admin-details">
                <h3>✅ Admin User Exists</h3>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($admin['username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($admin['email']); ?></p>
                <p><strong>User Type:</strong> <?php echo htmlspecialchars($admin['user_type']); ?></p>
                <p><strong>Created:</strong> <?php echo date('F j, Y', strtotime($admin['created_at'])); ?></p>
            </div>
            
            <form method="POST">
                <button type="submit" name="update_password">Reset Password to: admin123</button>
            </form>
            
            <div class="warning">
                <strong>⚠️ Security Notice:</strong> After logging in, please change the default password from the admin panel!
            </div>
        <?php else: ?>
            <div class="info message">
                Click the button below to create an admin user with:<br>
                <strong>Username:</strong> admin<br>
                <strong>Password:</strong> admin123
            </div>
            
            <form method="POST">
                <button type="submit" name="create_admin">Create Admin User</button>
            </form>
        <?php endif; ?>
        
        <div class="links">
            <a href="login.php">User Login</a>
            <a href="admin/admin_login.php">Admin Login</a>
            <a href="registration.php">Register New User</a>
        </div>
    </div>
</body>
</html>
