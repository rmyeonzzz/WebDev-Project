<?php
session_start();

// 1. Security: Redirect if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit();
}

// 2. Database Connection
$serverName = "LAPTOP-1AD6EHQ4";
$connectionOptions = ["Database" => "DLSU", "Uid" => "", "PWD" => ""]; 
$conn = sqlsrv_connect($serverName, $connectionOptions); 

if ($conn === false) {
    die("Database Connection Failed: " . print_r(sqlsrv_errors(), true));
}

// 3. Fetch User's Bookings
// We sort by bookingid DESC so the newest bookings appear at the top
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Booking History - VigGo Flights</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src='https://kit.fontawesome.com/4c729db828.js' crossorigin='anonymous'></script>
    <style>
        body { background-color: #f8f9fa; }
        .card { border: none; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
        .table-responsive { border-radius: 8px; overflow: hidden; }
        .status-badge { font-size: 0.9em; padding: 5px 10px; border-radius: 20px; }
        .status-confirmed { background-color: #d1e7dd; color: #0f5132; }
        .status-cancelled { background-color: #f8d7da; color: #842029; }
        .status-pending { background-color: #fff3cd; color: #664d03; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">VigGo Flights</a>
        <div class="ms-auto">
            <a href="index.php" class="btn btn-outline-light btn-sm">Back to Home</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-history me-2"></i> My Booking History</h2>
        <a href="searchflight.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i> Book New Flight</a>
    </div>

    <?php if (count($bookings) > 0): ?>
        <div class="card p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
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
                            // Format Date
                            $dateRaw = $booking['depart_date'];
                            // Check if date is a DateTime object (SQLSRV often returns objects)
                            $dateDisplay = ($dateRaw instanceof DateTime) ? $dateRaw->format('M d, Y') : $dateRaw;
                            
                            // Determine Status Color
                            $status = ucfirst($booking['status']); // Capitalize first letter
                            $badgeClass = 'status-pending';
                            if (stripos($status, 'Confirm') !== false) $badgeClass = 'status-confirmed';
                            if (stripos($status, 'Cancel') !== false) $badgeClass = 'status-cancelled';
                        ?>
                        <tr>
                            <td><strong>#<?php echo $booking['bookingid']; ?></strong></td>
                            
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($booking['flight_number']); ?></div>
                                <small class="text-muted"><?php echo $booking['passengers']; ?> Passenger(s)</small>
                            </td>
                            
                            <td>
                                <?php echo htmlspecialchars($booking['origin']); ?> 
                                <i class="fas fa-arrow-right text-muted mx-1"></i> 
                                <?php echo htmlspecialchars($booking['destination']); ?>
                            </td>
                            
                            <td><?php echo $dateDisplay; ?></td>
                            
                            <td><?php echo htmlspecialchars($booking['travel_type']); ?></td>
                            
                            <td class="fw-bold text-success">
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
            <div class="display-1 text-muted mb-3"><i class="fas fa-plane-slash"></i></div>
            <h3 class="text-muted">No bookings found</h3>
            <p class="mb-4">You haven't booked any flights with us yet.</p>
            <a href="searchflight.php" class="btn btn-lg btn-primary">Find a Flight</a>
        </div>
    <?php endif; ?>

</div>

</body>
</html>