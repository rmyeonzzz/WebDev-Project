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

// === 1. Input Retrieval ===
$origin = $_POST['origin'] ?? '';
$destination = $_POST['destination'] ?? '';
$depart_date = $_POST['depart_date'] ?? ''; 
$return_date = $_POST['return_date'] ?? ''; // New Variable
$travel_type = $_POST['travel_type'] ?? 'One Way'; // "One Way" or "Round Trip"
$passengers = (int)($_POST['passengers'] ?? 1); 

// === 2. Define the Query Function (Reusable) ===
function searchFlights($conn, $from, $to, $date, $seats) {
    // Selects flight details based on route, date, and available seats
    $sql = "SELECT flight_id, flight_number, depart_city, arrival_city, depart_date, depart_time, price, seats, flight_type 
            FROM DUMMYFLIGHTS2
            WHERE depart_city = ? 
            AND arrival_city = ? 
            AND depart_date = ? 
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

// === 3. Execute Searches ===

// Search A: Outbound (This always runs)
$outbound_flights = searchFlights($conn, $origin, $destination, $depart_date, $passengers);

// Search B: Inbound (Only runs if "Round Trip" is selected AND a date is provided)
$inbound_flights = [];
if ($travel_type === "Round Trip" && !empty($return_date)) {
    // Note: We swap Origin and Destination for the return leg
    $inbound_flights = searchFlights($conn, $destination, $origin, $return_date, $passengers);
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
        .table th { background-color: #e3f2fd; }
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
        
        <div class="flight-section">
            <h3 class="section-title">
                <i class="fas fa-plane-departure me-2"></i> 
                Outbound: <?php echo htmlspecialchars($origin); ?> to <?php echo htmlspecialchars($destination); ?>
            </h3>
            <p class="text-muted mb-3"><i class="far fa-calendar-alt me-2"></i>Date: <strong><?php echo htmlspecialchars($depart_date); ?></strong></p>

            <?php if (count($outbound_flights) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Flight #</th>
                                <th>Time</th>
                                <th>Class</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($outbound_flights as $row): 
                                $price_formatted = number_format($row['price'], 2);
                                $time_formatted = $row['depart_time']->format('H:i');
                                
                                // Build the link to payment.php
                                $params = http_build_query([
                                    'flight_id' => $row['flight_id'],
                                    'flight_number' => $row['flight_number'],
                                    'origin' => $row['depart_city'], 
                                    'destination' => $row['arrival_city'], 
                                    'depart_date' => $row['depart_date']->format('Y-m-d'), 
                                    'price' => $row['price'], 
                                    'passengers' => $passengers, 
                                    'travel_type' => $row['flight_type']
                                ]);
                                
                                // Logic: If not logged in, redirect to login first
                                if ($is_logged_in) {
                                    $link = "payment.php?" . $params;
                                } else {
                                    $link = "login.html?redirect=" . urlencode("payment.php?" . $params);
                                }
                            ?>
                            <tr>
                                <td class="fw-bold"><?php echo $row['flight_number']; ?></td>
                                <td><?php echo $time_formatted; ?></td>
                                <td><span class="badge bg-secondary"><?php echo $row['flight_type']; ?></span></td>
                                <td class="fw-bold text-success">₱<?php echo $price_formatted; ?></td>
                                <td>
                                    <a href="<?php echo $link; ?>" class="btn btn-sm btn-primary px-3">Select</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-warning"><i class="fas fa-exclamation-circle me-2"></i>No outbound flights found for this date.</div>
            <?php endif; ?>
        </div>

        <?php if ($travel_type === "Round Trip"): ?>
            <div class="flight-section">
                <h3 class="section-title">
                    <i class="fas fa-plane-arrival me-2"></i> 
                    Return: <?php echo htmlspecialchars($destination); ?> to <?php echo htmlspecialchars($origin); ?>
                </h3>
                <p class="text-muted mb-3"><i class="far fa-calendar-alt me-2"></i>Date: <strong><?php echo htmlspecialchars($return_date); ?></strong></p>

                <?php if (count($inbound_flights) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Flight #</th>
                                    <th>Time</th>
                                    <th>Class</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inbound_flights as $row): 
                                    $price_formatted = number_format($row['price'], 2);
                                    $time_formatted = $row['depart_time']->format('H:i');
                                    
                                    // Build the link
                                    $params = http_build_query([
                                        'flight_id' => $row['flight_id'],
                                        'flight_number' => $row['flight_number'],
                                        'origin' => $row['depart_city'], 
                                        'destination' => $row['arrival_city'], 
                                        'depart_date' => $row['depart_date']->format('Y-m-d'), 
                                        'price' => $row['price'], 
                                        'passengers' => $passengers, 
                                        'travel_type' => $row['flight_type']
                                    ]);

                                    if ($is_logged_in) {
                                        $link = "payment.php?" . $params;
                                    } else {
                                        $link = "login.html?redirect=" . urlencode("payment.php?" . $params);
                                    }
                                ?>
                                <tr>
                                    <td class="fw-bold"><?php echo $row['flight_number']; ?></td>
                                    <td><?php echo $time_formatted; ?></td>
                                    <td><span class="badge bg-secondary"><?php echo $row['flight_type']; ?></span></td>
                                    <td class="fw-bold text-success">₱<?php echo $price_formatted; ?></td>
                                    <td>
                                        <a href="<?php echo $link; ?>" class="btn btn-sm btn-primary px-3">Select</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning"><i class="fas fa-exclamation-circle me-2"></i>No return flights found for this date.</div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="text-center mb-5">
            <a href="index.html" class="btn btn-outline-secondary"><i class="fas fa-search me-2"></i>Search Again</a>
        </div>

    </div>
</body>
</html>