<?php
include('../../database/db.php'); 
session_start();

if (!isset($_SESSION['customer_id']) || empty($_SESSION['customer_id'])) {
    header("Location: ../Login/login.php?notloggedIn=1");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$stone_id = $_GET['stone_id'] ?? $_POST['stone_id'] ?? null;

if (!$stone_id) {
    die("Stone ID is missing.");
}

// Step 1: Check if this is a bidding stone
$biddingStoneQuery = "SELECT biddingStone_id FROM biddingstone WHERE stone_id = ?";
$biddingStmt = $conn->prepare($biddingStoneQuery);
$biddingStmt->bind_param("i", $stone_id);
$biddingStmt->execute();
$biddingResult = $biddingStmt->get_result();
$biddingRow = $biddingResult->fetch_assoc();

if ($biddingRow) {
    // It's a bidding stone
    $biddingStone_id = $biddingRow['biddingStone_id'];

    // Step 2: Get the highest bid
    $bidQuery = "SELECT MAX(amount) AS highestBid FROM bid WHERE biddingStone_id = ?";
    $bidStmt = $conn->prepare($bidQuery);
    $bidStmt->bind_param("i", $biddingStone_id);
    $bidStmt->execute();
    $bidResult = $bidStmt->get_result();
    $bidRow = $bidResult->fetch_assoc();
    $highestBid = $bidRow['highestBid'] ?? null;

    // Step 3: Update inventory price if there's a bid
    if ($highestBid) {
        $updateInventoryQuery = "UPDATE inventory SET amount = ? WHERE stone_id = ?";
        $updateInventoryStmt = $conn->prepare($updateInventoryQuery);
        $updateInventoryStmt->bind_param("di", $highestBid, $stone_id);
        $updateInventoryStmt->execute();
    }
}

// Step 4: Add to cart (only once)
$checkQuery = "SELECT cart_id FROM cart WHERE customer_id = ? AND stone_id = ?";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bind_param("ii", $customer_id, $stone_id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows === 0) {
    $insertQuery = "INSERT INTO cart (customer_id, stone_id) VALUES (?, ?)";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param("ii", $customer_id, $stone_id);
    $insertStmt->execute();
}

header("Location: ../cart/cart.php");
exit();
?>
