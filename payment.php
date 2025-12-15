<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

// CHANGE: Use $_REQUEST instead of $_POST to capture data from the Search URL
if (empty($_REQUEST['flight_id'])) {
    echo "No flight selected. <a href='searchflight.php'>Go back</a>";
    exit();
}

// Retrieve flight details from URL parameters
$flight_id      = $_REQUEST['flight_id'];
$flight_number  = $_REQUEST['flight_number'];
$origin         = $_REQUEST['origin'];
$destination    = $_REQUEST['destination'];
$price          = $_REQUEST['price'];
$depart_date    = $_REQUEST['depart_date'];
$travel_type    = $_REQUEST['travel_type'] ?? 'One Way';

// Handle passenger count (default to 1 if missing)
$passengers     = isset($_REQUEST['passengers']) ? intval($_REQUEST['passengers']) : 1;
$total_amount   = $price * $passengers;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Payment - Agila Airlines</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .summary { background: #eef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn-pay { background: #28a745; color: white; padding: 15px; width: 100%; border: none; font-size: 18px; cursor: pointer; border-radius: 5px; }
        .btn-pay:hover { background: #218838; }
    </style>
</head>
<body>

<div class="container">
    <h2>Complete Your Booking</h2>

    <div class="summary">
        <h3>Flight Summary</h3>
        <p><strong>Flight:</strong> <?php echo htmlspecialchars($flight_number); ?> (<?php echo htmlspecialchars($origin); ?> to <?php echo htmlspecialchars($destination); ?>)</p>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($depart_date); ?></p>
        <p><strong>Passengers:</strong> <?php echo $passengers; ?></p>
        <p><strong>Total Price:</strong> <span style="font-size: 1.2em; color: green; font-weight: bold;">₱<?php echo number_format($total_amount, 2); ?></span></p>
    </div>

    <form action="process_booking.php" method="POST">
        <input type="hidden" name="flight_id" value="<?php echo $flight_id; ?>">
        <input type="hidden" name="flight_number" value="<?php echo $flight_number; ?>">
        <input type="hidden" name="origin" value="<?php echo $origin; ?>">
        <input type="hidden" name="destination" value="<?php echo $destination; ?>">
        <input type="hidden" name="depart_date" value="<?php echo $depart_date; ?>">
        <input type="hidden" name="travel_type" value="<?php echo $travel_type; ?>">
        <input type="hidden" name="price" value="<?php echo $price; ?>">
        <input type="hidden" name="passengers" value="<?php echo $passengers; ?>">
        <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">

        <h3>Payment Details</h3>
        
        <div class="form-group">
            <label>Cardholder Name</label>
            <input type="text" name="card_name" required placeholder="Name on Card">
        </div>

        <div class="form-group">
            <label>Card Number</label>
            <input type="text" name="card_number" required placeholder="1234 5678 1234 5678" maxlength="19">
        </div>

        <div style="display: flex; gap: 10px;">
            <div class="form-group" style="flex: 1;">
                <label>Expiry Date</label>
                <input type="text" name="expiry" placeholder="MM/YY" required>
            </div>
            <div class="form-group" style="flex: 1;">
                <label>CVV</label>
                <input type="password" name="cvv" placeholder="123" required maxlength="3">
            </div>
        </div>

        <button type="submit" class="btn-pay">Pay & Book Now</button>
    </form>
</div>

</body>
</html>