<?php
session_start();
include('../../database/db.php');

$customer_id = $_SESSION['customer_id'] ?? null;

$customer_details = [
    'address1' => '',
    'address2' => '',
    'city' => '',
    'country' => '',
    'postalCode' => '',
];

if ($customer_id) {
    $customer_sql = "SELECT address1, address2, city, country, postalCode FROM customer WHERE customer_id = ?";
    $customer_stmt = $conn->prepare($customer_sql);
    $customer_stmt->bind_param("i", $customer_id);
    $customer_stmt->execute();
    $customer_result = $customer_stmt->get_result();

    if ($customer_result->num_rows > 0) {
        $customer_details = $customer_result->fetch_assoc();
    }
}

$total_amount = 0;
if ($customer_id) {
    $cart_sql = "SELECT SUM(inventory.amount) AS total FROM cart 
                 INNER JOIN inventory ON cart.stone_id = inventory.stone_id 
                 WHERE cart.customer_id = ?";
    $cart_stmt = $conn->prepare($cart_sql);
    $cart_stmt->bind_param("i", $customer_id);
    $cart_stmt->execute();
    $cart_result = $cart_stmt->get_result();

    if ($row = $cart_result->fetch_assoc()) {
        $total_amount = $row['total'] ?? 0;
    }
}

$exchange_rate = 0.0031;
$total_usd = number_format($total_amount * $exchange_rate, 2, '.', '');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Order</title>
    <link rel="stylesheet" href="./styles.css">
    <link rel="stylesheet" href="../../styles/common.css">
    <link rel="stylesheet" href="../../components/header/header.css">
    <link rel="stylesheet" href="../../components/footer/footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body id="top">
    <custom-header></custom-header>
    <main class="shipping-container">
        <h1 class="page-title">Shipping Details</h1>
    
        <form id="checkoutForm" class="shipping-form" method="POST" action="./createOrder.php">
            <div class="radio-group">
                <label class="radio-label">
                    <input type="radio" name="shipping-method" value="store-pickup" checked>
                    Store Pickup
                </label>
                <label class="radio-label">
                    <input type="radio" name="shipping-method" value="home-shipping">
                    Home Shipping
                </label>
            </div>

            <div class="pickup-date">
                <h2>Select Pickup Date</h2>
                <div class="form-group">
                    <label for="pickup-date">Pickup Date</label>
                    <input type="date" id="pickup-date" name="pickup-date" min="" required>
                </div>
            </div>

            <div class="shipping-address hidden">
                <h2>Enter Shipping Address</h2>
                <div class="form-group">
                    <label for="address1">Address Line 1</label>
                    <input type="text" id="address1" name="address1" value="<?php echo htmlspecialchars($customer_details['address1']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="address2">Address Line 2</label>
                    <input type="text" id="address2" name="address2" value="<?php echo htmlspecialchars($customer_details['address2']); ?>"> 
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($customer_details['city']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="postalCode">Postal Code</label>
                    <input type="text" id="postalCode" name="postalCode" value="<?php echo htmlspecialchars($customer_details['postalCode']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($customer_details['country']); ?>" required>
                </div>
            </div>

            <div class="radio-group" style="display: none;">
                <h2>Select Payment Type</h2>
                <label class="radio-label" hidden>
                    <input type="radio" name="payment-method" value="pay-online" checked hidden>
                    Pay Online
                </label>
            </div>

            <input type="hidden" id="total_amount" value="<?php echo $total_usd; ?>">

            <div id="paypal-button-container"></div>
        </form>
    </main>

    <script src="https://www.paypal.com/sdk/js?client-id=AVle4HaFl3fiWsj3VRw3uC-Gb1NwhZ4j632SBgURYzQr8G9oR5KsrpkXUmCleQx3hNTwUnq5ZIdx8VRc&currency=USD"></script>

    <script>
    // Dynamically show/hide shipping or pickup sections
    document.querySelectorAll('input[name="shipping-method"]').forEach(radio => {
        radio.addEventListener('change', (e) => {
            const isPickup = e.target.value === 'store-pickup';
            const isHome = e.target.value === 'home-shipping';

            document.querySelector('.pickup-date').style.display = isPickup ? 'block' : 'none';
            document.getElementById('pickup-date').required = isPickup;

            document.querySelector('.shipping-address').style.display = isHome ? 'block' : 'none';

            ['address1', 'city', 'postalCode', 'country'].forEach(id => {
                document.getElementById(id).required = isHome;
            });
        });
    });

    paypal.Buttons({
        createOrder: function(data, actions) {
            const total = document.getElementById('total_amount').value;

            // Manual validation
            const shippingMethod = document.querySelector('input[name="shipping-method"]:checked').value;
            const pickupDate = document.getElementById('pickup-date').value;

            if (shippingMethod === 'store-pickup' && !pickupDate) {
                alert("Please select a pickup date before proceeding.");
                return actions.reject(); // Stop PayPal flow
            }

            return actions.order.create({
                purchase_units: [{
                    amount: { value: total }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                const form = document.getElementById('checkoutForm');
                const formData = new FormData(form);

                fetch('./createOrder.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        orderID: data.orderID,
                        shipping_method: formData.get('shipping-method'),
                        payment_method: formData.get('payment-method'),
                        pickup_date: formData.get('pickup-date'),
                        address1: formData.get('address1') || '',
                        address2: formData.get('address2') || '',
                        city: formData.get('city') || '',
                        postalCode: formData.get('postalCode') || '',
                        country: formData.get('country') || ''
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Payment successful! Redirecting...');
                        window.location.href = "./orderSuccess.php";
                    } else {
                        alert('Error processing order: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("Payment processed, but order failed. Contact support.");
                });
            });
        }
    }).render('#paypal-button-container');
</script>
<script>
    window.addEventListener('DOMContentLoaded', () => {
        const pickupInput = document.getElementById('pickup-date');
        const now = new Date();

        // Add 24 hours (1 day)
        now.setDate(now.getDate() + 1);

        // Format to YYYY-MM-DD
        const minDate = now.toISOString().split('T')[0];

        pickupInput.min = minDate;
    });
</script>

    <script src="placeOrder.js"></script>
    <script src="../../components/header/header.js"></script>
    <script src="../../components/footer/footer.js"></script>
</body>
</html>
