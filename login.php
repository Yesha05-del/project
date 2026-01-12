<?php
session_start();
require_once 'config/database.php';

// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identifier = trim($_POST['email']);
    $password = $_POST['password'];

    // Basic validation
    if (empty($identifier) || empty($password)) {
        $error = "Email and password are required!";
    } else {
        // Authenticate against merged users table, allow email or username
        $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);
        if ($isEmail) {
            $query = "SELECT * FROM users WHERE email = ? LIMIT 1";
        } else {
            $query = "SELECT * FROM users WHERE username = ? LIMIT 1";
        }

        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("s", $identifier);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $user = $result->fetch_assoc();

                // Support hashed passwords; fallback to plaintext for legacy data
                $valid = false;
                if (!empty($user['password']) && strlen($user['password']) >= 20) {
                    // Likely a hash
                    $valid = password_verify($password, $user['password']);
                }
                if (!$valid) {
                    $valid = ($password === $user['password']);
                }

                if ($valid) {
                    // Set session variables (unified)
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['logged_in'] = true;
                    $_SESSION['user_type'] = $user['user_type'] ?? 'user';

                    // Regenerate session for security
                    session_regenerate_id(true);

                    // Redirect based on user type
                    if (($_SESSION['user_type']) === 'admin') {
                        header("Location: admin/dashboard.php");
                        exit();
                    } else {
                        header("Location: index.php");
                        exit();
                    }
                } else {
                    $error = "Invalid email or password!";
                }
            } else {
                $error = "Invalid email or password!";
            }
            $stmt->close();
        } else {
            $error = "Login temporarily unavailable. Please try again.";
        }
    }
}

// Redirect if already logged in
if (isset($_SESSION['logged_in'])) {
    if (($_SESSION['user_type'] ?? '') === 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: index.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | Desi Routes Of India</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="shortcut icon" href="images/favicon.jpg">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background: url('images/Home/home1_2.png') center/cover no-repeat fixed;
      min-height: 100vh;
    }

    /* Admin Form Section */
    .admin-form-section {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 60px 20px;
      position: relative;
    }

    .form-container {
      max-width: 500px;
      width: 100%;
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-radius: 16px;
      padding: 40px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
      color: #fff;
    }

    .form-container h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #ffffff;
      font-size: 28px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    form label {
      font-weight: bold;
      display: block;
      margin-bottom: 8px;
      color: #fff;
    }

    form input {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      background: rgba(255, 255, 255, 0.9);
      color: #000;
    }

    form input:focus {
      outline: none;
      background: rgba(255, 255, 255, 1);
    }

    .btn {
      margin-top: 25px;
      padding: 15px;
      width: 100%;
      background-color: #e67e22;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 18px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s;
    }

    .btn:hover {
      background-color: #d35400;
    }

    .error-message {
      background: #e74c3c;
      color: white;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 20px;
      text-align: center;
    }

    .success-message {
      background: #27ae60;
      color: white;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 20px;
      text-align: center;
    }

    .register-link {
      text-align: center;
      margin-top: 20px;
    }

    .register-link a {
      color: #e67e22;
      text-decoration: none;
      font-weight: bold;
    }

    .register-link a:hover {
      text-decoration: underline;
    }

    .login-info {
      text-align: center;
      margin-top: 15px;
      padding: 10px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 5px;
      font-size: 14px;
    }

    .login-info strong {
      color: #e67e22;
    }

    .user-type-badge {
      display: inline-block;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 12px;
      font-weight: bold;
      margin-left: 5px;
    }

    .badge-admin {
      background: #e74c3c;
      color: white;
    }

    .badge-user {
      background: #3498db;
      color: white;
    }
  </style>
</head>
<body>
  <section class="admin-form-section">
    <div class="form-container">
      <h2><i class="fas fa-route"></i> Desi Routes Login</h2>
      
      <?php if ($error): ?>
        <div class="error-message">
          <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
      <?php endif; ?>

      <form id="loginForm" method="POST" action="">
        <div class="form-group">
          <label for="email"><i class="fas fa-envelope"></i> Email / Username</label>
          <input type="text" id="email" name="email" placeholder="Enter your email or admin username" 
                 value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
        </div>

        <div class="form-group">
          <label for="password"><i class="fas fa-lock"></i> Password</label>
          <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>

        <button type="submit" class="btn">
          <i class="fas fa-sign-in-alt"></i> Sign In
        </button>
      </form>

      <div class="login-info">
        <p><strong>For Users:</strong> Use your registered email</p>
        <p><strong>For Admins:</strong> Use your admin username</p>
      </div>

      <div class="register-link">
        <h3>Don't have an account? <a href="registration.php">Create one here</a></h3>
      </div>
    </div>
  </section>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var form = document.getElementById('loginForm');
      
      // Form submission validation
      form.addEventListener('submit', function(e) {
        var isValid = true;
        var errorMessage = '';
        
        // Get form values
        var email = document.getElementById('email').value.trim();
        var password = document.getElementById('password').value;
        
        // Validation checks
        if (email === '') {
          isValid = false;
          errorMessage = 'Please enter your email or username.';
        } else if (password === '') {
          isValid = false;
          errorMessage = 'Please enter your password.';
        }
        
        if (!isValid) {
          e.preventDefault();
          alert('Error: ' + errorMessage);
        }
      });
    });
  </script>
</body>
</html>