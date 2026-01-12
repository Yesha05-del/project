<?php
session_start();
require_once '../config/database.php';

// if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
//     header('Location: ../login.php');
//     exit();
// }

// Handle contact actions
if(isset($_GET['delete'])) {
    $contact_id = $_GET['delete'];
    $conn->query("DELETE FROM contacts WHERE id='$contact_id'");
    header('Location: contacts.php');
}

// Fetch all contacts
$contacts = $conn->query("SELECT * FROM contacts ORDER BY submitted_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Contacts - Admin</title>
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
                <li><a href="bookings.php"><i class="fas fa-calendar-check"></i> Bookings</a></li>
                <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
                <li class="active"><a href="contacts.php"><i class="fas fa-envelope"></i> Contacts</a></li>
                                <li><a href="feedback.php"><i class="fas fa-comment"></i>feedback</a></li>

                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="header">
                <h1>Contact Messages</h1>
            </div>

            <div class="content-card">
                <?php if($contacts->num_rows > 0): ?>
                    <div class="contacts-list">
                        <?php while($contact = $contacts->fetch_assoc()): ?>
                        <div class="contact-item">
                            <div class="contact-header">
                                <div class="contact-info">
                                    <h3><?php echo $contact['name']; ?></h3>
                                    <p><?php echo $contact['email']; ?> • <?php echo $contact['phone']; ?></p>
                                </div>
                                <div class="contact-meta">
                                    <span class="date"><?php echo date('M d, Y g:i A', strtotime($contact['submitted_at'])); ?></span>
                                    <a href="contacts.php?delete=<?php echo $contact['id']; ?>" class="btn-delete" 
                                       onclick="return confirm('Delete this message?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="contact-message">
                                <p><?php echo nl2br(htmlspecialchars($contact['message'])); ?></p>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-envelope-open"></i>
                        <h3>No Contact Messages</h3>
                        <p>No contact form submissions yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>