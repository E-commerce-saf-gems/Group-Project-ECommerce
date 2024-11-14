<?php
// Include database connection
include '../../database/db.php';  // Adjust the path to match the location of db.php relative to this file

// Initialize variables for form inputs
$gemType = "";
$caratWeight = 0;
$cut = "";
$color = "";
$specialRequirements = "";
$customerID = 1;  // Hardcoded customer ID for this example

$errorMessage = "";
$successMessage = "";

// Check if form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize the input
    $gemType = isset($_POST['gem-type']) ? htmlspecialchars(trim($_POST['gem-type'])) : '';
    $caratWeight = isset($_POST['carat-weight']) ? floatval($_POST['carat-weight']) : 0;
    $cut = isset($_POST['cut']) ? htmlspecialchars(trim($_POST['cut'])) : '';
    $color = isset($_POST['color']) ? htmlspecialchars(trim($_POST['color'])) : '';
    $specialRequirements = isset($_POST['special-requirements']) ? htmlspecialchars(trim($_POST['special-requirements'])) : '';

    // Basic validation
    if (empty($gemType) || empty($cut) || empty($color)) {
        $errorMessage = "Gemstone Type, Cut, and Color are required.";
    } else {
        // Construct the SQL query
        $sql = "INSERT INTO request (type, weight, shape, color, requirement, customer_id) 
                VALUES ('$gemType', '$caratWeight', '$cut', '$color', '$specialRequirements', '$customerID')";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            $successMessage = "Your custom request has been submitted successfully!";
        } else {
            $errorMessage = "Error: " . $conn->error;
        }
    }
}

// Close the database connection
$conn->close();
?>
