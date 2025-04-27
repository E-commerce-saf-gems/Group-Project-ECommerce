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

$biddingStoneQuery = "SELECT biddingStone_id FROM biddingstone WHERE stone_id = ?";
$biddingStmt = $conn->prepare($biddingStoneQuery);
$biddingStmt->bind_param("i", $stone_id);
$biddingStmt->execute();
$biddingResult = $biddingStmt->get_result();
$biddingRow = $biddingResult->fetch_assoc();

if ($biddingRow) {
    $biddingStone_id = $biddingRow['biddingStone_id'];

    $bidQuery = "SELECT MAX(amount) AS highestBid FROM bid WHERE biddingStone_id = ? AND validity='valid'";
    $bidStmt = $conn->prepare($bidQuery);
    $bidStmt->bind_param("i", $biddingStone_id);
    $bidStmt->execute();
    $bidResult = $bidStmt->get_result();
    $bidRow = $bidResult->fetch_assoc();
    $highestBid = $bidRow['highestBid'] ?? null;

    if ($highestBid) {
        $updateInventoryQuery = "UPDATE inventory SET amount = ? WHERE stone_id = ?";
        $updateInventoryStmt = $conn->prepare($updateInventoryQuery);
        $updateInventoryStmt->bind_param("di", $highestBid, $stone_id);
        $updateInventoryStmt->execute();
    }
}

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
