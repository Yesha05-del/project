<?php
// Generate password hash for admin123
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Password: " . $password . "\n";
echo "Hash: " . $hash . "\n";
echo "\n";
echo "SQL Query to insert admin:\n";
echo "INSERT INTO users (username, email, password, phone, city, gender, user_type, created_at) \n";
echo "VALUES ('admin', 'admin@desiroutes.com', '$hash', '9999999999', 'Admin City', 'male', 'admin', NOW());\n";
?>
