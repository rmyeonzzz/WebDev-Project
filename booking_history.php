<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit();
}

$serverName = "LAPTOP-1AD6EHQ4";
$connectionOptions = ["Database" => "DLSU", "Uid" => "", "PWD" => ""]; 
$conn = sqlsrv_connect($serverName, $connectionOptions); 

if ($conn === false) {
    die("Database Connection Failed: " . print_r(sqlsrv_errors(), true));
}

$sql = "SELECT bookingid, flight_number, origin, destination, depart_date, 
                total_amount, status, travel_type, passengers 
        FROM AGILA_BOOKINGDRAFT 
        WHERE USERID = ? 
        ORDER BY bookingid DESC";

$params = [$_SESSION['USERID']];
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die("Error fetching history: " . print_r(sqlsrv_errors(), true));
}

$bookings = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $bookings[] = $row;
}

$current_username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Booking History - VigGo Travel</title>
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
        .card { 
            border: none; 
            border-radius: 15px; 
            box-shadow: 0 10px 20px rgba(0,0,0,0.05); 
            overflow: hidden;
        }
        .table thead {
            background-color: #2a6dac;
            color: white;
        }
        .table th {
            font-weight: 600;
            padding: 15px;
            border: none;
        }
        .table td {
            padding: 15px;
            vertical-align: middle;
        }
        .status-badge { 
            font-size: 0.85em; 
            padding: 8px 12px; 
            border-radius: 30px; 
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-confirmed { background-color: #d1e7dd; color: #0f5132; }
        .status-cancelled { background-color: #f8d7da; color: #842029; }
        .status-pending { background-color: #fff3cd; color: #664d03; }
        
        .btn-primary {
            background-color: #FF5804;
            border: none;
            font-weight: 700;
        }
        .btn-primary:hover { background-color: #e04e03; }
        
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-secondary"><i class="fas fa-history me-2 text-warning"></i> My Booking History</h2>
                <a href="searchflight1.php" class="btn btn-primary rounded-pill px-4 shadow-sm">
                    <i class="fas fa-plus me-2"></i> Book New Flight
                </a>
            </div>

            <?php if (count($bookings) > 0): ?>
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Ref ID</th>
                                    <th>Flight Details</th>
                                    <th>Route</th>
                                    <th>Travel Date</th>
                                    <th>Class</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookings as $booking): 
                                    $dateRaw = $booking['depart_date'];
                                    $dateDisplay = ($dateRaw instanceof DateTime) ? $dateRaw->format('M d, Y') : $dateRaw;
                                    
                                    $status = ucfirst($booking['status']); 
                                    $badgeClass = 'status-pending';
                                    if (stripos($status, 'Confirm') !== false) $badgeClass = 'status-confirmed';
                                    if (stripos($status, 'Cancel') !== false) $badgeClass = 'status-cancelled';
                                ?>
                                <tr>
                                    <td class="text-secondary"><strong>#<?php echo $booking['bookingid']; ?></strong></td>
                                    
                                    <td>
                                        <div class="fw-bold" style="color: #2a6dac;"><?php echo htmlspecialchars($booking['flight_number']); ?></div>
                                        <small class="text-muted"><?php echo $booking['passengers']; ?> Passenger(s)</small>
                                    </td>
                                    
                                    <td>
                                        <span class="fw-semibold"><?php echo htmlspecialchars($booking['origin']); ?></span>
                                        <i class="fas fa-arrow-right text-muted mx-2 small"></i> 
                                        <span class="fw-semibold"><?php echo htmlspecialchars($booking['destination']); ?></span>
                                    </td>
                                    
                                    <td><?php echo $dateDisplay; ?></td>
                                    
                                    <td><span class="badge bg-secondary"><?php echo htmlspecialchars($booking['travel_type']); ?></span></td>
                                    
                                    <td class="fw-bold" style="color: #2a6dac;">
                                        ₱<?php echo number_format($booking['total_amount'], 2); ?>
                                    </td>
                                    
                                    <td>
                                        <span class="badge status-badge <?php echo $badgeClass; ?>">
                                            <?php echo htmlspecialchars($status); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <div class="mb-3 text-muted" style="font-size: 5rem; opacity: 0.3;">
                        <i class="fas fa-plane-slash"></i>
                    </div>
                    <h3 class="text-muted fw-bold">No bookings found</h3>
                    <p class="text-secondary mb-4">You haven't booked any flights with us yet.</p>
                    <a href="searchflight1.php" class="btn btn-lg btn-outline-primary rounded-pill px-5 fw-bold">Find a Flight</a>
                </div>
            <?php endif; ?>

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