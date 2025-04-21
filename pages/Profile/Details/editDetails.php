<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: ../Login/login.php?notloggedIn=1");
    exit;
}

$customerID = $_SESSION['customer_id'];

// Include database connection
include './db.php';

try {
    // Fetch customer details
    $sqlCustomer = "SELECT firstname, lastname, email, contactNo FROM customer WHERE customer_id = ?";
    $stmt = $conn->prepare($sqlCustomer);
    $stmt->bind_param("i", $customerID);
    $stmt->execute();
    $stmt->bind_result($FirstName, $LastName, $Email, $Phone);
    $stmt->fetch();
    $stmt->close();

    // Fetch billing address
    $sqlAddress = "SELECT address1, address2, city, country, postalCode FROM customer WHERE customer_id = ?";
    $stmt = $conn->prepare($sqlAddress);
    $stmt->bind_param("i", $customerID);
    $stmt->execute();
    $stmt->bind_result($Address1, $Address2, $city, $country, $postalCode);
    $stmt->fetch();
    $stmt->close();
} catch (Exception $e) {
    error_log("Error fetching customer details: " . $e->getMessage());
    die("An error occurred. Please try again later.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Details</title>
    
    <link rel="stylesheet" href="../../../styles/common.css">
    <link rel="stylesheet" href="../profile.css">
    <link rel="stylesheet" href="./bids.css">
    <link rel="stylesheet" href="../../../components/profileHeader/header.css">
    <link rel="stylesheet" href="../../../components/footer/footer.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preload" as="image" href="../../../assets/images/logo.png">
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
                <li><a href="../Purchases/MyPurchases.html">Purchases</a></li>
                <li><a href="../Requests/MyRequest.php">Requests</a></li>
                <li><a href="../Emails/MyEmails.html">Email Preferences</a></li>
                <li><a href="../../Login/logout.php">Signout</a></li>
            </ul>
        </div>


    <div class="main-content">
        <h1>Edit Your Details</h1>
        <form method="POST" action="updateDetails.php" class="details-form">
            <!-- Personal Details -->
            <h2>Personal Information</h2>
            <div class="details-form">
                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($FirstName); ?>" required>
            </div>
            <div class="details-form">
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($LastName); ?>" required>
            </div>
            <div class="details-form">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($Email); ?>" required>
            </div>
            <div class="details-form">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="contactNo" value="<?php echo htmlspecialchars($Phone); ?>" required>
            </div>

            <!-- Billing Address -->
            <h2>Billing Address</h2>
            <div class="details-form">
                <label for="billingAddress1">Address Line 1</label>
                <input type="text" id="billingAddress1" name="address1" value="<?php echo htmlspecialchars($Address1); ?>" required>
            </div>
            <div class="details-form">
                <label for="billingAddress2">Address Line 2</label>
                <input type="text" id="billingAddress2" name="address2" value="<?php echo htmlspecialchars($Address2); ?>">
            </div>
            <div class="details-form">
                <label for="billingCity">City</label>
                <input type="text" id="billingCity" name="city" value="<?php echo htmlspecialchars($city); ?>" required>
            </div>
            <div class="details-form">
                <label for="billingCountry">Country</label>
                <input type="text" id="billingCountry" name="country" value="<?php echo htmlspecialchars($country); ?>" required>
            </div>
            <div class="details-form">
                <label for="billingPostalCode">Postal Code</label>
                <input type="text" id="billingPostalCode" name="postalCode" value="<?php echo htmlspecialchars($postalCode); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>

    <script src="../../../components/profileHeader/header.js"></script>
    <script src="../../components/footer/footer.js"></script>
    <script src="./profile.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>
</html> 