<?php
// Include database connection
include '../../database/db.php';  // Adjust the path to match the location of db.php relative to this file

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Initialize variables for form inputs
$appointmentType = "";
$meetingDate = "";
$meetingTime = "";
$participantName = "";
$customerID = 1; 
$participantEmail = "";
$status = 'P';  // Default status

$errorMessage = "";
$successMessage = "";

// Check if form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize the input
    $appointmentType = isset($_POST['appointment']) ? htmlspecialchars(trim($_POST['appointment'])) : '';
    $meetingDate = isset($_POST['date']) ? htmlspecialchars(trim($_POST['date'])) : '';
    $meetingTime = isset($_POST['time']) ? htmlspecialchars(trim($_POST['time'])) : '';
    //$participantName = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    //$participantEmail = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';

    // Basic validation
    if (empty($appointmentType) || empty($meetingDate) || empty($meetingTime) ) {
        $errorMessage = "All fields are required.";
    } else {
        // Construct the SQL query
        $sql = "INSERT INTO meeting (type, date, time, customer_id) 
                VALUES ('$appointmentType', '$meetingDate', '$meetingTime','$customerID')";

        echo $sql;

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            $successMessage = "Your meeting request has been submitted successfully!";
            echo $successMessage ;
        } else {
            $errorMessage = "Error: " . $conn->error;
            echo $errorMessage ;
        }
    }
}

// Close the database connection
$conn->close();
?>
