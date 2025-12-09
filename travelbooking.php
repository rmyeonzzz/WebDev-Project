<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles2.css">
    <link rel="stylesheet" href="media.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Fuzzy+Bubbles:wght@400;700&family=Mynerve&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src='https://kit.fontawesome.com/4c729db828.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <title>VigGo Travel Booking</title>
</head>


<body>
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successModalLabel">
                    <i class="fa-solid fa-circle-check me-2"></i> Registration Successful!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <p id="modalMessage"></p> 
                <a href="index2.html" class="btn btn-primary btn-lg mt-3">Return to Home Page & Log In</a>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>

    <?php
    // Start session if it wasn't already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['registration_status']) && isset($_SESSION['registration_message'])) {
        $status = $_SESSION['registration_status'];
        $message = $_SESSION['registration_message'];
        
        // Clear the session variables so the modal doesn't pop up on refresh
        unset($_SESSION['registration_status']);
        unset($_SESSION['registration_message']);

        // Only generate script if there's a status
        echo "<script>";
        echo "document.addEventListener('DOMContentLoaded', function() {";
        
        // Show success modal
        if ($status === 'success') {
            echo "var successModal = new bootstrap.Modal(document.getElementById('successModal'));";
            echo "document.getElementById('modalMessage').textContent = '" . $message . "';";
            echo "successModal.show();";
        } 
        
        // Optional: Show an error notification/modal if registration failed
        if ($status === 'error') {
            // For errors, we can reuse the Sign In Modal or create a simple alert.
            echo "alert('Error: " . str_replace("'", "\'", $message) . "');"; 
        }
        
        echo "});";
        echo "</script>";
    }
    ?>

</body>
</html>
