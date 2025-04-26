<?php
session_start();
include('../../../database/db.php'); // Include database connection

if (!isset($_GET['id'])) {
    echo "Order ID is required.";
    exit;
}

$order_id = intval($_GET['id']);
$customer_id = $_SESSION['customer_id'] ?? null;

// Fetch order details
$order_sql = "SELECT * FROM orders WHERE order_id = ?";
$stmt = $conn->prepare($order_sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows === 0) {
    echo "Order not found or access denied.";
    exit;
}

$order = $order_result->fetch_assoc();

// Fetch stone details and order item data
$stones_sql = "
    SELECT i.*
    FROM inventory i
    INNER JOIN order_items oi ON oi.stone_id = i.stone_id
    WHERE oi.order_id = ?
";
$stones_stmt = $conn->prepare($stones_sql);
$stones_stmt->bind_param("i", $order_id);
$stones_stmt->execute();
$stones_result = $stones_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../styles/common.css">
    <link rel="stylesheet" href="./orderDetails.css">
    <link rel="stylesheet" href="../../../components/profileHeader/header.css">
    <link rel="stylesheet" href="../../../components/footer/footer.css">
    <link rel="stylesheet" href="./viewPurchase.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <title>Order Details</title>
</head>
<body>
<custom-header></custom-header>
<div class="order-details-container">

    <!-- Order Header -->
    <div class="order-header">
        <h1>Order #<?php echo $order['order_id']; ?></h1>
        <p><strong>Order Date:</strong> <?php echo $order['order_date']; ?> 
        <span class="status-badge"><?php echo $order['order_status']; ?></span></p>
    </div>

    <!-- Order Summary Table -->
    <div class="order-summary-table">
        <table>
            <thead>
                <tr>
                    <th>Shipping Status</th>
                    <th>Payment Status</th>
                    <th>Payment Method</th>
                    <th>Shipping Method</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Open</td>
                    <td><?php echo $order['order_status']; ?></td>
                    <td><?php echo $order['payment_method']; ?></td>
                    <td><?php echo $order['shipping_method']; ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Product Details Table -->
    <div class="product-details-table">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($stones_result->num_rows > 0): ?>
                    <?php while ($stone = $stones_result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <div class="product-info">
                                    <img src="http://localhost/Group-Project-ECommerce/assets/images/<?php echo htmlspecialchars($stone['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Stone Image" class="product-image">
                                    <div>
                                        <p><strong><?php echo $stone['type']; ?></strong></p>
                                        <p>Shape: <?php echo $stone['shape']; ?></p>
                                        <p>Size: <?php echo $stone['size']; ?> carats</p>
                                        <p>Colour: <?php echo $stone['colour']; ?></p>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo number_format($stone['amount'], 2); ?> LKR</td>
                            <td><?php echo number_format($stone['amount'], 2); ?> LKR</td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4">No products found for this order.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Order Summary Footer -->
    <div class="order-summary-footer">
        <table>
            <tbody>
                <tr>
                    <td><strong>Order Date:</strong></td>
                    <td><?php echo $order['order_date']; ?></td>
                </tr>
                <tr>
                    <td><strong>Order Number:</strong></td>
                    <td><?php echo $order['order_id']; ?></td>
                </tr>
                <tr>
                    <td><strong>Payment Method:</strong></td>
                    <td><?php echo $order['payment_method']; ?></td>
                </tr>
                <tr>
                    <td><strong>Shipping Method:</strong></td>
                    <td><?php echo $order['shipping_method']; ?></td>
                </tr>
                <tr>
                    <td><strong>Total (Gross):</strong></td>
                    <td><?php echo number_format($order['total_amount'], 2); ?> LKR</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script src="../../../components/profileHeader/header.js"></script>
<script src="../../../components/footer/footer.js"></script>
</body>
</html>
