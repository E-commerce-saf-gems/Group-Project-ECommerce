<?php
include('../../../database/db.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $meeting_id = $_POST['meeting_id'];
    $type = $_POST['appointment'];
    $date = $_POST['date'];
    $time = $_POST['time'];
   

    // Update query
    $sql = "UPDATE meeting SET  type=?, date=?, time=? WHERE meeting_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $type, $date, $time, $meeting_id);


    if ($stmt->execute()) {
        echo "Record updated successfully.";
        header("Location: ./MyMeetings.php?editSuccess=1"); 
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
        header("Location: ./MyMeetings.php?editSuccess=2"); 

    }
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
