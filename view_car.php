<?php
include 'db.php';

// Fetch car details based on ID and name
if (isset($_GET['id']) && isset($_GET['name'])) {
    $id = $_GET['id'];
    $name = $_GET['name'];

    $sql = "
        SELECT c.*, cb.name as brand_name, pr.range_name 
        FROM cars c
        JOIN car_brands cb ON c.brand_id = cb.id
        JOIN price_ranges pr ON c.price_range_id = pr.id
        WHERE c.id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $car = $result->fetch_assoc();
    } else {
        echo "Car not found.";
        exit();
    }
} else {
    echo "Invalid car details.";
    exit();
}

// Function to format the date
function formatDate($date) {
    return date('d-m-Y', strtotime($date));
}

// Handle enquiry form submission
$enquirySuccess = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enquire'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $area = $conn->real_escape_string($_POST['area']);
    
    $sql = "INSERT INTO enquiries (car_id, name, phone, area) VALUES ('$id', '$name', '$phone', '$area')";
    
    if ($conn->query($sql) === TRUE) {
        $enquirySuccess = true;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Car - <?php echo htmlspecialchars($car['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'frontend_header.php'; ?>

    <div class="container mt-5 mb-5">
        <div class="row">
            <!-- Carousel Column -->
            <div class="col-lg-8 mt-5">
                <div id="carouselGallery" class="carousel slide mb-3" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <!-- Featured Image as the First Carousel Item -->
                        <div class="carousel-item active">
                            <a href="uploads/<?php echo $car['featured_image']; ?>" data-toggle="lightbox">
                                <img src="uploads/<?php echo $car['featured_image']; ?>" class="d-block w-100 img-fluid" alt="<?php echo htmlspecialchars($car['name']); ?>">
                            </a>
                        </div>

                        <!-- Gallery Images -->
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php if (!empty($car["gallery_image$i"])): ?>
                                <div class="carousel-item">
                                    <a href="uploads/<?php echo $car["gallery_image$i"]; ?>" data-toggle="lightbox">
                                        <img src="uploads/<?php echo $car["gallery_image$i"]; ?>" class="d-block w-100 img-fluid" alt="Gallery Image <?php echo $i; ?>">
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselGallery" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselGallery" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>

                <!-- Thumbnails Below the Carousel -->
                <div class="row mt-3">
                    <div class="col-4">
                        <img src="uploads/<?php echo $car['featured_image']; ?>" class="img-fluid img-thumbnail" data-bs-target="#carouselGallery" data-bs-slide-to="0" alt="Thumbnail 1">
                    </div>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <?php if (!empty($car["gallery_image$i"])): ?>
                            <div class="col-4">
                                <img src="uploads/<?php echo $car["gallery_image$i"]; ?>" class="img-fluid img-thumbnail" data-bs-target="#carouselGallery" data-bs-slide-to="<?php echo $i; ?>" alt="Thumbnail <?php echo $i + 1; ?>">
                            </div>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Car Information Column -->
            <div class="col-lg-4 mt-0 mt-sm-5">
                <h1 class=""><?php echo htmlspecialchars($car['mfg_year']); ?> <?php echo htmlspecialchars($car['brand_name']); ?> <?php echo htmlspecialchars($car['name']) ?></h1>
                <h2 class="pb-3 text-success">Price : ₹ <?php echo htmlspecialchars($car['price']); ?></h2>
                
                <!-- Enquire Button -->
                <button type="button" class="btn btn-danger bg-gradient mb-3" data-bs-toggle="modal" data-bs-target="#enquiryModal">
                    Enquire
                </button>
                
                <!-- Modal -->
                <div class="modal fade" id="enquiryModal" tabindex="-1" aria-labelledby="enquiryModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="enquiryModalLabel">Enquire about this car</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="post" action="">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" class="form-control" id="phone" name="phone" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="area" class="form-label">Area</label>
                                        <input type="text" class="form-control" id="area" name="area" required>
                                    </div>
                                    <button type="submit" class="btn btn-dark w-100" name="enquire">Submit Enquiry</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Car Information -->
                <h5 class="card-title mb-3">Car Information</h5>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Brand:</strong> <?php echo htmlspecialchars($car['brand_name']); ?></li>
                    <li class="list-group-item"><strong>Name:</strong> <?php echo htmlspecialchars($car['name']); ?></li>
                    <li class="list-group-item"><strong>Variant:</strong> <?php echo htmlspecialchars($car['variant']); ?></li>
                    <li class="list-group-item"><strong>Transmission:</strong> <?php echo htmlspecialchars($car['transmission']); ?></li>
                    <li class="list-group-item"><strong>Fuel:</strong> <?php echo htmlspecialchars($car['fuel']); ?></li>
                    <li class="list-group-item"><strong>Manufacture Year:</strong> <?php echo htmlspecialchars($car['mfg_year']); ?></li>
                    <li class="list-group-item"><strong>Price Range:</strong> <?php echo htmlspecialchars($car['range_name']); ?></li>
                    <li class="list-group-item"><strong>Availability:</strong> <?php echo $car['available'] ? 'Available' : 'Unavailable'; ?></li>
                    <li class="list-group-item"><strong>Registration Date:</strong> <?php echo formatDate($car['reg_date']); ?></li>
                    <li class="list-group-item"><strong>Owner:</strong> <?php echo htmlspecialchars($car['owner']); ?></li>
                    <li class="list-group-item"><strong>Color:</strong> <?php echo htmlspecialchars($car['color']); ?></li>
                    <li class="list-group-item"><strong>Kilometers Driven:</strong> <?php echo htmlspecialchars($car['kilometer']); ?></li>
                    <li class="list-group-item"><strong>Passing:</strong> <?php echo htmlspecialchars($car['passing']); ?></li>
                    <li class="list-group-item"><strong>Insurance:</strong> <?php echo formatDate($car['insurance']); ?></li>
                    <li class="list-group-item"><strong>Hypothecation:</strong> <?php echo htmlspecialchars($car['hypothication']); ?></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <?php if ($enquirySuccess): ?>
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Your enquiry has been submitted successfully!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        });
    </script>
    <?php endif; ?>

    <?php include 'backend_footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>
    <script>
        document.querySelectorAll('[data-toggle="lightbox"]').forEach(el => {
            el.addEventListener('click', function(event) {
                event.preventDefault();
                let lightbox = new EkkoLightbox(el);
            });
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
