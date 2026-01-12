<?php
session_start();
require_once '../config/database.php';

// Enforce admin-only access (unified session)
if(!isset($_SESSION['user_id']) || ($_SESSION['user_type'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Handle user actions
if(isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    // Don't delete if user has bookings
    $check_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE user_id='$user_id'");
    $has_bookings = $check_bookings->fetch_assoc()['count'] > 0;
    
    if(!$has_bookings) {
        $conn->query("DELETE FROM users WHERE id='$user_id'");
    } else {
        $error = "Cannot delete user with existing bookings!";
    }
    header('Location: users.php');
}

if(isset($_POST['make_admin'])) {
    $user_id = $_POST['user_id'];
    $conn->query("UPDATE users SET user_type='admin' WHERE id='$user_id'");
    header('Location: users.php');
}

// Fetch all users
$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin</title>
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
                <li class="active"><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="contacts.php"><i class="fas fa-envelope"></i> Contacts</a></li>
                                <li><a href="feedback.php"><i class="fas fa-comment"></i>feedback</a></li>

                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="header">
                <h1>Manage Users</h1>
            </div>

            <?php if(isset($error)): ?>
                <div class="error-message" style="margin-bottom: 20px;"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="content-card">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>City</th>
                            <th>Joined</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php while($user = $users->fetch_assoc()): 
        // Set default user_type if not set
        $user_type = $user['user_type'] ?? 'user';
    ?>
    <tr>
        <td>#<?php echo $user['id']; ?></td>
        <td><?php echo htmlspecialchars($user['username']); ?></td>
        <td><?php echo htmlspecialchars($user['email']); ?></td>
        <td><?php echo htmlspecialchars($user['phone']); ?></td>
        <td><?php echo htmlspecialchars($user['city']); ?></td>
        <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
        <td>
            <span class="badge <?php echo $user_type == 'admin' ? 'admin' : 'user'; ?>">
                <?php echo ucfirst($user_type); ?>
            </span>
        </td>
        <td>
            <?php if($user_type == 'user'): ?>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <button type="submit" name="make_admin" class="btn-edit" 
                            onclick="return confirm('Make this user an admin?')">
                        <i class="fas fa-user-shield"></i> Make Admin
                    </button>
                </form>
            <?php endif; ?>
            <a href="users.php?delete=<?php echo $user['id']; ?>" class="btn-delete" 
               onclick="return confirm('Are you sure you want to delete this user?')">
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