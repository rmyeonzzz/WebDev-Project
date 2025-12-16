<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles2.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src='https://kit.fontawesome.com/4c729db828.js' crossorigin='anonymous'></script>
    <title>VigGo Travel Booking</title>
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
        }
        .signup-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }
        .card-header {
            background-color: #FF5804 !important;
            border-bottom: none;
        }
        .btn-primary {
            background-color: #2a6dac;
            border: none;
            font-weight: 700;
        }
        .btn-primary:hover {
            background-color: #1e5285;
        }
        footer {
            background-color: #333;
            color: white;
            padding: 20px 0;
            margin-top: auto;
            text-align: center;
        }
    </style>
</head>

<body>
    <header class="navbar-container">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">
                     VigGo
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="main-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-lg signup-card">
                        <div class="card-header text-center text-white py-4">
                            <h2 class="h4 mb-0 fw-bold">Create Account</h2>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="signup.php" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Complete Name</label>
                                    <input type="text" class="form-control" name="complete_name" required/>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email</label>
                                    <input type="email" class="form-control" name="email" required/>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Username</label>
                                    <input type="text" class="form-control" name="username" required/>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Password</label>
                                    <input type="password" class="form-control" name="password" 
                                    minlength= "8" placeholder = "Minimum of 8 characters" required/>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Confirm Password</label>
                                    <input type="password" class="form-control" name="confirm_password" required/>
                                </div>
                                <p class="text-danger small fw-bold" id="error-msg">
                                    <?php 
                                        if(isset($_SESSION['signup_status']) && $_SESSION['signup_status'] !== 'success') {
                                            if ($_SESSION['signup_status'] === 'user_exists') {
                                                echo "User already exists. Please choose a different username.";
                                            } elseif ($_SESSION['signup_status'] === 'password_mismatch') {
                                                echo "Password and Confirm Password do not match.";
                                            }
                                            unset($_SESSION['signup_status']);
                                        }
                                    ?>
                                </p>
                                <button type="submit" class="btn btn-primary w-100 py-2">
                                    Sign Up
                                </button>
                            </form>
                        </div>
                        <div class="card-footer text-center bg-light py-3 border-0">
                            Already have an account? 
                            <a href="login.html" class="btn btn-sm btn-outline-secondary ms-1 fw-bold">
                                Log In
                            </a>
                        </div>
                    </div>
                </div>
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
    
    <footer>
        <div class="container">
            <p class="mb-0 small">&copy; 2025 VigGo Travel. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php 
            if (isset($_SESSION['signup_status']) && $_SESSION['signup_status'] === 'success') {
                $redirect_url = isset($_SESSION['success_redirect']) ? $_SESSION['success_redirect'] : 'login.html';
                
                unset($_SESSION['signup_status']);
                unset($_SESSION['success_redirect']);
                
                echo "var successModal = new bootstrap.Modal(document.getElementById('successModal'));";
                echo "document.getElementById('loginButton').href = '$redirect_url';";
                echo "successModal.show();";
            }
            ?>
        });
    </script>         
</body>
</html>