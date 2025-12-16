<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    $redirect_url = "payment.php?" . $_SERVER['QUERY_STRING'];
    header("Location: login.html?redirect=" . urlencode($redirect_url));
    exit();
}

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

$outbound_id = $_SESSION['outbound_flight_id'] ?? null;
$inbound_id  = $_GET['select_inbound'] ?? null;
$passengers  = $_SESSION['search_criteria']['passengers'] ?? 1;

if (!$outbound_id) {
    echo "No flight selected. <a href='searchflight.php'>Go back to Search</a>";
    exit();
}

function getFlightDetails($conn, $id) {
    $sql = "SELECT * FROM DUMMYFLIGHTS2 WHERE flight_id = ?";
    $params = array($id);
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        return $row;
    }
    return null;
}

$outbound_flight = getFlightDetails($conn, $outbound_id);
$inbound_flight  = ($inbound_id) ? getFlightDetails($conn, $inbound_id) : null;

if (!$outbound_flight) {
    die("Error: Outbound flight not found.");
}

$price_outbound = $outbound_flight['price'];
$price_inbound  = $inbound_flight ? $inbound_flight['price'] : 0;

$total_price_per_person = $price_outbound + $price_inbound;
$total_amount_due = $total_price_per_person * $passengers;

$current_username = htmlspecialchars($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Payment - VigGo Travels</title>
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
        .summary-box { 
            background: white; 
            padding: 25px; 
            border-radius: 15px; 
            box-shadow: 0 10px 20px rgba(0,0,0,0.05); 
            margin-bottom: 20px; 
        }
        .total-highlight { 
            font-size: 1.5rem; 
            color: #FF5804; 
            font-weight: 800; 
        }
        .flight-leg-title { 
            border-bottom: 1px solid #eee; 
            padding-bottom: 10px; 
            margin-bottom: 15px; 
            color: #2a6dac; 
            font-weight: 600;
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
        .form-label { font-weight: 600; font-size: 0.9rem; }
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
    <div class="container payment-container">
        
        <h2 class="mb-4 text-center fw-bold text-secondary">Complete Your Booking</h2>

        <div class="row">
            <div class="col-md-5 order-md-2">
                <div class="summary-box">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-primary fw-bold" style="color: #2a6dac !important;">Flight Summary</span>
                        <span class="badge bg-primary rounded-pill"><?php echo $passengers; ?> Pax</span>
                    </h4>
                    
                    <h6 class="flight-leg-title mt-3">✈️ Outbound (Depart)</h6>
                    <ul class="list-group mb-3 shadow-sm">
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                                <h6 class="my-0 fw-bold"><?php echo htmlspecialchars($outbound_flight['flight_number']); ?></h6>
                                <small class="text-muted"><?php echo htmlspecialchars($outbound_flight['depart_city']); ?> ➝ <?php echo htmlspecialchars($outbound_flight['arrival_city']); ?></small>
                            </div>
                            <span class="text-muted">₱<?php echo number_format($outbound_flight['price'], 2); ?></span>
                        </li>
                        <li class="list-group-item bg-light">
                            <small>Date: <strong><?php echo $outbound_flight['depart_date']->format('Y-m-d'); ?></strong></small><br>
                            <small>Time: <?php echo $outbound_flight['depart_time']->format('H:i'); ?></small>
                        </li>
                    </ul>

                    <?php if ($inbound_flight): ?>
                    <h6 class="flight-leg-title mt-3">✈️ Inbound (Return)</h6>
                    <ul class="list-group mb-3 shadow-sm">
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                                <h6 class="my-0 fw-bold"><?php echo htmlspecialchars($inbound_flight['flight_number']); ?></h6>
                                <small class="text-muted"><?php echo htmlspecialchars($inbound_flight['depart_city']); ?> ➝ <?php echo htmlspecialchars($inbound_flight['arrival_city']); ?></small>
                            </div>
                            <span class="text-muted">₱<?php echo number_format($inbound_flight['price'], 2); ?></span>
                        </li>
                        <li class="list-group-item bg-light">
                            <small>Date: <strong><?php echo $inbound_flight['depart_date']->format('Y-m-d'); ?></strong></small><br>
                            <small>Time: <?php echo $inbound_flight['depart_time']->format('H:i'); ?></small>
                        </li>
                    </ul>
                    <?php endif; ?>

                    <ul class="list-group mb-3 shadow-sm">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Total (PHP)</span>
                            <span class="total-highlight">₱<?php echo number_format($total_amount_due, 2); ?></span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-md-7 order-md-1">
                <div class="summary-box">
                    <h4 class="mb-3 fw-bold">Payment Details</h4>
                    
                    <form action="process_booking.php" method="POST">
                        
                        <input type="hidden" name="outbound_flight_id" value="<?php echo $outbound_id; ?>">
                        <input type="hidden" name="inbound_flight_id" value="<?php echo $inbound_id; ?>"> 
                        <input type="hidden" name="passengers" value="<?php echo $passengers; ?>">
                        <input type="hidden" name="total_amount" value="<?php echo $total_amount_due; ?>">

                        <div class="row g-3">
                            <div class="col-12">
                                <label for="cc-name" class="form-label">Name on Card</label>
                                <input type="text" class="form-control" id="cc-name" name="card_name" placeholder="Juan Dela Cruz" required>
                            </div>

                            <div class="col-12">
                                <label for="cc-number" class="form-label">Credit Card Number</label>
                                <input type="text" class="form-control" id="cc-number" name="card_number" placeholder="0000 0000 0000 0000" maxlength="19" required>
                            </div>

                            <div class="col-md-6">
                                <label for="cc-expiration" class="form-label">Expiration</label>
                                <input type="text" class="form-control" id="cc-expiration" name="expiry" placeholder="MM/YY" required>
                            </div>

                            <div class="col-md-6">
                                <label for="cc-cvv" class="form-label">CVV</label>
                                <input type="password" class="form-control" id="cc-cvv" name="cvv" placeholder="123" maxlength="3" required>
                            </div>
                        </div>

                        <hr class="my-4">

                        <button class="w-100 btn btn-success btn-lg rounded-pill" type="submit">Pay ₱<?php echo number_format($total_amount_due, 2); ?></button>
                    </form>
                </div>
            </div>
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