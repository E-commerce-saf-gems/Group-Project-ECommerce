<?php
session_start();
include('../../database/db.php');

$customer_id = $_SESSION['customer_id'] ?? null;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$customer_id) {
        header("Location: ../Login/login.php?notloggedIn=1");
        exit();
    }

    // Capture form inputs
    $shipping_method = $_POST['shipping-method'];
    $pickup_date = $_POST['pickup-date'] ?? null;
    $address1 = $_POST['address1'] ?? null;
    $address2 = $_POST['address2'] ?? null;
    $city = $_POST['city'] ?? null;
    $postalCode = $_POST['postalCode'] ?? null;
    $country = $_POST['country'] ?? null;
    $payment_method = $_POST['payment-method'];

    // Fetch cart items for the customer
    $cart_query = "
        SELECT cart.stone_id, inventory.amount AS price 
        FROM cart 
        INNER JOIN inventory ON cart.stone_id = inventory.stone_id 
        WHERE cart.customer_id = ?";
    $cart_stmt = $conn->prepare($cart_query);
    $cart_stmt->bind_param("i", $customer_id);
    $cart_stmt->execute();
    $cart_result = $cart_stmt->get_result();

    if ($cart_result->num_rows > 0) {
        $conn->begin_transaction();

        try {
            // Insert into orders table
            $order_query = "
                INSERT INTO orders (customer_id, shipping_method, pickup_date, address1, address2, city, postalCode, country, payment_method, order_status, order_date) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())";
            $order_stmt = $conn->prepare($order_query);
            $order_stmt->bind_param(
                "issssssss",
                $customer_id,
                $shipping_method,
                $pickup_date,
                $address1,
                $address2,
                $city,
                $postalCode,
                $country,
                $payment_method
            );
            $order_stmt->execute();
            $order_id = $conn->insert_id;

            // Insert items into order_items table
            $order_items_query = "INSERT INTO order_items (order_id, stone_id, price) VALUES (?, ?, ?)";
            $order_items_stmt = $conn->prepare($order_items_query);

            while ($cart_item = $cart_result->fetch_assoc()) {
                $stone_id = $cart_item['stone_id'];
                $price = $cart_item['price']; // Price fetched from inventory
                $order_items_stmt->bind_param("iid", $order_id, $stone_id, $price);
                $order_items_stmt->execute();
            }

            // Clear the cart
            $clear_cart_query = "DELETE FROM cart WHERE customer_id = ?";
            $clear_cart_stmt = $conn->prepare($clear_cart_query);
            $clear_cart_stmt->bind_param("i", $customer_id);
            $clear_cart_stmt->execute();

            $conn->commit();
            echo "Order placed successfully!";
        } catch (Exception $e) {
            $conn->rollback();
            die("Error placing order: " . $e->getMessage());
        }
    } else {
        die("Cart is empty.");
    }
}
?>
