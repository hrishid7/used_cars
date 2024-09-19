<?php
// Include the database connection
include 'db.php';

// Query to get the total number of cars
$sqlCars = "SELECT COUNT(*) as car_count FROM cars";
$resultCars = $conn->query($sqlCars);
$carCount = ($resultCars->num_rows > 0) ? $resultCars->fetch_assoc()['car_count'] : 0;

// Query to get the total number of brands
$sqlBrands = "SELECT COUNT(*) as brand_count FROM car_brands";
$resultBrands = $conn->query($sqlBrands);
$brandCount = ($resultBrands->num_rows > 0) ? $resultBrands->fetch_assoc()['brand_count'] : 0;

// Query to get the total number of price ranges
$sqlPriceRanges = "SELECT COUNT(*) as price_range_count FROM price_ranges";
$resultPriceRanges = $conn->query($sqlPriceRanges);
$priceRangeCount = ($resultPriceRanges->num_rows > 0) ? $resultPriceRanges->fetch_assoc()['price_range_count'] : 0;

// Query to get the total number of enquiries
$sqlEnquiries = "SELECT COUNT(*) as enquiry_count FROM enquiries";
$resultEnquiries = $conn->query($sqlEnquiries);
$enquiryCount = ($resultEnquiries->num_rows > 0) ? $resultEnquiries->fetch_assoc()['enquiry_count'] : 0;

// Query to get the number of enquiries by month (for the chart)
// Query to get the number of enquiries by month (replace 'enquiry_date' with the correct column name)
$sqlEnquiryByMonth = "SELECT MONTH(enquiry_date) as month, COUNT(*) as count FROM enquiries GROUP BY MONTH(enquiry_date)";
$resultEnquiryByMonth = $conn->query($sqlEnquiryByMonth);

$enquiriesByMonth = [];
while ($row = $resultEnquiryByMonth->fetch_assoc()) {
    $enquiriesByMonth[(int)$row['month']] = (int)$row['count'];
}

// Query to get the number of enquiries by brand
$sqlEnquiryByBrand = "SELECT car_brands.name AS brand, COUNT(enquiries.id) AS count 
                      FROM enquiries 
                      JOIN cars ON enquiries.car_id = cars.id
                      JOIN car_brands ON cars.brand_id = car_brands.id
                      GROUP BY car_brands.name";
$resultEnquiryByBrand = $conn->query($sqlEnquiryByBrand);
$enquiryByBrandData = [];
$totalEnquiries = 0; // Initialize total count
while ($row = $resultEnquiryByBrand->fetch_assoc()) {
    $enquiryByBrandData[] = $row;
    $totalEnquiries += $row['count']; // Sum up total enquiries
}

// Pass total inquiries to JavaScript
echo "<script>const totalEnquiries = $totalEnquiries;</script>";



// Close the database connection
$conn->close();

// Prepare data for the chart
$months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
$enquiryData = [];
foreach ($months as $monthNum => $monthName) {
    $enquiryData[] = $enquiriesByMonth[$monthNum] ?? 0;  // Use 0 if no inquiries for that month
}
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
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Add Chart.js -->
</head>
<body class="bg-light">
    <?php include 'backend_header.php'; ?>

    <div class="container mt-5 mb-5 p-5 bg-white shadow-sm rounded">
        <h1 class=" mb-4">Dashboard</h1>
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm bg-danger bg-gradient text-white">
                    <div class="card-body">
                        <h3 class="card-title">Cars</h3>
                        <p class="display-4"><?php echo $carCount; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm bg-warning bg-gradient text-white">
                    <div class="card-body">
                        <h3 class="card-title">Enquiries</h3>
                        <p class="display-4"><?php echo $enquiryCount; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm bg-info bg-gradient text-white">
                    <div class="card-body">
                        <h3 class="card-title">Brands</h3>
                        <p class="display-4"><?php echo $brandCount; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inquiry Chart -->
        <div class="row mt-5">
            <div class="col-8">
                <div class="card shadow-sm bg-white">
                    <div class="card-body">
                        <h3 class="card-title text-center">Enquiries Over the Year</h3>
                        <canvas id="enquiryChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card shadow-sm bg-white" style="padding-bottom:13px;">
                    <div class="card-body">
                        <h3 class="text-center mb-4">Enquiries by Brand</h3>
                        <canvas id="enquiryPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Data from PHP
        const enquiryByBrandData = <?php echo json_encode($enquiryByBrandData); ?>;

        // Extract labels and data
        const labels = enquiryByBrandData.map(item => item.brand);
        const data = enquiryByBrandData.map(item => item.count);

        // Calculate percentages
        const percentages = data.map(count => ((count / totalEnquiries) * 100).toFixed(2));

        // Pie Chart Configuration
        const ctx = document.getElementById('enquiryPieChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Enquiries by Brand',
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const percentage = ((value / totalEnquiries) * 100).toFixed(2);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>

    <script>
        // Prepare data for the chart
        const enquiryData = <?php echo json_encode($enquiryData); ?>;
        const monthLabels = <?php echo json_encode(array_values($months)); ?>;

        // Initialize Chart.js
        const ctx = document.getElementById('enquiryChart').getContext('2d');
        const enquiryChart = new Chart(ctx, {
            type: 'line',  // Type of chart
            data: {
                labels: monthLabels,  // Month labels
                datasets: [{
                    label: 'Number of Enquiries',
                    data: enquiryData,  // Inquiry data for each month
                    backgroundColor: 'rgba(44, 162, 235, 1)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true  // Start y-axis from 0
                    }
                }
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
