<?php
// Include database connection
include 'db.php';

// Fetch brands that have at least one car
$sql = "
    SELECT b.id, b.name 
    FROM car_brands b 
    JOIN cars c ON b.id = c.brand_id 
    WHERE c.available = 1
    GROUP BY b.id, b.name";

$result = $conn->query($sql);
?>

<style>
@media (min-width: 992px) { 
  .navbar-nav .dropdown:hover .dropdown-menu {
    display: block;
    margin-top: 0;
  }
  .navbar-nav .dropdown .dropdown-menu {
    display: none;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
  }
  .navbar-nav .dropdown:hover .dropdown-menu {
    display: block;
    opacity: 1;
    transition: opacity 0.3s ease-in-out;
  }
}
.nav-link {
    font-size: 16px;
    font-weight: 600;
}
</style>

<nav class="navbar navbar-expand-lg bg-white fixed-top shadow-sm px-lg-5  ">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <div class="container">
        <div class="d-flex justify-content-center">
            <img src="img/logo.png" alt="Logo" width="100%" height="20px" class="d-inline-block">
        </div>
    </div>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse p-lg-2" id="navbarNavAltMarkup">
      <div class="navbar-nav mx-auto">
        <a class="nav-link active px-lg-3" aria-current="page" href="index.php">Home</a>
        
        <!-- Brands Dropdown Menu -->
        <li class="nav-item dropdown">
          <a class="nav-link active px-lg-3 dropdown-toggle" href="#" id="brandsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Brands
          </a>
          <ul class="dropdown-menu" aria-labelledby="brandsDropdown">
            <?php if ($result->num_rows > 0): ?>
              <?php while($row = $result->fetch_assoc()): ?>
                <!-- URL now uses brand_name instead of brand_id -->
                <li><a class="dropdown-item" href="brand_page.php?brand_name=<?php echo urlencode($row['name']); ?>"><?php echo htmlspecialchars($row['name']); ?></a></li>
              <?php endwhile; ?>
            <?php else: ?>
              <li><a class="dropdown-item" href="#">No Brands Available</a></li>
            <?php endif; ?>
          </ul>
        </li>
        
      </div>
      <button type="button" class="btn btn-dark bg-gradient d-none d-md-block">+91 9090 909090</button>
    </div>
  </div>
</nav>

