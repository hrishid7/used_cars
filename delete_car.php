// delete_car.php
<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM cars WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Car deleted successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error deleting car: " . $conn->error;
        $_SESSION['message_type'] = "danger";
    }

    $conn->close();
    header("Location: update_cars.php");
    exit();
}
?>
