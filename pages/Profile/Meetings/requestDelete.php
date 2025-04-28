<?php
include('../../../database/db.php');

if (isset($_GET['id'])) {
    $meetingId = $_GET['id'];

    
    $query = "UPDATE meeting SET status = 'R' WHERE meeting_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $meetingId);

    if ($stmt->execute()) {
        
        header("Location: ./MyMeetings.php?requestDeleteSuccess=1");
    } else {
        
        header("Location: ./MyMeetings.php?requestDeleteSuccess=2");
    }
    $stmt->close();
}
$conn->close();
