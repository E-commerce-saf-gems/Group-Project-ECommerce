<?php

session_start();
include '../../database/db.php';  

$customerID = $_SESSION['customer_id'];

$gemType = "";
$caratWeight = 0;
$cut = "";
$color = "";
$specialRequirements = "";
$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gemType = isset($_POST['gem-type']) ? htmlspecialchars(trim($_POST['gem-type'])) : '';
    $caratWeight = isset($_POST['carat-weight']) ? floatval($_POST['carat-weight']) : 0;
    $cut = isset($_POST['cut']) ? htmlspecialchars(trim($_POST['cut'])) : '';
    $color = isset($_POST['color']) ? htmlspecialchars(trim($_POST['color'])) : '';
    $specialRequirements = isset($_POST['special-requirements']) ? htmlspecialchars(trim($_POST['special-requirements'])) : '';

    if (empty($gemType) || empty($cut) || empty($color)) {
        $errorMessage = "Gemstone Type, Cut, and Color are required.";
    } else {
        $sql = "INSERT INTO request (type, weight, shape, color, requirement, customer_id) 
                VALUES ('$gemType', '$caratWeight', '$cut', '$color', '$specialRequirements', '$customerID')";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            $successMessage = "Your custom request has been submitted successfully!";
            header("Location: ./customRequest.php?success=1") ;

        } else {
            $errorMessage = "Error: " . $conn->error;
            header("Location: ./customRequest.php?success=2") ;
        }
    }
}

// Close the database connection
$conn->close();
?>