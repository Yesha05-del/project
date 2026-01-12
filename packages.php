<?php
session_start();
require_once '../config/database.php';

// // Check if user is admin and logged in
// if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
//     header('Location: ../login.php');
//     exit();
// }

// Handle package actions
if(isset($_POST['add_package'])) {
    // Escape all string inputs to prevent SQL errors
    $package_name = $conn->real_escape_string(trim($_POST['package_name']));
    $destination = $conn->real_escape_string(trim($_POST['destination']));
    $description = $conn->real_escape_string(trim($_POST['description']));
    $duration = $conn->real_escape_string(trim($_POST['duration']));
    $price = trim($_POST['price']);
    $category = $conn->real_escape_string($_POST['category']);
    
    $image_path = 'images/package/placeholder.jpg'; // Default placeholder
    
    // Handle image upload
    if(isset($_FILES['package_image']) && $_FILES['package_image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        $file_type = $_FILES['package_image']['type'];
        $file_size = $_FILES['package_image']['size'];
        $file_tmp = $_FILES['package_image']['tmp_name'];
        $file_name = $_FILES['package_image']['name'];
        
        // Validate file type
        if(!in_array($file_type, $allowed_types)) {
            $error = "Only JPG, JPEG, PNG, GIF, and WebP images are allowed.";
        }
        // Validate file size
        elseif($file_size > $max_size) {
            $error = "Image size must be less than 5MB.";
        }
        else {
            // Create package directory if it doesn't exist
            $upload_dir = '../images/package/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            // Generate unique filename
            $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $new_filename = uniqid() . '_' . str_replace(' ', '_', $package_name) . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            
            // Move uploaded file
            if(move_uploaded_file($file_tmp, $upload_path)) {
                $image_path = 'images/package/' . $new_filename;
                $success = "Package added successfully with image!";
            } else {
                $error = "Failed to upload image. Package added without image.";
                // Still add the package but without custom image
            }
        }
    }
    
    if(!isset($error) || (isset($error) && strpos($error, 'Package added without') !== false)) {
        $query = "INSERT INTO packages (package_name, destination, description, duration, price, category, image_path) 
                  VALUES ('$package_name', '$destination', '$description', '$duration', '$price', '$category', '$image_path')";
        
        if($conn->query($query)) {
            if(!isset($success)) {
                $success = "Package added successfully!";
            }
            // Refresh page to show new package
            header("Location: packages.php");
            exit();
        } else {
            $error = "Failed to add package: " . $conn->error;
        }
    }
}

// DELETE PACKAGE FUNCTIONALITY
if(isset($_GET['delete'])) {
    $package_id = $_GET['delete'];
    
    // Get package image path before deleting
    $package_query = $conn->query("SELECT image_path FROM packages WHERE package_id = '$package_id'");
    if($package_query->num_rows > 0) {
        $package = $package_query->fetch_assoc();
        // Delete image file if it exists and is not placeholder
        if($package['image_path'] && file_exists('../' . $package['image_path']) && 
           !str_contains($package['image_path'], 'placeholder')) {
            unlink('../' . $package['image_path']);
        }
    }
    
    // Delete the package from database
    if($conn->query("DELETE FROM packages WHERE package_id = '$package_id'")) {
        $success = "Package deleted successfully!";
    } else {
        $error = "Failed to delete package: " . $conn->error;
    }
    
    // Refresh page
    header("Location: packages.php");
    exit();
}

// Fetch all packages
$packages = $conn->query("SELECT * FROM packages ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Packages - Admin</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 10px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }
        
        .file-input-wrapper input[type=file] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .file-input-style {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            border: 2px dashed #ddd;
            border-radius: 8px;
            background: #f9f9f9;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .file-input-style:hover {
            border-color: #3498db;
            background: #f0f8ff;
        }
        
        .file-input-style i {
            font-size: 24px;
            color: #3498db;
            margin-right: 10px;
        }
        
        .file-info {
            margin-top: 10px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
        
        .image-preview {
            margin-top: 15px;
            text-align: center;
        }
        
        .image-preview img {
            max-width: 200px;
            max-height: 150px;
            border-radius: 6px;
            border: 2px solid #ddd;
            display: none;
        }

        .package-image {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .table-image {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar (same as dashboard) -->
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
                <h1>Manage Packages</h1>
                <button class="btn-primary" onclick="openAddPackageModal()">
                    <i class="fas fa-plus"></i> Add New Package
                </button>
            </div>

            <!-- Display Messages -->
            <?php if(isset($success)): ?>
                <div class="success-message" style="margin: 20px 0;"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if(isset($error)): ?>
                <div class="error-message" style="margin: 20px 0;"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Packages Table -->
            <div class="content-card">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Package Name</th>
                            <th>Destination</th>
                            <th>Duration</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($package = $packages->fetch_assoc()): ?>
                        <tr>
                            <td class="table-image">
                                <?php if($package['image_path'] && file_exists('../' . $package['image_path'])): ?>
                                    <img src="../<?php echo $package['image_path']; ?>" alt="<?php echo htmlspecialchars($package['package_name']); ?>" class="package-image">
                                <?php else: ?>
                                    <div style="width: 80px; height: 60px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-image" style="color: #ccc;"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $package['package_name']; ?></td>
                            <td><?php echo $package['destination']; ?></td>
                            <td><?php echo $package['duration']; ?></td>
                            <td>₹<?php echo number_format($package['price']); ?></td>
                            <td><span class="badge"><?php echo ucfirst($package['category']); ?></span></td>
                            <td>
                                <a href="edit_package.php?id=<?php echo $package['package_id']; ?>" class="btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="packages.php?delete=<?php echo $package['package_id']; ?>" class="btn-delete" 
                                   onclick="return confirm('Are you sure you want to delete this package?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Package Modal -->
    <div id="addPackageModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddPackageModal()">&times;</span>
            <h2>Add New Package</h2>
            <form method="POST" enctype="multipart/form-data" id="addPackageForm">
                <!-- Image Upload Section -->
                <div class="form-group">
                    <label>Package Image</label>
                    <div class="file-input-wrapper">
                        <div class="file-input-style">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Click to choose image or drag & drop here</span>
                            <input type="file" name="package_image" id="package_image" accept="image/*">
                        </div>
                    </div>
                    <div class="file-info">
                        <p><i class="fas fa-info-circle"></i> Supported formats: JPG, JPEG, PNG, GIF, WebP | Max size: 5MB</p>
                    </div>
                    <div class="image-preview">
                        <img id="image_preview" src="#" alt="Image Preview">
                    </div>
                </div>

                <div class="form-group">
                    <label>Package Name</label>
                    <input type="text" name="package_name" required>
                </div>
                <div class="form-group">
                    <label>Destination</label>
                    <input type="text" name="destination" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="4" required></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Duration</label>
                        <input type="text" name="duration" placeholder="e.g., 7 Days / 6 Nights" required>
                    </div>
                    <div class="form-group">
                        <label>Price (₹)</label>
                        <input type="number" name="price" step="0.01" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" required>
                        <option value="heritage">Heritage</option>
                        <option value="nature">Nature</option>
                        <option value="adventure">Adventure</option>
                        <option value="spiritual">Spiritual</option>
                        <option value="cultural">Cultural</option>
                    </select>
                </div>
                <button type="submit" name="add_package" class="btn-primary">Add Package</button>
            </form>
        </div>
    </div>

    <script>
        // Modal functionality
        var modal = document.getElementById('addPackageModal');
        
        function openAddPackageModal() {
            modal.style.display = "block";
            // Reset form when opening modal
            document.getElementById('addPackageForm').reset();
            document.getElementById('image_preview').style.display = 'none';
        }
        
        function closeAddPackageModal() {
            modal.style.display = "none";
        }
        
        window.onclick = function(event) {
            if (event.target == modal) {
                closeAddPackageModal();
            }
        }

        // Image preview functionality
        document.getElementById('package_image').addEventListener('change', function(e) {
            const preview = document.getElementById('image_preview');
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });

        // Drag and drop functionality
        const fileInput = document.getElementById('package_image');
        const fileInputStyle = document.querySelector('.file-input-style');

        fileInputStyle.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.borderColor = '#3498db';
            this.style.background = '#e3f2fd';
        });

        fileInputStyle.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.style.borderColor = '#ddd';
            this.style.background = '#f9f9f9';
        });

        fileInputStyle.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.borderColor = '#ddd';
            this.style.background = '#f9f9f9';
            
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                
                // Trigger change event
                const event = new Event('change');
                fileInput.dispatchEvent(event);
            }
        });

        // Show file name when selected
        fileInput.addEventListener('change', function() {
            const fileName = this.files[0] ? this.files[0].name : 'Click to choose image or drag & drop here';
            const span = fileInputStyle.querySelector('span');
            span.textContent = fileName;
        });
    </script>
</body>
</html>