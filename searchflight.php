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
    $errors = sqlsrv_errors();
    $_SESSION['search_error'] = 'Database connection error. Details: ' . ($errors ? $errors[0]['message'] : 'Unknown error.');
    header("Location: searchflight.php");
    exit();
}

// Check for and display any errors from the initial page load redirect
if (isset($_SESSION['search_error'])) {
 $session_error = $_SESSION['search_error'];
 unset($_SESSION['search_error']); // Clear the error after displaying
} else {
 $session_error = null;
}

// === 1. Input Retrieval and Validation using empty() and isset() ===
$origin = $_POST['origin'] ?? '';
$destination = $_POST['destination'] ?? '';
$depart_date = $_POST['depart_date'] ?? ''; 
$passengers = (int)($_POST['passengers'] ?? 1); 

if (empty($origin) || empty($destination) || empty($depart_date) || $passengers < 1) { 
    if (!$session_error) { 
        $_SESSION['search_error'] = 'Please fill in all search criteria correctly.';
    }
    $stmt = false; // Set stmt to false so the results table is not processed
} else {

    // FIX: Added 'flight_id' to the SELECT list.
    // NOTE: depart_city and arrival_city are aliased to 'origin' and 'destination' in the booking process.
    $sql = "SELECT flight_id, flight_number, depart_city, arrival_city, depart_date, depart_time, price, seats, flight_type 
    FROM DUMMYFLIGHTS2
    WHERE depart_city = ? 
    AND arrival_city = ? 
    AND depart_date = ? 
    AND seats >= ?"; 

    $params = array($origin, $destination, $depart_date, $passengers);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        $errors = sqlsrv_errors();
        $session_error = 'An error occurred during the flight search. Details: ' . ($errors ? $errors[0]['message'] : 'Unknown error.');
    }
}


$is_logged_in = !empty($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE; 
$current_username = $is_logged_in ? htmlspecialchars($_SESSION['username']) : '';


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Search Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src='https://kit.fontawesome.com/4c729db828.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="styles2.css">
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="searchflight.php">VigGo Flights</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item d-flex align-items-center">
                        <?php if ($is_logged_in): ?>
                            <span class="nav-link text-white me-3">
                                Welcome, **<?php echo $current_username; ?>**!
                            </span>
                            <a class="btn btn-outline-light" href="logout.php">Log Out</a>
                        <?php else: ?>
                            <a class="nav-link" href="login.html">Log In / Sign Up</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">
            <i class="fas fa-plane-departure me-2"></i> 
            Search Results for <?php echo htmlspecialchars($origin); ?> to <?php echo htmlspecialchars($destination); ?>
        </h2>
        
        <?php if ($session_error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-times-circle me-2"></i> **Error:** <?php echo htmlspecialchars($session_error); ?>
            </div>
        <?php endif; ?>

        <?php 
        $row_count = 0;
        $flights_found = [];
        
        // Only attempt to fetch results if the query was executed successfully
        if ($stmt !== false) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $flights_found[] = $row;
                $row_count++;
            }
        }
        
        if ($row_count > 0): 
        ?>
            <div class="alert alert-info">
                Found **<?php echo $row_count; ?>** flight(s) matching your criteria on **<?php echo htmlspecialchars($depart_date); ?>**.
            </div>
            
            <table class="table table-hover table-striped shadow-sm">
                <thead class="table-primary">
                    <tr>
                        <th>Flight #</th>
                        <th>Time</th>
                        <th>Available Seats</th>
                        <th>Price (PHP)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($flights_found as $row):
                        // Format time and date from SQLSRV DateTime objects
                        $depart_time_formatted = $row['depart_time'] ? $row['depart_time']->format('H:i') : 'N/A';
                        $depart_date_raw = $row['depart_date'] ? $row['depart_date']->format('Y-m-d') : ''; 
                        $price_formatted = number_format($row['price'], 2);

                        // --- START OF BOOK NOW LOGIC (RULE #1 IMPLEMENTATION) ---
                        
                        // 1. Package all necessary flight details for the next page (booking.php)
                        $flight_query_params = http_build_query([
                            'flight_id' => $row['flight_id'],
                            'flight_number' => $row['flight_number'],
                            'origin' => $row['depart_city'], // Use column name from SQL
                            'destination' => $row['arrival_city'], // Use column name from SQL
                            'depart_date' => $depart_date_raw, // Use the raw date string
                            'price' => $row['price'], // Raw price value (not formatted)
                            'passengers' => $passengers, // Number of passengers from the search input
                            'travel_type' => $row['flight_type'] // The class/type
                        ]);

                        $booking_url = 'booking.php?' . $flight_query_params;
                        
                        // 2. Conditional Link Generation
                        $book_button_html = '';

                        if ($is_logged_in) {
                            // User IS logged in: Direct link to booking confirmation
                            $book_button_html = '
                                <a href="' . htmlspecialchars($booking_url) . '" 
                                   class="btn btn-sm btn-primary">
                                    Book Now
                                </a>';
                        } else {
                            // User IS NOT logged in: Link to login page with redirect URL
                            $login_redirect_url = 'login.html?redirect=' . urlencode($booking_url);

                            $book_button_html = '
                                <button 
                                    type="button" 
                                    class="btn btn-sm btn-outline-secondary" 
                                    onclick="window.location.href=\'' . htmlspecialchars($login_redirect_url) . '\'"
                                    title="Please log in to book this flight."
                                >
                                    Book Now
                                </button>
                                <div class="small mt-1">
                                    <a href="' . htmlspecialchars($login_redirect_url) . '">Login to Book</a>
                                </div>';
                        }
                        // --- END OF BOOK NOW LOGIC ---
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['flight_number']); ?></td>
                        <td><?php echo $depart_time_formatted; ?></td>
                        <td><?php echo htmlspecialchars($row['seats']); ?></td>
                        <td><strong><?php echo $price_formatted; ?></strong></td>
                        <td>
                            <?php echo $book_button_html; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning text-center">
                <i class="fas fa-exclamation-triangle me-2"></i> No flights found matching your search criteria. Please try different dates or destinations.
            </div>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="searchflight.html" class="btn btn-outline-primary"><i class="fas fa-chevron-left me-2"></i> New Search</a>
        </div>
    </div>

    <?php 
    // Only free the statement and close connection if they were opened
    if (isset($stmt) && $stmt !== false) {
        sqlsrv_free_stmt($stmt);
    }
    if (isset($conn) && $conn !== false) {
        sqlsrv_close($conn);
    }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>