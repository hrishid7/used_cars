<?php

include 'db.php';


if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];


    $sql = "DELETE FROM enquiries WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $delete_id);

    if ($stmt->execute()) {

        session_start();
        $_SESSION['message'] = 'Inquiry deleted successfully!';
        $_SESSION['message_type'] = 'success';
    } else {

        session_start();
        $_SESSION['message'] = 'Failed to delete inquiry. Please try again.';
        $_SESSION['message_type'] = 'danger';
    }

 
    header('Location: website_inquiries.php');
    exit(); 
}



$sql = "
    SELECT e.id, c.mfg_year, cb.name as brand_name, c.name as car_name, c.color, e.name, e.phone, e.area, e.enquiry_date
    FROM enquiries e
    JOIN cars c ON e.car_id = c.id
    JOIN car_brands cb ON c.brand_id = cb.id
    ORDER BY e.enquiry_date DESC";
$result = $conn->query($sql);




session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Inquiries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
        <?php include 'backend_header.php'; ?>
    <div class="container mt-5 pt-5">
        <h1 class="mb-4">Website Inquiries</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php
            
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
            ?>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
            <table class="table table-striped table-hover shadow">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Mfg Year</th>
                        <th class="text-center">Brand Name</th>
                        <th class="text-center">Car Name</th>
                        <th class="text-center">Color</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Phone Number</th>
                        <th class="text-center">Area</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="text-center"><?php echo $row['id']; ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($row['mfg_year']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($row['brand_name']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($row['car_name']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($row['color']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($row['area']); ?></td>
                            <td class="text-center"><?php echo date('d-m-Y H:i:s', strtotime($row['enquiry_date'])); ?></td>
                            <td class="text-center">
                                <a href="website_inquiries.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                No inquiries found.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
