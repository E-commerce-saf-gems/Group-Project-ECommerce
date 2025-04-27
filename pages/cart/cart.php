<?php

include('../../database/db.php'); 

session_start();

if (!isset($_SESSION['customer_id']) || empty($_SESSION['customer_id'])) {
    header("Location: ../Login/login.php?notloggedIn=1");
    exit();
}

$customer_id = $_SESSION['customer_id'];

$sql = "SELECT cart.cart_id, inventory.type, inventory.amount, inventory.image
        FROM cart
        JOIN inventory ON cart.stone_id = inventory.stone_id
        WHERE cart.customer_id = ?";
        
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL error: " . $conn->error);
}

$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles/common.css">
    <link rel="stylesheet" href="../../components/header/header.css">
    <link rel="stylesheet" href="../../components/footer/footer.css">
    <link rel="stylesheet" href="./cart.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <title>Custom Requests</title>
</head>
<body id="top">
    <custom-header></custom-header>
    <div class="cart-title h1">Your Cart</div>
    <main class="cart-container">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody id="cart-items">
    <?php
        $subtotal = 0;
        $hasItems = false; 

        while ($row = $result->fetch_assoc()) {
            $hasItems = true; 
            $product_name = htmlspecialchars($row['type']);
            $product_price = htmlspecialchars($row['amount']);
            $product_total = $product_price; 
            $subtotal += $product_total; 
            $product_image = htmlspecialchars($row['image']);

            echo "<tr>";
            echo "<td class='product-info'>";
            echo "<img src='http://localhost/Group-Project-ECommerce/assets/images/" . htmlspecialchars($row['image']) . "' alt='Uploaded Image' class='product-image'/>";
            echo "<span class='product-name'>$product_name</span>";
            echo "</td>";
            echo "<td class='product-price'>Rs. " . number_format($product_price, 2) . "</td>";
            echo "<td class='product-total'>Rs. " . number_format($product_total, 2) . "</td>";
            echo "<td class='product-remove'>";
            echo "<button class='header-action-btn' aria-label='user' onclick='confirmDelete(" . $row['cart_id'] . ")'>";
            echo "<ion-icon name='trash-outline'></ion-icon>";
            echo "</button>";
            echo "</td>";
            echo "</tr>";
        }
        
        $total = $subtotal;

        if (!$hasItems) {
            echo "<tr><td colspan='4' class='empty-cart-message'>No items in the cart.</td></tr>";
        }
    ?>
    </tbody>
    </table>

    <div class="cart-summary">
        <h2>Cart Summary</h2>
        <div class="summary-item">
            <span>Subtotal</span>
            <span id="cart-subtotal">Rs. <?php echo number_format($subtotal, 2); ?></span>
        </div>
        <div class="summary-item">
            <span>Total</span>
            <span id="cart-total">Rs. <?php echo number_format($total, 2); ?></span>
        </div>

        <?php if (!$hasItems): ?>
            <p class="no-items-message">No items in the cart.</p>
        <?php elseif ($total > 500000): ?>
            <p class="error-message">⚠️ Cart total exceeds the limit of Rs. 500,000. Please remove some items.</p>
            <button class="btn btn-primary" disabled>Proceed to Checkout</button>
        <?php else: ?>
            <form action="../placeOrder/placeOrder.php" method="POST">
                <input type="hidden" name="total" value="<?php echo htmlspecialchars($total); ?>">
                <button type="submit" class="btn btn-primary">Proceed to Checkout</button>
            </form>

        <?php endif; ?>
    </div>



    </main>
    <script>
        function confirmDelete(cartId) {
            if (!confirm("Are you sure you want to delete this item?")) {
                return;
            }

            fetch('./deleteItem.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `cart_id=${cartId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Item removed successfully!');
                    location.reload(); 
                } else {
                    alert('Error removing item: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error removing item.');
            });
        }
    </script>

    <script src="../../components/header/header.js"></script>
    <script src="../../components/footer/footer.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>