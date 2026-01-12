<?php
session_start();
require_once '../php/config/database.php';

// Fetch packages from database
$packages = array();
$query = "SELECT * FROM packages ORDER BY created_at DESC";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $packages[] = $row;
    }}
// } else {
//     // Sample data if no packages in database
//     $packages = array(
//         array(
//             'package_name' => 'Golden Triangle Tour',
//             'destination' => 'Delhi, Agra, Jaipur',
//             'description' => 'Explore the iconic cities of North India with our best-selling package covering historic monuments and cultural experiences.',
//             'duration' => '7 Days / 6 Nights',
//             'price' => '25,000',
//             'category' => 'heritage',
//             'image_path' => 'images/packages/golden_triangle.jpg'
//         ),
//         array(
//             'package_name' => 'Kerala Backwaters',
//             'destination' => 'Kerala',
//             'description' => 'Experience the serene backwaters and lush greenery of God\'s Own Country with houseboat stays and Ayurvedic treatments.',
//             'duration' => '5 Days / 4 Nights',
//             'price' => '18,000',
//             'category' => 'nature',
//             'image_path' => 'images/packages/kerala.jpg'
//         ),
//         array(
//             'package_name' => 'Himalayan Adventure',
//             'destination' => 'Himachal Pradesh',
//             'description' => 'Trek through the majestic Himalayas and explore beautiful hill stations with adventure activities and camping.',
//             'duration' => '10 Days / 9 Nights',
//             'price' => '35,000',
//             'category' => 'adventure',
//             'image_path' => 'images/packages/himalayas.jpg'
//         ),
//         array(
//             'package_name' => 'Spiritual Varanasi',
//             'destination' => 'Varanasi',
//             'description' => 'Immerse yourself in the spiritual aura of Varanasi with Ganga Aarti, temple visits, and cultural experiences.',
//             'duration' => '4 Days / 3 Nights',
//             'price' => '12,000',
//             'category' => 'spiritual',
//             'image_path' => 'images/packages/varanasi.jpg'
//         ),
//         array(
//             'package_name' => 'Rajasthan Royal Tour',
//             'destination' => 'Rajasthan',
//             'description' => 'Live like royalty exploring majestic forts, palaces, and desert camps in the land of kings.',
//             'duration' => '8 Days / 7 Nights',
//             'price' => '30,000',
//             'category' => 'cultural',
//             'image_path' => 'images/packages/rajasthan.jpg'
//         ),
//         array(
//             'package_name' => 'Goa Beach Vacation',
//             'destination' => 'Goa',
//             'description' => 'Relax on pristine beaches, enjoy water sports, and experience Portuguese heritage in this tropical paradise.',
//             'duration' => '6 Days / 5 Nights',
//             'price' => '20,000',
//             'category' => 'nature',
//             'image_path' => 'images/packages/goa.jpg'
//         )
//     );
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Travel Packages - Desi Routes Of India</title>
    <link rel="stylesheet" href="assests/css/package.css">   <link rel="shortcut icon" href="images/favicon.jpg">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  
  <header class="hero">
    <nav class="navbar">
      <div class="logo">DESI ROUTES OF INDIA</div>
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="package.php" class="active">Packages</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="booking.php" class="btn-orange">Plan Your Trip</a>
      <?php else: ?>
        <a href="registration.php" class="btn-orange">Plan Your Trip</a>
      <?php endif; ?>
    </nav>

    <div class="hero-content">
      <button class="btn-outline" style="font-size: large;">Discover Amazing Destinations</button>
      <h1>Explore Our <br> Curated Travel Packages</h1>
    </div>
    
    <div class="features">
      <span>✅ Best Price Guarantee</span>
      <span>🎧 24/7 Support</span>
      <span>🔘 Customizable Itineraries</span>
    </div>

    
  </header>

  <section class="packages-section">
    <div class="container">
      <div class="section-header">
        <span class="tag">POPULAR PACKAGES</span>
        <h1>Destinations You'll Love</h1>
        <p>Handpicked experiences crafted to create unforgettable memories</p>
      </div>

      <div class="filters">
        <button class="filter-btn active" data-filter="all">All Packages</button>
        <button class="filter-btn" data-filter="heritage">Heritage</button>
        <button class="filter-btn" data-filter="nature">Nature</button>
        <button class="filter-btn" data-filter="adventure">Adventure</button>
        <button class="filter-btn" data-filter="spiritual">Spiritual</button>
        <button class="filter-btn" data-filter="cultural">Cultural</button>
      </div>

      <div class="packages-grid">
        <?php foreach ($packages as $index => $package): ?>
          <div class="package-card" data-category="<?php echo $package['category']; ?>">
            <div class="card-image">
              <img src="<?php echo $package['image_path'] ?? 'images/default-package.jpg'; ?>" alt="<?php echo $package['package_name']; ?>">
              <div class="category-badge"><?php echo ucfirst($package['category']); ?></div>
            </div>
            
            <div class="card-content">
              <h3><?php echo $package['package_name']; ?></h3>
              <p class="destination"><i class="fas fa-map-marker-alt"></i> <?php echo $package['destination']; ?></p>
              <p class="description"><?php echo $package['description']; ?></p>
              
              <div class="package-details">
                <div class="detail">
                  <i class="fas fa-clock"></i>
                  <span><?php echo $package['duration']; ?></span>
                </div>
                <div class="detail">
                  <i class="fas fa-rupee-sign"></i>
                  <span>₹<?php echo $package['price']; ?> per person</span>
                </div>
              </div>
              
              <div class="card-actions">
                <?php if(isset($_SESSION['user_id'])): ?>
                  <a href="booking.php?package=<?php echo urlencode($package['package_name']); ?>" class="btn-book">Book Now</a>
                <?php else: ?>
                  <a href="registration.php" class="btn-book">Login to Book</a>
                <?php endif; ?>
                <button class="btn-details" onclick="showPackageDetails(<?php echo $index; ?>)">View Details</button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="why-choose-us">
    <div class="container">
      <div class="section-header">
        <span class="tag">WHY CHOOSE US</span>
        <h1>Your Perfect Travel Partner</h1>
      </div>
      
      <div class="features-grid">
        <div class="feature">
          <i class="fas fa-shield-alt"></i>
          <h3>Safe & Secure</h3>
          <p>Your safety is our priority with verified accommodations and trusted guides</p>
        </div>
        
        <div class="feature">
          <i class="fas fa-rupee-sign"></i>
          <h3>Best Value</h3>
          <p>Quality experiences at the best prices with no hidden costs</p>
        </div>
        
        <div class="feature">
          <i class="fas fa-headset"></i>
          <h3>24/7 Support</h3>
          <p>Round-the-clock assistance throughout your journey</p>
        </div>
        
        <div class="feature">
          <i class="fas fa-cogs"></i>
          <h3>Customizable</h3>
          <p>Tailor your itinerary to match your preferences</p>
        </div>
      </div>
    </div>
  </section>

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

  <!-- Package Details Modal -->
  <div id="packageModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <div id="modal-body">
        <!-- Modal content will be loaded here -->
      </div>
    </div>
  </div>

  <!-- Hidden div to store package data -->
  <div id="packageData" style="display: none;">
    <?php foreach ($packages as $index => $package): ?>
      <div class="package-detail" id="package-<?php echo $index; ?>">
        <h2><?php echo $package['package_name']; ?></h2>
        <p class="destination"><i class="fas fa-map-marker-alt"></i> <?php echo $package['destination']; ?></p>
        <p class="description"><?php echo $package['description']; ?></p>
        <div class="package-info">
          <div class="info-item">
            <strong>Duration:</strong> <?php echo $package['duration']; ?>
          </div>
          <div class="info-item">
            <strong>Price:</strong> ₹<?php echo $package['price']; ?> per person
          </div>
          <div class="info-item">
            <strong>Category:</strong> <?php echo ucfirst($package['category']); ?>
          </div>
        </div>
        <div class="modal-actions">
          <?php if(isset($_SESSION['user_id'])): ?>
            <a href="booking.php?package=<?php echo urlencode($package['package_name']); ?>" class="btn-book">Book Now</a>
          <?php else: ?>
            <a href="registration.php" class="btn-book">Login to Book</a>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<script src="assets/js/script.js"></script>
  <script>
    // Package filtering
    document.addEventListener('DOMContentLoaded', function() {
      var filterBtns = document.querySelectorAll('.filter-btn');
      var packageCards = document.querySelectorAll('.package-card');
      
      for (var i = 0; i < filterBtns.length; i++) {
        filterBtns[i].addEventListener('click', function() {
          // Remove active class from all buttons
          for (var j = 0; j < filterBtns.length; j++) {
            filterBtns[j].classList.remove('active');
          }
          
          // Add active class to clicked button
          this.classList.add('active');
          
          var filter = this.getAttribute('data-filter');
          
          // Filter packages
          for (var k = 0; k < packageCards.length; k++) {
            if (filter === 'all' || packageCards[k].getAttribute('data-category') === filter) {
              packageCards[k].style.display = 'block';
            } else {
              packageCards[k].style.display = 'none';
            }
          }
        });
      }
      
      // Modal functionality
      var modal = document.getElementById('packageModal');
      var closeBtn = document.querySelector('.close');
      
      closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
      });
      
      window.addEventListener('click', function(e) {
        if (e.target === modal) {
          modal.style.display = 'none';
        }
      });
    });
    
    function showPackageDetails(index) {
      var packageDetail = document.getElementById('package-' + index);
      var modalBody = document.getElementById('modal-body');
      
      if (packageDetail) {
        modalBody.innerHTML = packageDetail.innerHTML;
        document.getElementById('packageModal').style.display = 'block';
      }
    }
    
    // Smooth scroll for navigation
    var navLinks = document.querySelectorAll('.nav-links a');
    
    for (var i = 0; i < navLinks.length; i++) {
      navLinks[i].addEventListener('click', function(e) {
        var href = this.getAttribute('href');
        if (href.startsWith('#')) {
          e.preventDefault();
          var target = document.querySelector(href);
          if (target) {
            window.scrollTo({
              top: target.offsetTop - 80,
              behavior: 'smooth'
            });
          }
        }
      });
    }
  </script>
</body>
</html>