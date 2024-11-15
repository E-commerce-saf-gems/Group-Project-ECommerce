<?php
session_start();
include('../../database/db.php'); // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_now_btn'])) {
    // Retrieve email and password from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user with the entered email from the database
    $query = "SELECT * FROM customer WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists and verify the password
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['customer_id'] = $user['customer_id'];
            $_SESSION['email'] = $user['email'];

            header("Location: ../homepage/homepage.html");
            exit();
        } else {
            echo "<script>alert('Incorrect password. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Email not found. Please register.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
