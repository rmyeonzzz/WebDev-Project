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
    header("Location: bookin.php"); 
    exit();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    $redirect_url = 'booking.php?' . $_SERVER['QUERY_STRING'];
    header("Location: login.html?redirect=" . urlencode($redirect_url)); 
    exit;
}

$flight_id = $_GET['flight_id'] ?? 'N/A';
$flight_number = $_GET['flight_number'] ?? 'N/A';
$origin = $_GET['origin'] ?? 'N/A';
$destination = $_GET['destination'] ?? 'N/A';
$depart_date = $_GET['depart_date'] ?? 'N/A';
$price_per_seat = floatval($_GET['price'] ?? 0.00); 
$passengers = intval($_GET['passengers'] ?? 1); 
$travel_type = $_GET['travel_type'] ?? 'Economy';

$total_amount = $price_per_seat * $passengers;

if ($total_amount <= 0 || $flight_id == 'N/A') {
    $_SESSION['error'] = "Invalid flight details received. Please search again.";
    header("Location: searchflight.php");
    exit();
}

$user_id = $_SESSION['USERID'];
$user_name = $_SESSION['completename'];
$current_username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Booking - VigGo Travel</title>
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
        }
        .card { 
            border: none; 
            border-radius: 15px; 
            box-shadow: 0 10px 20px rgba(0,0,0,0.05); 
        }
        .list-group-item {
            border: none;
            padding: 12px 15px;
        }
        .btn-success {
            background-color: #2a6dac;
            border-color: #2a6dac;
            font-weight: 700;
        }
        .btn-success:hover {
            background-color: #1e5285;
            border-color: #1e5285;
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
            <h2 class="mb-4 text-center fw-bold" style="color: #2a6dac;"><i class="fas fa-receipt me-2"></i> Confirm Your Flight Booking</h2>
            <p class="lead text-center mb-5">Please review the details below before proceeding.</p>
            
            <?php if (isset($_SESSION['booking_error'])): ?>
                <div class="alert alert-danger shadow-sm rounded-3">
                    <i class="fas fa-exclamation-circle me-2"></i> <?php echo $_SESSION['booking_error']; unset($_SESSION['booking_error']); ?>
                </div>
            <?php endif; ?>

            <div class="card p-4 mb-4">
                <h4 class="fw-bold mb-3" style="color: #FF5804;">Flight and Passenger Details</h4>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item bg-light rounded mb-2"><strong>Flight ID:</strong> <?php echo htmlspecialchars($flight_id); ?></li>
                            <li class="list-group-item bg-light rounded mb-2"><strong>Flight Number:</strong> <?php echo htmlspecialchars($flight_number); ?></li>
                            <li class="list-group-item bg-light rounded mb-2"><strong>Route:</strong> 
                                <span class="fw-bold text-primary"><?php echo htmlspecialchars($origin); ?> &rarr; <?php echo htmlspecialchars($destination); ?></span>
                            </li>
                            <li class="list-group-item bg-light rounded mb-2"><strong>Departure Date:</strong> <?php echo htmlspecialchars($depart_date); ?></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item bg-light rounded mb-2"><strong>Travel Class:</strong> <?php echo htmlspecialchars($travel_type); ?></li>
                            <li class="list-group-item bg-light rounded mb-2"><strong>Passengers:</strong> 
                                <span class="badge bg-primary fs-6"><?php echo $passengers; ?></span>
                            </li>
                            <li class="list-group-item bg-light rounded mb-2"><strong>Price per Seat (PHP):</strong> <?php echo number_format($price_per_seat, 2); ?></li>
                            <li class="list-group-item bg-success text-white fw-bold rounded shadow-sm">
                                TOTAL AMOUNT (PHP): <span class="float-end fs-5"><?php echo number_format($total_amount, 2); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info shadow-sm rounded-3">
                <i class="fas fa-user-check me-2"></i> Booking for registered user: <strong><?php echo htmlspecialchars($user_name); ?></strong> (User ID: <?php echo $user_id; ?>)
            </div>

            <form action="process_booking.php" method="POST" class="mt-4 text-center">
                
                <input type="hidden" name="flight_id" value="<?php echo htmlspecialchars($flight_id); ?>">
                <input type="hidden" name="flight_number" value="<?php echo htmlspecialchars($flight_number); ?>">
                <input type="hidden" name="origin" value="<?php echo htmlspecialchars($origin); ?>">
                <input type="hidden" name="destination" value="<?php echo htmlspecialchars($destination); ?>">
                <input type="hidden" name="depart_date" value="<?php echo htmlspecialchars($depart_date); ?>">
                <input type="hidden" name="price" value="<?php echo htmlspecialchars($price_per_seat); ?>">
                <input type="hidden" name="passengers" value="<?php echo htmlspecialchars($passengers); ?>">
                <input type="hidden" name="total_amount" value="<?php echo htmlspecialchars($total_amount); ?>">
                <input type="hidden" name="travel_type" value="<?php echo htmlspecialchars($travel_type); ?>">
                
                <button type="submit" class="btn btn-success btn-lg px-5 rounded-pill shadow-sm">
                    <i class="fas fa-check-circle me-2"></i> Confirm and Finalize Booking
                </button>
                <a href="searchflight.php" class="btn btn-outline-secondary btn-lg ms-3 rounded-pill px-4">Cancel</a>
            </form>
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