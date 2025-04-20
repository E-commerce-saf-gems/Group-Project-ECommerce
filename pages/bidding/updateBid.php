<?php
include '../../database/db.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $biddingStoneId = intval($_POST['biddingStoneId']);
    $newBid = floatval($_POST['newBid']);

    // Fetch the current bid
    $query = "SELECT currentBid FROM biddingstone WHERE biddingStone_id = $biddingStoneId";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentBid = floatval($row['currentBid']);

        // Ensure the new bid is valid
        if ($newBid >= $currentBid + 500) {
            // Update the bid in the database
            $updateQuery = "UPDATE biddingstone SET currentBid = $newBid WHERE biddingStone_id = $biddingStoneId";
            if ($conn->query($updateQuery)) {
                echo json_encode(['success' => true]);
                exit;
            }
        }
    }
}

echo json_encode(['success' => false]);
?>