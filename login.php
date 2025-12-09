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

    $username = $_POST['username'];
    $password = $_POST['passwordd'];


    //check if there's existing username and pw
    $sql_check = "SELECT * FROM AGILA_REGISTRATION WHERE username = ? AND passwordd = ?";
    $result_check =sqlsrv_query($conn, $sql_check,[$username,$password]);
    $row = sqlsrv_fetch_array($result_check);
    if($row){
        header("Location: index2.html");
    }else{
        header("");
    }

  ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src='https://kit.fontawesome.com/4c729db828.js' crossorigin='anonymous'></script>
    
    <link rel="stylesheet" href="styles2.css"> 
    </head>

<body class="login-body"> 
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-lg border-0 login-card">
                    <div class="card-header text-center bg-primary text-white py-3">
                        <i class="fa-solid fa-plane-departure fa-2x mb-2"></i>
                        <h2 class="h4 mb-0">Log in your Agila Account</h2>
                    </div>

                    <div class="card-body p-4">
                        
                        <div class="mb-3 p-2 border rounded login-error-box">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i> 
                            <span class="text-danger small">User does not exist or Incorrect Password</span>
                        </div>                
                        <form method="POST" action="login.php">
                            <div class="mb-3">
                                <label for="username" class="form-label small">Username</label>
                                <input type="text" class="form-control" name="username" id="username" required/>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label small">Password</label>
                                <input type="password" class="form-control" name="passwordd" id="password" required/>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                Log In
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="loginToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body fw-bold" id="toast-message">
                    </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

</body>
</html>


   
 