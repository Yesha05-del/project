<?php
session_start();
require_once '../php/config/database.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $message = trim($_POST['message']);
    
    // Basic validation
    if (empty($name) || empty($email) || empty($phone) || empty($message)) {
        $error = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $error = "Phone number must be 10 digits!";
    } else {
        // First, create the contacts table if it doesn't exist
        $create_table = "CREATE TABLE IF NOT EXISTS contacts (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL,
            phone VARCHAR(20) NOT NULL,
            message TEXT NOT NULL,
            submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        if ($conn->query($create_table)) {
            // Insert contact form data
            $insert_query = "INSERT INTO contacts (name, email, phone, message) 
                            VALUES ('$name', '$email', '$phone', '$message')";
            
            if ($conn->query($insert_query)) {
                $success = "Thank you for your message! We'll get back to you soon.";
                // Clear form
                $_POST = array();
            } else {
                $error = "Failed to submit your message. Please try again.";
            }
        } else {
            $error = "Database error. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - Desi Routes Of India</title>
  <link rel="shortcut icon" href="images/favicon.jpg">
 <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assests/css/contact.css">  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
     <header class="hero">
    <nav class="navbar">
      <div class="logo">DESI ROUTES OF INDIA</div>
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="package.php">Packages</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
      <?php if(isset($_SESSION['user_id'])): ?>
      <a href="feedback.php" class="btn-orange">Customer Feedback</a>
      <?php else: ?>
        <a href="feedback.php" class="btn-orange">Customer Feedback</a>
      <?php endif; ?>
    </nav>

    <div class="hero-content">
      <button class="btn-outline" style="font-size: large;">Say Hello to New Destinations</button>
      <h1>Ready to Explore? <br> Contact Us!</h1>
    </div>
      <div class="features">
        <span>✅ Trusted Partner</span>
        <span>🎧 24/7 Support</span>
        <span>🔘 Best Price Guarantee</span>
      </div>

      
      <section class="contact-wrapper">
    <div class="contact-container">
      <form class="contact-form" method="POST" action="">
        <?php if ($error): ?>
          <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
          <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>

        <label for="name">Name</label>
        <input type="text" id="name" name="name" placeholder="Enter your name" 
               value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" 
               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>

        <label for="phone">Phone</label>
        <input type="tel" id="phone" name="phone" placeholder="Enter your phone (10 digits)" 
               value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" required>

        <label for="message">Your Message</label>
        <textarea id="message" name="message" rows="4" placeholder="Type your message..." required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>

        <button type="submit">Submit</button>
      </form>

      <div class="contact-image">
        <div class="image-text">
          <img src="images/contact/contact_explore.jpg" alt="Explore"/>
        </div>
      </div>
    </div>
  </section>
     </header>

     
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

 <section class="page3">
    <div class="left">
      <h1>Ready to Start Your Adventure?</h1>
      <p>Let us help you create the perfect journey. Our travel experts are ready to craft your dream vacation.</p>
      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="booking.php"><button>Start Planning</button></a>
      <?php else: ?>
        <a href="registration.php"><button>Start Planning</button></a>
      <?php endif; ?>
    </div>
    <div class="right">
      <div class="airplane-path"></div>
      <img src="images/Home/home3.png" alt="Traveler">
    </div>
  </section>

  <footer class="footer">
    <div class="footer-top">
      <h2>DESI ROUTES OF INDIA</h2>
      
      <p>Your trusted partner for extraordinary travel experiences since 2024.</p>

    <div class="footer-links">
      <div>
        <h4>Quick Links</h4>  <br>  
        <a href="index.php">Home</a>  <br>
        <a href="about.php">About</a>  <br>
        <a href="packages.php">Packages</a>  <br>
        <a href="contact.php">Contact</a> 
      </div>
      <div>
        <h4>Contact</h4>  <br>  
       <p>Gala Empire , <br>  opp.  Doordarshan  Tower ,<br> Thaltej , 380054-Ahmedabad,<br> Gujarat</p>  <br>
        <p>📞 +91 1234567890</p>  <br>
        <p>✉️ desiroutes@gmail.com</p>
      </div>
      <div>
        <h4>Follow Us</h4>  <br>  
        <a href="https://www.instagram.com/desi_routes?igsh=MWJsdnpuaXF3Z244NA==" target="_blank">
          <i class="fa-brands fa-square-instagram" style="color: #e70d4f;"></i> Instagram
        </a><br>
      </div>
    </div>

    <div class="footer-bottom">
      <p>© 2025 Desi Routes Of India</p>
    </div>
  </footer>
<script src="assets/js/script.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var form = document.querySelector('.contact-form');
      
      form.addEventListener('submit', function(e) {
        var name = document.getElementById('name').value.trim();
        var email = document.getElementById('email').value.trim();
        var phone = document.getElementById('phone').value.trim();
        var message = document.getElementById('message').value.trim();
        var isValid = true;
        var errorMessage = '';
        
        // Validation checks
        if (name === '') {
          isValid = false;
          errorMessage = 'Please enter your name.';
        } else if (email === '') {
          isValid = false;
          errorMessage = 'Please enter your email.';
        } else if (!isValidEmail(email)) {
          isValid = false;
          errorMessage = 'Please enter a valid email address.';
        } else if (phone === '') {
          isValid = false;
          errorMessage = 'Please enter your phone number.';
        } else if (!isValidPhone(phone)) {
          isValid = false;
          errorMessage = 'Please enter a valid 10-digit phone number.';
        } else if (message === '') {
          isValid = false;
          errorMessage = 'Please enter your message.';
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