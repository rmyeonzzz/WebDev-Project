<?php
session_start();

$is_logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

$user_display_name = '';
$user_full_name = '';

if ($is_logged_in) {
    $user_display_name = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User';
    $user_full_name = isset($_SESSION['completename']) ? htmlspecialchars($_SESSION['completename']) : 'User';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
    <link href="https://fonts.googleapis.com/css2?family=Fuzzy+Bubbles:wght@400;700&family=Mynerve&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src='https://kit.fontawesome.com/4c729db828.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <link rel="stylesheet" href="styles2.css"> 

    <title>Viggo Search Flight</title>

   <style>
        /* --- Navbar Styles --- */
        .navbar-container {
            position: fixed;
            top: 0;
            left: 50%; 
            transform: translateX(-50%);
            width: 95%; 
            max-width: 1600px; 
            padding: 0;
            z-index: 1000;
            transition: all 0.4s ease-in-out;
            background-color: transparent;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 0 0 15px 15px; 
        }

        .navbar-container .navbar {
            padding: 10px 30px; 
        }

        /* Scrolled State */
        .navbar-container.scrolled {
            background-color: #FF5804 !important; 
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); 
            border-bottom: none;
            width: 100%;
            border-radius: 0;
        }

        .navbar-container.scrolled .nav-link,
        .navbar-container.scrolled .heading-logo,
        .navbar-container.scrolled .fa-map {
            color: white !important;
        }

        .navbar-container.scrolled .heading-logo span {
            color: #FFD700 !important;
        }

        .navbar-container.scrolled .btn-outline-light {
            color: white !important;
            border-color: white !important;
        }
        .navbar-container.scrolled .btn-outline-light:hover {
            background-color: white !important;
            color: #FF5804 !important;
        }

        /* Logo Sizing */
        .logo-img {
            height: 70px;
            width: auto; 
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .navbar-container.scrolled .logo-img {
            height: 70px; 
        }

        /* --- Global Button Boldness --- */
        .btn, .tab-btn, .btn-home, .book-now-btn, .ratings-button, .nav-link {
            font-weight: 700 !important;
            letter-spacing: 0.5px;
        }

        /* --- NEW STYLES FOR SEARCH FLIGHT HEADER --- */
        
        /* 1. Reduce height to half (45vh instead of 90vh) */
        .searchflight-section, 
        .home-content {
            height: 45vh !important; 
            min-height: 350px; /* Prevents it from getting too small on mobile */
        }

        /* 2. Set the Seoul Background Image */
        .img-1 {
            /* Note: Forward slashes / are used for web paths */
            background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('pictures/seoul/515441030_10223093755690841_261263445350321395_n.jpg') !important;
            background-position: center center;
            background-size: cover;
            background-repeat: no-repeat;
        }

        /* Adjust text position for smaller height */
        .content-home {
            margin-top: 50px; /* Push text down slightly so it's not hidden by navbar */
        }
    </style>
</head>

<body>

<header class="navbar-container">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom-transparent">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                 <img src="pictures/logo4.png"  class="logo-img">
            </a>
            <div>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" aria-label="Search"/>
                    <button class="btn btn-outline-light" type="submit">Search</button>
                </form>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="searchflight1.php">Book</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
                    <?php if ($is_logged_in): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-user-circle me-1"></i> <?php echo $user_display_name; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><h6 class="dropdown-header">Signed in as <?php echo $user_display_name; ?></h6></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="booking_history.php">Booking History</a></li>
                                <li><a class="dropdown-item text-danger fw-bold" href="logout.php">Log Out</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-signup"><a class="nav-link" href="login.html">Log In / Sign Up</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>

<div class="searchflight-section">
    <div class="container-fluid gx-0"> 
        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="home-content img-1">
                        <div class ="content-home">
                            <h2 class ="heading-home">Discover</h2>
                            <h2 class ="sub-heading-home">SEOUL</h2>
                        </div>
                    </div>
                 </div>
              </div>
         </div>
    </div>
</div>



<div class="booking-section">
    
    <div class="booking-tabs mb-4 d-flex justify-content-center gap-2">

    <div id="flight-container" class="selection-container p-4 bg-white rounded shadow-sm">
        <form id="flightSearchForm" method="POST" action="searchflight.php">
            
            <div class="d-flex align-items-center mb-3">
                <span class="text-warning me-2 fs-4">✈️</span>
                <span class="fw-bold me-4 fs-5">Flight</span>
                
                <select class="form-select form-select-sm fw-bold text-primary border-primary w-auto" 
                     id="travel_type" name="travel_type" onchange="toggleReturnDate()">
                    <option value="Round Trip">Round-trip</option>
                    <option value="One Way">One Way</option>
                </select>
            </div>

            <div class="row g-3 align-items-end">
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <label for="origin" class="form-label fw-semibold">Departure</label>
                    <select class="form-select" id="origin" name="origin" required>
                        <option value="" selected disabled>Select Origin</option>
                        <optgroup label="Local Philippine Hubs">
                            <option value="Manila (MNL)">Manila (MNL)</option>
                            <option value="Cebu (CEB)">Cebu (CEB)</option>
                        </optgroup>
                        <optgroup label="Domestic Destinations">
                            <option value="Boracay (MPH)">Boracay (MPH)</option>
                            <option value="Palawan (PPS)">Palawan (PPS)</option>
                            <option value="Siargao (IAO)">Siargao (IAO)</option>
                            <option value="Iloilo (ILO)">Iloilo (ILO)</option>
                        </optgroup>
                        <optgroup label="International Destinations">
                            <option value="Seoul (ICN)">Seoul (ICN)</option>
                            <option value="Singapore (SIN)">Singapore (SIN)</option>
                            <option value="Tokyo (NRT)">Tokyo (NRT)</option>
                            <option value="Dubai (DXB)">Dubai (DXB)</option>
                            <option value="London (LHR)">London (LHR)</option>
                        </optgroup>
                    </select>
                </div>
                
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <label for="destination" class="form-label fw-semibold">Arrival</label>
                    <select class="form-select" id="destination" name="destination" required>
                        <option value="" selected disabled>Select Destination</option>
                        <optgroup label="Local Philippine Hubs">
                            <option value="Manila (MNL)">Manila (MNL)</option>
                            <option value="Cebu (CEB)">Cebu (CEB)</option>
                        </optgroup>
                        <optgroup label="Domestic Destinations">
                            <option value="Boracay (MPH)">Boracay (MPH)</option>
                            <option value="Palawan (PPS)">Palawan (PPS)</option>
                            <option value="Siargao (IAO)">Siargao (IAO)</option>
                            <option value="Iloilo (ILO)">Iloilo (ILO)</option>
                        </optgroup>
                        <optgroup label="International Destinations">
                            <option value="Seoul (ICN)">Seoul (ICN)</option>
                            <option value="Singapore (SIN)">Singapore (SIN)</option>
                            <option value="Tokyo (NRT)">Tokyo (NRT)</option>
                            <option value="Dubai (DXB)">Dubai (DXB)</option>
                            <option value="London (LHR)">London (LHR)</option>
                        </optgroup>
                    </select>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6">
                    <label for="departDate" class="form-label fw-semibold">Departure Date</label>
                    <input type="date" class="form-control" id="departDate" name="depart_date" required>
                </div>
                
                <div class="col-lg-2 col-md-4 col-sm-6" id="returnDateContainer">
                    <label for="returnDate" class="form-label fw-semibold">Return Date</label>
                    <input type="date" class="form-control" id="returnDate" name="return_date">
                </div>

                <div class="col-lg-1 col-md-4 col-sm-6">
                    <label for="passengers" class="form-label fw-semibold">Pax</label>
                    <input type="number" class="form-control" id="passengers" name="passengers" value="1" min="1" required>
                </div>

                <div class="col-lg-3 col-md-12">
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" 
                    style="background-color: #2a6dac; border: none;">
                        Search Flights
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script src="utils/searchflight.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const navbar = document.querySelector(".navbar-container");
        function checkScroll() {
            if (window.scrollY > 50) {
                navbar.classList.add("scrolled");
            } else {
                navbar.classList.remove("scrolled");
            }
        }
        checkScroll();
        window.addEventListener("scroll", checkScroll);
    });
</script>
  <script>
        // 1. Define the function directly here to ensure it exists
        function toggleReturnDate() {
            var tripType = document.getElementById("travel_type");
            var returnInput = document.getElementById("returnDate");

            if (!tripType || !returnInput) return; // Safety check

            if (tripType.value === "One Way") {
                // Disable and Grey out
                returnInput.disabled = true;
                returnInput.required = false;
                returnInput.style.backgroundColor = "#e9ecef";
                returnInput.style.cursor = "not-allowed";
                returnInput.value = "";
            } else {
                // Enable and Restore
                returnInput.disabled = false;
                returnInput.required = true;
                returnInput.style.backgroundColor = "white";
                returnInput.style.cursor = "default";
            }
        }

        // 2. Attach the listener manually
        var dropdown = document.getElementById("travel_type");
        if (dropdown) {
            dropdown.addEventListener("change", toggleReturnDate);
            // Run it once on load to set the initial state
            toggleReturnDate();
        }
    </script>
</body>
</html>