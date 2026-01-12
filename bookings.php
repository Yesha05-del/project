<?php
session_start();
require_once '../config/database.php';

// if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
//     header('Location: ../login.php');
//     exit();
// }

// Handle booking actions
if(isset($_GET['confirm'])) {
    $booking_id = $_GET['confirm'];
    $conn->query("UPDATE bookings SET status='confirmed' WHERE booking_id='$booking_id'");
}

if(isset($_GET['cancel'])) {
    $booking_id = $_GET['cancel'];
    $conn->query("UPDATE bookings SET status='cancelled' WHERE booking_id='$booking_id'");
}

if(isset($_GET['delete'])) {
    $booking_id = $_GET['delete'];
    $conn->query("DELETE FROM bookings WHERE booking_id='$booking_id'");
    header('Location: bookings.php');
}

// Fetch all bookings with user info
$bookings = $conn->query("
    SELECT b.*, u.username, u.email as user_email 
    FROM bookings b 
    LEFT JOIN users u ON b.user_id = u.id 
    ORDER BY b.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - Admin</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Desi Routes Admin</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="packages.php"><i class="fas fa-suitcase"></i> Packages</a></li>
                <li class="active"><a href="bookings.php"><i class="fas fa-calendar-check"></i> Bookings</a></li>
                <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="contacts.php"><i class="fas fa-envelope"></i> Contacts</a></li>
                                <li><a href="feedback.php"><i class="fas fa-comment"></i>feedback</a></li>

                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="header">
                <h1>Manage Bookings</h1>
            </div>

            <div class="content-card">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Customer</th>
                            <th>Package</th>
                            <th>Destination</th>
                            <th>Travel Date</th>
                            <th>Persons</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($booking = $bookings->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $booking['booking_id']; ?></td>
                            <td>
                                <strong><?php echo $booking['fullname']; ?></strong><br>
                                <small><?php echo $booking['user_email']; ?></small>
                            </td>
                            <td><?php echo $booking['package_name'] ?: 'Custom'; ?></td>
                            <td><?php echo $booking['place']; ?></td>
                            <td><?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></td>
                            <td><?php echo $booking['number_of_person']; ?></td>
                            <td>₹<?php echo number_format($booking['total_amount']); ?></td>
                            <td>
                                <span class="status <?php echo $booking['status']; ?>">
                                    <?php echo ucfirst($booking['status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if($booking['status'] == 'pending'): ?>
                                    <a  href="bookings.php?confirm=<?php echo $booking['booking_id']; ?>" class="btn-edit">
                                        <i class="fas fa-check"></i> 
                                    </a> &nbsp;
                                    <br>
                                    <a href="bookings.php?cancel=<?php echo $booking['booking_id']; ?>" class="btn-delete">
                                        <i class="fas fa-times"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="bookings.php?delete=<?php echo $booking['booking_id']; ?>" class="btn-delete" 
                                   onclick="return confirm('Are you sure you want to delete this booking?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>