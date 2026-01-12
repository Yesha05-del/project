<?php
session_start();
require_once '../config/database.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identifier = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($identifier) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        // Authenticate admin from merged users table
        $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);
        if ($isEmail) {
            $query = "SELECT * FROM users WHERE email = ? AND user_type = 'admin' LIMIT 1";
        } else {
            $query = "SELECT * FROM users WHERE username = ? AND user_type = 'admin' LIMIT 1";
        }

        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("s", $identifier);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $user = $result->fetch_assoc();

                $valid = false;
                if (!empty($user['password']) && strlen($user['password']) >= 20) {
                    $valid = password_verify($password, $user['password']);
                }
                if (!$valid) {
                    $valid = ($password === $user['password']);
                }

                if ($valid) {
                    // Standardize session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['logged_in'] = true;
                    $_SESSION['user_type'] = 'admin';

                    session_regenerate_id(true);
                    header('Location: dashboard.php');
                    exit();
                } else {
                    $error = "Invalid password.";
                }
            } else {
                $error = "No admin found with that username/email.";
            }
            $stmt->close();
        } else {
            $error = "Login temporarily unavailable. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Desi Routes Of India</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="css/admin_login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="admin-login-container">
        <div class="admin-login-form">
            <h2>Admin Login</h2>
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn-login">Login</button>
            </form>
        </div>
    </div>
</body>
</html>