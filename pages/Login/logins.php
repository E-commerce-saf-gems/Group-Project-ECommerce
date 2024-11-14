<?php
session_start();
require 'db.php'; // Include database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email and password from the form
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if fields are not empty
    if (!empty($email) && !empty($password)) {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM customer WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['customer_id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['firstname'];
                
                // Redirect to a protected page
                header("Location: db.php");
                exit();
            } else {
                // Invalid password
                echo "Invalid password. Please try again.";
            }
        } else {
            // No user found
            echo "No account found with that email address.";
        }
        $stmt->close();
    } else {
        echo "Please fill in all fields.";
    }
}

$conn->close();
?>
