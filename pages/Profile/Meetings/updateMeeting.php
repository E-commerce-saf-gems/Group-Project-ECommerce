<?php
include('../../../database/db.php');
session_start();

if (!isset($_POST['meeting_id'], $_POST['availableTimes_id'], $_POST['type'])) {
    header("Location: ./MyMeetings.php?editSuccess=2");
    exit;
}

$meeting_id = $_POST['meeting_id'];
$new_availableTimes_id = $_POST['availableTimes_id'];
$new_type = $_POST['type'];

// Fetch the current availableTimes_id
$sql = "SELECT availableTimes_id FROM meeting WHERE meeting_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $meeting_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$old_availableTimes_id = $row['availableTimes_id'];

// Begin transaction
$conn->begin_transaction();

try {
    // Update the meeting with the new time slot and type
    $sql = "UPDATE meeting SET availableTimes_id = ?, type = ? WHERE meeting_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $new_availableTimes_id, $new_type, $meeting_id);
    $stmt->execute();

    // Set the old slot to 'available'
    $sql = "UPDATE availabletimes SET availability = 'available' WHERE availableTimes_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $old_availableTimes_id);
    $stmt->execute();

    // Set the new slot to 'reserved'
    $sql = "UPDATE availabletimes SET availability = 'reserved' WHERE availableTimes_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $new_availableTimes_id);
    $stmt->execute();

    $conn->commit();
    header("Location: ./MyMeetings.php?editSuccess=1");
} catch (Exception $e) {
    $conn->rollback();
    header("Location: ./updateMeeting.php");
}
?>
