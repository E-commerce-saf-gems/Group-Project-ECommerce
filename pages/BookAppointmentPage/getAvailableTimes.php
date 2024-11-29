<?php
include '../../database/db.php';

$date = $_GET['date'] ?? null;

if ($date) {
    try {
        // Correct query to fetch unique times for the given date
        $sql = "SELECT MIN(availableTimes_id) AS availableTime_id, time 
        FROM availabletimes 
        WHERE date = ? AND availability = 'available' 
        GROUP BY time;
        ";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $result = $stmt->get_result();

        $times = [];
        while ($row = $result->fetch_assoc()) {
            $times[] = $row; // Collect both availableTime_id and time
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
