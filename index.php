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
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="styles2.css"> 

    <title>VigGo Travel Booking</title>

   <style>
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
        .logo-img {
            height: 70px;
            width: auto; 
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .navbar-container.scrolled .logo-img {
            height: 70px; 
        }
       
        .btn, button,.tab-btn,.btn-home,.book-now-btn,.ratings-button,.nav-link {  
            font-weight: 700 !important; 
            letter-spacing: 0.5px;       
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
            <div class="search-wrapper position-relative"> <form class="d-flex" role="search" id="searchForm">
                    <input class="form-control me-2" type="search" id="navbarSearch" placeholder="Where to? (e.g. Paris)" aria-label="Search" autocomplete="off"/>
                    <button class="btn btn-outline-light" type="submit">Search</button>
                </form>
                
                <div id="searchResults" class="search-results-modal"></div>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="searchflight1.php">Book</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#aboutContactModal">About Us</a>
                    </li>
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

<div class="home-section">
    <div class="container-fluid gx-0"> 
        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="home-content img-1">
                        <div class ="content-home">
                            <h2 class ="heading-home">JAPAN</h2>
                            <h2 class ="sub-heading-home">Sa VigGo, Go, Go, Go na 'to!</h2>
                         </div>
                    </div>
                </div>

                <div class="carousel-item">
                    <div class="home-content img-2">
                        <div class ="content-home">
                            <h2 class ="heading-home">CEBU</h2>
                            <h2 class ="sub-heading-home">Sa VigGo, Go, Go, Go na 'to!</h2>
                         </div>
                    </div>
                </div>
                
                <div class="carousel-item">
                    <div class="home-content img-3">
                        <div class ="content-home">
                            <h2 class ="heading-home">SIQUIJOR</h2>
                            <h2 class ="sub-heading-home">Sa VigGo, Go, Go, Go na 'to!</h2>
                        </div>
                    </div>
                </div>
            </div>
            
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</div>

<div class="booking-section">
    <div class="booking-tabs mb-4 d-flex justify-content-center gap-2">
        <button class="tab-btn active btn btn-light fw-bold px-4" onclick="switchTab('flight')" id="btn-flight">
            ✈️ Flights
        </button>
        <button class="tab-btn btn btn-light fw-bold px-4" onclick="switchTab('hotel')" id="btn-hotel">
            🏨 Hotels
        </button>
    </div>

    <div id="flight-container" class="selection-container p-4 bg-white rounded shadow-sm">
        <form id="flightSearchForm" method="POST" action="flights.php">
            
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
                    style="background-color: rgb(7,80,86); border: none;">
                        Search Flights
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div id="hotel-container" class="selection-container p-4 bg-white rounded shadow-sm" style="display: none;">
        
        <div class="d-flex align-items-center mb-3">
            <span class="text-warning me-2 fs-4">🏨</span>
            <span class="fw-bold fs-5">Find a Hotel</span>
        </div>

        <div class="row g-3 align-items-end">
            <div class="col-lg-9 col-md-8">
                <label for="hotel-location-input" class="form-label fw-semibold">Destination / Hotel Name</label>
                <input type="text" class="form-control" id="hotel-location-input" placeholder="e.g. Philippines, Boracay, Manila...">
            </div>

            <div class="col-lg-3 col-md-4">
                <button type="button" onclick="searchHotels()" class="btn btn-primary w-100 py-2 fw-bold" 
                style="background-color: #2a6dac; border: none;">
                    Search Hotels
                </button>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div id="hotel-results" class="results-grid row g-3">
    </div>
</div>

<div class="top-picks-section">
    <div class="container">
        
        <div class="row mb-3">
            
           <div class="col-lg-6">
                <div class="card card-hover-effect h-100">
                    
                    <div class="position-relative">
                        <img src="pictures/Boracay/IMG_4335-1.jpg" class="card-img-top card-one" alt="Boracay Beach">
                        
                        <div class="booking-overlay">
                            <a href="searchflight1.php" class="btn btn-lg btn-warning book-now-btn">
                                Book Now
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body p-1">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <div class="d-flex align-items-center">
                                <i class="fa-sharp fa-solid fa-location-dot me-1 text-danger small-icon"></i>
                                <h6 class="card-title mb-0 small-title">Boracay Island, Aklan, PH</h6>
                            </div>
                            <a class="btn btn-sm ratings-button" data-bs-toggle="modal" data-bs-target="#ratingsModal">
                                <i class="fa-solid fa-star me-1"></i> 4.6
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="hot-title">HOT THIS MONTH!!! 🔥</h2>
                
                <p class="hot-description">
                Discover our handpicked destinations and tour packages specially selected for this month. 
                From relaxing beaches to thrilling adventures and cultural escapes, 
                these top picks highlight the most popular and highly recommended experiences right now. 
                Check back often as we update this list with fresh deals and exciting destinations you won't want to miss!
                </p>
            </div>
            
        </div>
        
    <hr>

        <div id="cardCarousel" class="carousel slide" data-bs-interval="false">
            <div class="carousel-inner">
                
                <div class="carousel-item active">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        
                        <div class="col">
                            <div class="card h-100 card-hover-effect border-0">
                                <div class="position-relative">
                                    <img src="pictures/banaue/20180608_155606.jpg" class="card-img-top" alt="Banaue Rice Terraces">
                                    <div class="booking-overlay">
                                        <a href="searchflight1.php" class="btn btn-lg btn-warning book-now-btn">Book Now</a>
                                    </div>
                                </div>
                                <div class="card-body p-1">
                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <div class="d-flex align-items-center">
                                            <i class="fa-sharp fa-solid fa-location-dot me-1 text-danger small-icon"></i>
                                            <h6 class="card-title mb-0 small-title">Ifugao, PH</h6>
                                        </div>
                                        <a class="btn btn-sm ratings-button" data-bs-toggle="modal" data-bs-target="#ratingsModal">
                                            <i class="fa-solid fa-star me-1"></i> 4.5
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col">
                            <div class="card h-100 card-hover-effect border-0">
                                <div class="position-relative">
                                    <img src="pictures/cebu/IMG_4187.JPG" class="card-img-top" alt="Cebu Island">
                                    <div class="booking-overlay">
                                        <a href="searchflight1.php" class="btn btn-lg btn-warning book-now-btn">Book Now</a>
                                    </div>
                                </div>
                                <div class="card-body p-1">
                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <div class="d-flex align-items-center">
                                            <i class="fa-sharp fa-solid fa-location-dot me-1 text-danger small-icon"></i>
                                            <h6 class="card-title mb-0 small-title">Cebu, PH</h6>
                                        </div>
                                        <a class="btn btn-sm ratings-button" data-bs-toggle="modal" data-bs-target="#ratingsModal">
                                            <i class="fa-solid fa-star me-1"></i> 4.3
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col">
                            <div class="card h-100 card-hover-effect border-0">
                                <div class="position-relative">
                                    <img src="pictures/bacolod/IMG_2536.JPG" class="card-img-top" alt="Bacolod City">
                                    <div class="booking-overlay">
                                        <a href="searchflight1.php" class="btn btn-lg btn-warning book-now-btn">Book Now</a>
                                    </div>
                                </div>
                                <div class="card-body p-1">
                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <div class="d-flex align-items-center">
                                            <i class="fa-sharp fa-solid fa-location-dot me-1 text-danger small-icon"></i>
                                            <h6 class="card-title mb-0 small-title">Bacolod, PH</h6>
                                        </div>
                                        <a class="btn btn-sm ratings-button" data-bs-toggle="modal" data-bs-target="#ratingsModal">
                                            <i class="fa-solid fa-star me-1"></i> 4.1
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <div class="carousel-item">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        
                        <div class="col">
                            <div class="card h-100 card-hover-effect border-0">
                                <div class="position-relative">
                                    <img src="pictures/london/7b199af2-1fa5-43eb-949e-d8face96497e.jpg" class="card-img-top" alt="Palawan">
                                    <div class="booking-overlay">
                                        <a href="searchflight1.php" class="btn btn-lg btn-warning book-now-btn">Book Now</a>
                                    </div>
                                </div>
                                <div class="card-body p-1">
                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <div class="d-flex align-items-center">
                                            <i class="fa-sharp fa-solid fa-location-dot me-1 text-danger small-icon"></i>
                                            <h6 class="card-title mb-0 small-title">London, UK</h6>
                                        </div>
                                        <a class="btn btn-sm ratings-button" data-bs-toggle="modal" data-bs-target="#ratingsModal">
                                            <i class="fa-solid fa-star me-1"></i> 4.8
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col">
                            <div class="card h-100 card-hover-effect border-0">
                                <div class="position-relative">
                                    <img src="pictures/dubai/att.U9RjRRtjsSuf51-rQSF-d76MLDeUNHR_G7_OKDvO5b4.jpg" class="card-img-top" alt="Baguio">
                                    <div class="booking-overlay">
                                        <a href="searchflight1.php" class="btn btn-lg btn-warning book-now-btn">Book Now</a>
                                    </div>
                                </div>
                                <div class="card-body p-1">
                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <div class="d-flex align-items-center">
                                            <i class="fa-sharp fa-solid fa-location-dot me-1 text-danger small-icon"></i>
                                            <h6 class="card-title mb-0 small-title">Dubai, UAE</h6>
                                        </div>
                                        <a class="btn btn-sm ratings-button" data-bs-toggle="modal" data-bs-target="#ratingsModal">
                                            <i class="fa-solid fa-star me-1"></i> 4.2
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col">
                            <div class="card h-100 card-hover-effect border-0">
                                <div class="position-relative">
                                    <img src="pictures/singapore/558982038_25285750857677027_7060521419416537381_n_2.jpg" class="card-img-top" alt="Vigan">
                                    <div class="booking-overlay">
                                        <a href="searchflight1.php" class="btn btn-lg btn-warning book-now-btn">Book Now</a>
                                    </div>
                                </div>
                                <div class="card-body p-1">
                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <div class="d-flex align-items-center">
                                            <i class="fa-sharp fa-solid fa-location-dot me-1 text-danger small-icon"></i>
                                            <h6 class="card-title mb-0 small-title">Merlion, SG</h6>
                                        </div>
                                        <a class="btn btn-sm ratings-button" data-bs-toggle="modal" data-bs-target="#ratingsModal">
                                            <i class="fa-solid fa-star me-1"></i> 4.4
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>

            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#cardCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#cardCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

    </div>
</div>

<section class="explore-section">
    <div class="container">
        <div class="explore-header">
            <h2>Explore More!</h2>
            <p>Discover hidden gems and popular spots tailored to your travel style.</p>
        </div>

        <div class="filter-pills">
            <button class="filter-btn active" onclick="filterSelection('all', this)">Popular Destination</button>
            <button class="filter-btn" onclick="filterSelection('local', this)">Local</button>
            <button class="filter-btn" onclick="filterSelection('international', this)">International</button>
            <button class="filter-btn" onclick="filterSelection('beaches', this)">Beaches</button>
            <button class="filter-btn" onclick="filterSelection('mountains', this)">Mountains</button>
        </div>

        <div class="scroller-wrapper">
            <button class="scroll-arrow arrow-left" onclick="scrollCards(-300)">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            
            <div class="cards-scroller" id="exploreScroller">
                
                <div class="explore-card card-hover-effect" data-category="local beaches">
                    <div class="position-relative">
                        <img src="pictures/baler/20180726_095235.jpg" class="card-img-top" alt="Baler">
                        <div class="booking-overlay">
                            <a href="searchflight1.php" class="btn btn-warning book-now-btn">Book Now</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fa-solid fa-location-dot text-danger"></i>
                                <span class="fw-bold">Baler, Aurora, PH</span>
                            </div>
                            <span class="ratings-button btn btn-sm"><i class="fa-solid fa-star"></i> 4.5</span>
                        </div>
                    </div>
                </div>

                <div class="explore-card card-hover-effect" data-category="local mountains">
                    <div class="position-relative">
                        <img src="pictures/banaue/20180608_155606.jpg" class="card-img-top" alt="Banaue">
                        <div class="booking-overlay">
                            <a href="searchflight1.php" class="btn btn-warning book-now-btn">Book Now</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fa-solid fa-location-dot text-danger"></i>
                                <span class="fw-bold">Banaue Rice Terraces</span>
                            </div>
                            <span class="ratings-button btn btn-sm"><i class="fa-solid fa-star"></i> 4.5</span>
                        </div>
                    </div>
                </div>

                <div class="explore-card card-hover-effect" data-category="international">
                    <div class="position-relative">
                        <img src="pictures/japan/IMG_0075.jpg" class="card-img-top" alt="Tokyo">
                        <div class="booking-overlay">
                            <a href="searchflight1.php" class="btn btn-warning book-now-btn">Book Now</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fa-solid fa-location-dot text-danger"></i>
                                <span class="fw-bold">Tokyo, Japan</span>
                            </div>
                            <span class="ratings-button btn btn-sm"><i class="fa-solid fa-star"></i> 4.8</span>
                        </div>
                    </div>
                </div>

                <div class="explore-card card-hover-effect" data-category="local beaches">
                    <div class="position-relative">
                        <img src="pictures/cebu/IMG_4187.JPG" class="card-img-top" alt="Cebu">
                        <div class="booking-overlay">
                            <a href="searchflight1.php" class="btn btn-warning book-now-btn">Book Now</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fa-solid fa-location-dot text-danger"></i>
                                <span class="fw-bold">Cebu, PH</span>
                            </div>
                            <span class="ratings-button btn btn-sm"><i class="fa-solid fa-star"></i> 4.5</span>
                        </div>
                    </div>
                </div>

                <div class="explore-card card-hover-effect" data-category="local">
                    <div class="position-relative">
                        <img src="pictures/bacolod/IMG_2536.JPG" class="card-img-top" alt="Bacolod">
                        <div class="booking-overlay">
                            <a href="searchflight1.php" class="btn btn-warning book-now-btn">Book Now</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fa-solid fa-location-dot text-danger"></i>
                                <span class="fw-bold">Bacolod, PH</span>
                            </div>
                            <span class="ratings-button btn btn-sm"><i class="fa-solid fa-star"></i> 4.5</span>
                        </div>
                    </div>
                </div>

                 <div class="explore-card card-hover-effect" data-category="international">
                    <div class="position-relative">
                        <img src="pictures/london/7b199af2-1fa5-43eb-949e-d8face96497e.jpg" class="card-img-top" alt="London">
                        <div class="booking-overlay">
                            <a href="searchflight1.php" class="btn btn-warning book-now-btn">Book Now</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fa-solid fa-location-dot text-danger"></i>
                                <span class="fw-bold">London, UK</span>
                            </div>
                            <span class="ratings-button btn btn-sm"><i class="fa-solid fa-star"></i> 4.7</span>
                        </div>
                    </div>
                </div>

            </div> <button class="scroll-arrow arrow-right" onclick="scrollCards(300)">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </div>
</section>

<section class="activities-section">
    <div class="container">
        <div class="activities-header mb-4">
            <h2>Blank itinerary? Don't worry, we got you!</h2>
            <p>Top rated activities tailored for you.</p>
        </div>

        <div class="scroller-wrapper">
            <button class="scroll-arrow arrow-left" onclick="scrollActivities(-300)">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            
            <div class="cards-scroller" id="activityScroller">
                <div class="d-flex justify-content-center align-items-center w-100 py-5">
                    <div class="spinner-border text-warning" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div> <button class="scroll-arrow arrow-right" onclick="scrollActivities(300)">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </div>
</section>

<section class="why-viggo-section">
    <div class="container">
        <div class="row align-items-center g-4">
            
            <div class="col-lg-6 col-md-12 text-center text-lg-end mb-4 mb-lg-0">
                <img src="pictures/clipart.png" alt="Travelers Illustration" class="img-fluid main-illustration">
            </div>

            <div class="col-lg-6 col-md-12 text-center text-lg-start">
                <div class="why-text-content">
                    <h2 class="why-title">Why VigGo Travels?</h2>
                    <p class="why-description">
                        "We don't just list tours; we curate experiences. Every destination and guide is rigorously vetted by our team 
                        to ensure you get authentic, high-quality, and safe adventures."
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<div class="modal fade" id="aboutContactModal" tabindex="-1" aria-labelledby="aboutContactLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content about-modal-content">
            
            <div class="modal-header about-modal-header">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                
                <div class="text-center w-100">
                    <img src="pictures/logo4.png" alt="VigGo Logo" class="about-logo-img">
                    <h4 class="modal-title fw-bold mt-2">VigGo Travels</h4>
                    <p class="small mb-0 opacity-75">Travel with no limits.</p>
                </div>
            </div>

            <div class="modal-body p-4 text-center">
                
                <div class="mb-4">
                    <h6 class="text-uppercase text-muted small fw-bold mb-2">Created By</h6>
                    <h3 class="creator-name">Samantha Imboy</h3>
                </div>

                <div class="divider-line"></div>

                <div class="contact-section mt-4">
                    <h6 class="text-uppercase text-muted small fw-bold mb-3">Get in Touch</h6>

                    <div class="contact-item">
                        <div class="icon-box icon-orange">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div class="text-start">
                            <small class="d-block text-muted">Call Us</small>
                            <span class="contact-text">0912-345-6789</span>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="icon-box icon-blue">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div class="text-start">
                            <small class="d-block text-muted">Email Us</small>
                            <a href="mailto:isx2059@dlsud.edu.ph" class="contact-link">isx2059@dlsud.edu.ph</a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer justify-content-center border-0 pt-0 pb-4">
                <small class="text-muted">© 2025 VigGo Travels Project</small>
            </div>

        </div>
    </div>
</div>

<footer class="viggo-footer-redesigned">
    <div class="container-fluid px-4">
        <div class="footer-content-wrapper">
            
            <div class="copyright-section">
                <i class="fa-regular fa-copyright me-2"></i> 
                <span>2025 VigGo Travels. All rights reserved.</span>
            </div>

            <div class="brand-social-section">
                
                <div class="footer-brand-group me-4">
                    <img src="pictures/logo4.png" alt="VigGo Logo" class="footer-logo-small">
                </div>

                <div class="social-icons-group">
                    <a href="https://facebook.com" target="_blank" class="social-link">
                        <i class="fa-brands fa-facebook"></i>
                    </a>
                    <a href="https://instagram.com" target="_blank" class="social-link">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                </div>

            </div>

        </div>
    </div>
</footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
    <script src="script.js"></script>

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
        function toggleReturnDate() {
            var tripType = document.getElementById("travel_type");
            var returnInput = document.getElementById("returnDate");

            if (!tripType || !returnInput) return; 

            if (tripType.value === "One Way") {
                returnInput.disabled = true;
                returnInput.required = false;
                returnInput.style.backgroundColor = "#e9ecef";
                returnInput.style.cursor = "not-allowed";
                returnInput.value = "";
            } else {
                returnInput.disabled = false;
                returnInput.required = true;
                returnInput.style.backgroundColor = "white";
                returnInput.style.cursor = "default";
            }
        }
        var dropdown = document.getElementById("travel_type");
        if (dropdown) {
            dropdown.addEventListener("change", toggleReturnDate);
            toggleReturnDate();
        }
    </script>

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
    function filterSelection(category, btn) {
        var cards = document.getElementsByClassName("explore-card");
        
        var btns = document.getElementsByClassName("filter-btn");
        for (var i = 0; i < btns.length; i++) {
            btns[i].classList.remove("active");
        }
        btn.classList.add("active");

        for (var i = 0; i < cards.length; i++) {
            var cardCats = cards[i].getAttribute("data-category");
            
            if (category === 'all') {
                cards[i].classList.remove("hidden");
            } else {
                if (cardCats.indexOf(category) > -1) {
                    cards[i].classList.remove("hidden");
                } else {
                    cards[i].classList.add("hidden");
                }
            }
        }
    }

    function scrollCards(amount) {
        const container = document.getElementById('exploreScroller');
        container.scrollBy({
            left: amount,
            behavior: 'smooth'
        });
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetchActivities();
    });

    async function fetchActivities() {
        const container = document.getElementById('activityScroller');
        
        try {
            const response = await fetch('get_activities.php');
            const data = await response.json();

            container.innerHTML = '';

            if (data.data && data.data.length > 0) {
                data.data.forEach(activity => {
                    const imageSrc = (activity.pictures && activity.pictures.length > 0) 
                        ? activity.pictures[0] 
                        : 'pictures/logo2.png'; 

                    const price = (activity.price && activity.price.amount) 
                        ? parseFloat(activity.price.amount).toFixed(2) + ' ' + activity.price.currencyCode 
                        : 'Check Price';

                    const link = activity.bookingLink || '#';

                    const cardHTML = `
                        <div class="activity-card">
                            <img src="${imageSrc}" class="activity-img-top" alt="${activity.name}">
                            <div class="activity-body">
                                <h5 class="activity-title" title="${activity.name}">${activity.name}</h5>
                                <p class="activity-price">Price: ${price}</p>
                                
                                <a href="${link}" target="_blank" class="activity-book-btn d-block text-center text-decoration-none">
                                    View More
                                </a>
                            </div>
                        </div>
                    `;
                    container.innerHTML += cardHTML;
                });
            } else {
                container.innerHTML = '<p class="text-center w-100">No activities found nearby.</p>';
            }

        } catch (error) {
            console.error('Error fetching activities:', error);
            container.innerHTML = '<p class="text-center w-100 text-danger">Failed to load activities.</p>';
        }
    }

    function scrollActivities(amount) {
        const container = document.getElementById('activityScroller');
        container.scrollBy({
            left: amount,
            behavior: 'smooth'
        });
    }
</script>
<script>
    const searchInput = document.getElementById('navbarSearch');
    const searchModal = document.getElementById('searchResults');
    const searchForm  = document.getElementById('searchForm');
    let searchTimeout;

    function performSearch(term) {
        if (!term || term.length < 3) {
            searchModal.style.display = 'none';
            return;
        }

        searchModal.style.display = 'block';
        searchModal.innerHTML = '<div class="p-3 text-center text-muted"><i class="fa-solid fa-circle-notch fa-spin"></i> Searching...</div>';

        fetch('search_engine.php?term=' + encodeURIComponent(term))
            .then(response => response.json())
            .then(data => {
                searchModal.innerHTML = ''; 

                if (data.error) {
                    console.error("Backend Error:", data.error);
                    searchModal.innerHTML = `<div class="p-3 text-center text-danger">Error: ${data.error}</div>`;
                    return;
                }

                if (data.length > 0) {
                    let html = '';
                    data.forEach(item => {
                        html += `
                            <a href="${item.link}" target="_blank" class="search-item" style="text-decoration:none; display:flex; align-items:center; padding:10px; border-bottom:1px solid #eee; color:inherit;">
                                <img src="${item.image}" class="search-thumb" alt="${item.name}" style="width:50px; height:50px; object-fit:cover; margin-right:10px; border-radius:4px;">
                                <div class="search-info">
                                    <h6 style="margin:0; font-size:14px; font-weight:bold;">${item.name}</h6>
                                    <small class="text-muted"><i class="fa-solid fa-location-dot"></i> ${item.location}</small>
                                </div>
                            </a>
                        `;
                    });
                    searchModal.innerHTML = html;
                } else {
                    searchModal.innerHTML = '<div class="p-3 text-center text-muted">No results found for "' + term + '".</div>';
                }
            })
            .catch(err => {
                console.error("JS Error:", err);
                searchModal.innerHTML = '<div class="p-3 text-center text-danger">Connection Failed. Check Console.</div>';
            });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const term = this.value.trim();
            
            clearTimeout(searchTimeout);
            
            searchTimeout = setTimeout(() => {
                performSearch(term);
            }, 500); 
        });

        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault(); 
                const term = searchInput.value.trim();
                performSearch(term);
            });
        }

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchModal.contains(e.target)) {
                searchModal.style.display = 'none';
            }
        });
    }
