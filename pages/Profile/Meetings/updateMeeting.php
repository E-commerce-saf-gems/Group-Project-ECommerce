<?php
include('../../../database/db.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $meeting_id = $_POST['meeting_id'];
    $newAvailableTime_id = $_POST['availableTimes_id'];
    $currentAvailableTime_id = $_POST['currentAvailableTime_id'];

    
    $conn->begin_transaction();

    try {
        
        $updateMeetingQuery = "UPDATE meeting SET availableTimes_id = ? WHERE meeting_id = ?";
        $updateMeetingStmt = $conn->prepare($updateMeetingQuery);
        $updateMeetingStmt->bind_param("ii", $newAvailableTime_id, $meeting_id);

        if (!$updateMeetingStmt->execute()) {
            throw new Exception("Failed to update meeting.");
        }

        
        $updateOldSlotQuery = "UPDATE availabletimes SET availability = 'available' WHERE availableTimes_id = ?";
        $updateOldSlotStmt = $conn->prepare($updateOldSlotQuery);
        $updateOldSlotStmt->bind_param("i", $currentAvailableTime_id);

        if (!$updateOldSlotStmt->execute()) {
            throw new Exception("Failed to update old time slot.");
        }

        
        $updateNewSlotQuery = "UPDATE availabletimes SET availability = 'reserved' WHERE availableTimes_id = ?";
        $updateNewSlotStmt = $conn->prepare($updateNewSlotQuery);
        $updateNewSlotStmt->bind_param("i", $newAvailableTime_id);

        if (!$updateNewSlotStmt->execute()) {
            throw new Exception("Failed to update new time slot.");
        }

        
        $conn->commit();

        
        header("Location: ./MyMeetings.php?success=1");
    } catch (Exception $e) {
        
        $conn->rollback();

        
        error_log("Transaction failed: " . $e->getMessage());
        header("Location: ./MyMeetings.php?error=transactionFailed");
    }

    
    $updateMeetingStmt->close();
    $updateOldSlotStmt->close();
    $updateNewSlotStmt->close();
}


$conn->close();
?>
