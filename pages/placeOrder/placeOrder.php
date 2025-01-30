<?php
// Start the session and include the database connection
session_start();
include('../../database/db.php');

// Retrieve the logged-in customer's ID
$customer_id = $_SESSION['customer_id'] ?? null;

// Initialize customer details with default empty values
$customer_details = [
    'address1' => '',
    'address2' => '',
    'city' => '',
    'country' => '',
    'postalCode' => '',
];

if ($customer_id) {
    // Fetch customer shipping details from the database
    $customer_sql = "SELECT address1, address2, city, country, postalCode FROM customer WHERE customer_id = ?";
    $customer_stmt = $conn->prepare($customer_sql);
    $customer_stmt->bind_param("i", $customer_id);
    $customer_stmt->execute();
    $customer_result = $customer_stmt->get_result();

    if ($customer_result->num_rows > 0) {
        $customer_details = $customer_result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
    <link rel="stylesheet" href="../../styles/common.css">
    <link rel="stylesheet" href="../../components/header/header.css">
    <link rel="stylesheet" href="../../components/footer/footer.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <title>Confirm Order</title>
</head>
<body id="top">
    <custom-header></custom-header>
    <main class="shipping-container">
        <h1 class="page-title">Shipping Details</h1>
    
        <form class="shipping-form" method="POST" action="./createOrder.php">
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
            <input type="date" id="pickup-date" name="pickup-date" required>
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
            <label for="postalCode">postalCode/Postal Code</label>
            <input type="text" id="postalCode" name="postalCode" value="<?php echo htmlspecialchars($customer_details['postalCode']); ?>" required>
        </div>
        <div class="form-group">
            <label for="country">Country</label>
            <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($customer_details['country']); ?>" required>
        </div>
    </div>

    <div class="radio-group">
        <h2>Select Payment Type</h2>
        <label class="radio-label">
            <input type="radio" name="payment-method" value="pay-online" checked>
            Pay Online
        </label>
        <label class="radio-label pay-in-store-option">
            <input type="radio" name="payment-method" value="pay-in-store">
            Pay In Store (Pickup Only)
        </label>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Proceed to Checkout</button>
    </div>
</form>

    </main>

    <script src="placeOrder.js"></script>
    <script src="../../components/header/header.js"></script>
    <script src="../../components/footer/footer.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
