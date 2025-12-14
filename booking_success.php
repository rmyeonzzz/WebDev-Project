<?php
session_start();
$serverName = "LAPTOP-1AD6EHQ4"; 
$connectionOptions = [ 
    "Database" => "DLSU", 
    "Uid" => "", 
    "PWD" => "" 
]; 
$conn = sqlsrv_connect($serverName, $connectionOptions); 
    echo $varname = $_POST ['destination'];
if ($conn === false) {
    // In case of a database connection failure
    $_SESSION['login_error'] = 'Database connection error. Please try again later.';
    header("Location: booking_success.php"); 
    exit();
}
// 1. Protection: Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html"); 
    exit;
}

// 2. Check if the booking was successful and retrieve data
// We check for the 'booking_status' flag set by process_booking.php
if (!isset($_SESSION['booking_status']) || $_SESSION['booking_status'] !== 'success') {
    // If they land here without a successful status, redirect them away
    header("Location: searchflight.php");
    exit;
}

// Retrieve the summarized booking data stored by process_booking.php
$booking_data = $_SESSION['last_booking'] ?? [];
$flight_number = htmlspecialchars($booking_data['flight_number'] ?? 'N/A');
$passengers = htmlspecialchars($booking_data['passengers'] ?? 'N/A');
$total_amount = floatval($booking_data['total_amount'] ?? 0.00);
$date = htmlspecialchars($booking_data['date'] ?? 'N/A');

// CRITICAL: Clear the success status and last booking data after use
// This prevents the user from seeing the success message again on refresh or direct access.
unset($_SESSION['booking_status']);
unset($_SESSION['last_booking']);

$user_name = htmlspecialchars($_SESSION['completename'] ?? 'Valued Customer');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src='https://kit.fontawesome.com/4c729db828.js' crossorigin='anonymous'></script>
    <style>
        .success-icon {
            color: #28a745; /* Bootstrap green */
            font-size: 5rem;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <div class="card p-5 shadow-lg">
                    <i class="fas fa-check-circle success-icon"></i>
                    
                    <h1 class="text-success mb-3">Booking Confirmed!</h1>
                    <p class="lead">Thank you, <strong><?php echo $user_name; ?></strong>. Your flight reservation has been successfully processed.</p>
                    
                    <hr class="my-4">

                    <h4 class="mb-3">Reservation Details:</h4>
                    
                    <div class="row text-start justify-content-center">
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush mb-4">
                                <li class="list-group-item"><strong>Flight Number:</strong> <?php echo $flight_number; ?></li>
                                <li class="list-group-item"><strong>Departure Date:</strong> <?php echo $date; ?></li>
                                <li class="list-group-item"><strong>Passengers:</strong> 
                                    <span class="badge bg-primary fs-6"><?php echo $passengers; ?></span>
                                </li>
                                <li class="list-group-item list-group-item-success fw-bold">
                                    Total Amount Paid (PHP): <span class="float-end fs-5"><?php echo number_format($total_amount, 2); ?></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="index2.php" class="btn btn-primary btn-lg me-3">
                            <i class="fas fa-home me-2"></i> Go to Homepage
                        </a>
                        <a href="searchflight.html" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-plane me-2"></i> Book Another Flight
                        </a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>