<?php
session_start();
include('../../database/db.php'); // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_now_btn'])) {
    // Retrieve email and password from the form
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Fetch user with the entered email from the database
    $query = "SELECT * FROM customer WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables for the logged-in user
            $_SESSION['customer_id'] = $user['customer_id']; // Assuming the column is `customer_id`
            $_SESSION['email'] = $user['email'];

            // Send JSON response (if needed by your front-end)
            header('Content-Type: application/json');
            echo json_encode([
                'loggedIn' => true,
                'email' => $_SESSION['email'],
                'customer_id' => $_SESSION['customer_id'],
            ]);

            // Redirect to homepage
            header("Location: ../homepage/homepage.html");
            exit();
        } else {
            // Invalid password
            header("Location: ../Login/login.php?fail=1");
            exit();
        }
    } else {
        // Email not found
        header("Location: ../Login/login.php?fail=2");
        exit();
    }
} else {
    // Redirect if accessed directly without POST data
    header("Location: ../Login/login.php");
    exit();
}
?>
