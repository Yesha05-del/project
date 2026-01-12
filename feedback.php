<?php
session_start();
require_once '../config/database.php';

// if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
//     header('Location: ../login.php');
//     exit();
// }

// Fetch feedback records
$result = $conn->query("SELECT * FROM feedback ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Feedback - Admin</title>
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
        <li><a href="contacts.php"><i class="fas fa-envelope"></i> Contacts</a></li>
        <li class="active"><a href="feedback.php"><i class="fas fa-comment"></i> Feedback</a></li>
        <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
      </ul>
    </div>

    <div class="main-content">
      <div class="header">
        <h1>User Feedback</h1>
      </div>

      <div class="content-card">
        <?php if ($result->num_rows > 0): ?>
          <table class="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Travel Date</th>
                <th>Experience</th>
                <th>Feedback</th>
                <th>Submitted On</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= $row['id']; ?></td>
                  <td><?= htmlspecialchars($row['name']); ?></td>
                  <td><?= htmlspecialchars($row['email']); ?></td>
                  <td><?= $row['travel_date']; ?></td>
                  <td><?= str_repeat("⭐", (int)$row['experience']); ?></td>
                  <td><?= htmlspecialchars($row['feedback']); ?></td>
                  <td><?= $row['created_at'] ?? ''; ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div class="no-data">
            <i class="fas fa-comment-dots"></i>
            <h3>No Feedback Submitted</h3>
            <p>No user feedback received yet.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>

<?php $conn->close(); ?>
