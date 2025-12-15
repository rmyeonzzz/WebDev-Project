<?php
session_start();

// minimal security: if no booking ID, redirect to home
if (!isset($_GET['booking_id'])) {
    header("Location: index.php");
    exit();
}

$booking_id = $_GET['booking_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful - Agila Airlines</title>
    <style>
        body { font-family: sans-serif; background: #eefdf3; text-align: center; padding: 50px; }
        .card { background: white; max-width: 500px; margin: 0 auto; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h1 { color: #28a745; margin-bottom: 10px; }
        .icon { font-size: 50px; color: #28a745; margin-bottom: 20px; }
        .ref { font-size: 1.2em; margin: 20px 0; color: #555; }
        .ref strong { color: #333; font-size: 1.4em; }
        .btn { display: inline-block; text-decoration: none; background: #007bff; color: white; padding: 10px 20px; border-radius: 5px; margin-top: 20px; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>

    <div class="card">
        <div class="icon">✓</div>
        <h1>Payment Successful!</h1>
        <p>Thank you for flying with Agila Airlines.</p>
        <p>Your seat has been reserved.</p>
        
        <div class="ref">
            Booking Reference:<br>
            <strong><?php echo htmlspecialchars($booking_id); ?></strong>
        </div>

        <a href="index.php" class="btn">Return to Home</a>
    </div>

</body>
</html>