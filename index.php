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
            width: 100%; */
            border-radius: 0; */
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
        /* --- FORCE ALL BUTTONS TO BE BOLD --- */
        .btn,                /* Bootstrap Buttons */
        button,              /* Generic Buttons */
        .tab-btn,            /* Flight/Hotel Tabs */
        .btn-home,           /* Slider Buttons */
        .book-now-btn,       /* Card Overlay Buttons */
        .ratings-button,
        .nav-link {    /* Star Rating Buttons */
            font-weight: 700 !important; /* 700 is standard Bold, 800 is Extra Bold */
            letter-spacing: 0.5px;       /* Adds a tiny bit of space so bold text is readable */
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

<div class="home-section">
    <div class="container-fluid gx-0"> 
        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="home-content img-1">
                        <div class ="content-home">
                            <h3 class ="heading-home">Placeholder</h3>
                            <h2 class ="sub-heading-home">JAPAN</h2>
                            
                         </div>
                    </div>
                </div>

                <div class="carousel-item">
                    <div class="home-content img-2">
                        <div class ="content-home">
                            <h2 class ="heading-home">Placeholder</h2>
                            <h2 class ="sub-heading-home">CEBU</h2>
                            
                         </div>
                    </div>
                </div>
                
                <div class="carousel-item">
                    <div class="home-content img-3">
                        <div class ="content-home">
                            <h2 class ="heading-home">Placeholder</h2>
                            <h2 class ="sub-heading-home">SIQUIJOR</h2>
                           
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
                <h2>Top Picks this Month</h2>
                <p>
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


<!--EXPLORE MORE-->
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


<footer class="viggo-footer">
    <div class="footer-content">
        
        <div class="footer-column brand-col">
            <img src="pictures/logo4.png" alt="VigGo Logo" class="footer-logo"> 
            <p class="tagline">Travel with no limits.</p>
            <p class="footer-desc">Your gateway to unforgettable adventures. Discover the Philippines' hidden gems with VigGo.</p>
        </div>

        <div class="footer-column">
            <h4>Explore</h4>
            <ul class="footer-links">
                <li><a href="#">Destinations</a></li>
                <li><a href="#">Activities</a></li>
                <li><a href="#">Hotels</a></li>
                <li><a href="#">Flight Deals</a></li>
            </ul>
        </div>

        <div class="footer-column">
            <h4>Support</h4>
            <ul class="footer-links">
                <li><a href="#">About Viggo</a></li>
                <li><a href="#">Contact Us</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Use</a></li>
            </ul>
        </div>

        <div class="footer-column">
            <h4>Follow Us</h4>
            <div class="social-icons">
                <a href="#" class="social-link">FB</a>
                <a href="#" class="social-link">IG</a>
                <a href="#" class="social-link">TK</a>
            </div>
            <p class="copyright">© 2025 VigGo. All rights reserved.</p>
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
            // Log to console to verify it's working
            console.log("Scrolling: " + window.scrollY);
            
            if (window.scrollY > 50) {
                navbar.classList.add("scrolled");
            } else {
                navbar.classList.remove("scrolled");
            }
        }

        // Run on load and on scroll
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

    //     // --- Configuration Constants ---
    //     const CHAT_API_URL = 'http://localhost:3000/chat'; 
    //     const HOTEL_API_URL = 'http://localhost:5000/api/makcorps-search';
    
    //     // --- Chat Elements ---
    //     const chatWidget = document.getElementById('chat-widget');
    //     const outputDiv = document.getElementById('chat-output');
    //     const inputField = document.getElementById('user-input');

    //     // --- Hotel Search Elements ---
    //     const hotelSearchForm = document.getElementById('hotel-search-form');
    //     const hotelResultsDiv = document.getElementById('hotel-results');
    //     const hotelResultsData = document.getElementById('hotel-results-data');
    //     const locationTitle = document.getElementById('location-title');
    //     const searchedLocationText = document.getElementById('searched-location-text');
    //     const locationTextInput = document.getElementById('location-text'); 
    //     const locationSuggestionsDiv = document.getElementById('location-suggestions'); 

    //     // --- Chat Functions ---

    //     function toggleChat() {
    //         if (!chatWidget) return;
    //         chatWidget.style.display = chatWidget.style.display === 'flex' ? 'none' : 'flex';
    //         if (chatWidget.style.display === 'flex') {
    //             inputField.focus();
    //         }
    //     }

    //     function appendMessage(sender, text) {
    //         if (!outputDiv) return;
    //         const msgDiv = document.createElement('div');
    //         msgDiv.className = `message ${sender}-message`;
            
    //         const content = sender === 'user' 
    //             ? text 
    //             : `<strong>AI:</strong> ${text}`;
                
    //         msgDiv.innerHTML = content;
    //         outputDiv.appendChild(msgDiv);
    //         outputDiv.scrollTop = outputDiv.scrollHeight; 
    //     }

    //     async function sendMessage() {
    //         if (!inputField) return;
    //         const message = inputField.value.trim();
    //         if (!message) return;

    //         appendMessage('user', message);
    //         inputField.value = '';
            
    //         const waitingDiv = document.createElement('div');
    //         waitingDiv.className = 'message ai-message waiting-message';
    //         waitingDiv.innerHTML = '<strong>AI:</strong> Typing...';
    //         outputDiv.appendChild(waitingDiv);
    //         outputDiv.scrollTop = outputDiv.scrollHeight; 

    //         try {
    //             const response = await fetch(CHAT_API_URL, {
    //                 method: 'POST',
    //                 headers: { 'Content-Type': 'application/json' },
    //                 body: JSON.stringify({ message: message }),
    //             });

    //             const data = await response.json();
                
    //             outputDiv.removeChild(waitingDiv);

    //             if (data.response) {
    //                 appendMessage('ai', data.response);
    //             } else if (data.error) {
    //                 appendMessage('ai', `Sorry, an error occurred: ${data.error}`);
    //             }

    //         } catch (error) {
    //             console.error("Frontend Fetch Error:", error);
    //             const lastMsg = outputDiv.lastElementChild;
    //             if (lastMsg && lastMsg.classList.contains('waiting-message')) {
    //                 outputDiv.removeChild(lastMsg);
    //             }
    //             appendMessage('ai', 'Connection Error. Is the Node.js server running on port 3000?');
    //         }
    //     }
    // </script>
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

        // Run on load and on scroll
        checkScroll();
        window.addEventListener("scroll", checkScroll);
    });
