<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id'])) {
    echo "Access denied.";
    exit;
}

$user_id=$_SESSION["user_id"];

$current_password = $_POST['current_password'];
$new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

$sql="SELECT * FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0){
    $user = mysqli_fetch_assoc($result);

    if(password_verify($current_password, $user["password"])){
        $update="UPDATE users SET password='$new_password' WHERE id=''$user_id";
        if(mysqli_query($conn, $update)){
            echo "Password changed successfully";
        }
        else{
            echo "Error: " . mysqli_error($conn);
        }
    }
    else{
        echo "Incorrect password";
    }
}
else{
    echo "User not found";
}
mysqli_close($conn );
?>