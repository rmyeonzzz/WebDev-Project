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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['search_criteria'] = [
        'origin' => $_POST['origin'],
        'destination' => $_POST['destination'],
        'depart_date' => $_POST['depart_date'],
        'return_date' => $_POST['return_date'] ?? '',
        'travel_type' => $_POST['travel_type'] ?? 'One Way',
        'passengers' => (int)($_POST['passengers'] ?? 1)
    ];
    unset($_SESSION['outbound_flight_id']);
    unset($_SESSION['inbound_flight_id']);
}

if (!isset($_SESSION['search_criteria'])) {
    header("Location: index.php");
    exit;
}

$criteria = $_SESSION['search_criteria'];
$origin = $criteria['origin'];
$destination = $criteria['destination'];
$depart_date = $criteria['depart_date'];
$return_date = $criteria['return_date'];
$travel_type = $criteria['travel_type'];
$passengers = $criteria['passengers'];

if (isset($_GET['select_outbound'])) {
    $_SESSION['outbound_flight_id'] = $_GET['select_outbound'];
    
    if ($travel_type === 'One Way') {
        header("Location: payment.php");
        exit;
    }
    header("Location: flights.php");
    exit;
}

$current_step = 'outbound'; 

if ($travel_type === 'Round Trip' && isset($_SESSION['outbound_flight_id'])) {
    $current_step = 'inbound';
}

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

$flights_to_display = [];
$display_title = "";
$display_date = "";

if ($current_step === 'outbound') {
    $flights_to_display = searchFlights($conn, $origin, $destination, $depart_date, $passengers, 'depart_date');
    $display_title = "Step 1: Select Departure Flight ($origin to $destination)";
    $display_date = $depart_date;
} else {
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
    <title>Select Flight - VigGo Travel</title>
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
        .flight-section { 
            background: white; 
            padding: 30px; 
            border-radius: 15px; 
            box-shadow: 0 10px 20px rgba(0,0,0,0.05); 
        }
        .section-title { 
            color: #FF5804; 
            border-bottom: 2px solid #eee; 
            padding-bottom: 15px; 
            margin-bottom: 20px; 
            font-weight: 700;
        }
        .step-indicator {
            background-color: #2a6dac;
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 30px;
            font-weight: 600;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color: #2a6dac;
            border: none;
            font-weight: 700;
        }
        .btn-primary:hover {
            background-color: #1e5285;
        }
        .table th {
            color: #555;
            font-weight: 600;
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
                        <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
                        <?php if ($is_logged_in): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-user-circle me-1"></i> <?php echo $current_username; ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><h6 class="dropdown-header">Signed in as <?php echo $current_username; ?></h6></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="booking_history.php">Booking History</a></li>
                                    <li><a class="dropdown-item text-danger fw-bold" href="logout.php">Log Out</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link" href="login.html">Log In / Sign Up</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="main-content">
        <div class="container">
            
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
                <p class="text-muted mb-4"><i class="far fa-calendar-alt me-2"></i>Date: <strong><?php echo htmlspecialchars($display_date); ?></strong></p>

                <?php if (count($flights_to_display) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
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
                                    
                                    if ($current_step === 'outbound') {
                                        $target_url = "flights.php?select_outbound=" . $row['flight_id'];
                                    } else {
                                        $target_url = "payment.php?select_inbound=" . $row['flight_id'];
                                    }

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
                                        <a href="<?php echo $final_link; ?>" class="btn btn-sm btn-primary px-4 rounded-pill">
                                            Select <?php echo ($current_step === 'outbound' && $travel_type === 'Round Trip') ? '& Continue' : 'Flight'; ?>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning border-0 shadow-sm">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        No flights found for this date. 
                        <a href="index.php" class="alert-link fw-bold">Search Again</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="text-center mt-4">
                <a href="searchflight1.php" class="btn btn-outline-secondary fw-bold rounded-pill px-4"><i class="fas fa-search me-2"></i>Restart Search</a>
            </div>

        </div>
    </div>
    <footer>
        <div class="container">
            <p class="mb-0 small">&copy; 2025 VigGo Travel. All rights reserved.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>