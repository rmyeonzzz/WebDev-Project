<?php 
$serverName="LAPTOP-1AD6EHQ4"; 
$connectionOptions=[ 
"Database"=>"DLSU", 
"Uid"=>"", 
"PWD"=>"" 
]; 
    $conn = sqlsrv_connect($serverName, $connectionOptions); 
    if ($conn === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $complete_name = $_POST['complete_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    //check if there's existing username
    $sql_check = "SELECT * FROM AGILA_REGISTRATION WHERE username = ?";
    $result_check =sqlsrv_query($conn, $sql_check,[$username]);
    $row = sqlsrv_fetch_array($result_check);
    if($row){
        echo "User already exists";
        die();
    }

    //confirmation of pw
    if($password == $confirm_password){
        $sql = "INSERT INTO AGILA_REGISTRATION
        (completename, username, email, passwordd) 
        VALUES ('$complete_name', '$username', '$email', '$password')";
        $result = sqlsrv_query($conn,$sql); 

        if($result==true){
            // Get the newly inserted user's ID
            $sql_get_id = "SELECT USERID FROM AGILA_REGISTRATION WHERE username = ?";
            $result_get_id = sqlsrv_query($conn, $sql_get_id, [$username]);
            $user_row = sqlsrv_fetch_array($result_get_id);
            $user_id = $user_row['USERID'];
            header("Location: signupsuccess.html"); 
        }

    }else{
        header("");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Sign Up</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src='https://kit.fontawesome.com/4c729db828.js' crossorigin='anonymous'></script>
    
    <link rel="stylesheet" href="styles2.css"> 
    </head>

<body class="signup-body"> 
    <div class="container py-5">
        <div class="row justify-content-center">
            
            <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5">
                
                <div class="card shadow-lg border-0 signup-card">
                    
                    <div class="card-header text-center bg-primary text-white py-3">
                        <i class="fa-solid fa-plane-departure fa-2x mb-2"></i>
                        <h2 class="h4 mb-0">Create Your Agila Account</h2>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="signup.php">
                            
                            <div class="mb-3">
                                <label for="completeName" class="form-label small">Complete Name</label>
                                <input type="text" class="form-control" name="complete_name" id="completeName" required/>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label small">Email Address</label>
                                <input type="email" class="form-control" name="email" id="email" required/>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label small">Username</label>
                                <input type="text" class="form-control" name="username" id="username" required/>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label small">Password</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder = "Password doesn't match" required />
                            </div>
                            <div class="mb-4">
                                <label for="confirmPassword" class="form-label small">Confirm Password</label>
                                <input type="password" class="form-control" name="confirm_password" id="confirmPassword" required/>
                            </div>
                            
                            <p class="text-danger small fw-bold" id="error-msg">
                                </p>
                             <div class="text-center text-muted mb-3 small">
                                 — OR —
                             </div>
                             <div class="d-grid mb-3">
                                <button class="btn btn-outline-dark" type="button" disabled>
                                    <i class="fa-brands fa-google me-2"></i> Sign Up with Google
                                 </button>
                             </div>
                        
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                Sign Up
                            </button>
                        </form>
                        
                    </div>
                    
                    <div class="card-footer text-center bg-light py-3 border-0">
                        Already have an account? 
                        <a href="login.html" 
                           class="btn btn-sm btn-outline-secondary ms-1 fw-bold"
                           data-bs-toggle="modal" 
                           data-bs-target="#loginModal">
                            Log In
                        </a>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
</body>
</html>


   
 