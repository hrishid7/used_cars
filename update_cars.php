<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch all cars from the database
$sql = "
    SELECT c.*, cb.name as brand_name, pr.range_name 
    FROM cars c
    JOIN car_brands cb ON c.brand_id = cb.id
    JOIN price_ranges pr ON c.price_range_id = pr.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Cars</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'backend_header.php'; ?>
    
    <div class="container mt-5">
        <h1 class="mb-4">Update Cars</h1>

        <!-- Display Bootstrap Alert if there's a session message -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
            ?>
        <?php endif; ?>

        <table class="table table-striped table-hover shadow">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Brand</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">Variant</th>
                    <th class="text-center">Transmission</th>
                    <th class="text-center">Fuel</th>
                    <th class="text-center">MFG Year</th>
                    <th class="text-center">Price Range</th>
                    <th class="text-center">Availability</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="text-center"><?php echo $row['id']; ?></td>
                        <td class="text-center"><?php echo $row['brand_name']; ?></td>
                        <td class="text-center"><?php echo $row['name']; ?></td>
                        <td class="text-center"><?php echo $row['variant']; ?></td>
                        <td class="text-center"><?php echo $row['transmission']; ?></td>
                        <td class="text-center"><?php echo $row['fuel']; ?></td>
                        <td class="text-center"><?php echo $row['mfg_year']; ?></td>
                        <td class="text-center"><?php echo $row['range_name']; ?></td>
                        <td class="text-center"><?php echo $row['available'] ? 'Available' : 'Unavailable'; ?></td>
                        <td class="text-center">
                            <?php 
                            // Encoding name to handle special characters in URL
                            $car_name_encoded = urlencode($row['name']);
                            ?>
                            <a href="view_car.php?id=<?php echo $row['id']; ?>&name=<?php echo $car_name_encoded; ?>" class="btn btn-info btn-sm">View</a>
                            <a href="edit_car.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-id="<?php echo $row['id']; ?>">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for Deletion Confirmation -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this car?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" id="deleteConfirmBtn" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'backend_footer.php'; ?>

    <script>
        var deleteModal = document.getElementById('confirmDeleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var carId = button.getAttribute('data-id'); // Extract info from data-* attributes
            var deleteUrl = 'delete_car.php?id=' + carId;
            var deleteConfirmBtn = document.getElementById('deleteConfirmBtn');
            deleteConfirmBtn.setAttribute('href', deleteUrl);
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>
