<?php
// run this once para mag insert ng admin user
require 'db.php';

$email = "admin@carriehotel.com";
$username = "Administrator";
$password = password_hash("admin123", PASSWORD_DEFAULT);

$sql = "INSERT INTO admins (username, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $email, $password);

if ($stmt->execute()) {
    echo "Admin account created!";
} else {
    echo "Error: " . $conn->error;
}
$stmt->close();
$conn->close();
?>
