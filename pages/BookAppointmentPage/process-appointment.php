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
        
        $sql = "INSERT INTO meeting (customer_id, availableTimes_id, type) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $customerID, $availableTime_id, $appointment_type);

        if ($stmt->execute()) {
            
            $updateSql = "UPDATE availabletimes SET availability = 'reserved' WHERE availableTimes_id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("i", $availableTime_id);

            if ($updateStmt->execute()) {
                
                header("Location: ./submit-appointment.php?success=1");
            } else {
                
                header("Location: ./submit-appointment.php?error=updateFailed");
            }

            $updateStmt->close();
        } else {
            
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
