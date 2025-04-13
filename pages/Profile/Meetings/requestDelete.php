<?php
include('../../../database/db.php');

if (isset($_GET['id'])) {
    $meetingId = $_GET['id'];

    // Update the status to "Request to Delete"
    $query = "UPDATE meeting SET status = 'R' WHERE meeting_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $meetingId);

    if ($stmt->execute()) {
        // Redirect with success message
        header("Location: ./MyMeetings.php?requestDeleteSuccess=1");
    } else {
        // Redirect with error message
        header("Location: ./MyMeetings.php?requestDeleteSuccess=2");
    }
    $stmt->close();
}
$conn->close();
