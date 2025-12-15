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
    die(print_r(sqlsrv_errors(), true));
}

// === 1. Handle New Search (POST) vs Existing Search (SESSION) ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // This is a brand new search from the index page
    $_SESSION['search_criteria'] = [
        'origin' => $_POST['origin'],
        'destination' => $_POST['destination'],
        'depart_date' => $_POST['depart_date'],
        'return_date' => $_POST['return_date'] ?? '',
        'travel_type' => $_POST['travel_type'] ?? 'One Way',
        'passengers' => (int)($_POST['passengers'] ?? 1)
    ];
    // Clear any previous flight selections so we start fresh
    unset($_SESSION['outbound_flight_id']);
    unset($_SESSION['inbound_flight_id']);
}

// Ensure we have search criteria
if (!isset($_SESSION['search_criteria'])) {
    header("Location: index.php");
    exit;
}

// Extract variables for easier usage
$criteria = $_SESSION['search_criteria'];
$origin = $criteria['origin'];
$destination = $criteria['destination'];
$depart_date = $criteria['depart_date'];
$return_date = $criteria['return_date'];
$travel_type = $criteria['travel_type'];
$passengers = $criteria['passengers'];

// === 2. Determine Which Step We Are On ===

// Check if user just selected an OUTBOUND flight
if (isset($_GET['select_outbound'])) {
    $_SESSION['outbound_flight_id'] = $_GET['select_outbound'];
    
    // If One Way, go straight to payment
    if ($travel_type === 'One Way') {
        header("Location: payment.php");
        exit;
    }
    // If Round Trip, reload page to show Step 2 (Inbound)
    header("Location: searchflight.php");
    exit;
}

// Determine Current Mode
$current_step = 'outbound'; // Default to Step 1

if ($travel_type === 'Round Trip' && isset($_SESSION['outbound_flight_id'])) {
    // If we already have an outbound flight, we are now looking for Inbound
    $current_step = 'inbound';
}

// === 3. Database Query Function ===
function searchFlights($conn, $from, $to, $date, $seats, $date_col = 'depart_date') {
    $sql = "SELECT flight_id, flight_number, depart_city, arrival_city, 
            COALESCE(depart_date, return_date) as depart_date, 
            depart_time, price, seats, flight_type 
            FROM DUMMYFLIGHTS2
            WHERE depart_city = ? 
            AND arrival_city = ? 
            AND CAST($date_col AS DATE) = ? 
            AND seats >= ?"; 
    
    $params = array($from, $to, $date, $seats);
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    $results = [];
    if ($stmt !== false) {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $results[] = $row;
        }
    }
    return $results;
}

// === 4. Execute the Query for the CURRENT Step Only ===
$flights_to_display = [];
$display_title = "";
$display_date = "";

if ($current_step === 'outbound') {
    // STEP 1: Search Outbound Flights
    $flights_to_display = searchFlights($conn, $origin, $destination, $depart_date, $passengers, 'depart_date');
    $display_title = "Step 1: Select Departure Flight ($origin to $destination)";
    $display_date = $depart_date;
} else {
    // STEP 2: Search Return Flights (Swap Origin/Dest)
    $flights_to_display = searchFlights($conn, $destination, $origin, $return_date, $passengers, 'return_date');
    $display_title = "Step 2: Select Return Flight ($destination to $origin)";
    $display_date = $return_date;
}

$is_logged_in = !empty($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE; 
$current_username = $is_logged_in ? htmlspecialchars($_SESSION['username']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Flight - <?php echo $current_step; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src='https://kit.fontawesome.com/4c729db828.js' crossorigin='anonymous'></script>
    <style>
        body { background-color: #f4f7f6; }
        .flight-section { 
            margin-bottom: 40px; 
            background: white; 
            padding: 25px; 
            border-radius: 10px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.05); 
        }
        .section-title { 
            color: #2a6dac; 
            border-bottom: 2px solid #eee; 
            padding-bottom: 15px; 
            margin-bottom: 20px; 
            font-weight: bold;
        }
        .step-indicator {
            background-color: #e3f2fd;
            color: #0d6efd;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="searchflight.php">VigGo Flights</a>
            <div class="ms-auto text-white">
                <?php if ($is_logged_in): ?>
                    Welcome, <?php echo $current_username; ?> | <a href="logout.php" class="text-white text-decoration-none fw-bold ms-2">Logout</a>
                <?php else: ?>
                    <a href="login.html" class="text-white text-decoration-none fw-bold">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        
        <div class="step-indicator">
            <?php if ($travel_type === 'Round Trip'): ?>
                Step <?php echo ($current_step === 'outbound') ? '1 of 2' : '2 of 2'; ?>: 
                <?php echo ($current_step === 'outbound') ? 'Departing Flight' : 'Returning Flight'; ?>
            <?php else: ?>
                One Way Flight Selection
            <?php endif; ?>
        </div>

        <div class="flight-section">
            <h3 class="section-title">
                <i class="fas <?php echo ($current_step === 'outbound') ? 'fa-plane-departure' : 'fa-plane-arrival'; ?> me-2"></i> 
                <?php echo $display_title; ?>
            </h3>
            <p class="text-muted mb-3"><i class="far fa-calendar-alt me-2"></i>Date: <strong><?php echo htmlspecialchars($display_date); ?></strong></p>

            <?php if (count($flights_to_display) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Flight #</th>
                                <th>Time</th>
                                <th>Class</th>
                                <th>Price per Pax</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($flights_to_display as $row): 
                                $price_formatted = number_format($row['price'], 2);
                                $time_formatted = $row['depart_time']->format('H:i');
                                
                                // === GENERATE LINK LOGIC ===
                                if ($current_step === 'outbound') {
                                    // Step 1: Link back to THIS page to trigger Step 2
                                    $target_url = "searchflight.php?select_outbound=" . $row['flight_id'];
                                } else {
                                    // Step 2: Link to Payment Page (passing inbound ID)
                                    // Payment page will read outbound ID from Session
                                    $target_url = "payment.php?select_inbound=" . $row['flight_id'];
                                }

                                // Login Check for Link
                                if (!$is_logged_in) {
                                    $final_link = "login.html?redirect=" . urlencode($target_url);
                                } else {
                                    $final_link = $target_url;
                                }
                            ?>
                            <tr>
                                <td class="fw-bold"><?php echo $row['flight_number']; ?></td>
                                <td><?php echo $time_formatted; ?></td>
                                <td><span class="badge bg-secondary"><?php echo $row['flight_type']; ?></span></td>
                                <td class="fw-bold text-success">₱<?php echo $price_formatted; ?></td>
                                <td>
                                    <a href="<?php echo $final_link; ?>" class="btn btn-sm btn-primary px-3">
                                        Select <?php echo ($current_step === 'outbound' && $travel_type === 'Round Trip') ? '& Continue' : 'Flight'; ?>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    No flights found for this date. 
                    <a href="index.php" class="alert-link">Search Again</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center mb-5">
            <a href="index.php" class="btn btn-outline-secondary"><i class="fas fa-search me-2"></i>Restart Search</a>
        </div>

    </div>
</body>
</html>
<?php>