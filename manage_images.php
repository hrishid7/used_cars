<?php
// manage_images.php

$uploadDir = 'uploads/';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $imageToDelete = $_POST['image'];
    $filePath = $uploadDir . $imageToDelete;

    if (file_exists($filePath)) {
        unlink($filePath);
        $message = "Image deleted successfully!";
    } else {
        $message = "Image not found!";
    }
}

$images = array_diff(scandir($uploadDir), array('.', '..'));
?>
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Manage Images</title>
</head>
<body class="bg-light">
    <?php
        include 'backend_header.php';
    ?>
    <div class="container mt-5">
        <h1>Manage Images</h1>
        <?php if(isset($message)): ?>
            <div class="alert alert-info"><?= $message ?></div>
        <?php endif; ?>
        <table class="table table-bordered shadow">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>File Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($images as $image): ?>
                    <tr>
                        <td><img src="<?= $uploadDir . $image ?>" alt="<?= $image ?>" width="100"></td>
                        <td><?= htmlspecialchars($image) ?></td>
                        <td>
                            <form method="POST" action="manage_images.php" class="d-inline">
                                <input type="hidden" name="image" value="<?= htmlspecialchars($image) ?>">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
        include 'backend_footer.php';
    ?>
