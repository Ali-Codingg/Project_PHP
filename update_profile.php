<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id'])) {
    echo "Access denied.";
    exit;
}

$user_id=$_SESSION["user_id"];
$name = htmlspecialchars($_POST['name']);

$email = mysqli_real_escape_string($conn, trim($_POST['email']));

$update="UPDATE users SET name='$name',email='$email' WHERE id='$user_id'";

if(mysqli_query($conn, $update)){
    echo "Profile updated successfully";
}
else{
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn );
?>