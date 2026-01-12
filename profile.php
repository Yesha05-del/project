<?php
session_start();
require_once '../php/config/database.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_query = "SELECT * FROM users WHERE id = '$user_id'";
$user_result = $conn->query($user_query);
$user = $user_result->fetch_assoc();

// Get user bookings
$bookings_query = "SELECT * FROM bookings WHERE user_id = '$user_id' ORDER BY created_at DESC";
$bookings_result = $conn->query($bookings_query);

$success = '';
$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $city = trim($_POST['city']);
    
    $update_query = "UPDATE users SET username='$username', email='$email', phone='$phone', city='$city' WHERE id='$user_id'";
    if($conn->query($update_query)) {
        $_SESSION['username'] = $username;
        $success = "Profile updated successfully!";
        // Refresh user data
        $user_result = $conn->query($user_query);
        $user = $user_result->fetch_assoc();
    } else {
        $error = "Failed to update profile!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Desi Routes Of India</title>
    <link rel="stylesheet" href="assests/css/style.css">
    <link rel="stylesheet" href="assests/css/profile.css">
     <link rel="shortcut icon" href="images/favicon.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <header class="navbar">
        <div class="logo">Desi Routes Of India</div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="package.php">Packages</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
        <a href="logout.php" class="btn-orange">Logout</a>
    </header>

    <section class="profile-hero">
        <div class="container">
            <h1>My Profile</h1>
            <p>Manage your account and view your bookings</p>
        </div>
    </section>

    <section class="profile-section">
        <div class="container">
            <div class="profile-layout">
                <!-- Profile Info -->
                <div class="profile-card">
                    <h2>Personal Information</h2>
                    
                    <?php if($success): ?>
                        <div class="success-message"><?php echo $success; ?></div>
                    <?php endif; ?>
                    
                    <?php if($error): ?>
                        <div class="error-message"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST" class="profile-form">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" name="city" value="<?php echo htmlspecialchars($user['city']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Gender</label>
                            <input type="text" value="<?php echo ucfirst($user['gender']); ?>" disabled>
                        </div>
                        
                        <div class="form-group">
                            <label>Member Since</label>
                            <input type="text" value="<?php echo date('F d, Y', strtotime($user['created_at'])); ?>" disabled>
                        </div>
                        
                        <button type="submit" class="btn-update">Update Profile</button>
                    </form>
                </div>

                <!-- Booking History -->
                <div class="bookings-card">
                    <h2>My Bookings</h2>
                    
                    <?php if($bookings_result->num_rows > 0): ?>
                        <div class="bookings-list">
                            <?php while($booking = $bookings_result->fetch_assoc()): ?>
                            <div class="booking-item">
                                <div class="booking-header">
                                    <h3><?php echo $booking['package_name'] ?: 'Custom Package'; ?></h3>
                                    <span class="status <?php echo $booking['status']; ?>">
                                        <?php echo ucfirst($booking['status']); ?>
                                    </span>
                                </div>
                                
                                <div class="booking-details">
                                    <div class="detail">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span><?php echo $booking['place']; ?></span>
                                    </div>
                                    
                                    <div class="detail">
                                        <i class="fas fa-calendar"></i>
                                        <span><?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></span>
                                    </div>
                                    
                                    <div class="detail">
                                        <i class="fas fa-users"></i>
                                        <span><?php echo $booking['number_of_person']; ?> Persons</span>
                                    </div>
                                    
                                    <?php if($booking['total_amount'] > 0): ?>
                                    <div class="detail">
                                        <i class="fas fa-rupee-sign"></i>
                                        <span>₹<?php echo number_format($booking['total_amount']); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="booking-meta">
                                    <span>Booked on: <?php echo date('M d, Y', strtotime($booking['created_at'])); ?></span>
                                    <span>Payment: <?php echo $booking['payment_method']; ?></span>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-bookings">
                            <i class="fas fa-suitcase"></i>
                            <h3>No Bookings Yet</h3>
                            <p>Start your journey by booking a package</p>
                            <a href="packages.php" class="btn-book">Explore Packages</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
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
</body>
</html>