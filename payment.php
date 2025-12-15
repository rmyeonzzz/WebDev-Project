<?php
session_start();

// 1. Security Check: Ensure user is logged in
if (!isset($_SESSION['loggedin'])) {
    // If not logged in, capture the current URL so we can return here after login
    $redirect_url = "payment.php?" . $_SERVER['QUERY_STRING'];
    header("Location: login.html?redirect=" . urlencode($redirect_url));
    exit();
}

// 2. Database Connection
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

// 3. Retrieve IDs and Search Criteria
$outbound_id = $_SESSION['outbound_flight_id'] ?? null;
$inbound_id  = $_GET['select_inbound'] ?? null;
$passengers  = $_SESSION['search_criteria']['passengers'] ?? 1;

if (!$outbound_id) {
    echo "No flight selected. <a href='searchflight.php'>Go back to Search</a>";
    exit();
}

// 4. Function to Fetch Flight Details
function getFlightDetails($conn, $id) {
    $sql = "SELECT * FROM DUMMYFLIGHTS2 WHERE flight_id = ?";
    $params = array($id);
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        return $row;
    }
    return null;
}

// 5. Fetch Data
$outbound_flight = getFlightDetails($conn, $outbound_id);
$inbound_flight  = ($inbound_id) ? getFlightDetails($conn, $inbound_id) : null;

if (!$outbound_flight) {
    die("Error: Outbound flight not found.");
}

// 6. Calculate Totals
$price_outbound = $outbound_flight['price'];
$price_inbound  = $inbound_flight ? $inbound_flight['price'] : 0;

$total_price_per_person = $price_outbound + $price_inbound;
$total_amount_due = $total_price_per_person * $passengers;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Payment - VigGo Travels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7f6; padding-top: 40px; }
        .payment-container { max-width: 900px; margin: 0 auto; }
        .card-header-custom { background-color: #2a6dac; color: white; font-weight: bold; }
        .summary-box { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .total-highlight { font-size: 1.5rem; color: #198754; font-weight: bold; }
        .flight-leg-title { border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px; color: #555; }
    </style>
</head>
<body>

<div class="container payment-container">
    
    <h2 class="mb-4 text-center">Complete Your Booking</h2>

    <div class="row">
        <div class="col-md-5 order-md-2">
            <div class="summary-box">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-primary">Flight Summary</span>
                    <span class="badge bg-primary rounded-pill"><?php echo $passengers; ?> Pax</span>
                </h4>
                
                <h6 class="flight-leg-title mt-3">✈️ Outbound (Depart)</h6>
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between lh-sm">
                        <div>
                            <h6 class="my-0"><?php echo htmlspecialchars($outbound_flight['flight_number']); ?></h6>
                            <small class="text-muted"><?php echo htmlspecialchars($outbound_flight['depart_city']); ?> ➝ <?php echo htmlspecialchars($outbound_flight['arrival_city']); ?></small>
                        </div>
                        <span class="text-muted">₱<?php echo number_format($outbound_flight['price'], 2); ?></span>
                    </li>
                    <li class="list-group-item">
                        <small>Date: <strong><?php echo $outbound_flight['depart_date']->format('Y-m-d'); ?></strong></small><br>
                        <small>Time: <?php echo $outbound_flight['depart_time']->format('H:i'); ?></small>
                    </li>
                </ul>

                <?php if ($inbound_flight): ?>
                <h6 class="flight-leg-title mt-3">✈️ Inbound (Return)</h6>
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between lh-sm">
                        <div>
                            <h6 class="my-0"><?php echo htmlspecialchars($inbound_flight['flight_number']); ?></h6>
                            <small class="text-muted"><?php echo htmlspecialchars($inbound_flight['depart_city']); ?> ➝ <?php echo htmlspecialchars($inbound_flight['arrival_city']); ?></small>
                        </div>
                        <span class="text-muted">₱<?php echo number_format($inbound_flight['price'], 2); ?></span>
                    </li>
                    <li class="list-group-item">
                        <small>Date: <strong><?php echo $inbound_flight['depart_date']->format('Y-m-d'); ?></strong></small><br>
                        <small>Time: <?php echo $inbound_flight['depart_time']->format('H:i'); ?></small>
                    </li>
                </ul>
                <?php endif; ?>

                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total (PHP)</span>
                        <span class="total-highlight">₱<?php echo number_format($total_amount_due, 2); ?></span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-7 order-md-1">
            <div class="summary-box">
                <h4 class="mb-3">Payment Details</h4>
                
                <form action="process_booking.php" method="POST">
                    
                    <input type="hidden" name="outbound_flight_id" value="<?php echo $outbound_id; ?>">
                    <input type="hidden" name="inbound_flight_id" value="<?php echo $inbound_id; ?>"> <input type="hidden" name="passengers" value="<?php echo $passengers; ?>">
                    <input type="hidden" name="total_amount" value="<?php echo $total_amount_due; ?>">

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="cc-name" class="form-label">Name on Card</label>
                            <input type="text" class="form-control" id="cc-name" name="card_name" placeholder="Juan Dela Cruz" required>
                            <div class="invalid-feedback">Name on card is required</div>
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

                    <button class="w-100 btn btn-success btn-lg" type="submit">Pay ₱<?php echo number_format($total_amount_due, 2); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>