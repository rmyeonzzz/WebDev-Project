<?php
session_start();

// 1. Security & Data Checks
if (!isset($_SESSION['loggedin']) || !isset($_POST['outbound_flight_id'])) {
    header("Location: index.php");
    exit();
}

// 2. Database Connection
$serverName = "LAPTOP-1AD6EHQ4";
$connectionOptions = ["Database" => "DLSU", "Uid" => "", "PWD" => ""]; 
$conn = sqlsrv_connect($serverName, $connectionOptions); 

if ($conn === false) {
    die("System Error: Unable to connect to database. " . print_r(sqlsrv_errors(), true));
}

// 3. Gather Input Data
$outbound_id   = $_POST['outbound_flight_id'];
$inbound_id    = !empty($_POST['inbound_flight_id']) ? $_POST['inbound_flight_id'] : null;
$passengers    = intval($_POST['passengers']);
$total_amount  = floatval($_POST['total_amount']);
$card_number   = preg_replace('/\D/', '', $_POST['card_number']); 
$masked_card   = '************' . substr($card_number, -4);
$user_id       = $_SESSION['USERID'];

// 4. Helper Function: Process a Single Flight Leg
function processFlightLeg($conn, $flight_id, $user_id, $passengers) {
    
    // A. Fetch Flight Details from DB (Secure)
    $sql_fetch = "SELECT * FROM DUMMYFLIGHTS2 WHERE flight_id = ?";
    $stmt_fetch = sqlsrv_query($conn, $sql_fetch, array($flight_id));
    
    if ($stmt_fetch === false || !($flight_data = sqlsrv_fetch_array($stmt_fetch, SQLSRV_FETCH_ASSOC))) {
        throw new Exception("Flight ID $flight_id not found.");
    }

    // B. Calculate Price for this leg (Price * Pax)
    $leg_total = $flight_data['price'] * $passengers;

    // C. Insert into AGILA_BOOKINGDRAFT
    $sql_insert = "INSERT INTO AGILA_BOOKINGDRAFT 
        (USERID, flight_id, flight_number, origin, destination, depart_date, 
         price, passengers, total_amount, status, travel_type)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); 
        SELECT SCOPE_IDENTITY() as new_id"; 

    $params_insert = [
        $user_id,
        $flight_data['flight_id'],
        $flight_data['flight_number'],
        $flight_data['depart_city'],
        $flight_data['arrival_city'],
        $flight_data['depart_date']->format('Y-m-d'), // Format date object to string
        $flight_data['price'],
        $passengers,
        $leg_total,
        'Confirmed',
        $flight_data['flight_type']
    ];

    $stmt_insert = sqlsrv_query($conn, $sql_insert, $params_insert);
    if ($stmt_insert === false) throw new Exception("Booking Insert Failed for Flight " . $flight_data['flight_number']);

    // D. Get New Booking ID
    sqlsrv_next_result($stmt_insert); 
    sqlsrv_fetch($stmt_insert);
    $new_booking_id = sqlsrv_get_field($stmt_insert, 0);

    // E. Update Seats
    $sql_update = "UPDATE DUMMYFLIGHTS2 SET seats = seats - ? WHERE flight_id = ? AND seats >= ?";
    $params_update = [$passengers, $flight_id, $passengers];
    $stmt_update = sqlsrv_query($conn, $sql_update, $params_update);

    if ($stmt_update === false || sqlsrv_rows_affected($stmt_update) == 0) {
        throw new Exception("Seats not available for Flight " . $flight_data['flight_number']);
    }

    return $new_booking_id;
}

// 5. Start Transaction
if (sqlsrv_begin_transaction($conn) === false) {
    die("System Error: Could not start transaction.");
}

try {

    // --- PROCESS OUTBOUND ---
    $main_booking_id = processFlightLeg($conn, $outbound_id, $user_id, $passengers);

    // --- PROCESS INBOUND (If exists) ---
    if ($inbound_id) {
        $inbound_booking_id = processFlightLeg($conn, $inbound_id, $user_id, $passengers);
    }

    // --- PROCESS PAYMENT ---
    // We link the payment to the Main (Outbound) Booking ID. 
    $sql_payment = "INSERT INTO AGILA_PAYMENT 
                    (bookingid, USERID, amount, payment_method, card_masked, status) 
                    VALUES (?, ?, ?, ?, ?, ?)";
    
    $params_payment = [
        $main_booking_id,    
        $user_id,       
        $total_amount,  
        'Credit Card',
        $masked_card,   
        'Completed'         
    ];

    $stmt_payment = sqlsrv_query($conn, $sql_payment, $params_payment);
    if ($stmt_payment === false) throw new Exception("Payment Insert Failed");

    // --- COMMIT ---
    sqlsrv_commit($conn);

    // --- CLEAN UP SESSION ---
    // Clear the search session data now that booking is complete
    unset($_SESSION['search_criteria']);
    unset($_SESSION['outbound_flight_id']);

    // Redirect to Success Page
    header("Location: payment_success.php?booking_id=" . $main_booking_id);
    exit();

} catch (Exception $e) {
    // Rollback changes if anything went wrong
    sqlsrv_rollback($conn);
    // Display error clearly
    die("<h2>Booking Failed</h2><p>Error: " . $e->getMessage() . "</p><a href='searchflight.php'>Return to Search</a>");
}
?>