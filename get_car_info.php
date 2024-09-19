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

$brand_name = $_GET['brand_name'] ?? '';
$range_name = $_GET['range_name'] ?? '';

$sql = "
    SELECT c.*, cb.name as brand_name, pr.range_name 
    FROM cars c
    JOIN car_brands cb ON c.brand_id = cb.id
    JOIN price_ranges pr ON c.price_range_id = pr.id
    WHERE cb.name LIKE ? AND pr.range_name LIKE ? AND c.available = 1";

$stmt = $conn->prepare($sql);
$brand_name = "%$brand_name%";
$range_name = "%$range_name%";
$stmt->bind_param('ss', $brand_name, $range_name);
$stmt->execute();
$result = $stmt->get_result();

$car_info = [];
$host = $_SERVER['HTTP_HOST']; // Get the host (domain)
$script_name = dirname($_SERVER['SCRIPT_NAME']); // Get the directory of the script
$base_url = "http://$host$script_name/"; // Base URL of the website
$uploads_url = $base_url . "uploads/"; // URL path to the uploads folder

while ($row = $result->fetch_assoc()) {
    // Format dates to dd mm yyyy
    $reg_date = (new DateTime($row['reg_date']))->format('d m Y');
    $insurance_date = (new DateTime($row['insurance']))->format('d m Y');

    // Add car page URL
    $car_page_url = $base_url . "view_car.php?id={$row['id']}&name=" . urlencode($row['name']);

    // Construct full image URLs
    $featured_image_url = $uploads_url . $row['featured_image'];
    $gallery_image1_url = $uploads_url . $row['gallery_image1'];
    $gallery_image2_url = $uploads_url . $row['gallery_image2'];
    $gallery_image3_url = $uploads_url . $row['gallery_image3'];
    $gallery_image4_url = $uploads_url . $row['gallery_image4'];
    $gallery_image5_url = $uploads_url . $row['gallery_image5'];

    // Append formatted data
    $car_info[] = array_merge($row, [
        'reg_date' => $reg_date,
        'insurance' => $insurance_date,
        'car_page_url' => $car_page_url,
        'featured_image' => $featured_image_url,
        'gallery_image1' => $gallery_image1_url,
        'gallery_image2' => $gallery_image2_url,
        'gallery_image3' => $gallery_image3_url,
        'gallery_image4' => $gallery_image4_url,
        'gallery_image5' => $gallery_image5_url
    ]);
}

if (empty($car_info)) {
    echo json_encode(['message' => 'No cars found for the specified brand and price range']);
} else {
    echo json_encode($car_info);
}

$stmt->close();
$conn->close();
?>
