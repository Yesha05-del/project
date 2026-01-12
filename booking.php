<?php
session_start();
require_once '../php/config/database.php';

// Redirect to login if not logged in
if(!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = 'booking.php' . (isset($_GET['package']) ? '?package=' . urlencode($_GET['package']) : '');
    header('Location: registration.php');
    exit();
}

$error = '';
$success = '';
$package_name = '';
$package_price = '';

// Get package details if package parameter is set
if(isset($_GET['package'])) {
    $package_name = $_GET['package'];
    $query = "SELECT * FROM packages WHERE package_name = '$package_name'";
    $result = $conn->query($query);
    
    if($result && $result->num_rows > 0) {
        $package = $result->fetch_assoc();
        $package_price = $package['price'];
    }
}

// Get user details
$user_id = $_SESSION['user_id'];
$user_query = "SELECT * FROM users WHERE id = '$user_id'";
$user_result = $conn->query($user_query);
$user = $user_result->fetch_assoc();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $number_of_person = $_POST['number_of_person'];
    $contact = trim($_POST['contact']);
    $booking_date = $_POST['booking_date'];
    $place = trim($_POST['place']);
    $package_name = trim($_POST['package_name']);
    $payment_method = $_POST['payment_method'];
    
    // Basic validation
    if(empty($fullname) || empty($email) || empty($number_of_person) || empty($contact) || 
       empty($booking_date) || empty($place) || empty($payment_method)) {
        $error = "All fields are required!";
    } elseif($number_of_person <= 0) {
        $error = "Number of persons must be greater than 0!";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } else {
        // Calculate total amount if package price is available
        $total_amount = 0;
        if(!empty($package_name) && !empty($package_price)) {
            $total_amount = $package_price * $number_of_person;
        }
        
        // Insert booking
        $insert_query = "INSERT INTO bookings (user_id, fullname, email, number_of_person, contact, booking_date, place, package_name, payment_method, total_amount) 
                        VALUES ('$user_id', '$fullname', '$email', '$number_of_person', '$contact', '$booking_date', '$place', '$package_name', '$payment_method', '$total_amount')";
        
        if($conn->query($insert_query)) {
          
            $success = "Booking successful! Your booking has been confirmed.";
            // Clear form
            $_POST = array();
        } else {
            $error = "Booking failed! Please try again.";
        }
    }
}

