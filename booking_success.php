<?php
session_start();
$serverName = "LAPTOP-1AD6EHQ4"; 
$connectionOptions = [ 
    "Database" => "DLSU", 
    "Uid" => "", 
    "PWD" => "" 
]; 
$conn = sqlsrv_connect($serverName, $connectionOptions); 


if ($conn === false) {
    $_SESSION['login_error'] = 'Database connection error. Please try again later.';
    header("Location: booking_success.php"); 
    exit();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html"); 
    exit;
}

if (!isset($_SESSION['booking_status']) || $_SESSION['booking_status'] !== 'success') {
    header("Location: searchflight.php");
    exit;
}

$booking_data = $_SESSION['last_booking'] ?? [];
$flight_number = htmlspecialchars($booking_data['flight_number'] ?? 'N/A');
$passengers = htmlspecialchars($booking_data['passengers'] ?? 'N/A');
$total_amount = floatval($booking_data['total_amount'] ?? 0.00);
$date = htmlspecialchars($booking_data['date'] ?? 'N/A');

unset($_SESSION['booking_status']);
unset($_SESSION['last_booking']);

$user_name = htmlspecialchars($_SESSION['completename'] ?? 'Valued Customer');
$current_username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed! - VigGo Travel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
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
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card { 
            border: none; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); 
        }
        .success-icon {
            color: #28a745; 
            font-size: 5rem;
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #2a6dac;
            border: none;
            font-weight: 700;
        }
        .btn-primary:hover { background-color: #1e5285; }
        
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
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-user-circle me-1"></i> <?php echo $current_username; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item text-danger fw-bold" href="logout.php">Log Out</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="main-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <div class="card p-5">
                        <i class="fas fa-check-circle success-icon"></i>
                        
                        <h1 class="text-success mb-3 fw-bold">Booking Confirmed!</h1>
                        <p class="lead text-secondary">Thank you, <strong><?php echo $user_name; ?></strong>. Your flight reservation has been successfully processed.</p>
                        
                        <hr class="my-4">

                        <h4 class="mb-4 fw-bold" style="color: #2a6dac;">Reservation Details</h4>
                        
                        <div class="row text-start justify-content-center">
                            <div class="col-md-8">
                                <ul class="list-group list-group-flush mb-4 shadow-sm rounded">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold text-secondary">Flight Number:</span>
                                        <span><?php echo $flight_number; ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold text-secondary">Departure Date:</span>
                                        <span><?php echo $date; ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-secondary">Passengers:</span>
                                        <span class="badge bg-primary rounded-pill"><?php echo $passengers; ?></span>
                                    </li>
                                    <li class="list-group-item list-group-item-success fw-bold d-flex justify-content-between align-items-center mt-2 rounded">
                                        <span>Total Amount Paid (PHP):</span>
                                        <span class="fs-5"><?php echo number_format($total_amount, 2); ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <a href="index.php" class="btn btn-primary btn-lg me-3 rounded-pill px-4 shadow-sm">
                                <i class="fas fa-home me-2"></i> Go to Homepage
                            </a>
                            <a href="searchflight1.php" class="btn btn-outline-secondary btn-lg rounded-pill px-4 shadow-sm">
                                <i class="fas fa-plane me-2"></i> Book Another Flight
                            </a>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>VigGo Travel</h5>
                    <p class="small text-secondary">Your trusted partner for exploring the world. We make travel easy, affordable, and memorable.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="index.php">Home</a></li>
                        <li class="mb-2"><a href="searchflight1.php">Book Flights</a></li>
                        <li class="mb-2"><a href="#">About Us</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Contact Us</h5>
                    <ul class="list-unstyled small text-secondary">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> Manila, Philippines</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> support@viggo.com</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> +63 912 345 6789</li>
                    </ul>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="small text-secondary mb-0">&copy; 2025 VigGo Travel. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="small text-secondary mb-0">Privacy Policy | Terms of Service</p>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>