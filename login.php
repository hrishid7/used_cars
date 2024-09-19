<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // First, check if the user is a superadmin
    $sql = "SELECT * FROM superadmin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = 'superadmin';
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Invalid credentials.";
        }
    } else {
        // If not superadmin, check if the user is a regular user
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Invalid credentials.";
            }
        } else {
            echo "Invalid credentials.";
        }
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
        .container {
            display: flex;
            justify-content: center; /* Align horizontally */
            align-items: center;     /* Align vertically */
            height: 100vh;           /* Full viewport height */
        }
        form {
            width: 100%;
            max-width: 400px;       /* Optional: limit form width */
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <form method="POST" action="login.php" class="border p-5 rounded shadow bg-white">
            <h2>Login</h2>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control shadow-sm" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control shadow-sm" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-dark w-100 mt-2">Login</button>
        </form>
    </div>
</body>
</html>