// Fetch all packages for dropdown
$packages_query = "SELECT package_name, price FROM packages ORDER BY package_name";
$packages_result = $conn->query($packages_query);
$packages = array();
if($packages_result && $packages_result->num_rows > 0) {
    while($row = $packages_result->fetch_assoc()) {
        $packages[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book Your Trip - Desi Routes Of India</title>
 <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assests/css/booking.css">  
      <link rel="shortcut icon" href="images/favicon.jpg">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
      <a href="profile.php" class="btn-orange">Welcome, <?php echo htmlspecialchars($user['username']); ?></a>
    </nav>

    <div class="hero-content">
      <button class="btn-outline" style="font-size: large;">Secure Your Journey</button>
      <h1>Book Your <br> Dream Vacation</h1>
    </div>
    
    <div class="features">
      <span>✅ Best Price Guarantee</span>
      <span>🎧 24/7 Support</span>
      <span>🔘 Easy Cancellation</span>
    </div>
  </header>

  <section class="booking-section">
    <div class="container">
      <div class="booking-container">
        <div class="booking-form-container">
          <h2>Complete Your Booking</h2>
          
          <?php if($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
          <?php endif; ?>
          
          <?php if($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
          <?php endif; ?>

          <form class="booking-form" method="POST" action="">
            <div class="form-row">
              <div class="form-group">
                <label for="fullname">Full Name *</label>
                <input type="text" id="fullname" name="fullname" 
                       value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : htmlspecialchars($user['username']); ?>" 
                       required>
              </div>
              
              <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : htmlspecialchars($user['email']); ?>" 
                       required>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="number_of_person">Number of Persons *</label>
                <input type="number" id="number_of_person" name="number_of_person" 
                       value="<?php echo isset($_POST['number_of_person']) ? htmlspecialchars($_POST['number_of_person']) : '1'; ?>" 
                       min="1" max="20" required>
              </div>
              
              <div class="form-group">
                <label for="contact">Phone Number *</label>
                <input type="tel" id="contact" name="contact" 
                       value="<?php echo isset($_POST['contact']) ? htmlspecialchars($_POST['contact']) : htmlspecialchars($user['phone']); ?>" 
                       required>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="booking_date">Travel Date *</label>
                <input type="date" id="booking_date" name="booking_date" 
                       value="<?php echo isset($_POST['booking_date']) ? htmlspecialchars($_POST['booking_date']) : ''; ?>" 
                       min="<?php echo date('Y-m-d'); ?>" required>
              </div>
              
              <div class="form-group">
                <label for="place">Destination *</label>
                <input type="text" id="place" name="place" 
                       value="<?php echo isset($_POST['place']) ? htmlspecialchars($_POST['place']) : ''; ?>" 
                       placeholder="Enter your destination" required>
              </div>
            </div>

            <div class="form-group">
              <label for="package_name">Select Package</label>
              <select id="package_name" name="package_name">
                <option value="">Select a package (optional)</option>
                <?php foreach($packages as $package): ?>
                  <option value="<?php echo htmlspecialchars($package['package_name']); ?>" 
                    <?php echo (isset($_POST['package_name']) && $_POST['package_name'] == $package['package_name']) || 
                              (isset($package_name) && $package_name == $package['package_name']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($package['package_name']); ?> - ₹<?php echo $package['price']; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label for="payment_method">Payment Method *</label>
              <select id="payment_method" name="payment_method" required>
                <option value="">Select payment method</option>
                <option value="Credit Card" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] == 'Credit Card') ? 'selected' : ''; ?>>Credit Card</option>
                <option value="UPI" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] == 'UPI') ? 'selected' : ''; ?>>UPI</option>
                <option value="Cash" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] == 'Cash') ? 'selected' : ''; ?>>Cash</option>
              </select>
            </div>

            <div class="price-summary">
              <h3>Price Summary</h3>
              <div class="price-details">
                <div class="price-item">
                  <span>Package:</span>
                  <span id="selected-package">-</span>
                </div>
                <div class="price-item">
                  <span>Persons:</span>
                  <span id="persons-count">1</span>
                </div>
                <div class="price-item total">
                  <span>Total Amount:</span>
                  <span id="total-amount">₹0</span>
                </div>
              </div>
            </div>

            <button type="submit" class="btn-book">Confirm Booking</button>
          </form>
        </div>

        <div class="booking-info">
          <h3>Why Book With Us?</h3>
          <div class="info-features">
            <div class="info-feature">
              <i class="fas fa-shield-alt"></i>
              <div>
                <h4>Secure Booking</h4>
                <p>Your personal and payment information is protected</p>
              </div>
            </div>
            
            <div class="info-feature">
              <i class="fas fa-headset"></i>
              <div>
                <h4>24/7 Support</h4>
                <p>Get assistance anytime during your journey</p>
              </div>
            </div>
            
            <div class="info-feature">
              <i class="fas fa-undo-alt"></i>
              <div>
                <h4>Easy Cancellation</h4>
                <p>Flexible cancellation policies</p>
              </div>
            </div>
            
            <div class="info-feature">
              <i class="fas fa-rupee-sign"></i>
              <div>
                <h4>Best Prices</h4>
                <p>Guaranteed best prices for all packages</p>
              </div>
            </div>
          </div>

          <div class="contact-support">
            <h4>Need Help?</h4>
            <p>Call us: +91 1234567890</p>
            <p>Email: desiroutes@gmail.com</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="page3">
    <div class="left">
      <h1>Have Questions?</h1>
      <p>Our travel experts are here to help you plan the perfect trip.</p>
      <a href="contact.php"><button>Contact Us</button></a>
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
          <h4>Quick Links</h4><br>
          <a href="index.php">Home</a><br>
          <a href="about.php">About</a><br>
          <a href="packages.php">Packages</a><br>
          <a href="contact.php">Contact</a>
        </div>
        <div>
          <h4>Contact</h4><br>
          <p>Gala Empire, <br> opp. Doordarshan Tower, <br> Thaltej, 380054-Ahmedabad, <br> Gujarat</p><br>
          <p>📞 +91 1234567890</p><br>
          <p>✉️ desiroutes@gmail.com</p>
        </div>
        <div>
          <h4>Follow Us</h4><br>
          <a href="https://www.instagram.com/desi_routes?igsh=MWJsdnpuaXF3Z244NA==" target="_blank">
            <i class="fa-brands fa-square-instagram" style="color: #e70d4f;"></i> Instagram
          </a><br>
        </div>
      </div>

      <div class="footer-bottom">
        <p>© 2025 Desi Routes Of India</p>
      </div>
    </div>
  </footer>
<script src="assets/js/script.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var packageSelect = document.getElementById('package_name');
      var personsInput = document.getElementById('number_of_person');
      var selectedPackageSpan = document.getElementById('selected-package');
      var personsCountSpan = document.getElementById('persons-count');
      var totalAmountSpan = document.getElementById('total-amount');
      
      // Package prices data
      var packagePrices = {
        <?php foreach($packages as $package): ?>
          '<?php echo $package['package_name']; ?>': <?php echo $package['price']; ?>,
        <?php endforeach; ?>
      };
      
      function calculateTotal() {
        var selectedPackage = packageSelect.value;
        var persons = parseInt(personsInput.value) || 1;
        var packagePrice = packagePrices[selectedPackage] || 0;
        var total = packagePrice * persons;
        
        // Update display
        selectedPackageSpan.textContent = selectedPackage || '-';
        personsCountSpan.textContent = persons;
        totalAmountSpan.textContent = '₹' + total.toLocaleString();
      }
      
      // Event listeners
      packageSelect.addEventListener('change', calculateTotal);
      personsInput.addEventListener('input', calculateTotal);
      
      // Initial calculation
      calculateTotal();
      
      // Form validation
      var form = document.querySelector('.booking-form');
      form.addEventListener('submit', function(e) {
        var bookingDate = document.getElementById('booking_date').value;
        var today = new Date().toISOString().split('T')[0];
        
        if(bookingDate < today) {
          e.preventDefault();
          alert('Please select a future date for travel.');
          return false;
        }
        
        var persons = parseInt(personsInput.value);
        if(persons < 1 || persons > 20) {
          e.preventDefault();
          alert('Number of persons must be between 1 and 20.');
          return false;
        }
        
        return true;
      });
      
      // Set minimum date to today
      var today = new Date().toISOString().split('T')[0];
      document.getElementById('booking_date').min = today;
    });
  </script>
</body>
</html>