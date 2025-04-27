<?php
include '../../database/db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Auction Winner Update</h2>";

$query = "SELECT biddingStone_id 
          FROM biddingstone 
          WHERE finishDate < NOW() 
          AND customer_id = 0";
          
$result = $conn->query($query);

if (!$result) {
    echo "Error with query: " . $conn->error;
    exit;
}

if ($result->num_rows > 0) {
    echo "Found " . $result->num_rows . " ended auctions to process.<br><br>";
    
    while ($row = $result->fetch_assoc()) {
        $auctionId = $row['biddingStone_id'];
        echo "Processing auction #$auctionId: ";
        
        $bidQuery = "SELECT customer_id, amount 
                    FROM bid 
                    WHERE biddingStone_id = $auctionId 
                    ORDER BY amount DESC 
                    LIMIT 1";
                    
        $bidResult = $conn->query($bidQuery);
        
        if (!$bidResult) {
            echo "Error with bid query: " . $conn->error;
            continue;
        }
        
        if ($bidResult->num_rows > 0) {
            $winner = $bidResult->fetch_assoc();
            $winnerId = $winner['customer_id'];
            $winningBid = $winner['amount'];
            
            $updateQuery = "UPDATE biddingstone 
                          SET customer_id = $winnerId 
                          WHERE biddingStone_id = $auctionId";
                          
            if ($conn->query($updateQuery)) {
                echo "<span style='color:green'>Winner set to customer #$winnerId with bid of Rs.$winningBid</span><br>";
            } else {
                echo "<span style='color:red'>Error updating database: " . $conn->error . "</span><br>";
            }
        } else {
            echo "<span style='color:orange'>No bids found for auction #$auctionId</span><br>";
        }
    }
} else {
    echo "No ended auctions with customer_id = 0 were found.<br>";
}

echo "<br><br><strong>Server time:</strong> " . date('Y-m-d H:i:s');

$conn->close();
?>
