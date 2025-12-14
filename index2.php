<?php
session_start();

// Determine if the user is logged in
$is_logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

// If logged in, get the username for display
if ($is_logged_in) {
    $user_display_name = htmlspecialchars($_SESSION['username']); 
    $user_full_name = htmlspecialchars($_SESSION['completename']);
}

// Optional: Add protection to redirect users who access index2.php without being logged in
if (!$is_logged_in) {
    header('Location: login.html');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles2.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Fuzzy+Bubbles:wght@400;700&family=Mynerve&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src='https://kit.fontawesome.com/4c729db828.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <title>VigGo Travel Booking</title>
</head>

<body>
<header class="navbar-container">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom-transparent">
        <div class="container-fluid">
            <i class="fa-regular fa-map"></i>
            <a class="navbar-brand" href="index.html">
                <h3 class="heading-logo">VigGo<span>Travels</span></h3>
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
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index2.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Book</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Explore</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            More
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">About Us</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                        <?php if ($is_logged_in): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-user-circle me-1"></i> 
                                    <?php echo $user_display_name; ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><h6 class="dropdown-header text-truncate">Signed in as <?php echo $user_display_name; ?></h6></li>
                                    <li><hr class="dropdown-divider"></li>

                                    <li><a class="dropdown-item" href="#">
                                        <i class="fa-solid fa-user me-2"></i> My Profile
                                    </a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <i class="fa-solid fa-plane-departure me-2"></i> Booking History
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger fw-bold" href="logout.php">
                                        <i class="fa-solid fa-sign-out-alt me-2"></i> Log Out
                                    </a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-signup">
                                <a class="nav-link" href="signupp.php">Log In / Sign Up</a>
                            </li>
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
                            <a href="#" class = "btn-home">Book Now</a> </div>
                    </div>
                </div>

                <div class="carousel-item">
                    <div class="home-content img-2">
                        <div class ="content-home">
                            <h2 class ="heading-home">Placeholder</h2>
                            <h2 class ="sub-heading-home">CEBU</h2>
                            <a href="#" class = "btn-home">Book Now</a> </div>
                    </div>
                </div>
                
                <div class="carousel-item">
                    <div class="home-content img-3">
                        <div class ="content-home">
                            <h2 class ="heading-home">Placeholder</h2>
                            <h2 class ="sub-heading-home">SIQUIJOR</h2>
                            <a href="#" class = "btn-home">Book Now</a> </div>
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
    <div class="selection-container"> 
        <form id="flightSearchForm" method="POST" action="searchflight.php">
            <div class="row g-3 align-items-end"> 
                
                <div class="d-flex align-items-center mb-3">
                            <span class="text-warning me-2" style="font-size: 1.2rem;">&#9992;</span>
                            <span class="fw-bold me-4">Flight</span>
                            
                            <div class="dropdown">
                                <button class="btn btn-sm dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #007bff; border-color: #007bff;">
                                    Round-trip
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">One Way</a></li>
                                </ul>
                            </div>
                        </div>


                <div class="col-lg-2 col-md-4 col-sm-6">
                    <label for="origin" class="form-label">Departure</label>
                    <select class="form-select" id="origin" name="origin" required>
                        <option value="" selected disabled>Select Origin</option>
                        <optgroup label="Local Philippine Hubs">
                            <option value="Manila (MNL)">Manila(MNL)</option>
                            <option value="Cebu (CEB)">Cebu(CEB)</option>
                        </optgroup>
                        <optgroup label="Domestic Destinations">
                            <option value="Boracay (MPH)">Boracay(MPH)</option>
                            <option value="Palawan (PPS)">Palawan(PPS)</option>
                            <option value="Siargao (IAO)">Siargao(IAO)</option>
                            <option value="Iloilo (ILO)">Iloilo(ILO)</option>
                        </optgroup>
                        <optgroup label="International Destinations">
                            <option value="Seoul (ICN)">Seoul(ICN)</option>
                            <option value="Singapore (SIN)">Singapore (SIN)</option>
                            <option value="Tokyo (NRT)">Tokyo (NRT)</option>
                            <option value="Dubai (DXB)">Dubai (DXB)</option>
                            <option value="London (LHR)">London (LHR)</option>
                        </optgroup>
                    </select>
                </div>
                
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <label for="destination" class="form-label">Arrival</label>
                    <select class="form-select" id="destination" name="destination" required>
                        <option value="" selected disabled>Select Destination</option>
                        <optgroup label="Local Philippine Hubs">
                            <option value="Manila (MNL)">Manila(MNL)</option>
                            <option value="Cebu (CEB)">Cebu(CEB)</option>
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
                    <label for="departDate" class="form-label">Departure Date</label>
                    <input type="date" class="form-control" id="departDate" name="depart_date" required>
                </div>
                
                <div class="col-lg-2 col-md-4 col-sm-6" id="returnDateContainer">
                    <label for="returnDate" class="form-label">Return Date</label>
                    <input type="date" class="form-control" id="returnDate" name="return_date">
                </div>

                <div class="col-lg-1 col-md-4 col-sm-6">
                    <label for="passengers" class="form-label">Pax</label>
                    <input type="number" class="form-control" id="passengers" name="passengers" value="1" min="1" required>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6">
                        <button type="submit" class="btn btn-primary h-100 w-100 fs-5 rounded-start-3 rounded-end-3" 
                        style="background-color: #2a6dac; border-color: #2a6dac;">
                            Search Flights
                        </button>
                    </div>
                    
                </div>
            </div>
        </form>
    </div>
</div>


<!--TOP PICKS SECTION-->
<div class="top-picks-section">
    <div class="container">
        
        <div class="row mb-3">
            
           <div class="col-lg-6">
                <div class="card card-hover-effect h-100">
                    
                    <div class="position-relative">
                        <img src="pictures/Boracay/IMG_4335-1.jpg" class="card-img-top card-one" alt="Boracay Beach">
                        
                        <div class="booking-overlay">
                            <a href="#booking-page" class="btn btn-lg btn-warning book-now-btn">
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
                                        <a href="#booking-page" class="btn btn-lg btn-warning book-now-btn">Book Now</a>
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
                                        <a href="#booking-page" class="btn btn-lg btn-warning book-now-btn">Book Now</a>
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
                                        <a href="#booking-page" class="btn btn-lg btn-warning book-now-btn">Book Now</a>
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
                                        <a href="#booking-page" class="btn btn-lg btn-warning book-now-btn">Book Now</a>
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
                                        <a href="#booking-page" class="btn btn-lg btn-warning book-now-btn">Book Now</a>
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
                                        <a href="#booking-page" class="btn btn-lg btn-warning book-now-btn">Book Now</a>
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

<section id="tourist-attractions" class="attraction-section">
    <h2>Find Tourist Attractions</h2>
    <div class="search-box">
        <input type="text" id="cityInput" placeholder="Enter city (e.g., Manila, Cebu)...">
        <button onclick="searchAttractions()">Search</button>
    </div>

    <div id="attractionResults" class="results-grid"></div>
</section>

<!-- HOTEL SECTION
<div class="card p-4 shadow-sm">
    <form id="hotel-search-form">
        <div class="row g-3 align-items-end">
            
            <div class="col-lg-3 col-md-6 position-relative">
                <label for="location-text" class="form-label fw-bold">Destination</label>
                <input type="text" id="location-text" name="location_text" class="form-control" placeholder="Type city or landmark" autocomplete="off" required>
                
                <div id="location-suggestions" class="list-group position-absolute w-100 shadow-lg" style="z-index: 1000; max-height: 200px; overflow-y: auto; display:none;">
                    </div>

                <input type="hidden" id="location" name="location" value=""> 
            </div>
            <div class="col-lg-3 col-md-6">
                <label for="checkin" class="form-label fw-bold">Check-in Date</label>
                <input type="date" id="checkin" name="checkin" class="form-control" required>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <label for="checkout" class="form-label fw-bold">Check-out Date</label>
                <input type="date" id="checkout" name="checkout" class="form-control" required>
            </div>
            
            <div class="col-lg-2 col-md-6">
                <label for="guests" class="form-label fw-bold">Rooms/Guests</label>
                <input type="number" id="guests" name="guests" class="form-control" value="1" min="1" required>
            </div>

            <div class="col-lg-1 col-12 d-grid">
                <button type="submit" class="btn btn-warning h-100 fs-5 rounded-3">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </div>
    </form>
    <div class="row mt-4">
        <div class="col-12">
            <h3 id="location-title">Hotel Search Results</h3>
            <p class="text-muted">Searching for: <span id="searched-location-text"></span></p>
            <pre id="hotel-results-data" style="white-space: pre-wrap; background: #f8f9fa; padding: 15px; border-radius: 5px;">
                Enter a city and click search to view results.
            </pre>
        </div>
    </div>
</form>
</div>
</div> -->


<!--RATING MODAL-->
<div class="modal fade" id="ratingsModal" tabindex="-1" aria-labelledby="ratingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content ratings-modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title" id="ratingsModalLabel">
                    <i class="fa-solid fa-star me-2"></i> Boracay Ratings & Reviews
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                
                <h3 class="text-center mb-4">Overall Score: 4.6/5.0</h3>
                
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Location & Accessibility
                        <span class="badge bg-primary rounded-pill">9.2 / 10</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Value for Money
                        <span class="badge bg-primary rounded-pill">7.5 / 10</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Cleanliness & Environment
                        <span class="badge bg-primary rounded-pill">9.8 / 10</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Activities & Attractions
                        <span class="badge bg-primary rounded-pill">8.9 / 10</span>
                    </li>
                </ul>
                
                <p class="text-center text-muted small">Based on 2,100 verified traveler reviews.</p>

                <hr>

                <h6>Recent Traveler Feedback:</h6>
                <div class="p-3 border rounded-3 mt-2">
                    <p class="mb-1 small">**Traveler A (5/5):** "The beaches were pristine, truly paradise!"</p>
                    <p class="mb-0 small text-muted fst-italic">— Reviewed 1 day ago</p>
                </div>
                <div class="p-3 border rounded-3 mt-2">
                    <p class="mb-1 small">**Traveler B (4/5):** "A little crowded, but the sunset view was worth it."</p>
                    <p class="mb-0 small text-muted fst-italic">— Reviewed 3 days ago</p>
                </div>
                
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" class="btn btn-primary">Submit Your Review</a>
            </div>
            
        </div>
    </div>
</div>

<div id="chat-float-btn" class="btn btn-primary" onclick="toggleChat()">
    <i class="fas fa-comment-alt"></i>
</div>

<div id="chat-widget">
    <div id="chat-header">
        VigGo AI Assistant
        <button class="btn btn-sm btn-light" onclick="toggleChat()" aria-label="Close Chat">&times;</button>
    </div>
    <div id="chat-output">
        <div class="message ai-message">
            <strong>AI:</strong> Hello! I'm your VigGo AI Travel Assistant. How can I help you plan your next flight or trip?
        </div>
    </div>
    <div id="chat-input-area">
        <input type="text" id="user-input" placeholder="Type your message..." onkeydown="if(event.key === 'Enter') sendMessage()">
        <button class="btn btn-primary" onclick="sendMessage()">Send</button>
    </div>
</div>

<script>
    async function searchAttractions() {
        const city = document.getElementById('cityInput').value.toLowerCase();
        const resultsDiv = document.getElementById('attractionResults');
        
        if (!city) {
            alert("Please enter a city name");
            return;
        }

        resultsDiv.innerHTML = '<p>Searching for amazing tours...</p>';

        try {
            // CALL YOUR PHP FILE
            const response = await fetch('/viggo/utils/get_attractions.php?city=' + city);
            
            // CHECK IF FILE EXISTS (404 Error)
            if (!response.ok) {
                throw new Error(`PHP file not found (404). Check folder path!`);
            }

            const jsonData = await response.json();

            // CHECK IF API RETURNED ERROR
            if (jsonData.error) {
                resultsDiv.innerHTML = `<p style="color:red; font-weight:bold;">${jsonData.error}</p>`;
                return;
            }

            // DISPLAY RESULTS
            if (jsonData.data && jsonData.data.length > 0) {
                displayAttractions(jsonData.data);
            } else {
                resultsDiv.innerHTML = '<p>No tours found for this location.</p>';
            }

        } catch (error) {
            console.error('Error:', error);
            resultsDiv.innerHTML = `<p style="color:red">Error: ${error.message}</p>`;
        }
    }

    function displayAttractions(items) {
        const resultsDiv = document.getElementById('attractionResults');
        resultsDiv.innerHTML = ''; 

        items.forEach(item => {
            const card = document.createElement('div');
            card.className = 'attraction-card';
            
            // 1. Get Image (Use the real one from Amadeus if available)
            let imageUrl = 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=500&q=60'; // Fallback
            if (item.pictures && item.pictures.length > 0) {
                imageUrl = item.pictures[0];
            }

            // 2. Get Price
            let priceInfo = '';
            if (item.price) {
                priceInfo = `<p class="price"><strong>Price:</strong> ${item.price.amount} ${item.price.currencyCode}</p>`;
            }

            // 3. Build Card HTML
            card.innerHTML = `
                <div class="image-container">
                    <img src="${imageUrl}" alt="${item.name}" onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
                </div>
                <div class="card-content">
                    <h3>${item.name}</h3>
                    <p class="desc">${item.shortDescription || "No description available."}</p>
                    ${priceInfo}
                    <a href="${item.bookingLink}" target="_blank" class="view-btn">Book Now</a>
                </div>
            `;
            
            resultsDiv.appendChild(card);
        });
    }
</script>

</body>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>

    <script>
        // --- Configuration Constants ---
        const CHAT_API_URL = 'http://localhost:3000/chat'; 
        const HOTEL_API_URL = 'http://localhost:5000/api/makcorps-search';
    

        // --- Chat Elements ---
        const chatWidget = document.getElementById('chat-widget');
        const outputDiv = document.getElementById('chat-output');
        const inputField = document.getElementById('user-input');

        // --- Hotel Search Elements ---
        const hotelSearchForm = document.getElementById('hotel-search-form');
        const hotelResultsDiv = document.getElementById('hotel-results');
        const hotelResultsData = document.getElementById('hotel-results-data');
        const locationTitle = document.getElementById('location-title');
        const searchedLocationText = document.getElementById('searched-location-text');
        const locationTextInput = document.getElementById('location-text'); // The visible text input
        const locationSuggestionsDiv = document.getElementById('location-suggestions'); // The suggestions dropdown

        // --- Chat Functions ---

        function toggleChat() {
            chatWidget.style.display = chatWidget.style.display === 'flex' ? 'none' : 'flex';
            if (chatWidget.style.display === 'flex') {
                inputField.focus();
            }
        }

        function appendMessage(sender, text) {
            const msgDiv = document.createElement('div');
            msgDiv.className = `message ${sender}-message`;
            
            const content = sender === 'user' 
                ? text 
                : `<strong>AI:</strong> ${text}`;
                
            msgDiv.innerHTML = content;
            outputDiv.appendChild(msgDiv);
            outputDiv.scrollTop = outputDiv.scrollHeight; // Auto-scroll to bottom
        }

        async function sendMessage() {
            const message = inputField.value.trim();
            if (!message) return;

            appendMessage('user', message);
            inputField.value = '';
            
            const waitingDiv = document.createElement('div');
            waitingDiv.className = 'message ai-message waiting-message';
            waitingDiv.innerHTML = '<strong>AI:</strong> Typing...';
            outputDiv.appendChild(waitingDiv);
            outputDiv.scrollTop = outputDiv.scrollHeight; 

            try {
                const response = await fetch(CHAT_API_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: message }),
                });

                const data = await response.json();
                
                outputDiv.removeChild(waitingDiv);

                if (data.response) {
                    appendMessage('ai', data.response);
                } else if (data.error) {
                    appendMessage('ai', `Sorry, an error occurred: ${data.error}`);
                }

            } catch (error) {
                console.error("Frontend Fetch Error:", error);
                const lastMsg = outputDiv.lastElementChild;
                if (lastMsg && lastMsg.classList.contains('waiting-message')) {
                    outputDiv.removeChild(lastMsg);
                }
                appendMessage('ai', 'Connection Error. Is the Node.js server running on port 3000?');
            }
        }


        // --- Location Autocomplete Logic ---

        // let locationSearchTimeout;

        // if (locationTextInput) {
        //     // Event listener for user typing (debounced)
        //     locationTextInput.addEventListener('input', () => {
        //         clearTimeout(locationSearchTimeout);
                
        //         locationSearchTimeout = setTimeout(() => {
        //             const query = locationTextInput.value.trim();
        //             // Ensure the hidden location key is cleared until a new one is selected
        //             document.getElementById('location').value = ''; 
                    
        //             if (query.length > 2) {
        //                 fetchLocationSuggestions(query);
        //             } else {
        //                 locationSuggestionsDiv.style.display = 'none';
        //             }
        //         }, 300); 
        //     });

        //     // Hide suggestions when the input field loses focus
        //     locationTextInput.addEventListener('blur', () => {
        //         // Use a slight delay so the click event on a suggestion registers first
        //         setTimeout(() => {
        //             locationSuggestionsDiv.style.display = 'none';
        //         }, 200); 
        //     });
        // }

        // async function fetchLocationSuggestions(query) {
        //     try {
        //         const response = await fetch(LOCATION_API_URL, {
        //             method: 'POST',
        //             headers: { 'Content-Type': 'application/json' },
        //             body: JSON.stringify({ query: query }),
        //         });

        //         const data = await response.json();

        //         if (data.status === 'OK' && data.autocomplete) {
        //             displaySuggestions(data.autocomplete);
        //         } else {
        //             locationSuggestionsDiv.style.display = 'none';
        //         }
        //     } catch (error) {
        //         console.error("Location Fetch Error:", error);
        //         locationSuggestionsDiv.style.display = 'none';
        //     }
        // }

        // function displaySuggestions(suggestions) {
        //     locationSuggestionsDiv.innerHTML = ''; // Clear previous suggestions
        //     if (suggestions && suggestions.length > 0) {
                
        //         suggestions.slice(0, 5).forEach(item => { // Show up to 5 suggestions
        //             // Only show relevant types (City, Airport, Neighborhood)
        //             if (item.type === 'CITY' || item.type === 'AIRPORT' || item.type === 'NEIGHBORHOOD') {
        //                 const suggestionItem = document.createElement('a');
        //                 suggestionItem.href = '#';
        //                 suggestionItem.className = 'list-group-item list-group-item-action';
        //                 suggestionItem.innerHTML = `
        //                     ${item.name} <span class="badge bg-secondary">${item.type}</span>
        //                 `;
                        
        //                 suggestionItem.addEventListener('mousedown', (e) => {
        //                     e.preventDefault(); 
                            
        //                     // Set the text input and the hidden key
        //                     locationTextInput.value = item.name;
        //                     document.getElementById('location').value = item.location_key;
                            
        //                     locationSuggestionsDiv.style.display = 'none';
        //                 });

        //                 locationSuggestionsDiv.appendChild(suggestionItem);
        //             }
        //         });
        //         locationSuggestionsDiv.style.display = 'block';
        //     } else {
        //         locationSuggestionsDiv.style.display = 'none';
        //     }
        // }



        // --- Hotel Search Logic for Xotelo API ---

        // if (hotelSearchForm) {
        //     hotelSearchForm.addEventListener('submit', handleHotelSearch);
        // }
        //             async function handleHotelSearch(event) {
        //     event.preventDefault();

        //     const destinationText = document.getElementById('location-text').value.trim();
        //     const checkin = document.getElementById('checkin').value;
        //     const checkout = document.getElementById('checkout').value;
        //     const rooms = parseInt(document.getElementById('guests').value);
        //     const adults = rooms; // or ask user for number of adults

        //     // 1) Get City ID first
        //     const mapRes = await fetch('/api/makcorps-map', {
        //         method: 'POST',
        //         headers: { 'Content-Type': 'application/json' },
        //         body: JSON.stringify({ name: destinationText })
        //     });
        //     const mapData = await mapRes.json();
        //     if (!Array.isArray(mapData) || mapData.length == 0) {
        //         alert('City not found.');
        //         return;
        //     }
        //     const cityId = mapData[0].document_id;

        //     // 2) Request hotels
        //     const hotelRes = await fetch('/api/makcorps-search', {
        //         method: 'POST',
        //         headers: { 'Content-Type': 'application/json' },
        //         body: JSON.stringify({
        //             destination: cityId,
        //             checkin, checkout, rooms, adults
        //         })
        //     });

        //     const hotelData = await hotelRes.json();
        //     document.getElementById('hotel-results-data').textContent = JSON.stringify(hotelData, null, 2);
        // }

    //    async function handleHotelSearch(event) {
    //         event.preventDefault();

    //         // *** CORRECTED ELEMENT IDs TO MATCH YOUR HTML FORM ***
    //         const destinationInput = document.getElementById('location-text');
    //         const checkin = document.getElementById('checkin').value;
    //         const checkout = document.getElementById('checkout').value;
    //         const rooms = document.getElementById('guests').value;
    //         // ******************************************************

    //         const destination = destinationInput.value; // Get the user's typed city name
            
    //         // Basic validation
    //         if (!destination || destination.trim() === '') {
    //             alert('Please enter a destination.');
    //             return;
    //         }
    //         if (!checkin || !checkout || !rooms || isNaN(parseInt(rooms)) || parseInt(rooms) <= 0) {
    //             alert('Please check your dates and room count.');
    //             return;
    //         }

    //         // Select the result display elements (must exist in HTML)
    //         const hotelResultsData = document.getElementById('hotel-results-data');
    //         const locationTitle = document.getElementById('location-title');
    //         const searchedLocationText = document.getElementById('searched-location-text');

    //         // Show loading state, but only if the elements exist
    //         if (locationTitle) locationTitle.textContent = 'Searching...';
    //         if (searchedLocationText) searchedLocationText.textContent = destination;
    //         if (hotelResultsData) hotelResultsData.textContent = 'Contacting hotel API via server...';
            
    //         // Helper function to update display or log console error if elements are missing
    //         const updateResultDisplay = (title, data) => {
    //             if (locationTitle) locationTitle.textContent = title;
    //             if (hotelResultsData) hotelResultsData.textContent = data;
    //             console.log('Search Result:', title, data);
    //         };

    //         try {
    //             const response = await fetch(HOTEL_API_URL, {
    //                 method: 'POST',
    //                 headers: {
    //                     'Content-Type': 'application/json',
    //                 },
    //                 body: JSON.stringify({ 
    //                 destination: destination,
    //                 checkin: checkin,
    //                 checkout: checkout,
    //                 rooms: rooms
    //             }),
    //             });

    //             const data = await response.json();

    //             if (response.ok) {
    //                 const hotels = data; 
                    
    //                 if (hotels && hotels.length > 0) {
    //                     const topHotels = hotels.slice(0, 10);
                        
    //                     const hotelsList = topHotels.map((hotel, index) => {
    //                         const price = hotel.price ? `₱${hotel.price.toFixed(2)}` : 'N/A';
    //                         const vendor = hotel.vendor || 'Unknown Vendor';
    //                         const rating = hotel.rating ? ` ★ ${hotel.rating}/5.0` : 'N/A';

    //                         return `${index + 1}. **${hotel.hotelName}**\n   - Price: ${price} (via ${vendor})\n   - Rating: ${rating}\n`;
    //                     }).join('\n');
                        
    //                     updateResultDisplay(
    //                         `Search Results for ${destination}`,
    //                         `Search Successful! Found ${hotels.length} hotels (showing top 10 for ${destination}):\n\n${hotelsList}`
    //                     );
                        
    //                 } else {
    //                     updateResultDisplay(
    //                         `No Results`,
    //                         `Search successful, but no hotels were found for ${destination}.`
    //                     );
    //                 }
    //             } else {
    //                 updateResultDisplay(
    //                     `Error`,
    //                     `Server Connection Error (${response.status}): ${data.error || 'Could not process request on the backend.'}`
    //                 );
    //             }

    //         } catch (error) {
    //             console.error('Hotel Search Network Error:', error);
    //             updateResultDisplay(
    //                 `Network Error`,
    //                 `Network Error: Failed to fetch from server. Ensure your backend is running and reachable at ${HOTEL_API_URL}.`
    //             );
    //         }
    //     }
    </script>
    <script src="utils/scroll.js"></script>

</html>