<?php
include 'db.php';
session_start();

// Add New Price Range
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_price_range'])) {
    $range_name = $_POST['range_name'];
    $sql = "INSERT INTO price_ranges (range_name) VALUES ('$range_name')";
    $conn->query($sql);
}

// Edit Price Range
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_price_range'])) {
    $range_id = $_POST['range_id'];
    $range_name = $_POST['edit_range_name'];
    $sql = "UPDATE price_ranges SET range_name='$range_name' WHERE id=$range_id";
    $conn->query($sql);
}

// Delete Price Range
if (isset($_GET['delete_range'])) {
    $range_id = $_GET['delete_range'];
    $sql = "DELETE FROM price_ranges WHERE id=$range_id";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Price range deleted successfully!";
    } else {
        $_SESSION['message'] = "Error deleting price range!";
    }
    header("Location: add_price_range.php");
    exit();
}

// Fetch Price Ranges
$price_ranges = $conn->query("SELECT * FROM price_ranges");

// Check user session
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
    <title>Add Price Range</title>
</head>
<body class="bg-light">
    <?php include 'backend_header.php'; ?>
    <div class="container mt-5">

        <!-- Display Alert -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?= $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <h1>Add Price Range</h1>
        <form method="POST" action="add_price_range.php">
            <input type="hidden" name="add_price_range" value="1">
            <div class="mb-3">
                <label for="range_name" class="form-label">Price Range</label>
                <input type="text" class="form-control shadow-sm" id="range_name" name="range_name" required>
            </div>
            <button type="submit" class="btn btn-dark">Add Price Range</button>
        </form>

        <h1 class="mt-5">Manage Price Ranges</h1>
        <table class="table table-striped table-bordered shadow">
            <thead>
                <tr class="table-dark">
                    <th class="text-center">ID</th>
                    <th class="text-center">Range Name</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $price_ranges->fetch_assoc()): ?>
                <tr>
                    <td class="text-center"><?= $row['id'] ?></td>
                    <td class="text-center"><?= $row['range_name'] ?></td>
                    <td class="text-center">
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editPriceRangeModal<?= $row['id'] ?>">Edit</button>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" data-range-id="<?= $row['id'] ?>">Delete</button>
                    </td>
                </tr>

                <!-- Edit Price Range Modal -->
                <div class="modal fade" id="editPriceRangeModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editPriceRangeModalLabel<?= $row['id'] ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editPriceRangeModalLabel<?= $row['id'] ?>">Edit Price Range</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="POST" action="add_price_range.php">
                                <input type="hidden" name="edit_price_range" value="1">
                                <input type="hidden" name="range_id" value="<?= $row['id'] ?>">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="edit_range_name" class="form-label">Range Name</label>
                                        <input type="text" class="form-control" id="edit_range_name" name="edit_range_name" value="<?= $row['range_name'] ?>" required>
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
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this price range?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a id="confirmDeleteButton" href="#" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'backend_footer.php'; ?>



    <script>
        var deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
        deleteConfirmationModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var rangeId = button.getAttribute('data-range-id');
            var confirmDeleteButton = document.getElementById('confirmDeleteButton');
            confirmDeleteButton.href = 'add_price_range.php?delete_range=' + rangeId;
        });
    </script>
</body>
</html>
