<?php
// Simple Hotel Details Page
$hotel_id = $_GET['id'] ?? '';
$name = urldecode($_GET['name'] ?? 'Hotel Name');
$image = urldecode($_GET['image'] ?? '');
$price = urldecode($_GET['price'] ?? '');
$score = $_GET['score'] ?? 'N/A';

// In a real app, you would call the API here again using $hotel_id 
// to get the description, amenities, and extra photos.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($name); ?> - VigGo Hotels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src='https://kit.fontawesome.com/4c729db828.js' crossorigin='anonymous'></script>
    <style>
        .hotel-header { height: 400px; object-fit: cover; width: 100%; }
        .feature-icon { font-size: 1.2rem; color: #2a6dac; width: 30px; }
        /* Card Container */
        /* Card Container */
        .hotel-card {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 24px;
        width: 100%;             
        max-width: 320px;        
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 280px;       /* Increased height slightly for the huge price */
        }

        /* Price Section */
        .price-section {
        display: flex;
        flex-direction: column;
        gap: 0px;                /* Reduced gap to keep label close to the huge number */
        }

        .price-label {
        color: #555;
        font-size: 16px;
        font-weight: 500;
        }

        .price-value {
        color: #FFC107;          /* Yellow Color (Amber/Gold for visibility) */
        font-size: 130px;        /* Massive font size to fill container */
        font-weight: 700;
        line-height: 1;
        letter-spacing: -4px;    /* Tighten spacing for that big impact look */
        margin-left: -8px;       /* Slight offset to align the huge dollar sign visually */
        }

        /* Footer Section */
        .card-footer {
        margin-top: auto; 
        }

        .divider {
        border: 0;
        border-top: 1px solid #e0e0e0;
        margin: 16px 0;
        }

        .rating-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        }

        .review-label {
        color: #333;
        font-size: 14px;
        }

        .rating-badge {
        background-color: #006ce4;
        color: white;
        font-weight: bold;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 14px;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index2.html">VigGo Travels</a>
            <a href="index2.html" class="btn btn-outline-light btn-sm">Back to Search</a>
        </div>
    </nav>

    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <div class="position-relative rounded overflow-hidden shadow">
                    <img src="<?php echo htmlspecialchars($image); ?>" class="hotel-header" alt="Hotel Image">
                    <div class="position-absolute bottom-0 start-0 bg-dark bg-opacity-75 text-white p-4 w-100">
                        <h1 class="mb-0"><?php echo htmlspecialchars($name); ?></h1>
                        <p class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Excellent Location</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h4>About this Hotel</h4>
                        <p class="text-muted">
                            Experience world-class service at <?php echo htmlspecialchars($name); ?>. 
                            Located in the heart of the city, this property offers stunning views, 
                            modern amenities, and easy access to local attractions. 
                            (Note: This is placeholder text. To get real text, we need a second API call).
                        </p>
                        
                        <h5 class="mt-4 mb-3">Popular Amenities</h5>
                        <div class="row g-3">
                            <div class="col-6 col-md-4"><i class="fas fa-wifi feature-icon"></i> Free WiFi</div>
                            <div class="col-6 col-md-4"><i class="fas fa-swimming-pool feature-icon"></i> Swimming Pool</div>
                            <div class="col-6 col-md-4"><i class="fas fa-utensils feature-icon"></i> Breakfast Included</div>
                            <div class="col-6 col-md-4"><i class="fas fa-parking feature-icon"></i> Free Parking</div>
                            <div class="col-6 col-md-4"><i class="fas fa-snowflake feature-icon"></i> Air Conditioning</div>
                            <div class="col-6 col-md-4"><i class="fas fa-concierge-bell feature-icon"></i> 24h Front Desk</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hotel-card">
  
                <div class="price-section">
                        <span class="price-label">Price starts from</span>
                        <span class="price-value">$69</span>
                    </div>

                    <div class="card-footer">
                        <hr class="divider">
                        <div class="rating-row">
                        <span class="review-label">Review Score</span>
                        <span class="rating-badge">8.5 / 10</span>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

</body>
</html>