</script>

<script>
function searchAttractions() {
    const input = document.getElementById('attractionInput');
    const container = document.getElementById('attractionResults');
    const term = input.value.trim();

    if (term.length < 3) {
        alert("Please enter at least 3 characters");
        return;
    }

    container.innerHTML = `
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-warning" role="status"></div>
            <p class="mt-2 text-muted">Searching for activities in ${term}...</p>
        </div>
    `;

    fetch('get_attractions.php?term=' + encodeURIComponent(term))
        .then(response => response.json())
        .then(data => {
            container.innerHTML = ''; 

            if (data.error) {
                container.innerHTML = `<div class="col-12 text-center text-danger">Error: ${data.error}</div>`;
                return;
            }

            if (data.length > 0) {
                data.forEach(item => {
                    const rating = (Math.random() * (5.0 - 4.0) + 4.0).toFixed(1);

                    const cardHTML = `
                        <div class="col">
                            <div class="card h-100 card-hover-effect border-0">
                                
                                <div class="position-relative">
                                    <img src="${item.image}" class="card-img-top" alt="${item.name}" style="height: 250px; object-fit: cover;">
                                    
                                    <div class="booking-overlay">
                                        <a href="${item.link}" target="_blank" class="btn btn-lg btn-warning book-now-btn">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="card-body p-1 pt-2">
                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        
                                        <div class="d-flex align-items-center" style="max-width: 75%;">
                                            <i class="fa-sharp fa-solid fa-location-dot me-1 text-danger small-icon"></i>
                                            <h6 class="card-title mb-0 small-title text-truncate" title="${item.name}">
                                                ${item.name}
                                            </h6>
                                        </div>
                                        
                                        <div class="btn btn-sm ratings-button">
                                            <i class="fa-solid fa-star me-1"></i> ${rating}
                                        </div>
                                        
                                    </div>
                                    <div class="text-muted small ps-3 pb-2 text-truncate">${item.location}</div>
                                </div>
                            </div>
                        </div>
                    `;
                    container.innerHTML += cardHTML;
                });
            } else {
                container.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="fa-regular fa-face-frown fa-3x mb-3 text-muted"></i>
                        <h5>No activities found for "${term}"</h5>
                        <p class="text-muted">Try searching for "Manila", "Paris", or "London".</p>
                    </div>
                `;
            }
        })
        .catch(err => {
            console.error(err);
            container.innerHTML = '<div class="col-12 text-center text-danger">Connection Failed. Check Console.</div>';
        });
}

document.getElementById('attractionInput').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        searchAttractions();
    }
});
</script>
</body>
</html>