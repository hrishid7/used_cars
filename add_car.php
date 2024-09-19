<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$alertMessage = '';
$alertType = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brand_id = $_POST['brand_id'];
    $price_range_id = $_POST['price_range_id'];
    $name = $_POST['name'];
    $variant = $_POST['variant'];
    $transmission = $_POST['transmission'];
    $fuel = $_POST['fuel'];
    $mfg_year = $_POST['mfg_year'];
    $reg_date = $_POST['reg_date'];
    $owner = $_POST['owner'];
    $color = $_POST['color'];
    $kilometer = $_POST['kilometer'];
    $passing = $_POST['passing'];
    $insurance = $_POST['insurance'];
    $hypothication = $_POST['hypothication'];
    $price = $_POST['price'];
    $featured_image = $_FILES['featured_image']['name'];
    $gallery_image1 = $_FILES['gallery_image1']['name'];
    $gallery_image2 = $_FILES['gallery_image2']['name'];
    $gallery_image3 = $_FILES['gallery_image3']['name'];
    $gallery_image4 = $_FILES['gallery_image4']['name'];
    $gallery_image5 = $_FILES['gallery_image5']['name'];
    $available = $_POST['available'];

    move_uploaded_file($_FILES['featured_image']['tmp_name'], "uploads/$featured_image");
    move_uploaded_file($_FILES['gallery_image1']['tmp_name'], "uploads/$gallery_image1");
    move_uploaded_file($_FILES['gallery_image2']['tmp_name'], "uploads/$gallery_image2");
    move_uploaded_file($_FILES['gallery_image3']['tmp_name'], "uploads/$gallery_image3");
    move_uploaded_file($_FILES['gallery_image4']['tmp_name'], "uploads/$gallery_image4");
    move_uploaded_file($_FILES['gallery_image5']['tmp_name'], "uploads/$gallery_image5");

    $sql = "INSERT INTO cars (brand_id, price_range_id, name, variant, transmission, fuel, mfg_year, reg_date, owner, color, kilometer, passing, insurance, hypothication, price, featured_image, gallery_image1, gallery_image2, gallery_image3, gallery_image4, gallery_image5, available) 
            VALUES ('$brand_id', '$price_range_id', '$name', '$variant', '$transmission', '$fuel', '$mfg_year', '$reg_date', '$owner', '$color', '$kilometer', '$passing', '$insurance', '$hypothication', '$price', '$featured_image', '$gallery_image1', '$gallery_image2', '$gallery_image3', '$gallery_image4', '$gallery_image5', '$available')";

    if ($conn->query($sql) === TRUE) {
        $alertMessage = "New car added successfully!";
        $alertType = "success";
    } else {
        $alertMessage = "Error: " . $sql . "<br>" . $conn->error;
        $alertType = "danger";
    }
}

