<?php
include 'db.php';

// Handle user deletion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $role = $_POST['role'];

    if ($role == 'superadmin') {
        $sql = "DELETE FROM superadmin WHERE id = ?";
    } else {
        $sql = "DELETE FROM users WHERE id = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = "User deleted successfully.";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Fetch all users including superadmins
$sql = "SELECT id, username, role FROM users
        UNION
        SELECT id, username, 'superadmin' AS role FROM superadmin";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>All Users</title>
</head>
<body class="bg-light">
    <?php
        include 'backend_header.php';
    ?>
    <div class="container mt-5">
        <h1>All Users</h1>
        <?php if (isset($message)): ?>
            <div class="alert alert-info"><?= $message ?></div>
        <?php endif; ?>
        <table class="table table-striped table-bordered shadow">
            <thead>
                <tr class="table-dark">
                    <th class="text-center">Username</th>
                    <th class="text-center">Role</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="text-center"><?= $row['username'] ?></td>
                        <td class="text-center"><?= $row['role'] ?></td>
                        <td class="text-center">
                            <form method="POST" action="list_users.php" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="role" value="<?= $row['role'] ?>">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php
        include 'backend_footer.php';
    ?>
