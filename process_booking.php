<?php
session_start();

// Ensure the user is logged in before processing the booking
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html"); 
    exit;
}

// 1. Database Connection Setup
$serverName = "LAPTOP-1AD6EHQ4";
$connectionOptions = [ 
     "Database" => "DLSU", 
     "Uid" => "", 
     "PWD" => "" 
]; 
$conn = sqlsrv_connect($serverName, $connectionOptions); 

if ($conn === false) {
    $_SESSION['booking_error'] = 'Database connection error during booking process. Please try again.';
    header("Location: searchflight.php");
    exit();
}

// 2. Retrieve Data from POST and Session
$user_id = $_SESSION['USERID'];
$booking_date = date('Y-m-d H:i:s'); // Record the booking timestamp

// Flight details retrieved from the hidden fields in booking.php
// Use the null coalescing operator (??) for safety
$flight_id = $_POST['flight_id'] ?? null;
$flight_number = $_POST['flight_number'] ?? null;
$origin = $_POST['origin'] ?? null;
$destination = $_POST['destination'] ?? null;
$depart_date = $_POST['depart_date'] ?? null;
$price_per_seat = floatval($_POST['price'] ?? 0);
$passengers = intval($_POST['passengers'] ?? 0);
$total_amount = floatval($_POST['total_amount'] ?? 0);
$travel_type = $_POST['travel_type'] ?? 'Economy';


// 3. Basic Validation
if (empty($flight_id) || $passengers < 1 || $total_amount <= 0 || empty($depart_date)) {
    $_SESSION['booking_error'] = 'Required booking details are missing or invalid.';
    header("Location: searchflight.php"); 
    exit();
}

// 4. Start SQL Server Transaction Block
// This is critical: if any query fails, the entire change is rolled back.
if (sqlsrv_begin_transaction($conn) === false) {
    $_SESSION['booking_error'] = 'Failed to start database transaction.';
    header("Location: searchflight.php");
    exit();
}

$success = true; // Flag to track overall transaction success

// --- TRANSACTION STEP 1: Insert Booking Record ---
// NOTE: Verify these column names EXACTLY match your AGILA_BOOKINGDRAFT table!
$sql_insert = "INSERT INTO AGILA_BOOKINGDRAFT 
    (USERID, flight_id, flight_number, origin, destination, depart_date, 
     price, passengers, total_amount, status, travel_type)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; // 11 placeholders (Correct)
    
$params_insert = [
    $user_id, 
    $flight_id, 
    $flight_number, 
    $origin, 
    $destination, 
    $depart_date, 
    $price_per_seat, 
    $passengers, 
    $total_amount, 
    'Pending', // <-- ADDED: Explicitly set a default status for the new booking
    $travel_type, 
]; // 11 parameters (Correct)

$stmt_insert = sqlsrv_query($conn, $sql_insert, $params_insert);

if ($stmt_insert === false) {
    $success = false;
    // The rollback block further down will now capture the specific error if it fails
}
// --- TRANSACTION STEP 2: Update Available Seats ---
if ($success) {
    // Decrement the 'seats' column in the flights table
    // The WHERE clause checks that seats >= passengers before updating.
    $sql_update = "UPDATE DUMMYFLIGHTS2 
                   SET seats = seats - ? 
                   WHERE flight_id = ? 
                   AND seats >= ?"; 

    $params_update = [$passengers, $flight_id, $passengers];

    $stmt_update = sqlsrv_query($conn, $sql_update, $params_update);

    // Check if the update failed OR if the seat count was too low (no rows affected)
    if ($stmt_update === false || sqlsrv_rows_affected($stmt_update) != 1) {
        $success = false;
    }
}


// 5. Commit or Rollback
if ($success) {
    sqlsrv_commit($conn);
    
    // Success: Set session message and redirect to confirmation page
    $_SESSION['booking_status'] = 'success';
    $_SESSION['last_booking'] = [
        'flight_number' => $flight_number,
        'passengers' => $passengers,
        'total_amount' => $total_amount,
        'date' => $depart_date
    ];
    // ADD THE LINE BELOW
    session_write_close(); // <-- ADD THIS LINE
    header("Location: booking_success.php");
    
} else {
    sqlsrv_rollback($conn);
    
    // Failure: Get error details and set session message
    $errors = sqlsrv_errors();
    $error_message = 'Booking failed. ';
    
    if ($errors) {
        // Display detailed error for debugging
        $error_message .= 'Database Error: ' . $errors[0]['message'] . ' | SQL State: ' . $errors[0]['SQLSTATE'];
    } else {
        $error_message .= 'The flight may no longer have enough seats available.';
    }
    
    $_SESSION['booking_error'] = $error_message;

    // Redirect back to searchflight.php to show the error
    header("Location: searchflight.php"); 
}

// 6. Cleanup
if (isset($stmt_insert)) sqlsrv_free_stmt($stmt_insert);
if (isset($stmt_update)) sqlsrv_free_stmt($stmt_update);
sqlsrv_close($conn);
exit();
?>