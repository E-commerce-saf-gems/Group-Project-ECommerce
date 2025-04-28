<?php
session_start();
$customer_id = $_SESSION['customer_id'];

include '../../../database/db.php';

date_default_timezone_set('Asia/Kolkata');
$currentDateTime = date('Y-m-d H:i:s');

echo "Local time is: $currentDateTime<br>";



$checkBidsQuery = "SELECT * FROM bid WHERE customer_id = $customer_id";
$checkBidsResult = mysqli_query($conn, $checkBidsQuery);

echo "Current DateTime: $currentDateTime<br>";

echo "Bids placed by this customer:<br>";
while ($bid = mysqli_fetch_assoc($checkBidsResult)) {
    echo "Stone ID: " . $bid['biddingStone_id'] . " | Amount: " . $bid['amount'] . " | Time: " . $bid['time'] . "<br>";
}
echo "<br><hr><br>";


mysqli_close($conn);
?>
