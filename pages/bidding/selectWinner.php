<?php
// update_winners.php
// A very simple script targeting auctions with customer_id = 0

// Include database connection
include '../../database/db.php';

// Enable error display
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Auction Winner Update</h2>";

// Query to find auctions that have finished and have customer_id = 0
$query = "SELECT biddingStone_id 
          FROM biddingstone 
          WHERE finishDate < NOW() 
          AND customer_id = 0";
          
$result = $conn->query($query);

if (!$result) {
    // Check for SQL errors
    echo "Error with query: " . $conn->error;
    exit;
}

if ($result->num_rows > 0) {
    echo "Found " . $result->num_rows . " ended auctions to process.<br><br>";
    
    // Process each auction
    while ($row = $result->fetch_assoc()) {
        $auctionId = $row['biddingStone_id'];
        echo "Processing auction #$auctionId: ";
        
        // Find the highest bid for this auction
        $bidQuery = "SELECT customer_id, amount 
                    FROM bid 
                    WHERE biddingStone_id = $auctionId 
                    ORDER BY amount DESC 
                    LIMIT 1";
                    
        $bidResult = $conn->query($bidQuery);
        
        if (!$bidResult) {
            // Check for SQL errors
            echo "Error with bid query: " . $conn->error;
            continue;
        }
        
        if ($bidResult->num_rows > 0) {
            // Got a winner! Update the database
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

// Display server time
echo "<br><br><strong>Server time:</strong> " . date('Y-m-d H:i:s');

// Close connection
$conn->close();
?>
