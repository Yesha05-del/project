<?php
session_start();
require_once '../config/database.php';

// if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
//     header('Location: ../login.php');
//     exit();
// }

if(!isset($_GET['id'])) {
    header('Location: packages.php');
    exit();
}

$package_id = $_GET['id'];
$package_query = "SELECT * FROM packages WHERE package_id = '$package_id'";
$package_result = $conn->query($package_query);

if($package_result->num_rows == 0) {
    header('Location: packages.php');
    exit();
}

$package = $package_result->fetch_assoc();

$success = '';
$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $package_name = trim($_POST['package_name']);
    $destination = trim($_POST['destination']);
    $description = trim($_POST['description']);
    $duration = trim($_POST['duration']);
    $price = trim($_POST['price']);
    $category = $_POST['category'];
    
    $update_query = "UPDATE packages SET 
                    package_name = '$package_name',
                    destination = '$destination',
                    description = '$description',
                    duration = '$duration',
                    price = '$price',
                    category = '$category'
                    WHERE package_id = '$package_id'";
    
    if($conn->query($update_query)) {
        $success = "Package updated successfully!";
        // Refresh package data
        $package_result = $conn->query($package_query);
        $package = $package_result->fetch_assoc();
    } else {
        $error = "Failed to update package!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Package - Admin</title>
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
                <li class="active"><a href="packages.php"><i class="fas fa-suitcase"></i> Packages</a></li>
                <li><a href="bookings.php"><i class="fas fa-calendar-check"></i> Bookings</a></li>
                <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="contacts.php"><i class="fas fa-envelope"></i> Contacts</a></li>
                                <li><a href="feedback.php"><i class="fas fa-comment"></i>feedback</a></li>

                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="header">
                <h1>Edit Package</h1>
                <a href="packages.php" class="btn-primary">
                    <i class="fas fa-arrow-left"></i> Back to Packages
                </a>
            </div>

            <div class="content-card">
                <?php if($success): ?>
                    <div class="success-message"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if($error): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" class="package-form">
                    <div class="form-group">
                        <label>Package Name</label>
                        <input type="text" name="package_name" value="<?php echo htmlspecialchars($package['package_name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Destination</label>
                        <input type="text" name="destination" value="<?php echo htmlspecialchars($package['destination']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="4" required><?php echo htmlspecialchars($package['description']); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Duration</label>
                            <input type="text" name="duration" value="<?php echo htmlspecialchars($package['duration']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="price" step="0.01" value="<?php echo $package['price']; ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" required>
                            <option value="heritage" <?php echo $package['category'] == 'heritage' ? 'selected' : ''; ?>>Heritage</option>
                            <option value="nature" <?php echo $package['category'] == 'nature' ? 'selected' : ''; ?>>Nature</option>
                            <option value="adventure" <?php echo $package['category'] == 'adventure' ? 'selected' : ''; ?>>Adventure</option>
                            <option value="spiritual" <?php echo $package['category'] == 'spiritual' ? 'selected' : ''; ?>>Spiritual</option>
                            <option value="cultural" <?php echo $package['category'] == 'cultural' ? 'selected' : ''; ?>>Cultural</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn-primary">Update Package</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>