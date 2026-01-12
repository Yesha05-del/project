<?php
session_start();
require_once '../config/database.php';

// Check if user is admin and logged in (using unified session)
if(!isset($_SESSION['user_id']) || ($_SESSION['user_type'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit();
}



// Get counts for dashboard
$users_count = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$packages_count = $conn->query("SELECT COUNT(*) as count FROM packages")->fetch_assoc()['count'];
$bookings_count = $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'];
$contacts_count = $conn->query("SELECT COUNT(*) as count FROM contacts")->fetch_assoc()['count'];
$revenue_result = $conn->query("SELECT SUM(total_amount) as revenue FROM bookings WHERE status='confirmed'");
$revenue = $revenue_result->fetch_assoc()['revenue'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Desi Routes Of India</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Desi Routes Admin</h2>
            </div>
            <ul class="sidebar-menu">
                <li class="active"><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="packages.php"><i class="fas fa-suitcase"></i> Packages</a></li>
                <li><a href="bookings.php"><i class="fas fa-calendar-check"></i> Bookings</a></li>
                <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="contacts.php"><i class="fas fa-envelope"></i> Contacts</a></li>
                <li><a href="feedback.php"><i class="fas fa-comment"></i>feedback</a></li>

                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Admin Dashboard</h1>
                <div class="user-info">
                    Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-cards">
                <div class="stat-card">
                    <div class="stat-icon users">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $users_count; ?></h3>
                        <p>Total Users</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon packages">
                        <i class="fas fa-suitcase"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $packages_count; ?></h3>
                        <p>Travel Packages</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon bookings">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $bookings_count; ?></h3>
                        <p>Total Bookings</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon revenue">
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                    <div class="stat-info">
                        <h3>₹<?php echo number_format($revenue); ?></h3>
                        <p>Total Revenue</p>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="recent-activity">
                <div class="activity-section">
                    <h3>Recent Bookings</h3>
                    <?php
                    $recent_bookings = $conn->query("SELECT * FROM bookings ORDER BY created_at DESC LIMIT 5");
                    if($recent_bookings->num_rows > 0):
                    ?>
                    <div class="activity-list">
                        <?php while($booking = $recent_bookings->fetch_assoc()): ?>
                        <div class="activity-item">
                            <div class="activity-info">
                                <strong><?php echo $booking['fullname']; ?></strong>
                                <span>booked <?php echo $booking['package_name'] ?: 'Custom Package'; ?></span>
                            </div>
                            <div class="activity-meta">
                                <span class="status <?php echo $booking['status']; ?>">
                                    <?php echo ucfirst($booking['status']); ?>
                                </span>
                                <span class="date"><?php echo date('M d, Y', strtotime($booking['created_at'])); ?></span>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php else: ?>
                    <p>No recent bookings</p>
                    <?php endif; ?>
                </div>

                <div class="activity-section">
                    <h3>Recent Contacts</h3>
                    <?php
                    $recent_contacts = $conn->query("SELECT * FROM contacts ORDER BY submitted_at DESC LIMIT 5");
                    if($recent_contacts->num_rows > 0):
                    ?>
                    <div class="activity-list">
                        <?php while($contact = $recent_contacts->fetch_assoc()): ?>
                        <div class="activity-item">
                            <div class="activity-info">
                                <strong><?php echo $contact['name']; ?></strong>
                                <span><?php echo substr($contact['message'], 0, 50); ?>...</span>
                            </div>
                            <div class="activity-meta">
                                <span class="date"><?php echo date('M d, Y', strtotime($contact['submitted_at'])); ?></span>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php else: ?>
                    <p>No recent contacts</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sidebar menu active state
        document.addEventListener('DOMContentLoaded', function() {
            var currentPage = window.location.pathname.split('/').pop();
            var menuItems = document.querySelectorAll('.sidebar-menu a');
            
            for(var i = 0; i < menuItems.length; i++) {
                var href = menuItems[i].getAttribute('href');
                if(href === currentPage) {
                    menuItems[i].parentElement.classList.add('active');
                }
            }
        });
    </script>
</body>
</html>