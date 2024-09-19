<?php
include 'db.php';

// Get brand_name from URL
$brand_name = isset($_GET['brand_name']) ? urldecode($_GET['brand_name']) : '';

// Fetch available cars for the specified brand from the database
$sql = "
    SELECT c.*, cb.name as brand_name, pr.range_name 
    FROM cars c
    JOIN car_brands cb ON c.brand_id = cb.id
    JOIN price_ranges pr ON c.price_range_id = pr.id
    WHERE cb.name = ? AND c.available = 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $brand_name);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are any available cars
$cars = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cars[] = $row;
    }
} else {
    $cars = []; // No cars available for the brand
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($brand_name); ?> Cars</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .divider {
            border-right: 3px solid #dee2e6;
        }
    </style>
</head>
<body class="bg-light">
     <div class="container-fluid bg-black bg-gradient text-white py-4 mt-5 pt-5">
        <h2 class="py-4 text-center display-3">
            <strong><?php echo htmlspecialchars($brand_name); ?></strong>
        </h2>
    </div>
    <?php include 'frontend_header.php'; ?>
    <div class="container  pt-5">
        <div class="row">
            <?php if (!empty($cars)): ?>
                <?php foreach ($cars as $car): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm">
                            <?php if (!empty($car['featured_image'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($car['featured_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($car['name']); ?>">
                            <?php else: ?>
                                <img src="uploads/default.jpg" class="card-img-top" alt="Default Image">
                            <?php endif; ?>
                            <div class="card-body">
                                <h4 class="card-title"><?php echo htmlspecialchars($car['mfg_year']); ?> <?php echo htmlspecialchars($car['brand_name']); ?> <?php echo htmlspecialchars($car['name']); ?> <?php echo htmlspecialchars($car['variant']); ?></h4>
                                <h5 class="card-title">Price ₹ <?php echo htmlspecialchars($car['price']); ?></h5>
                                <div class="row my-3">
                                    <div class="col-4 divider">
                                        <p class="card-text text-center"><?php echo htmlspecialchars($car['kilometer']); ?> Km</p>
                                    </div>
                                    <div class="col-4 divider">
                                        <p class="card-text text-center"><?php echo htmlspecialchars($car['fuel']); ?></p>
                                    </div>
                                    <div class="col-4">
                                        <p class="card-text text-center"><?php echo htmlspecialchars($car['passing']); ?></p>
                                    </div>
                                </div>
                                <a href="view_car.php?id=<?php echo $car['id']; ?>&name=<?php echo urlencode($car['name']); ?>" class="btn btn-dark w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info" role="alert">
                        No available cars for <?php echo htmlspecialchars($brand_name); ?>.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
