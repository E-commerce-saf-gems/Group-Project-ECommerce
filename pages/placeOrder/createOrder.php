<?php
session_start();
include('../../database/db.php');

$customer_id = $_SESSION['customer_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || !$customer_id) {
        echo json_encode(["success" => false, "message" => "Missing data or not logged in."]);
        exit;
    }

    $shipping_method = $data['shipping_method'] ?? 'store-pickup';
    $payment_method = $data['payment_method'] ?? 'pay-online';
    $pickup_date = $data['pickup_date'] ?? null;
    $address1 = $data['address1'] ?? '';
    $address2 = $data['address2'] ?? '';
    $city = $data['city'] ?? '';
    $postalCode = $data['postalCode'] ?? '';
    $country = $data['country'] ?? '';

    // Calculate total from cart
    $total_sql = "SELECT SUM(inventory.amount) AS total FROM cart 
                  INNER JOIN inventory ON cart.stone_id = inventory.stone_id 
                  WHERE cart.customer_id = ?";
    $stmt = $conn->prepare($total_sql);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $total_amount = $row['total'] ?? 0;
    $stmt->close();

    // Insert order
    $insert_sql = "INSERT INTO orders (customer_id, shipping_method, payment_method, pickup_date, address1, address2, city, postalCode, country, total_amount) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("issssssssd", $customer_id, $shipping_method, $payment_method, $pickup_date, $address1, $address2, $city, $postalCode, $country, $total_amount);

    if ($stmt->execute()) {
        $order_id = $conn->insert_id;
        $stmt->close();

        // Get all stone_ids from cart
        $cart_sql = "SELECT inventory.stone_id FROM cart 
                     INNER JOIN inventory ON cart.stone_id = inventory.stone_id 
                     WHERE cart.customer_id = ?";
        $stmt = $conn->prepare($cart_sql);
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $stone_id = $row['stone_id'];
            $updateStmt = $conn->prepare("UPDATE inventory SET availability = 'notAvailable' WHERE stone_id = ?");
            $updateStmt->bind_param("i", $stone_id);
            $updateStmt->execute();
            $updateStmt->close();
        }

        // 🧹 Clear the cart
        $delete_cart_sql = "DELETE FROM cart WHERE customer_id = ?";
        $stmt = $conn->prepare($delete_cart_sql);
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $stmt->close();

        echo json_encode(["success" => true, "order_id" => $order_id]);
    } else {
        echo json_encode(["success" => false, "message" => "Order insert failed."]);
    }
}
?>
