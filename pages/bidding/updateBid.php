<?php
include '../../database/db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $biddingStoneId = intval($_POST['biddingStoneId']);
    $newBid = floatval($_POST['newBid']);

    $query = "SELECT currentBid FROM biddingstone WHERE biddingStone_id = $biddingStoneId";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentBid = floatval($row['currentBid']);

        if ($newBid >= $currentBid + 500) {
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