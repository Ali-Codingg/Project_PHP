<?php
session_start();
include("config.php");
 
if(!isset($_SESSION["user_id"])){
    header("Location:login.php");
    exit();
}
$user_id=$_SESSION["user_id"];
$sql="SELECT * FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result)> 0){
    $user = mysqli_fetch_array($result);
}
else{
    echo "User not found";
}
mysqli_close($conn );
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
</head>
<body>
    <header>
        <h1>Your Profile</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <h2>View and Update Your Profile</h2>
        <!-- Display User Information -->
        <form action="update_profile.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            <br><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            <br><br>
            <button type="submit">Update Profile</button>
        </form>
        <h3>Change Password</h3>
        <!-- Change Password Form -->
        <form action="change_password.php" method="POST">
            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password" required>
            <br><br>
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>
            <br><br>
            <button type="submit">Change Password</button>
        </form>
    </main>
</body>
</html>