</script>
<script>
    // 1. Filtering Logic
    function filterSelection(category, btn) {
        var cards = document.getElementsByClassName("explore-card");
        
        // Remove 'active' class from all buttons
        var btns = document.getElementsByClassName("filter-btn");
        for (var i = 0; i < btns.length; i++) {
            btns[i].classList.remove("active");
        }
        // Add 'active' to the clicked button
        btn.classList.add("active");

        // Filter the cards
        for (var i = 0; i < cards.length; i++) {
            // Get categories from data attribute (e.g. "local beaches")
            var cardCats = cards[i].getAttribute("data-category");
            
            if (category === 'all') {
                cards[i].classList.remove("hidden");
            } else {
                // Check if card category string includes the selected category
                // This allows a card with "local beaches" to show up in both "local" and "beaches"
                if (cardCats.indexOf(category) > -1) {
                    cards[i].classList.remove("hidden");
                } else {
                    cards[i].classList.add("hidden");
                }
            }
        }
    }

    // 2. Horizontal Scrolling Logic
    function scrollCards(amount) {
        const container = document.getElementById('exploreScroller');
        container.scrollBy({
            left: amount,
            behavior: 'smooth'
        });
    }
</script>
<script>
//1. Fetch Activities from PHP API
    document.addEventListener("DOMContentLoaded", function() {
        fetchActivities();
    });

    async function fetchActivities() {
        const container = document.getElementById('activityScroller');
        
        try {
            // Call the PHP file we created
            const response = await fetch('get_activities.php');
            const data = await response.json();

            // Clear loading spinner
            container.innerHTML = '';

            if (data.data && data.data.length > 0) {
                data.data.forEach(activity => {
                    // Get image or use placeholder
                    const imageSrc = (activity.pictures && activity.pictures.length > 0) 
                        ? activity.pictures[0] 
                        : 'pictures/logo2.png'; 

                    // Get price or default
                    const price = (activity.price && activity.price.amount) 
                        ? parseFloat(activity.price.amount).toFixed(2) + ' ' + activity.price.currencyCode 
                        : 'Check Price';

                    // Get Booking Link
                    const link = activity.bookingLink || '#';

                    // Create HTML for the card
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

    // 2. Horizontal Scrolling Logic
    function scrollActivities(amount) {
        const container = document.getElementById('activityScroller');
        container.scrollBy({
            left: amount,
            behavior: 'smooth'
        });
    }
</script>
<script>
    // 2. Horizontal Scrolling Logic for Activities
    function scrollActivities(amount) {
        const container = document.getElementById('activityScroller');
        container.scrollBy({
            left: amount,
            behavior: 'smooth'
        });
    }
</script>
<script>
    // --- SEARCH ENGINE LOGIC ---
    const searchInput = document.getElementById('navbarSearch');
    const searchModal = document.getElementById('searchResults');
    const searchForm  = document.getElementById('searchForm');
    let searchTimeout;

    // Function to perform the search
    function performSearch(term) {
        // Validation: Don't search if empty or too short
        if (!term || term.length < 3) {
            searchModal.style.display = 'none';
            return;
        }

        // Show Loading UI
        searchModal.style.display = 'block';
        searchModal.innerHTML = '<div class="p-3 text-center text-muted"><i class="fa-solid fa-circle-notch fa-spin"></i> Searching...</div>';

        // EXECUTE FETCH
        // FIX: We use 'term' here (passed from the function), not 'query'
        fetch('search_engine.php?term=' + encodeURIComponent(term))
            .then(response => response.json())
            .then(data => {
                searchModal.innerHTML = ''; // Clear loading spinner

                // Handle Backend Errors (e.g., API limits)
                if (data.error) {
                    console.error("Backend Error:", data.error);
                    searchModal.innerHTML = `<div class="p-3 text-center text-danger">Error: ${data.error}</div>`;
                    return;
                }

                // Display Results
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
                    // No Results
                    searchModal.innerHTML = '<div class="p-3 text-center text-muted">No results found for "' + term + '".</div>';
                }
            })
            .catch(err => {
                console.error("JS Error:", err);
                searchModal.innerHTML = '<div class="p-3 text-center text-danger">Connection Failed. Check Console.</div>';
            });
    }

    // --- EVENT LISTENERS ---
    if (searchInput) {
        // 1. LIVE SEARCH (Type & Wait)
        searchInput.addEventListener('input', function() {
            const term = this.value.trim();
            
            // Clear the previous timer so we don't search on every single keystroke
            clearTimeout(searchTimeout);
            
            // Set a new timer to search after 500ms of silence
            searchTimeout = setTimeout(() => {
                performSearch(term);
            }, 500); 
        });

        // 2. BUTTON CLICK / ENTER KEY
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Stop page reload
                const term = searchInput.value.trim();
                performSearch(term);
            });
        }

        // 3. HIDE MODAL (Click Outside)
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchModal.contains(e.target)) {
                searchModal.style.display = 'none';
            }
        });
    }
</script>

<script>
    /* --- SPECIFIC SCRIPT FOR ATTRACTIONS SECTION --- */
function searchAttractions() {
    const input = document.getElementById('attractionInput');
    const container = document.getElementById('attractionResults');
    const term = input.value.trim();

    if (term.length < 3) {
        alert("Please enter at least 3 characters");
        return;
    }

    // 1. Show Loading State
    container.innerHTML = `
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-warning" role="status"></div>
            <p class="mt-2 text-muted">Searching for activities in ${term}...</p>
        </div>
    `;

    // 2. Fetch Data
    fetch('get_attractions.php?term=' + encodeURIComponent(term))
        .then(response => response.json())
        .then(data => {
            container.innerHTML = ''; // Clear loading

            if (data.error) {
                container.innerHTML = `<div class="col-12 text-center text-danger">Error: ${data.error}</div>`;
                return;
            }

            if (data.length > 0) {
                // 3. Generate Cards (Matches "Top Picks" Design exactly)
                data.forEach(item => {
                    // Random Rating (4.0 - 5.0)
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
                // Empty State
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

// Enable Enter Key
document.getElementById('attractionInput').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        searchAttractions();
    }
});
</script>
</body>
</html>