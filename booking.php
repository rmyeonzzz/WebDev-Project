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
    // In case of a database connection failure
    $_SESSION['login_error'] = 'Database connection error. Please try again later.';
    header("Location: bookin.php"); 
    exit();
}
// 1. Protection: Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // If not logged in, redirect them back to login.html,
    // preserving the current flight data URL (though this shouldn't happen 
    // if login.php is working, it's good defensive programming).
    $redirect_url = 'booking.php?' . $_SERVER['QUERY_STRING'];
    header("Location: login.html?redirect=" . urlencode($redirect_url)); 
    exit;
}

// 2. Retrieve Flight Data from the URL (GET)
// These parameters are passed from searchflight.php after a user clicks 'Book Now'
$flight_id = $_GET['flight_id'] ?? 'N/A';
$flight_number = $_GET['flight_number'] ?? 'N/A';
$origin = $_GET['origin'] ?? 'N/A';
$destination = $_GET['destination'] ?? 'N/A';
$depart_date = $_GET['depart_date'] ?? 'N/A';
$price_per_seat = floatval($_GET['price'] ?? 0.00); // Unit price (numeric)
$passengers = intval($_GET['passengers'] ?? 1); // Number of passengers (integer)
$travel_type = $_GET['travel_type'] ?? 'Economy';

// 3. Validation and Calculation
// Calculate the total amount
$total_amount = $price_per_seat * $passengers;

if ($total_amount <= 0 || $flight_id == 'N/A') {
    // Basic validation check
    $_SESSION['error'] = "Invalid flight details received. Please search again.";
    header("Location: searchflight.php");
    exit();
}

// User details from session
$user_id = $_SESSION['USERID'];
$user_name = $_SESSION['completename'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src='https://kit.fontawesome.com/4c729db828.js' crossorigin='anonymous'></script>
    </head>
<body>
    <div class="container mt-5">
        <h2><i class="fas fa-receipt me-2"></i> Confirm Your Flight Booking</h2>
        <p class="lead">Please review the details below before proceeding.</p>
        
        <?php 
        // Display any error messages from the previous page (e.g., process_booking.php failure)
        if (isset($_SESSION['booking_error'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i> <?php echo $_SESSION['booking_error']; unset($_SESSION['booking_error']); ?>
            </div>
        <?php endif; ?>

        <div class="card p-4 mb-4 shadow-sm">
            <h4>Flight and Passenger Details</h4>
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Flight ID:</strong> <?php echo htmlspecialchars($flight_id); ?></li>
                        <li class="list-group-item"><strong>Flight Number:</strong> <?php echo htmlspecialchars($flight_number); ?></li>
                        <li class="list-group-item"><strong>Route:</strong> 
                            <span class="fw-bold"><?php echo htmlspecialchars($origin); ?> &rarr; <?php echo htmlspecialchars($destination); ?></span>
                        </li>
                        <li class="list-group-item"><strong>Departure Date:</strong> <?php echo htmlspecialchars($depart_date); ?></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Travel Class:</strong> <?php echo htmlspecialchars($travel_type); ?></li>
                        <li class="list-group-item"><strong>Passengers:</strong> 
                            <span class="badge bg-primary fs-6"><?php echo $passengers; ?></span>
                        </li>
                        <li class="list-group-item"><strong>Price per Seat (PHP):</strong> <?php echo number_format($price_per_seat, 2); ?></li>
                        <li class="list-group-item list-group-item-success fw-bold">
                            TOTAL AMOUNT (PHP): <span class="float-end fs-5"><?php echo number_format($total_amount, 2); ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="alert alert-info">
            <i class="fas fa-user-check me-2"></i> Booking for registered user: <strong><?php echo htmlspecialchars($user_name); ?></strong> (User ID: <?php echo $user_id; ?>)
        </div>

        <form action="booking_success.php" method="POST" class="mt-4">
            
            <input type="hidden" name="flight_id" value="<?php echo htmlspecialchars($flight_id); ?>">
            <input type="hidden" name="flight_number" value="<?php echo htmlspecialchars($flight_number); ?>">
            <input type="hidden" name="origin" value="<?php echo htmlspecialchars($origin); ?>">
            <input type="hidden" name="destination" value="<?php echo htmlspecialchars($destination); ?>">
            <input type="hidden" name="depart_date" value="<?php echo htmlspecialchars($depart_date); ?>">
            <input type="hidden" name="price" value="<?php echo htmlspecialchars($price_per_seat); ?>">
            <input type="hidden" name="passengers" value="<?php echo htmlspecialchars($passengers); ?>">
            <input type="hidden" name="total_amount" value="<?php echo htmlspecialchars($total_amount); ?>">
            <input type="hidden" name="travel_type" value="<?php echo htmlspecialchars($travel_type); ?>">
            
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-check-circle me-2"></i> Confirm and Finalize Booking
            </button>
            <a href="searchflight.php" class="btn btn-outline-secondary btn-lg ms-3">Cancel / Change Flight</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>