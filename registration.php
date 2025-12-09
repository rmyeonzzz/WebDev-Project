<?php 
$serverName="LAPTOP-1AD6EHQ4"; 
$connectionOptions=[ 
"Database"=>"DLSU", 
"Uid"=>"", 
"PWD"=>"" 
]; 
$conn=sqlsrv_connect($serverName, $connectionOptions); 
if($conn==false) 
    die(print_r(sqlsrv_errors(),true)); 
else echo 'Connection Success'; 
 
$completename = $_POST['completename'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$sql = "INSERT INTO AGILA_REGISTRATION
        (completename, username, email, passwordd) 
        VALUES ('$completename', '$username', '$email', '$password')";
$result = sqlsrv_query($conn,$sql); 
 
if($result==true){ 
    echo "New record created successfully"; 
} else{ 
    echo "Error: " ; 
} 
?>