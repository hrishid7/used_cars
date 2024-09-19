<?php
include 'db.php';

// Fetch car brands from the database
$brand_sql = "SELECT id, name FROM car_brands";
$brand_result = $conn->query($brand_sql);
$brands = [];
if ($brand_result->num_rows > 0) {
    while ($row = $brand_result->fetch_assoc()) {
        $brands[] = $row;
    }
}

// Fetch price ranges from the database
$price_sql = "SELECT id, range_name FROM price_ranges";
$price_result = $conn->query($price_sql);
$price_ranges = [];
if ($price_result->num_rows > 0) {
    while ($row = $price_result->fetch_assoc()) {
        $price_ranges[] = $row;
    }
}

// Initialize the SQL query for fetching cars
$sql = "
    SELECT c.*, cb.name as brand_name, pr.range_name 
    FROM cars c
    JOIN car_brands cb ON c.brand_id = cb.id
    JOIN price_ranges pr ON c.price_range_id = pr.id
    WHERE c.available = 1";

// Check if filters are applied
$brand_filter = isset($_GET['brand']) ? $_GET['brand'] : '';
$price_filter = isset($_GET['price_range']) ? $_GET['price_range'] : '';

if ($brand_filter) {
    $sql .= " AND c.brand_id = $brand_filter";
}
if ($price_filter) {
    $sql .= " AND c.price_range_id = $price_filter";
}

$result = $conn->query($sql);

// Check if there are any available cars
$cars = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cars[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kothari Autobiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .divider {
            border-right: 3px solid #dee2e6; /* Change color and style as needed */
        }
    </style>
</head>
<body class="bg-light">
    <?php include 'frontend_header.php'; ?>

    <div class="container mt-5 pt-5">
        <!-- Filter Form -->
        <form class="row g-3 mb-4" method="GET" action="index.php">
            <div class="col-md-4">
                <label for="brand" class="form-label">Select Car Brand</label>
                <select class="form-select" id="brand" name="brand">
                    <option value="">All Brands</option>
                    <?php foreach ($brands as $brand): ?>
                        <option value="<?php echo $brand['id']; ?>" <?php if ($brand_filter == $brand['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($brand['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="price_range" class="form-label">Select Price Range</label>
                <select class="form-select" id="price_range" name="price_range">
                    <option value="">All Price Ranges</option>
                    <?php foreach ($price_ranges as $price_range): ?>
                        <option value="<?php echo $price_range['id']; ?>" <?php if ($price_filter == $price_range['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($price_range['range_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 align-self-end">
                <button type="submit" class="btn btn-dark w-100">Search</button>
            </div>
        </form>

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
                        No available cars at the moment.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
