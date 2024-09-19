<?php
include 'db.php';

header('Content-Type: application/json');

$valid_api_key = 'b7d0d93c-0a31-4845-a5fc-7cef41890745';

$headers = apache_request_headers();
$api_key = isset($headers['API-Key']) ? $headers['API-Key'] : '';

if ($api_key !== $valid_api_key) {
    http_response_code(401);
    echo json_encode(['message' => 'Unauthorized: Invalid API Key']);
    exit;
}

// Fetch car brands with at least one available car
$brands_sql = "
    SELECT DISTINCT cb.id, cb.name 
    FROM car_brands cb
    JOIN cars c ON c.brand_id = cb.id
    WHERE c.available = 1";

$brands_result = $conn->query($brands_sql);

$brands = [];
while ($row = $brands_result->fetch_assoc()) {
    $brands[] = $row;
}

// Fetch price ranges with at least one available car
$price_ranges_sql = "
    SELECT DISTINCT pr.id, pr.range_name 
    FROM price_ranges pr
    JOIN cars c ON c.price_range_id = pr.id
    WHERE c.available = 1";

$price_ranges_result = $conn->query($price_ranges_sql);

$price_ranges = [];
while ($row = $price_ranges_result->fetch_assoc()) {
    $price_ranges[] = $row;
}

$response = [
    'car_brands' => $brands,
    'price_ranges' => $price_ranges
];

echo json_encode($response);

$conn->close();
?>
