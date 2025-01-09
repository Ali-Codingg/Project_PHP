<?php
// Include the database configuration file
include('config.php');

// Initialize variables for success and error messages
$success = "";
$error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize user inputs
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email is already registered
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $error = "Email is already registered!";
    } else {
        // Insert the new user into the database
        $insert = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";
        if (mysqli_query($conn, $insert)) {
            $success = "User registered successfully. You can now <a href='login.php'>login</a>.";
        } else {
            $error = "Could not register. Please try again.";
        }
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Honey E-Commerce</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Optional: Custom CSS for additional styling -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-card {
            margin-top: 50px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .register-card-header {
            background-color: #fff;
            border-bottom: none;
            text-align: center;
        }

        .register-card-footer {
            background-color: #fff;
            border-top: none;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Center the form vertically and horizontally -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Registration Card -->
                <div class="card register-card">
                    <div class="card-header register-card-header">
                        <h3>Register</h3>
                    </div>
                    <div class="card-body">
                        <!-- Display Success Message -->
                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo $success; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Display Error Message -->
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Registration Form -->
                        <form action="register.php" method="POST">
                            <!-- Name Field -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Name:</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="name" 
                                    name="name" 
                                    required 
                                    value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                                    placeholder="Enter your full name"
                                >
                            </div>
                            <!-- Email Field -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input 
                                    type="email" 
                                    class="form-control" 
                                    id="email" 
                                    name="email" 
                                    required 
                                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                    placeholder="Enter your email address"
                                >
                            </div>
                            <!-- Password Field -->
                            <div class="mb-4">
                                <label for="password" class="form-label">Password:</label>
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    id="password" 
                                    name="password" 
                                    required 
                                    placeholder="Create a password"
                                >
                                <div class="form-text">Your password must be at least 8 characters long.</div>
                            </div>
                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>
                    </div>
                    <div class="card-footer register-card-footer">
                        <p>Already have an account? <a href="login.php">Login here</a>.</p>
                    </div>
                </div>
                <!-- End of Registration Card -->
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper (for interactive components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Optional: Custom JS for additional functionality -->
</body>
</html>
