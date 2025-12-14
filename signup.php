<?php 

session_start();
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


    //checking of user
    $sql_check = "SELECT * FROM AGILA_REGISTRATION WHERE username = ?";
    $result_check =sqlsrv_query($conn, $sql_check,[$username]);
    $row = sqlsrv_fetch_array($result_check);
    if($row){
        $_SESSION['signup_status'] ='user_exists';
        header("Location: signupp.php");
        exit();
    }

    //checking of pw
    if($password == $confirm_password){
        $sql = "INSERT INTO AGILA_REGISTRATION
        (completename, username, email, passwordd) 
        VALUES ('$complete_name', '$username', '$email', '$password')";
        $result = sqlsrv_query($conn,$sql); 


            if($result==true){
            $_SESSION ['signup_status'] ='success';
            $_SESSION['succes_redirect'] ='login.html';
            header("Location: signupp.php");
        } else {
                $_SESSION['signup_status'] = 'insert_fail';
                header("Location: signupp.php");
                exit();
            }

        }else{
            $_SESSION['signup_status'] = 'password_mismatch';
            header("Location: signupp.php");
            exit();
}
?>