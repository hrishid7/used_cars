<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? null;
$successMessage = '';
$errorMessage = '';

if ($id) {
    // Fetch car details
    $sql = "SELECT * FROM cars WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $car = $result->fetch_assoc();

    // Fetch car brands and price ranges for dropdowns
    $brands_sql = "SELECT id, name FROM car_brands";
    $brands_result = $conn->query($brands_sql);

    $prices_sql = "SELECT id, range_name FROM price_ranges";
    $prices_result = $conn->query($prices_sql);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Handle form submission to update the car details

        // Define upload directory
        $uploadDir = 'uploads/';
        
        // Function to handle file upload and return the new file name or existing one if not changed
        function uploadImage($fileInput, $currentImage) {
            global $uploadDir;
            if (!empty($_FILES[$fileInput]['name'])) {
                $newImageName = basename($_FILES[$fileInput]['name']);
                move_uploaded_file($_FILES[$fileInput]['tmp_name'], $uploadDir . $newImageName);
                return $newImageName;
            }
            return $currentImage; // If no new file is uploaded, retain the current image
        }

        // Get the updated images
        $featured_image = uploadImage('featured_image', $car['featured_image']);
        $gallery_image1 = uploadImage('gallery_image1', $car['gallery_image1']);
        $gallery_image2 = uploadImage('gallery_image2', $car['gallery_image2']);
        $gallery_image3 = uploadImage('gallery_image3', $car['gallery_image3']);
        $gallery_image4 = uploadImage('gallery_image4', $car['gallery_image4']);
        $gallery_image5 = uploadImage('gallery_image5', $car['gallery_image5']);
        
        // Other form fields
        $brand_id = $_POST['brand_id'];
        $name = $_POST['name'];
        $variant = $_POST['variant'];
        $transmission = $_POST['transmission'];
        $fuel = $_POST['fuel'];
        $mfg_year = $_POST['mfg_year'];
        $price_range_id = $_POST['price_range_id'];
        $reg_date = $_POST['reg_date'];
        $owner = $_POST['owner'];
        $color = $_POST['color'];
        $kilometer = $_POST['kilometer'];
        $passing = $_POST['passing'];
        $insurance = $_POST['insurance'];
        $hypothication = $_POST['hypothication'];
        $price = $_POST['price'];
        $available = $_POST['available'];

        // Update car details
        $sql = "UPDATE cars SET 
            brand_id = ?, 
            name = ?, 
            variant = ?, 
            transmission = ?, 
            fuel = ?, 
            mfg_year = ?, 
            price_range_id = ?, 
            reg_date = ?, 
            owner = ?, 
            color = ?, 
            kilometer = ?, 
            passing = ?, 
            insurance = ?, 
            hypothication = ?, 
            price = ?, 
            featured_image = ?, 
            gallery_image1 = ?, 
            gallery_image2 = ?, 
            gallery_image3 = ?, 
            gallery_image4 = ?, 
            gallery_image5 = ?, 
            available = ? 
            WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('isssssisssissssssssssii', 
            $brand_id, 
            $name, 
            $variant, 
            $transmission, 
            $fuel, 
            $mfg_year, 
            $price_range_id, 
            $reg_date, 
            $owner, 
            $color, 
            $kilometer, 
            $passing, 
            $insurance, 
            $hypothication, 
            $price, 
            $featured_image, 
            $gallery_image1, 
            $gallery_image2, 
            $gallery_image3, 
            $gallery_image4, 
            $gallery_image5, 
            $available, 
            $id
        );

        if ($stmt->execute()) {
            $successMessage = "Car details updated successfully!";
        } else {
            $errorMessage = "Failed to update car details.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Car</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'backend_header.php'; ?>
    <div class="container mt-5 mb-5">
        <h1 class="mb-4">Edit Car</h1>

        <!-- Display Success or Error Messages -->
        <?php if ($successMessage): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $successMessage; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $errorMessage; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($car): ?>
            <form method="POST" enctype="multipart/form-data">
                <!-- Car Brand -->
                <div class="row">
                    <div class="mb-3 col-md-4 mb-3">
                        <label for="brand_id" class="form-label">Car Brand</label>
                        <select class="form-select shadow-sm" id="brand_id" name="brand_id" required>
                            <?php while ($brand = $brands_result->fetch_assoc()): ?>
                                <option value="<?php echo $brand['id']; ?>" <?php echo $car['brand_id'] == $brand['id'] ? 'selected' : ''; ?>>
                                    <?php echo $brand['name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- Car Name -->
                    <div class="mb-3 col-md-4 mb-3">
                        <label for="name" class="form-label">Car Name</label>
                        <input type="text" class="form-control shadow-sm" id="name" name="name" value="<?php echo $car['name']; ?>" required>
                    </div>

                    <!-- Variant -->
                    <div class="mb-3 col-md-4 mb-3">
                        <label for="variant" class="form-label">Variant</label>
                        <input type="text" class="form-control shadow-sm" id="variant" name="variant" value="<?php echo $car['variant']; ?>" required>
                    </div>

                    <!-- Transmission -->
                    <div class="mb-3 col-md-4 mb-3">
                        <label for="transmission" class="form-label">Transmission</label>
                        <input type="text" class="form-control shadow-sm" id="transmission" name="transmission" value="<?php echo $car['transmission']; ?>" required>
                    </div>

                    <!-- Fuel -->
                    <div class="mb-3 col-md-4 mb-3">
                        <label for="fuel" class="form-label">Fuel</label>
                        <input type="text" class="form-control shadow-sm" id="fuel" name="fuel" value="<?php echo $car['fuel']; ?>" required>
                    </div>

                    <!-- MFG Year -->
                    <div class="mb-3 col-md-4 mb-3">
                        <label for="mfg_year" class="form-label">MFG Year</label>
                        <input type="number" class="form-control shadow-sm" id="mfg_year" name="mfg_year" value="<?php echo $car['mfg_year']; ?>" required>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-3 col-md-4 mb-3">
                        <label for="price_range_id" class="form-label">Price Range</label>
                        <select class="form-select shadow-sm" id="price_range_id" name="price_range_id" required>
                            <?php while ($price = $prices_result->fetch_assoc()): ?>
                                <option value="<?php echo $price['id']; ?>" <?php echo $car['price_range_id'] == $price['id'] ? 'selected' : ''; ?>>
                                    <?php echo $price['range_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- Registration Date -->
                    <div class="mb-3 col-md-4 mb-3">
                        <label for="reg_date" class="form-label">Registration Date</label>
                        <input type="date" class="form-control shadow-sm" id="reg_date" name="reg_date" value="<?php echo $car['reg_date']; ?>" required>
                    </div>

                    <!-- Owner -->
                    <div class="mb-3 col-md-4 mb-3">
                        <label for="owner" class="form-label">Owner</label>
                        <input type="text" class="form-control shadow-sm" id="owner" name="owner" value="<?php echo $car['owner']; ?>" required>
                    </div>

                    <!-- Color -->
                    <div class="mb-3 col-md-4 mb-3">
                        <label for="color" class="form-label">Color</label>
                        <input type="text" class="form-control shadow-sm" id="color" name="color" value="<?php echo $car['color']; ?>" required>
                    </div>

                    <!-- Kilometer -->
                    <div class="mb-3 col-md-4 mb-3">
                        <label for="kilometer" class="form-label">Kilometer</label>
                        <input type="number" class="form-control shadow-sm" id="kilometer" name="kilometer" value="<?php echo $car['kilometer']; ?>" required>
                    </div>

                    <!-- Passing -->
                    <div class="mb-3 col-md-4 mb-3">
                        <label for="passing" class="form-label">Passing</label>
                        <input type="text" class="form-control shadow-sm" id="passing" name="passing" value="<?php echo $car['passing']; ?>" required>
                    </div>

                    <!-- Insurance -->
                    <div class="mb-3 col-md-4 mb-3">
                        <label for="insurance" class="form-label">Insurance</label>
                        <input type="text" class="form-control shadow-sm" id="insurance" name="insurance" value="<?php echo $car['insurance']; ?>" required>
                    </div>

                    <!-- Hypothication -->
                    <div class="mb-3 col-md-4 mb-3">
                        <label for="hypothication" class="form-label">Hypothication</label>
                        <input type="text" class="form-control shadow-sm" id="hypothication" name="hypothication" value="<?php echo $car['hypothication']; ?>" required>
                    </div>

                    <!-- Price -->
                    <div class="mb-3 col-md-4 mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="text" class="form-control shadow-sm" id="price" name="price" value="<?php echo $car['price']; ?>" required>
                    </div>

                    <!-- Featured Image -->
                    <div class="mb-3 col-md-4 mb-3">
                        <label for="featured_image" class="form-label">Featured Image</label>
                        <input type="file" class="form-control shadow-sm" id="featured_image" name="featured_image">
                        <img src="uploads/<?php echo $car['featured_image']; ?>" alt="Current Featured Image" class="img-thumbnail mt-2" width="150">
                    </div>

                    <!-- Gallery Image 1 -->
                    <div class="mb-3 col-md-4 mb-3">
                        <label for="gallery_image1" class="form-label">Gallery Image 1</label>
                        <input type="file" class="form-control shadow-sm" id="gallery_image1" name="gallery_image1">
                        <img src="uploads/<?php echo $car['gallery_image1']; ?>" alt="Current Gallery Image 1" class="img-thumbnail mt-2" width="150">
                    </div>

                    <!-- Gallery Image 2 -->
                    <div class="mb-3 col-md-4 mb-3">
                        <label for="gallery_image2" class="form-label">Gallery Image 2</label>
                        <input type="file" class="form-control shadow-sm" id="gallery_image2" name="gallery_image2">
                        <img src="uploads/<?php echo $car['gallery_image2']; ?>" alt="Current Gallery Image 2" class="img-thumbnail mt-2" width="150">
                    </div>

                    <!-- Gallery Image 3 -->
                    <div class="mb-3 col-md-4 mb-3">
                        <label for="gallery_image3" class="form-label">Gallery Image 3</label>
                        <input type="file" class="form-control shadow-sm" id="gallery_image3" name="gallery_image3">
                        <img src="uploads/<?php echo $car['gallery_image3']; ?>" alt="Current Gallery Image 3" class="img-thumbnail mt-2" width="150">
                    </div>

                    <!-- Gallery Image 4 -->
                    <div class="mb-3 col-md-4 mb-3">
                        <label for="gallery_image4" class="form-label">Gallery Image 4</label>
                        <input type="file" class="form-control shadow-sm" id="gallery_image4" name="gallery_image4">
                        <img src="uploads/<?php echo $car['gallery_image4']; ?>" alt="Current Gallery Image 4" class="img-thumbnail mt-2" width="150">
                    </div>

                    <!-- Gallery Image 5 -->
                    <div class="mb-3 col-md-4 mb-3">
                        <label for="gallery_image5" class="form-label">Gallery Image 5</label>
                        <input type="file" class="form-control shadow-sm" id="gallery_image5" name="gallery_image5">
                        <img src="uploads/<?php echo $car['gallery_image5']; ?>" alt="Current Gallery Image 5" class="img-thumbnail mt-2" width="150">
                    </div>
                    <!-- Availability -->
                    <div class="mb-3 col-md-4 mb-3">
                    <label class="form-label">Availability</label>
                    <div class="d-flex">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="radio" id="available_yes" name="available" value="1" <?php echo $car['available'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="available_yes">Available</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="available_no" name="available" value="0" <?php echo !$car['available'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="available_no">Not Available</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-dark w-100">Update Car</button>
            </form>
        <?php else: ?>
            <div class="alert alert-warning">Car not found.</div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
