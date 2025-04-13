<?php
include('../../../database/db.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $meeting_id = $_POST['meeting_id'];
    $newAvailableTime_id = $_POST['availableTimes_id'];
    $currentAvailableTime_id = $_POST['currentAvailableTime_id'];

    // Start transaction to ensure data integrity
    $conn->begin_transaction();

    try {
        // Step 1: Update `meeting` table to use the new time slot
        $updateMeetingQuery = "UPDATE meeting SET availableTimes_id = ? WHERE meeting_id = ?";
        $updateMeetingStmt = $conn->prepare($updateMeetingQuery);
        $updateMeetingStmt->bind_param("ii", $newAvailableTime_id, $meeting_id);

        if (!$updateMeetingStmt->execute()) {
            throw new Exception("Failed to update meeting.");
        }

        // Step 2: Mark the old time slot as 'available'
        $updateOldSlotQuery = "UPDATE availabletimes SET availability = 'available' WHERE availableTimes_id = ?";
        $updateOldSlotStmt = $conn->prepare($updateOldSlotQuery);
        $updateOldSlotStmt->bind_param("i", $currentAvailableTime_id);

        if (!$updateOldSlotStmt->execute()) {
            throw new Exception("Failed to update old time slot.");
        }

        // Step 3: Mark the new time slot as 'reserved'
        $updateNewSlotQuery = "UPDATE availabletimes SET availability = 'reserved' WHERE availableTimes_id = ?";
        $updateNewSlotStmt = $conn->prepare($updateNewSlotQuery);
        $updateNewSlotStmt->bind_param("i", $newAvailableTime_id);

        if (!$updateNewSlotStmt->execute()) {
            throw new Exception("Failed to update new time slot.");
        }

        // Commit transaction
        $conn->commit();

        // Redirect with success message
        header("Location: ./MyMeetings.php?success=1");
    } catch (Exception $e) {
        // Roll back the transaction in case of error
        $conn->rollback();

        // Log error and redirect with error message
        error_log("Transaction failed: " . $e->getMessage());
        header("Location: ./MyMeetings.php?error=transactionFailed");
    }

    // Close statements
    $updateMeetingStmt->close();
    $updateOldSlotStmt->close();
    $updateNewSlotStmt->close();
}

// Close the connection
$conn->close();
?>
