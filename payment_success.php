<?php
session_start();

if (!isset($_GET['booking_id'])) {
    header("Location: index.php");
    exit();
}

$booking_id = $_GET['booking_id'];
$current_username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest';
$is_logged_in = isset($_SESSION['loggedin']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful - VigGo Travel</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card { 
            background: white; 
            max-width: 500px; 
            width: 100%;
            padding: 40px; 
            border: none;
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); 
            text-align: center;
        }
        h1 { color: #2a6dac; font-weight: 700; margin-bottom: 10px; }
        .icon { font-size: 60px; color: #198754; margin-bottom: 20px; }
        .ref { 
            background-color: #f1f3f5;
            padding: 15px;
            border-radius: 8px;
            font-size: 1.1em; 
            margin: 25px 0; 
            color: #555; 
        }
        .ref strong { color: #FF5804; font-size: 1.4em; letter-spacing: 1px;}
        .btn-home { 
            background: #FF5804; 
            color: white; 
            padding: 12px 30px; 
            border-radius: 50px; 
            font-weight: 700; 
            border: none;
            transition: all 0.3s;
        }
        .btn-home:hover { background: #e04e03; color: white; }
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
                        <?php if ($is_logged_in): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-user-circle me-1"></i> <?php echo $current_username; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item text-danger fw-bold" href="logout.php">Log Out</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="main-content">
        <div class="card">
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <h1>Payment Successful!</h1>
            <p class="text-muted">Thank you for flying with VigGo.</p>
            <p class="text-muted">Your seat has been reserved successfully.</p>
            
            <div class="ref">
                Booking Reference:<br>
                <strong><?php echo htmlspecialchars($booking_id); ?></strong>
            </div>

            <a href="index.php" class="btn btn-home shadow-sm">Return to Home</a>
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