<?php
session_start();

if (!isset($_SESSION['customer_id']) || empty($_SESSION['customer_id'])) {
    header("Location: ../Login/login.php?notloggedIn=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles/common.css">
    <link rel="stylesheet" href="../../components/header/header.css">
    <link rel="stylesheet" href="../../components/footer/footer.css">
    <link rel="stylesheet" href="./styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <title>Order Success</title>
</head>
<body id="top">
    <custom-header></custom-header>

    <main class="success-container">
        <div class="success-card">
            <ion-icon name="checkmark-circle-outline" class="success-icon"></ion-icon>
            <h1>Order Placed Successfully!</h1>
            <p>Thank you for your purchase.</p>
            <a href="../Profile/Purchases/MyPurchases.php" class="btn btn-primary">View My Orders</a>
        </div>
    </main>

    <custom-footer></custom-footer>

    <script src="../../components/header/header.js"></script>
    <script src="../../components/footer/footer.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
