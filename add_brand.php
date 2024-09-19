<?php
session_start();
include 'db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Add New Brand
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_brand'])) {
    $name = $_POST['name'];
    $sql = "INSERT INTO car_brands (name) VALUES ('$name')";
    if ($conn->query($sql)) {
        $_SESSION['message'] = "Brand added successfully!";
        $_SESSION['message_type'] = "success";
    }
}

// Edit Brand
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_brand'])) {
    $brand_id = $_POST['brand_id'];
    $name = $_POST['edit_name'];
    $sql = "UPDATE car_brands SET name='$name' WHERE id=$brand_id";
    if ($conn->query($sql)) {
        $_SESSION['message'] = "Brand updated successfully!";
        $_SESSION['message_type'] = "success";
    }
}

// Delete Brand
if (isset($_GET['delete_brand'])) {
    $brand_id = $_GET['delete_brand'];
    $sql = "DELETE FROM car_brands WHERE id=$brand_id";
    if ($conn->query($sql)) {
        $_SESSION['message'] = "Brand deleted successfully!";
        $_SESSION['message_type'] = "danger";
        // Redirect to avoid showing the delete_brand in the URL
        header("Location: add_brand.php");
        exit();
    }
}

// Fetch Brands
$brands = $conn->query("SELECT * FROM car_brands");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Add Car Brand</title>
</head>
<body class="bg-light">
    <?php include 'backend_header.php'; ?>
    <div class="container mt-5">
        <h1>Add Car Brand</h1>
        <form method="POST" action="add_brand.php">
            <input type="hidden" name="add_brand" value="1">
            <div class="mb-3">
                <label for="name" class="form-label">Brand Name</label>
                <input type="text" class="form-control shadow-sm" id="name" name="name" required>
            </div>
            <button type="submit" class="btn btn-dark">Add Brand</button>
        </form>

        <!-- Display alert if message is set -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show mt-4" role="alert">
                <?php echo $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php
            // Clear the message after displaying it
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
            ?>
        <?php endif; ?>

        <h1 class="mt-5">Manage Car Brands</h1>
        <table class="table table-striped table-bordered shadow">
            <thead>
                <tr class="table-dark">
                    <th class="text-center">ID</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $brands->fetch_assoc()): ?>
                <tr>
                    <td class="text-center"><?= $row['id'] ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['name']) ?></td>
                    <td class="text-center">
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editBrandModal<?= $row['id'] ?>">Edit</button>
                        <button class="btn btn-danger" onclick="confirmDelete(<?= $row['id'] ?>)">Delete</button>
                    </td>
                </tr>

                <!-- Edit Brand Modal -->
                <div class="modal fade" id="editBrandModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editBrandModalLabel<?= $row['id'] ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editBrandModalLabel<?= $row['id'] ?>">Edit Brand</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="POST" action="add_brand.php">
                                <input type="hidden" name="edit_brand" value="1">
                                <input type="hidden" name="brand_id" value="<?= $row['id'] ?>">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="edit_name" class="form-label">Brand Name</label>
                                        <input type="text" class="form-control" id="edit_name" name="edit_name" value="<?= htmlspecialchars($row['name']) ?>" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this brand?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'backend_footer.php'; ?>
    <script>
        function confirmDelete(brandId) {
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            confirmDeleteBtn.href = 'add_brand.php?delete_brand=' + brandId;
            const deleteConfirmationModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
            deleteConfirmationModal.show();
        }
    </script>
</body>
</html>
