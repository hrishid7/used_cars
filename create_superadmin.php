<?php
include 'db.php';

$username = 'superadmin';  // Change to desired username
$password = password_hash('Kothari_Autobiz@2024', PASSWORD_DEFAULT);  // Change to desired password

$sql = "INSERT INTO superadmin (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);

if ($stmt->execute()) {
    echo "Superadmin created successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
