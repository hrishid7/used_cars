<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $car_id = $_POST['car_id'];
    $available = $_POST['available'];

    $sql = "UPDATE cars SET available = '$available' WHERE id = '$car_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Car availability updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
