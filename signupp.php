<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles2.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Fuzzy+Bubbles:wght@400;700&family=Mynerve&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src='https://kit.fontawesome.com/4c729db828.js' crossorigin='anonymous'></script>
    <title>VigGo Travel Booking</title>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                 <h1 class="text-center">Create Account</h1>
                <form
                    method="POST"
                    action="signup.php"
                    enctype="multipart/form-data"
                >
                    <div class="mb-3">
                        <label>Complete Name</label>
                        <input type="text" class="form-control" name="complete_name" required/>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" required/>
                    </div>
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" class="form-control" name="username" required/>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" 
                        minlength= "8" placeholder = " Minimum of 8 characters"  required/>
                    </div>
                    <div class="mb-3">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control" name="confirm_password" required/>
                    </div>
                    <p class="text-danger" id="error-msg">

                        <?php 
                            if(isset($_SESSION['signup_status']) && $_SESSION['signup_status'] !== 'success') {
                                if ($_SESSION['signup_status'] === 'user_exists') {
                                    echo "User already exists. Please choose a different username.";
                                } elseif ($_SESSION['signup_status'] === 'password_mismatch') {
                                    echo "Password and Confirm Password do not match.";
                                }
                                // Clear the non-success status after displaying
                                unset($_SESSION['signup_status']);
                            }
                        ?>

                    </p>

                    <button type="submit" class="btn btn-primary w-100">
                        Sign Up
                    </button>
                </form>
            </div>
        </div>
        
    </div>
    
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel"><i class="fas fa-check-circle me-2"></i> Registration Complete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <p class="h5 mb-4">You have successfully created your VigGo Account!</p>
                    <a id="loginButton" href="login.html" class="btn btn-primary btn-lg w-75">Log In Now</a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php 
            // Check for success status passed from signup.php
            if (isset($_SESSION['signup_status']) && $_SESSION['signup_status'] === 'success') {
                $redirect_url = isset($_SESSION['success_redirect']) ? $_SESSION['success_redirect'] : 'login.html';
                
                // Clear the session variables after reading them
                unset($_SESSION['signup_status']);
                unset($_SESSION['success_redirect']);
                
                echo "var successModal = new bootstrap.Modal(document.getElementById('successModal'));";
                echo "document.getElementById('loginButton').href = '$redirect_url';";
                echo "successModal.show();";
            }

            ?>
        });

    </script>
                <div class="card-footer text-center bg-light py-3 border-0">
                        Already have an account? 
                        <a href="login.html" 
                           class="btn btn-sm btn-outline-secondary ms-1 fw-bold">
                            Log In
                        </a>
                    </div>
                    
</body>
</html>