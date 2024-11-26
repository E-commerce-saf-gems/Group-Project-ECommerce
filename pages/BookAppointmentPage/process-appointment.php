<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../Login/login.php?notloggedIn=1");
    exit;
}

include '../../database/db.php';

$customerID = $_SESSION['customer_id'];
$availableTime_id = $_POST['time'] ?? null;
$appointment_type = $_POST['appointment'] ?? null;

if ($availableTime_id) {
    try {
        // Insert the meeting into the meeting table
        $sql = "INSERT INTO meeting (customer_id, availableTimes_id, type) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $customerID, $availableTime_id, $appointment_type);

        if ($stmt->execute()) {
            // After inserting the appointment, update the availability in availabletimes table to 'reserved'
            $updateSql = "UPDATE availabletimes SET availability = 'reserved' WHERE availableTimes_id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("i", $availableTime_id);

            if ($updateStmt->execute()) {
                // Successfully updated availability to 'reserved'
                header("Location: ./submit-appointment.php?success=1");
            } else {
                // Error updating availability
                header("Location: ./submit-appointment.php?error=updateFailed");
            }

            $updateStmt->close();
        } else {
            // Error inserting appointment into meeting table
            header("Location: ./submit-appointment.php?error=1");
        }

        $stmt->close();
    } catch (Exception $e) {
        error_log("Error processing appointment: " . $e->getMessage());
        header("Location: ./submit-appointment.php?error=1");
    }

    $conn->close();
} else {
    header("Location: ./submit-appointment.php?error=invalidTime");
}
?>