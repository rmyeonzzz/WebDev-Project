<?php
session_start();

$hotel_id = $_GET['id'] ?? '';
$name = urldecode($_GET['name'] ?? 'Hotel Name');
$image = urldecode($_GET['image'] ?? '');
$price = urldecode($_GET['price'] ?? '');
$score = $_GET['score'] ?? 'N/A';
$current_username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest';
$is_logged_in = isset($_SESSION['loggedin']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($name); ?> - VigGo Hotels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src='https://kit.fontawesome.com/4c729db828.js' crossorigin='anonymous'></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .navbar-container {
            position: fixed;
            top: 0;
            left: 0; 
            width: 100%;
            z-index: 1000;
            background-color: #FF5804; 
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); 
        }
        .nav-link, .navbar-brand {
            color: white !important;
            font-weight: 700;
        }
        .main-content {
            flex: 1;
            padding-top: 100px;
            padding-bottom: 50px;
        }
        .hotel-header-container {
            position: relative;
            height: 400px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .hotel-header-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .hotel-header-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            padding: 30px;
            color: white;
        }
        .feature-icon {
            font-size: 1.2rem;
            color: #FF5804; 
            width: 30px;
            text-align: center;
        }
        .price-card {
            background: white;
            border: none;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            position: sticky;
            top: 120px;
        }
        .price-value {
            color: #2a6dac;
            font-size: 3rem; 
            font-weight: 800;
            line-height: 1;
        }
        .btn-book {
            background-color: #FF5804;
            color: white;
            font-weight: 700;
            border: none;
            padding: 12px;
            border-radius: 50px;
            width: 100%;
            transition: all 0.3s;
        }
        .btn-book:hover {
            background-color: #e04e03;
            color: white;
        }
        .rating-badge {
            background-color: #2a6dac;
            color: white;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 8px;
        }
        footer {
            background-color: #212529;
            color: white;
            padding-top: 3rem;
            padding-bottom: 1.5rem;
            margin-top: auto;
        }
        footer a { color: #ccc; text-decoration: none; transition: color 0.3s ease; }
        footer a:hover { color: #FF5804; }
        footer h5 { color: #FF5804; font-weight: 700; margin-bottom: 1.5rem; }
        .social-icons a { font-size: 1.5rem; margin-right: 15px; color: white; }
        .social-icons a:hover { color: #FF5804; }
    </style>
</head>
<body>

    <header class="navbar-container">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">VigGo</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="searchflight1.php">Book</a></li>
                        <?php if ($is_logged_in): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-user-circle me-1"></i> <?php echo $current_username; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item text-danger fw-bold" href="logout.php">Log Out</a></li>
                            </ul>
                        </li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link" href="login.html">Log In</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="main-content">
        <div class="container">
            
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Hotel Details</li>
                </ol>
            </nav>

            <div class="row mb-5">
                <div class="col-12">
                    <div class="hotel-header-container">
                        <img src="<?php echo htmlspecialchars($image); ?>" class="hotel-header-img" alt="Hotel Image" onerror="this.src='https://via.placeholder.com/1200x400?text=Hotel+Image'">
                        <div class="hotel-header-overlay">
                            <h1 class="display-4 fw-bold mb-2"><?php echo htmlspecialchars($name); ?></h1>
                            <p class="mb-0 fs-5"><i class="fas fa-map-marker-alt me-2 text-warning"></i>Excellent Location</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4 p-lg-5">
                            <h3 class="fw-bold mb-4" style="color: #2a6dac;">About this Hotel</h3>
                            <p class="text-secondary lead" style="line-height: 1.8;">
                                Experience world-class service at <strong><?php echo htmlspecialchars($name); ?></strong>. 
                                Located in the heart of the city, this property offers stunning views, 
                                modern amenities, and easy access to local attractions. Whether you are traveling for business or leisure,
                                we ensure a comfortable and memorable stay.
                            </p>
                            
                            <h4 class="fw-bold mt-5 mb-4" style="color: #2a6dac;">Popular Amenities</h4>
                            <div class="row g-4">
                                <div class="col-6 col-md-4 d-flex align-items-center"><i class="fas fa-wifi feature-icon me-3"></i> <span class="fw-medium">Free WiFi</span></div>
                                <div class="col-6 col-md-4 d-flex align-items-center"><i class="fas fa-swimming-pool feature-icon me-3"></i> <span class="fw-medium">Swimming Pool</span></div>
                                <div class="col-6 col-md-4 d-flex align-items-center"><i class="fas fa-utensils feature-icon me-3"></i> <span class="fw-medium">Breakfast</span></div>
                                <div class="col-6 col-md-4 d-flex align-items-center"><i class="fas fa-parking feature-icon me-3"></i> <span class="fw-medium">Free Parking</span></div>
                                <div class="col-6 col-md-4 d-flex align-items-center"><i class="fas fa-snowflake feature-icon me-3"></i> <span class="fw-medium">AC</span></div>
                                <div class="col-6 col-md-4 d-flex align-items-center"><i class="fas fa-concierge-bell feature-icon me-3"></i> <span class="fw-medium">24h Front Desk</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="price-card">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="text-muted d-block small fw-bold text-uppercase">Starts from</span>
                                <span class="price-value"><?php echo htmlspecialchars($price); ?></span>
                                <span class="text-muted small">/ night</span>
                            </div>
                            <div class="text-end">
                                <span class="rating-badge mb-1 d-inline-block">8.5 / 10</span>
                                <div class="small text-muted">Excellent</div>
                            </div>
                        </div>

                        <hr class="my-4 text-muted">

                        <ul class="list-unstyled mb-4 text-secondary">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Free Cancellation</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> No Pre-payment needed</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Best Price Guarantee</li>
                        </ul>

                        <button class="btn btn-book shadow-sm">Reserve Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
<footer class="viggo-footer-redesigned">
    <div class="container-fluid px-4">
        <div class="footer-content-wrapper">
            
            <div class="copyright-section">
                <i class="fa-regular fa-copyright me-2"></i> 
                <span>2025 VigGo Travels. All rights reserved.</span>
            </div>

            <div class="brand-social-section">
                
                <div class="footer-brand-group me-4">
                    <img src="pictures/logo4.png" alt="VigGo Logo" class="footer-logo-small">
                </div>

                <div class="social-icons-group">
                    <a href="https://facebook.com" target="_blank" class="social-link">
                        <i class="fa-brands fa-facebook"></i>
                    </a>
                    <a href="https://instagram.com" target="_blank" class="social-link">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                </div>

            </div>

        </div>
    </div>
</footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>