<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "safgems";

// Create connection using MySQLi
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for form inputs
$name = "";
$email = "";
$message = "";

// Initialize error and success messages
$errorMessage = "";
$successMessage = "";

// Check if form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["message"];

    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        $errorMessage = "Please fill in all the fields!";
    } else {
        // Prepare SQL query to insert form data into database
        $sql = "INSERT INTO test_requests (name, email, message) VALUES ('$name', '$email', '$message')";

        if ($conn->query($sql) === TRUE) {
            $successMessage = "Your message has been sent successfully!";
            
            // After a successful submission, redirect to another page (e.g., a confirmation page)
            header("Location: index.php");
            exit(); // Always call exit after header redirect to stop script execution
        } else {
            $errorMessage = "Error: " . $conn->error;
        }
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Status</title>
</head>
<body>

    <h2>Contact Form Status</h2>

    <!-- Display success or error message -->
    <?php
    if (!empty($errorMessage)) {
        echo "<p style='color: red;'>$errorMessage</p>";
    }

    if (!empty($successMessage)) {
        echo "<p style='color: green;'>$successMessage</p>";
    }
    ?>

</body>
</html>
