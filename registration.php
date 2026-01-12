<?php
session_start();
require_once 'config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $phone = trim($_POST['phone']);
    $city = trim($_POST['city']);
    $gender = $_POST['gender'];

    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($phone) || empty($city) || empty($gender)) {
        $error = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } else {
        // Check if username or email already exists
        $check_query = "SELECT id FROM users WHERE email = ? OR username = ? LIMIT 1";
        if ($stmt = $conn->prepare($check_query)) {
            $stmt->bind_param("ss", $email, $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $result->num_rows > 0) {
                $error = "Email or username already registered!";
            } else {
                // Hash password
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                // Insert user with default user_type 'user'
                $insert_query = "INSERT INTO users (username, email, password, phone, city, gender, user_type) VALUES (?, ?, ?, ?, ?, ?, 'user')";
                if ($insert = $conn->prepare($insert_query)) {
                    $insert->bind_param("ssssss", $username, $email, $hashed, $phone, $city, $gender);
                    if ($insert->execute()) {
                        $success = "Registration successful! You can now login.";
                        // Clear form
                        $_POST = array();
                    } else {
                        $error = "Registration failed! Please try again.";
                    }
                    $insert->close();
                } else {
                    $error = "Registration temporarily unavailable. Please try again.";
                }
            }
            $stmt->close();
        } else {
            $error = "Registration temporarily unavailable. Please try again.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registration | Desi Routes Of India</title>
  <link rel="stylesheet" href="assests/css/style.css">
  <link rel="stylesheet" href="assests/css/login.css"> <!-- If you have specific styles -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="shortcut icon" href="images/favicon.jpg">
</head>
<body>
<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: Arial, sans-serif;
}

/* Admin Form Section */
.admin-form-section {
  background: url('images/Home/home1_2.png') center/cover no-repeat;
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

form input,
form select {
  width: 100%;
  padding: 12px;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  background: rgba(255, 255, 255, 0.9);
  color: #000;
}

form input:focus,
form select:focus {
  outline: none;
  background: rgba(255, 255, 255, 1);
}

form button {
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
}

form button:hover {
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

.login-link {
  text-align: center;
  margin-top: 20px;
}

.login-link a {
  color: #e67e22;
  text-decoration: none;
  font-weight: bold;
}

.login-link a:hover {
  text-decoration: underline;
}
</style>

  <section class="admin-form-section">
    <div class="form-container">
      <h2>Create Your Account</h2>
      
      <?php if ($error): ?>
        <div class="error-message"><?php echo $error; ?></div>
      <?php endif; ?>
      
      <?php if ($success): ?>
        <div class="success-message"><?php echo $success; ?></div>
      <?php endif; ?>

      <form id="registrationForm" method="POST" action="">
        <div class="form-group">
          <label for="username">Full Name</label>
          <input type="text" id="username" name="username" placeholder="Enter your full name" 
                 value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Enter your email" 
                 value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Enter password" required>
        </div>

        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" 
                 value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" required>
        </div>

        <div class="form-group">
          <label for="city">City</label>
          <input type="text" id="city" name="city" placeholder="Enter your city" 
                 value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>" required>
        </div>

        <div class="form-group">
          <label for="gender">Gender</label>
          <select id="gender" name="gender" required>
            <option value="" disabled selected>Select your gender</option>
            <option value="male" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
            <option value="female" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
            
          </select>
        </div>

        <button type="submit" class="btn">Create Account</button>
      </form>

      <div class="login-link">
        <h3>Already have an account? <a href="login.php">Sign in</a></h3>
      </div>
    </div>
  </section>
<script src="assets/js/script.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var form = document.getElementById('registrationForm');
      
      // Form submission validation
      form.addEventListener('submit', function(e) {
        var isValid = true;
        var errorMessage = '';
        
        // Get form values
        var username = document.getElementById('username').value.trim();
        var email = document.getElementById('email').value.trim();
        var password = document.getElementById('password').value;
        var phone = document.getElementById('phone').value.trim();
        var city = document.getElementById('city').value.trim();
        var gender = document.getElementById('gender').value;
        
        // Validation checks
        if (username === '') {
          isValid = false;
          errorMessage = 'Please enter your full name.';
        } else if (email === '') {
          isValid = false;
          errorMessage = 'Please enter your email.';
        } else if (!isValidEmail(email)) {
          isValid = false;
          errorMessage = 'Please enter a valid email address.';
        } else if (password === '') {
          isValid = false;
          errorMessage = 'Please enter a password.';
        } else if (password.length < 6) {
          isValid = false;
          errorMessage = 'Password must be at least 6 characters long.';
        } else if (phone === '') {
          isValid = false;
          errorMessage = 'Please enter your phone number.';
        } else if (!isValidPhone(phone)) {
          isValid = false;
          errorMessage = 'Please enter a valid phone number (10 digits).';
        } else if (city === '') {
          isValid = false;
          errorMessage = 'Please enter your city.';
        } else if (gender === '') {
          isValid = false;
          errorMessage = 'Please select your gender.';
        }
        
        if (!isValid) {
          e.preventDefault();
          alert('Error: ' + errorMessage);
        }
      });
      
      function isValidEmail(email) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
      }
      
      function isValidPhone(phone) {
        var cleanPhone = phone.replace(/\D/g, '');
        return cleanPhone.length === 10;
      }
    });
  </script>
</body>
</html>