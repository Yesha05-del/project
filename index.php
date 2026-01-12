<?php
// Start session for user data
session_start();

// Database connection (we'll create this later)
require_once '../php/config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Desi Routes Of India</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assests/css/India.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="shortcut icon" href="images/favicon.jpg">
 
</head>
<body>
 
  <header class="navbar">
    <div class="logo">Desi Routes Of India</div>
    <nav>
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="about_us.php">About</a></li>
        <li><a href="package.php">Packages</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
    </nav>
    <?php if (isset($_SESSION['user_id'])): ?>
    <a href="profile.php" class="btn-orange" title="Your Profile">
        <i class="fa-solid fa-user"></i> 
        <?php echo htmlspecialchars($_SESSION['username']); ?>
    </a>
<?php else: ?>
    <a href="registration.php" class="btn-orange" title="Login / Register">
        <i class="fa-solid fa-user"></i>
    </a>
<?php endif; ?>
  </header>

  <!-- Hero Section -->
  <section class="page">
    <div class="overlay"><br><br><br>
      <h3 class="tagline">EXPERIENCE LUXURY TRAVEL</h3>
      <h1>Explore the India <br> Like Never Before</h1>
      <p>
        Curated journeys to the India's most extraordinary destinations.  
        Let us transform your travel dreams into unforgettable memories.
      </p>

      <div class="buttons">
        <a href="images/v1.mp4" class="btn orange">Watch Video</a>
        <a href="package.php" class="btn blue">Explore Packages</a>
      </div>
    </div>
  </section>

  <!-- Experience Section -->
  <section class="page1">
    <br><br>
    <h2>Best Experience</h2>
    <br><br>
    <h3>Explore the echoes of history and the vibrant tapestry of culture on your journeys.</h3>
    <br><br>
    <div class="card-container">
      <?php
      // Dynamic experience cards - we can later fetch this from database
      $experiences = array(
        array(
          'image' => 'images/Home/historical.png',
          'title' => 'Historical',
          'description' => "India's historical richness includes iconic structures like the Taj Mahal, Red Fort, Qutub Minar, Ajanta & Ellora Caves, and the majestic forts of Rajasthan."
        ),
        array(
          'image' => 'images/Home/wildlife.png',
          'title' => 'Wildlife',
          'description' => "India offers diverse wildlife in sanctuaries like Ranthambore, Kaziranga, Gir, Jim Corbett, and Sundarbans – home to Bengal tigers, elephants, and rhinos."
        ),
        array(
          'image' => 'images/Home/neture.png',
          'title' => 'Nature',
          'description' => "From Himalayan peaks to Kerala backwaters, India's landscapes include lush forests, rivers, waterfalls, deserts, and serene lakes like Pangong and Dal."
        ),
        array(
          'image' => 'images/Home/culture.png',
          'title' => 'Cultural',
          'description' => "India's cultural diversity is reflected in its festivals, traditions, music, dance, temples, and architectural marvels – offering a vibrant heritage experience."
        )
      );

      foreach ($experiences as $experience) {
       echo '
<div class="card">
  <img src="' . $experience['image'] . '" alt="' . $experience['title'] . '">
          <div class="card-content">
            <h3>' . $experience['title'] . '</h3>
            <p>' . $experience['description'] . '</p>
          </div>
        </div>';
      }
      ?>
    </div>
    <br><br>
    <br><br>
    <br><br>
  </section>

  <!-- Why Us Section -->
  <section class="page2">
    <div class="page2-text">
      <span class="tag">WHY US</span>
      <h1>Crafting <br> Unforgettable <br> Journeys</h1>
    </div>

    <!-- Right Card Section -->
    <div class="features-card">
      <div class="feature">
        <i class="fa-solid fa-star" aria-hidden="true"></i>
        <h3>Real Indian Experience</h3>
        <p>Curated by locals, our journeys offer a true immersion into Indian life.</p>
      </div>

      <div class="feature">
        <i class="fa-solid fa-person-hiking"></i>
        <h3>Adventure Tours</h3>
        <p>Explore thrilling destinations and activities from mountain trekking to scuba diving for the adrenaline seekers.</p>
      </div>

      <div class="feature">
        <i class="fa-solid fa-hotel"></i>
        <h3>Luxury Vacations</h3>
        <p>Indulge in world-class comfort, exotic resorts, and seamless travel experiences crafted just for you.</p>
      </div>

      <div class="feature">
        <i class="fas fa-question-circle"></i> 
        <h3>Help Anytime , Anywhere</h3>
        <p>Benefit from 24/7 assistance and trusted local guides for worry-free travel.</p>
      </div> 
    </div>
  </section>
  <br><br><br>

  <!-- Call to Action Section -->
  <section class="page3">
    <div class="left">
      <h1>Ready to Start Your Adventure?</h1>
      <p>Let us help you create the perfect journey. Our travel experts are ready to craft your dream vacation.</p>
      <?php if(isset($_SESSION['user_id'])): ?>
        <!-- Show booking button only if logged in -->
        <a href="booking.php"><button>Book Now</button></a>
      <?php else: ?>
        <!-- Show login/register prompt if not logged in -->
        <a href="registration.php"><button>Login to Book</button></a>
        <p style="margin-top: 10px; font-size: 14px;">Create an account to start booking your trips</p>
      <?php endif; ?>
    </div>
    <div class="right">
      <div class="airplane-path"></div>
      <img src="images/Home/home3.png" alt="Traveler">
    </div>
  </section>

  <!-- Visitor Counter -->
  <!-- <div class="visitor-counter">
    <p>Visitors Today: <span id="visitorCount">0</span></p>
  </div> -->

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
    // Simple visitor counter (without arrow functions)
    document.addEventListener('DOMContentLoaded', function() {
      // Get current count from localStorage or set to 0
      var currentCount = localStorage.getItem('visitorCount');
      if (currentCount === null) {
        currentCount = 0;
      }
      
      // Increment count
      currentCount = parseInt(currentCount) + 1;
      
      // Store updated count
      localStorage.setItem('visitorCount', currentCount);
      
      // Display count
      document.getElementById('visitorCount').textContent = currentCount;
    });

    // Smooth scroll for navigation links
    var navLinks = document.querySelectorAll('.nav-links a');
    
    for (var i = 0; i < navLinks.length; i++) {
      navLinks[i].addEventListener('click', function(e) {
        var href = this.getAttribute('href');
        
        // Only smooth scroll for internal links
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

    // Image lazy loading
    function lazyLoadImages() {
      var images = document.querySelectorAll('img');
      
      for (var i = 0; i < images.length; i++) {
        var img = images[i];
        var src = img.getAttribute('data-src');
        
        if (src && !img.classList.contains('loaded')) {
          img.src = src;
          img.classList.add('loaded');
        }
      }
    }

    // Call lazy load when page loads
    window.addEventListener('load', lazyLoadImages);
  </script>
</body>
</html>