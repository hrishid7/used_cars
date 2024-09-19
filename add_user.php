<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: login.php");
    exit();
}

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        echo "User created successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php
        include 'backend_header.php';
    ?>
    <div class="container mt-5">
        <h1>Add User</h1>
        <form method="POST" action="add_user.php">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control shadow-sm" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control shadow-sm" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select shadow-sm" id="role" name="role" required>
                    <option value="user">User</option>
                    <option value="superadmin">Superadmin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-dark">Add User</button>
        </form>
    </div>
    <?php
        include 'backend_footer.php';
    ?>
