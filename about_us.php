<?php
session_start();
require_once '../php/config/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Desi Routes Of India</title>
 <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assests/css/about_us.css">  
       <link rel="shortcut icon" href="images/favicon.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
   
  <header class="hero">
    <nav class="navbar">
      <div class="logo">DESI ROUTES OF INDIA</div>
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php" class="active">About</a></li>
        <li><a href="package.php">Packages</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="booking.php" class="btn-orange">Plan Your Trip</a>
      <?php else: ?>
        <a href="registration.php" class="btn-orange">Plan Your Trip</a>
      <?php endif; ?>
    </nav>

    <div class="hero-content">
      <button class="btn-outline">ABOUT US</button>
      <br>
      <br>
      <h1>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Established with a passion<br> &nbsp; &nbsp; &nbsp; for exploration</h1>
    </div>

    <div class="stats">
      <div class="stat">
        <div class="icon">👤</div>
        <h2>10+</h2>
        <p>Years of Experience</p>
      </div>

      <div class="stat">
        <div class="icon">🌍</div>
        <h2>500+</h2>
        <p>Destinations</p>
      </div>

      <div class="stat">
        <div class="icon">🎧</div>
        <h2>24/7</h2>
        <p>Customer Support</p>
      </div>

      <div class="stat">
        <div class="icon">🙂</div>
        <h2>98%</h2>
        <p>Happy Clients</p>
      </div>
    </div>
  </header>

  <div class="middle-section">
    <section class="track-hero">
      <div class="track-content"><br><br>
        <div class="tag">CUSTOMIZED ITINERARIES</div>
        <h1>We Believe That Travel<br>is a Personal Journey</h1>
        <p>Our team of seasoned travel experts brings years of experience and an in-depth understanding of the world's most captivating destinations. We believe that travel is a personal journey, and we strive to provide personalized service that reflects your unique tastes and interests.</p>

        <div class="customers">
          <img src="https://i.pinimg.com/736x/83/60/f6/8360f6e8e6167d545b0c34de7490cc1e.jpg" alt="user1">
          <img src="https://i.pinimg.com/736x/15/91/11/159111051b40ba944379623d60de09ca.jpg" alt="user2">
          <img src="https://i.pinimg.com/736x/46/88/12/468812df30ab33d9c66397e40be563af.jpg" alt="user3">
          <span style="color: black;">500K+ Happy Customer</span>
        </div>

        <div class="features">
          <span>Adventurous Trek</span>
          <span>Family-Friendly</span>
          <span>Expert Guides</span>
        </div>
      </div>

      <div class="track-image">
        <img src="images/about/about2.jpg" alt="Hiker Image" />
      </div>
    </section>

    <br><br><br>

    <section class="about-hero">
      <div class="image-grid">
        <img src="images/about/about3.jpg" alt="Travel 1" class="grid-img top-left">
        <img src="images/about/about4.jpg" alt="Travel 2" class="grid-img top-right">
        <img src="images/about/about5.jpg" alt="Travel 3" class="grid-img bottom-left">
        <img src="images/about/about6.jpg" alt="Travel 4" class="grid-img bottom-right">
      </div>

      <div class="right-content">
        <span class="about-tag">ABOUT US</span>
        <h1>We Make Travel<br>Accessible and<br>Enjoyable</h1>
        <p>
          Our mission is to make travel accessible and enjoyable for all. That's why we pride
          ourselves on being budget-friendly, without compromising on quality or experience.
          Our partnerships with trusted local guides and accommodations ensure that you
          receive the best value wherever you go.
        </p>
        <a href="packages.php" class="btn">Find Packages <span class="arrow">&#10140;</span></a>
      </div>
    </section>
    <br><br>
  
    <div class="food-section">
      <h1 style="color: rgb(11, 11, 11); text-align: center; font-weight: bolder; font-size: 50px;">Famous Food Of <br> Different Indian States</h1>
      <div class="table">
        <div class="tr">
          <div class="td">
            <img src="images/about/about7.png" width="100%" height="75%" alt="Gujarati Food">
            <h2 style="color: black;">Gujarat's iconic snack</h2>
          </div>
          <div class="td">
            <img src="images/about/about8.jpg" width="100%" height="75%" alt="Punjabi Food">
            <h2 style="color: black;">Punjab on a Plate</h2>
          </div>
          <div class="td">
            <img src="images/about/about9.png" width="100%" height="75%" alt="Street Food">
            <h2 style="color: black;">A Burst of Street Flavors</h2>
          </div>
        </div>
      </div>
    </div>
    <br><br>
 
    <section class="trending-section">
      <div class="tag">TRENDING PACKAGES</div>
      <h1 class="main-heading" style="text-align: center;">Destinations You Don't<br>Wanna Miss</h1>

      <section class="card-container">
        <div class="card" style="background-image: url('images/about/about10.jpg');">
          <div class="card-content">
            <h2>Vrindavan</h2>
            <div class="info">
              <span>Uttar Pradesh</span>
              <span>3 Packages</span>
            </div>
          </div>
        </div>

        <div class="card" style="background-image: url('images/about/about11.jpg');">
          <div class="card-content">
            <h2>Kutch-White Desert</h2>
            <div class="info">
              <span>Gujarat</span>
              <span>2 Packages</span>
            </div>
          </div>
        </div>

        <div class="card" style="background-image: url('images/about/about12.jpg');">
          <div class="card-content">
            <h2>Taj Mahal</h2>
            <div class="info">
              <span>Uttar Pradesh</span>
              <span>7 Packages</span>
            </div>
          </div>
        </div>
      </section>
    </section>
    <br><br><br><br><br><br><br><br><br><br>
  </div>

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
      <img src="images/about/about13.png" alt="Traveler">
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
  <script src="assests/js/script.js"></script>

  </script>
  <script>
    // Add some interactive features
    document.addEventListener('DOMContentLoaded', function() {
      // Add animation to stats counter
      var stats = document.querySelectorAll('.stat h2');
      
      stats.forEach(function(stat) {
        var target = parseInt(stat.textContent);
        var current = 0;
        var increment = target / 50;
        var timer = setInterval(function() {
          current += increment;
          if (current >= target) {
            current = target;
            clearInterval(timer);
          }
          stat.textContent = Math.floor(current) + (stat.textContent.includes('+') ? '+' : '');
        }, 50);
      });

      // Add smooth scrolling for internal links
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

      // Add hover effect to cards
      var cards = document.querySelectorAll('.card');
      
      for (var i = 0; i < cards.length; i++) {
        cards[i].addEventListener('mouseenter', function() {
          this.style.transform = 'scale(1.05)';
        });
        
        cards[i].addEventListener('mouseleave', function() {
          this.style.transform = 'scale(1)';
        });
      }
    });
  </script>
</body>
</html>