<?php
include('../../database/db.php'); 

session_start();

// Check if the customer is logged in
if (!isset($_SESSION['customer_id']) || empty($_SESSION['customer_id'])) {
    // Redirect to the login page if not logged in
    header("Location: ../Login/login.php?notloggedIn=1");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$stone_id = $_POST['stone_id'];

$sql = "SELECT cart_id FROM cart WHERE customer_id = ? AND stone_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $customer_id, $stone_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $new_quantity = $row['quantity'] + $quantity;

    $update_sql = "UPDATE cart SET quantity = ? WHERE cart_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ii", $new_quantity, $row['cart_id']);
    $update_stmt->execute();
} else {
    $insert_sql = "INSERT INTO cart (customer_id, stone_id) VALUES (?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("ii", $customer_id, $stone_id);
    $insert_stmt->execute();
}

header("Location: ../cart/cart.php");
exit();
?>
