<?php
session_start();

// Redirect if accessed directly without login or data
if (!isset($_SESSION['loggedin']) || !isset($_POST['flight_id'])) {
    header("Location: index.php"); // Or your home page
    exit();
}

// Database Connection
$serverName = "LAPTOP-1AD6EHQ4";
$connectionOptions = ["Database" => "DLSU", "Uid" => "", "PWD" => ""]; 
$conn = sqlsrv_connect($serverName, $connectionOptions); 

if ($conn === false) {
    die("System Error: Unable to connect to database.");
}

// Gather Data
$flight_id      = $_POST['flight_id'];
$passengers     = intval($_POST['passengers']);
$total_amount   = floatval($_POST['total_amount']);
$card_number    = preg_replace('/\D/', '', $_POST['card_number']); // Remove spaces
$masked_card    = '************' . substr($card_number, -4); // Mask the card

// Start Transaction
if (sqlsrv_begin_transaction($conn) === false) {
    die("System Error: Could not start transaction.");
}

try {
    // 1. Insert Booking Draft
    $sql_insert = "INSERT INTO AGILA_BOOKINGDRAFT 
        (USERID, flight_id, flight_number, origin, destination, depart_date, 
         price, passengers, total_amount, status, travel_type)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); 
        SELECT SCOPE_IDENTITY() as new_id"; 

    $params_insert = [
        $_SESSION['USERID'], 
        $flight_id, 
        $_POST['flight_number'], 
        $_POST['origin'], 
        $_POST['destination'], 
        $_POST['depart_date'], 
        $_POST['price'], 
        $passengers, 
        $total_amount, 
        'Confirmed', 
        $_POST['travel_type']
    ];

    $stmt_insert = sqlsrv_query($conn, $sql_insert, $params_insert);
    if ($stmt_insert === false) throw new Exception("Booking Insert Failed");

    // Retrieve the new Booking ID
    sqlsrv_next_result($stmt_insert); 
    sqlsrv_fetch($stmt_insert);
    $booking_id = sqlsrv_get_field($stmt_insert, 0);

    // 2. Insert Payment
    // Note: 'transaction_date' is handled automatically by DB Default now
    $sql_payment = "INSERT INTO AGILA_PAYMENT 
                    (bookingid, USERID, amount, payment_method, card_masked, status) 
                    VALUES (?, ?, ?, ?, ?, ?)";
    
    $params_payment = [
        $booking_id,    
        $_SESSION['USERID'],       
        $total_amount,  
        'Credit Card',
        $masked_card,   
        'Completed'         
    ];

    $stmt_payment = sqlsrv_query($conn, $sql_payment, $params_payment);
    if ($stmt_payment === false) throw new Exception("Payment Insert Failed");

    // 3. Update Seats
    $sql_update = "UPDATE DUMMYFLIGHTS2 
                   SET seats = seats - ? 
                   WHERE flight_id = ? AND seats >= ?";
    $params_update = [$passengers, $flight_id, $passengers];
    $stmt_update = sqlsrv_query($conn, $sql_update, $params_update);

    if ($stmt_update === false || sqlsrv_rows_affected($stmt_update) == 0) {
        throw new Exception("Seat Update Failed (Sold out or Invalid ID)");
    }

    // Commit Transaction
    sqlsrv_commit($conn);

    // Redirect to Success Page
    header("Location: payment_success.php?booking_id=" . $booking_id);
    exit();

} catch (Exception $e) {
    // Rollback changes if anything went wrong
    sqlsrv_rollback($conn);
    die("<h2>Booking Failed</h2><p>Error: " . $e->getMessage() . "</p><a href='search.php'>Return to Search</a>");
}
?>