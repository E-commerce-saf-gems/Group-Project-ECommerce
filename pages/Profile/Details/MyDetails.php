<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../Login/login.php?notloggedIn=1");
    exit;
}

$customerID = $_SESSION['customer_id'];
$customerFirstName = "";
$customerLastName = "";
$customerEmail = "";
$customerPhone = "";
$billingAddress1 = "";
$billingAddress2 = "";
$billingcity = "";
$billingcountry= "";
$billingpostalCode ="";

include './db.php';

try {
    // Fetch user details
    $sql = "SELECT firstname, lastname, email, contactNo FROM customer WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customerID);
    $stmt->execute();
    $stmt->bind_result($customerFirstName, $customerLastName, $customerEmail, $customerPhone);
    $stmt->fetch();
    $stmt->close();

    // Fetch addresses
    $sqlAddress = "SELECT address1, address2,city,country,postalCode FROM customer WHERE customer_id = ?";
    $stmt = $conn->prepare($sqlAddress);
    $stmt->bind_param("i", $customerID);
    $stmt->execute();
    $stmt->bind_result($billingAddress1,$billingAddress2,$billingcity,$billingcountry,$billingpostalCode );
    $stmt->fetch();
    $stmt->close();
} catch (Exception $e) {
    error_log("Error fetching customer details: " . $e->getMessage());
}

$conn->close();
?>

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
    <link rel="preload" as="image" href="../../../assets/images/logo.png">
    <title>My Details</title>
    <style>
        <style>

        .details-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.details-table th, .details-table td {
    padding: 12px 20px;
    vertical-align: top;
}

.details-table th {
    font-weight: bold;
    background-color: #f4f4f4; /* Light gray background for headers */
    text-align: left;
    color: #333; /* Dark color for text */
    border-bottom: 2px solid #ddd; /* Bottom border to separate header from rows */
}

.details-table td {
    background-color: #fff; /* White background for table data */
    color: #555; /* Slightly lighter text color for rows */
    border-bottom: 1px solid #f0f0f0; /* Light border between rows */
}

.details-table td:first-child {
    font-weight: bold;
    color: #333; /* Darker color for the first column */
}

/* Add some hover effect for rows */
.details-table tr:hover {
    background-color: #f9f9f9; /* Light hover effect */
}

.details-table td, .details-table th {
    padding: 15px;
}


    .address-section {
        margin-top: 40px;
    }

    .address {
        border: 1px solid #c2e2e2;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 10px;
        background-color: #f1fafa;
    }

    .address h3 {
        margin-bottom: 10px;
        font-size: 20px;
        color: #337c7c;
    }

    .btn-primary {
        margin-top: 10px;
        padding: 10px 20px;
        background-color: #449b9b;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #337a7a;
    }
</style>

    </style>
</head>
<body>
    <custom-header></custom-header>

    <div class="profile-container profile-h1">
        <div class="profile-sidebar">
            <h2 class="profile-h1">Hello</h2>
            <ul>
                <li><a href="../Details/MyDetails.php" class="active">My Details</a></li>
                <li><a href="../Bids/MyBids.html">My Bids</a></li>
                <li><a href="../Wishlist/MyWishlist.html">My Wishlist</a></li>
                <li><a href="../Sales/MySales.html">My Sales</a></li>
                <li><a href="../Meetings/MyMeetings.php">My Meetings</a></li>
                <li><a href="../Purchases/MyPurchases.php">Purchases</a></li>
                <li><a href="../Requests/MyRequest.php">Requests</a></li>
                <li><a href="../Emails/MyEmails.html">Email Preferences</a></li>
                <li><a href="../../Login/logout.php">Signout</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <h1 class="h1">My Account</h1>
            <h2 class="h2">My Details</h2>
            
           
            
           <form class="details-form" method="POST" action="editDetails.php">
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($customerFirstName); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($customerLastName); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($customerEmail); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($customerPhone); ?>" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Update Details</button>
            </form>
            <br>
            <!--<h2 class="h2">Password</h2>
            <form class="password-form">
                <div class="form-group">
                    <label for="currentPassword">Current Password</label>
                    <input type="password" id="currentPassword" name="currentPassword" required>
                </div>
                
                <div class="form-group">
                    <label for="newPassword">New Password</label>
                    <input type="password" id="newPassword" name="newPassword" required>
                    <ul>
                        <div>
                            <li>One capital letter</li>
                            <li>lowercase letter</li> 
                        </div>
                        <div>
                            <li>One number</li>
                            <li>One special character</li>
                        </div>
                    </ul>
                </div>
                
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Update Password</button>
            </form>-->
            
            <h2>Billing & Shipping Details</h2>
            <form class="details-form" method="POST" action="editAddress.php">
            <div class="address-section">
                <div class="address">
                    <h3>Billing Address</h3>
                    <p><?php echo htmlspecialchars($billingAddress1 ?? 'Not available'); ?></p>
                    <p><?php echo htmlspecialchars($billingAddress2 ?? 'Not available'); ?></p>
                    <p><?php echo htmlspecialchars($billingcity ?? 'Not available'); ?></p>
                    <p><?php echo htmlspecialchars($billingcountry ?? 'Not available'); ?></p>
                    <p><?php echo htmlspecialchars($billingpostalCode ?? 'Not available'); ?></p>
                   <!-- <button class="btn btn-primary">Edit Address</button> -->
                </div>
                <br>
                <div class="address">
                    <h3>Shipping Address</h3>
                    <p><?php echo htmlspecialchars($billingAddress1 ?? 'Not available'); ?></p>
                    <p><?php echo htmlspecialchars($billingAddress2 ?? 'Not available'); ?></p>
                    <p><?php echo htmlspecialchars($billingcity ?? 'Not available'); ?></p>
                    <p><?php echo htmlspecialchars($billingcountry ?? 'Not available'); ?></p>
                    <p><?php echo htmlspecialchars($billingpostalCode ?? 'Not available'); ?></p>
                    <button class="btn btn-primary">Edit Address</button>
                </div>
            </div>
        </div>
</form>
    </div> 

    <script src="../../../components/profileHeader/header.js"></script>
    <script src="../../../components/footer/footer.js"></script>
    <script src="../profile.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
