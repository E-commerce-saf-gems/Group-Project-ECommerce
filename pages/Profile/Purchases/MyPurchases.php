<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../styles/common.css">
    <link rel="stylesheet" href="../profile.css">
    <link rel="stylesheet" href="../../../components/profileHeader/header.css">
    <link rel="stylesheet" href="../../../components/footer/footer.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="preload" as="image" href="./assets/images/logo.png">


    <title>My Details</title>
</head>
<body>
    <custom-header></custom-header>

    <div class="profile-container profile-h1">
        <div class="profile-sidebar">
            <h2>Hello</h2>
            <ul>
                <li><a href="../Details/MyDetails.html">My Details</a></li>
                <li><a href="../Bids/MyBids.html">My Bids</a></li>
                <li><a href="../Wishlist/MyWishlist.html" >My Wishlist</a></li>
                <li><a href="../Sales/MySales.html">My Sales</a></li>
                <li><a href="../Meetings/MyMeetings.html">My Meetings</a></li>
                <li><a href="../Purchases/MyPurchases.html" class="active">Purchases</a></li>
                <li><a href="../Requests/MyRequest.php">Requests</a></li>
                <li><a href="../Emails/MyEmails.html">Email Preferences</a></li>
                <li><a href="../../Login/logout.php">Signout</a></li>
            </ul>
        </div>
        
                 <!-- Main Content -->
                <div class="main-content">
                    <h1>My Account</h1>
                    <h2>Invoices</h2>
                    <div class="tab-content">
                    <table class="sales-table">
                        <thead>
                            <tr>
                                <th>Order No</th>
                                <th>Order Date</th>
                                <th>Shipping Method</th>
                                <th>Pick Up Date</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Total Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
        
                            session_start();
        
                            include('../../../database/db.php'); // Include the database connection
                            
                            $customer_id = $_SESSION['customer_id'] ?? null;
        
                            $sql = "SELECT * FROM orders WHERE customer_id=$customer_id"; // Replace `orders` with your actual table name
                            $result = $conn->query($sql);
                
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>" . $row['order_id'] . "</td>
                                            <td>" . $row['order_date'] . "</td>
                                            <td>" . $row['shipping_method'] . "</td>
                                            <td>" . $row['pickup_date'] . "</td>
                                            <td>" . $row['payment_method'] . "</td>
                                            <td>LKR " . number_format($row['total_amount'], 2) . " </td>
                                            <td>" . $row['order_status'] . "</td>
                                            <td class='actions'>";
                
                                    if ($row['order_status'] === 'pending') {
                                        echo "<a href='#' onclick='confirmDeleteOrder(" . $row['order_id'] . ")' class='btn'><i class='bx bx-trash'></i></a>";
                                    }
                                    
                                    echo "<a href='./viewPurchase.php?id=" . $row['order_id'] . "' class='btn'><i class='bx bx-eye'></i></a>";
                                    echo "</td>
                                        </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No orders found.</td></tr>";
                            }
                
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
        
    <script src="../../../components/profileHeader/header.js"></script>
    <script src="../../../components/footer/footer.js"></script>
    <script src="../profile.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    
</body>
</html>