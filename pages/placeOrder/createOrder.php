<?php
session_start();
include('../../database/db.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include('./sendOrderEmail.php'); 

// Check if JSON data is received from PayPal
if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
    $json_data = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($json_data['orderID'])) {
        die(json_encode(["success" => false, "message" => "Invalid PayPal response."]));
    }

    // Override POST variables from JSON
    $_POST['payment-method'] = 'pay-online';  
    $_POST['shipping-method'] = $json_data['shipping_method'] ?? 'store-pickup';
    $_POST['pickup-date'] = $json_data['pickup_date'] ?? null;
    $_POST['address1'] = $json_data['address1'] ?? null;
    $_POST['address2'] = $json_data['address2'] ?? null;
    $_POST['city'] = $json_data['city'] ?? null;
    $_POST['postalCode'] = $json_data['postalCode'] ?? null;
    $_POST['country'] = $json_data['country'] ?? null;
}


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
        SELECT cart.stone_id, inventory.amount AS price, inventory.type, inventory.shape, inventory.size, inventory.colour 
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

            // Prepare inventory update query
            $update_inventory_query = "UPDATE inventory SET availability = 'not Available' WHERE stone_id = ?";
            $update_inventory_stmt = $conn->prepare($update_inventory_query);

            // Prepare variables for email content
            $order_items = [];
            $total_amount = 0;

            while ($cart_item = $cart_result->fetch_assoc()) {
                $stone_id = $cart_item['stone_id'];
                $price = $cart_item['price']; // Price fetched from inventory
                $total_amount += $price;

                // Insert into order_items
                $order_items_stmt->bind_param("iid", $order_id, $stone_id, $price);
                $order_items_stmt->execute();

                // Update inventory availability
                $update_inventory_stmt->bind_param("i", $stone_id);
                $update_inventory_stmt->execute();

                // Collect the product details for email
                $order_items[] = [
                    'type' => $cart_item['type'],
                    'shape' => $cart_item['shape'],
                    'size' => $cart_item['size'],
                    'colour' => $cart_item['colour'],
                    'price' => number_format($price, 2) . " LKR"
                ];
            }

            // Clear the cart
            $clear_cart_query = "DELETE FROM cart WHERE customer_id = ?";
            $clear_cart_stmt = $conn->prepare($clear_cart_query);
            $clear_cart_stmt->bind_param("i", $customer_id);
            $clear_cart_stmt->execute();

            // Fetch customer name and email for email sending
            $customer_query = "SELECT firstName, email FROM customer WHERE customer_id = ?";
            $customer_stmt = $conn->prepare($customer_query);
            $customer_stmt->bind_param("i", $customer_id);
            $customer_stmt->execute();
            $customer_result = $customer_stmt->get_result();
            $customer = $customer_result->fetch_assoc();

            // Debugging customer data
            if (!$customer) {
                die("Customer details not found.");
            }

            // Output customer and order details before sending email
            echo "<h3>Order Details for Customer: {$customer['firstName']} ({$customer['email']})</h3>";
            echo "<p><strong>Shipping Method:</strong> $shipping_method</p>";
            echo "<p><strong>Payment Method:</strong> $payment_method</p>";
            echo "<p><strong>Total Amount:</strong> " . number_format($total_amount, 2) . " LKR</p>";

            echo "<h4>Order Items:</h4>";
            echo "<table border='1'>";
            echo "<tr><th>Product</th><th>Shape</th><th>Size</th><th>Color</th><th>Price</th></tr>";
            foreach ($order_items as $item) {
                echo "<tr>";
                echo "<td>{$item['type']}</td>";
                echo "<td>{$item['shape']}</td>";
                echo "<td>{$item['size']}</td>";
                echo "<td>{$item['colour']}</td>";
                echo "<td>{$item['price']}</td>";
                echo "</tr>";
            }
            echo "</table>";

            // Send the order confirmation email
            sendOrderEmail($customer['firstName'], $customer['email'], [
                'order_id' => $order_id,
                'order_date' => date('Y-m-d'),  // Ensure the date is in correct format
                'payment_method' => $payment_method,
                'shipping_method' => $shipping_method,
                'total_amount' => number_format($total_amount, 2),  // Ensure total amount is formatted correctly
                'products' => $order_items
            ]);

            $conn->commit();
            echo "<br/>Order placed successfully! A confirmation email has been sent.";
        } catch (Exception $e) {
            $conn->rollback();
            die("Error placing order: " . $e->getMessage());
        }
    } else {
        die("Cart is empty.");
    }
}
?>
