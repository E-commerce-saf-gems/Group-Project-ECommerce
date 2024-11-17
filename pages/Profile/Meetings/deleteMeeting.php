<?php

if (isset($_GET['id'])) {
    $meetingId = intval($_GET['id']);

    // Database connection
    include '../../../database/db.php';

    // SQL to delete the record
    $sql = "DELETE FROM meeting WHERE meeting_id = $meetingId";

    if ($conn->query($sql) === TRUE) {
        // Redirect back with a success message
        header("Location: ./MyMeetings.php?deleteSuccess=1");
        
    } else {
        // Redirect back with an error message
        echo "Error updating record: " . $conn->error;
        header("Location: ./MyMeetings.php?deleteSuccess=2");

    }

    $conn->close();
} else {
    // Redirect if ID is not set
    header("Location: ./meeting.php");
}
?>