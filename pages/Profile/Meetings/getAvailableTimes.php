<?php
include '../../database/db.php';

$date = $_GET['date'] ?? null;

if ($date) {
    try {
        // Query to fetch available times for the given date
        $sql = "SELECT availableTimes_id, time 
                FROM availabletimes 
                WHERE date = ? AND status = 'available'";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $result = $stmt->get_result();

        $times = [];
        while ($row = $result->fetch_assoc()) {
            $times[] = $row;
        }

        $stmt->close();
        $conn->close();

        echo json_encode([
            'success' => true,
            'times' => $times,
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error fetching times: ' . $e->getMessage(),
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid date provided',
    ]);
}
?>
