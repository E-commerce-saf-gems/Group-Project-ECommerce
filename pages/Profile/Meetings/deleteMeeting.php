<?php
include('../../../database/db.php'); // Include the database connection

// Check if the meeting ID is passed
if (isset($_GET['id'])) {
    $meetingId = $_GET['id'];

    // Update the status to 'RD' (Request to Delete)
    $sql = "UPDATE meeting SET status = 'RD' WHERE meeting_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $meetingId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Meeting status updated to RD']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update meeting status']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Meeting ID not provided']);
}
?>
