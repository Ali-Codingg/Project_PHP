<?php
// Include the configuration file to connect to the database
include('config.php');
session_start();

// Check if the user is an admin (only admins can delete orders)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Check if an order ID is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $order_id = $_GET['id'];

    // Make sure we have a valid database connection
    if ($conn) {
        // Start the transaction to ensure both deletions happen atomically
        mysqli_begin_transaction($conn);
        try {
            // Prepare the SQL query to delete the order from the orders table
            $query = "DELETE FROM orders WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            
            // Bind the parameter (order ID) to the prepared statement
            mysqli_stmt_bind_param($stmt, 'i', $order_id);

            // Execute the query
            if (mysqli_stmt_execute($stmt)) {
                // Prepare and execute the query to delete associated order items
                $delete_items_query = "DELETE FROM order_items WHERE order_id = ?";
                $stmt_items = mysqli_prepare($conn, $delete_items_query);
                mysqli_stmt_bind_param($stmt_items, 'i', $order_id);
                mysqli_stmt_execute($stmt_items);

                // Commit the transaction to ensure both queries are successful
                mysqli_commit($conn);

                // Redirect to the order list with a success message
                header('Location: order_list.php?status=success');
                exit();
            } else {
                // If the deletion fails, rollback the transaction
                mysqli_rollback($conn);
                echo "Error: Unable to delete the order. Please try again.";
            }
        } catch (Exception $e) {
            // Rollback the transaction in case of any exception
            mysqli_rollback($conn);
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Error: Database connection failed.";
    }

    // Close the prepared statements
    if (isset($stmt)) {
        mysqli_stmt_close($stmt);
    }
    if (isset($stmt_items)) {
        mysqli_stmt_close($stmt_items);
    }
} else {
    // If no order ID is provided, redirect to the order list
    header('Location: order_list.php');
    exit();
}

// Close the database con
