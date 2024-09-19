<br><nav class="navbar bg-white fixed-top shadow-sm">
  <div class="container-fluid">
    <!-- Add a container for the navbar toggler and brand -->
    <div class="d-flex align-items-center">
      <!-- Navbar toggler (hamburger button) -->
      <button class="navbar-toggler me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Navbar brand -->
      <div class="container">
        <div class="d-flex justify-content-center">
            <img src="img/logo.png" alt="Logo" width="100%" height="20px" class="d-inline-block">
        </div>
    </div>
    </div>
    <a href="logout.php"<button type="button" class="btn btn-danger">Logout</button></a>
    <!-- Offcanvas menu -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Kothari Autobiz</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="dashboard.php">Dashboard</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Cars
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="add_car.php">Add a Car</a></li>
              <li><a class="dropdown-item" href="update_cars.php">Edit Cars</a></li>
              <!--<li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>-->
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="add_brand.php">Car Brands</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="add_price_range.php">Price Ranges</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="manage_images.php">Manage Images</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="website_inquiries.php">Website Inquiries</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Users
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="add_user.php">Add User</a></li>
              <li><a class="dropdown-item" href="list_users.php">Users List</a></li>
              <!--<li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>-->
            </ul>
          </li>
        </ul>
        <!--<form class="d-flex mt-3" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>-->
      </div>
    </div>
  </div>
</nav>