$brands = $conn->query("SELECT * FROM car_brands");
$price_ranges = $conn->query("SELECT * FROM price_ranges");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Add Car</title>
</head>
<body class="bg-light">
    <?php
        include 'backend_header.php';
    ?>

    <div class="container mt-5 mb-5 ">
        <h1>Add Car</h1>
        <?php if ($alertMessage): ?>
            <div class="alert alert-<?= $alertType ?> alert-dismissible fade show" role="alert">
                <?= $alertMessage ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <form method="POST" action="add_car.php" enctype="multipart/form-data">
            <div class="row">
            <div class="mb-3 col-md-4 mb-3">
                <label for="brand_id" class="form-label">Car Brand</label>
                <select class="form-select shadow-sm" id="brand_id" name="brand_id" required>
                    <?php while($row = $brands->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3 col-md-4 mb-3">
                <label for="price_range_id" class="form-label">Price Range</label>
                <select class="form-select shadow-sm" id="price_range_id" name="price_range_id" required>
                    <?php while($row = $price_ranges->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>"><?= $row['range_name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3 col-md-4 mb-3">
                <label for="name" class="form-label">Car Name</label>
                <input type="text" class="form-control shadow-sm" id="name" name="name" required>
            </div>
            <div class="mb-3 col-md-4 mb-3">
                <label for="variant" class="form-label">Variant</label>
                <input type="text" class="form-control shadow-sm" id="variant" name="variant" required>
            </div>
            <div class="mb-3 col-md-4 mb-3">
                <label for="transmission" class="form-label">Transmission</label>
                <input type="text" class="form-control shadow-sm" id="transmission" name="transmission" required>
            </div>
            <div class="mb-3 col-md-4 mb-3">
                <label for="fuel" class="form-label">Fuel</label>
                <input type="text" class="form-control shadow-sm" id="fuel" name="fuel" required>
            </div>
            <div class="mb-3 col-md-4 mb-3">
                <label for="mfg_year" class="form-label">Manufacturing Year</label>
                <input type="text" class="form-control shadow-sm" id="mfg_year" name="mfg_year" required>
            </div>
            <div class="mb-3 col-md-4 mb-3">
                <label for="reg_date" class="form-label">Registration Date</label>
                <input type="date" class="form-control shadow-sm" id="reg_date" name="reg_date" required>
            </div>
            <div class="mb-3 col-md-4 mb-3">
                <label for="owner" class="form-label">Owner</label>
                <input type="text" class="form-control shadow-sm" id="owner" name="owner" required>
            </div>
            <div class="mb-3 col-md-4 mb-3">
                <label for="color" class="form-label">Color</label>
                <input type="text" class="form-control shadow-sm" id="color" name="color" required>
            </div>
            <div class="mb-3 col-md-4 mb-3">
                <label for="kilometer" class="form-label">Kilometer</label>
                <input type="text" class="form-control shadow-sm" id="kilometer" name="kilometer" required>
            </div>
            <div class="mb-3 col-md-4 mb-3">
                <label for="passing" class="form-label">Passing</label>
                <input type="text" class="form-control shadow-sm" id="passing" name="passing" required>
            </div>
            <div class="mb-3 col-md-4 mb-3">
                <label for="insurance" class="form-label">Insurance Expiry Date</label>
                <input type="date" class="form-control shadow-sm" id="insurance" name="insurance" required>
            </div>
            <div class="mb-3 col-md-4 mb-3">
                <label for="hypothication" class="form-label">Hypothication</label>
                <input type="text" class="form-control shadow-sm" id="hypothication" name="hypothication" required>
            </div>
            <div class="mb-3 col-md-4 mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="text" class="form-control shadow-sm" id="price" name="price" required>
            </div>
            <div class="mb-3 col-md-4 mb-3">
                <label for="featured_image" class="form-label">Featured Image</label>
                <input type="file" class="form-control shadow-sm" id="featured_image" name="featured_image" required>
            </div>
            <div class="mb-3 col-md-4 mb-3">
                <label for="gallery_image1" class="form-label">Gallery Image 1</label>
                <input type="file" class="form-control shadow-sm" id="gallery_image1" name="gallery_image1" required>
            </div>
            <div class="mb-3 col-md-4 mb-3">
                <label for="gallery_image2" class="form-label">Gallery Image 2</label>
                <input type="file" class="form-control shadow-sm" id="gallery_image2" name="gallery_image2" required>
            </div>
            <div class="mb-3 col-md-4 mb-3">
                <label for="gallery_image3" class="form-label">Gallery Image 3</label>
                <input type="file" class="form-control shadow-sm" id="gallery_image3" name="gallery_image3" required>
            </div>
            <div class="mb-3 col-md-4 mb-3">
                <label for="gallery_image4" class="form-label">Gallery Image 4</label>
                <input type="file" class="form-control shadow-sm" id="gallery_image4" name="gallery_image4" required>
            </div>
            <div class="mb-3 col-md-4 mb-3">
                <label for="gallery_image5" class="form-label">Gallery Image 5</label>
                <input type="file" class="form-control shadow-sm" id="gallery_image5" name="gallery_image5" required>
            </div>
            <div class="mb-3 col-md-4 mb-3">
                <label for="available" class="form-label">Availability</label>
                <div class="col-md-6 mb-3">
    <div class="d-flex align-items-center">
        <div class="form-check me-3">
            <input class="form-check-input" type="radio" name="available" id="available1" value="1" checked>
            <label class="form-check-label" for="available1">
                Available
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="available" id="available2" value="0">
            <label class="form-check-label" for="available2">
                Unavailable
            </label>
        </div>
    </div>
</div>

            </div>
            <button type="submit" class="btn btn-dark">Add Car</button>
            </div>
        </form>
    </div>
    <?php
        include 'backend_footer.php';
    ?>