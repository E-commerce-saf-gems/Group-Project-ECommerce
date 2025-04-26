<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../Login/login.php?notloggedIn=1");
    exit;
}

$customerID = $_SESSION['customer_id'];

include './db.php';

try {
    // Fetch user details
    $sql = "SELECT * FROM customer WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customerID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();  // Fetch the result as an associative array
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
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preload" as="image" href="../../../assets/images/logo.png">
    <title>My Details</title>
    <style>
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .details-table th,
        .details-table td {
            padding: 8px 12px;
            /* Reduced padding */
            vertical-align: middle;
            text-align: left;
            /* Align text to the left for better readability */
        }

        .details-table th {
            font-weight: 600;
            /* Slightly lighter font weight */
            background-color:rgb(240, 245, 244);
            /* Slightly softer background */
            color: #333;
            border-bottom: 1px solid #ddd;
            /* Thin border for separation */
        }

        .details-table td {
            background-color: #fff;
            color: black;
            border-bottom: 1px solid #f0f0f0;
        }

        .details-table td:first-child {
            font-weight: 500;
            color: #333;
        }

        .details-table tr:hover {
            background-color: #f7f7f7;
            /* Light hover effect */
        }

        .details-table td,
        .details-table th {
            padding: 10px;
            /* Uniform padding */
        }

        .btn-primary {
            margin-top: 12px;
            padding: 8px 18px;
            /* Smaller button padding */
            background-color: #44a0a0;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 14px;
            /* Smaller font for buttons */
        }

        .btn-primary:hover {
            background-color: #358b8b;
            /* Darker hover state */
        }
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
                <li><a href="../Meetings/MyMeetings.php">My Meetings</a></li>
                <li><a href="../Purchases/MyPurchases.php">Purchases</a></li>
                <li><a href="../Requests/MyRequest.php">Requests</a></li>
                <li><a href="../../Login/logout.php">Signout</a></li>
            </ul>
        </div>

        <div class="main-content">
            <h1 class="h1">My Account</h1>
            <h2 class="h2">My Details</h2>

            <table class="details-table">
                <tr>
                    <th>NIC</th>
                    <td><?php echo htmlspecialchars($row['NIC']); ?></td>
                </tr>
                <tr>
                    <th>Title</th>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                </tr>
                <tr>
                    <th>First Name</th>
                    <td><?php echo htmlspecialchars($row['firstName']); ?></td>
                </tr>
                <tr>
                    <th>Last Name</th>
                    <td><?php echo htmlspecialchars($row['lastName']); ?></td>
                </tr>
                <tr>
                    <th>Contact Number</th>
                    <td><?php echo htmlspecialchars($row['contactNo']); ?></td>
                </tr>
                <tr>
                    <th>Email Address</th>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                </tr>
            </table>
            <a href="editDetails.php">
    <button type="button" class="btn btn-primary">Edit Details</button>
</a><br><br>

            <h2>Billing & Shipping Details</h2>
            <div class="address-section">
                <div class="address">
                    <h3>Billing Address</h3>
                    <table class="details-table">
                        <tr>
                            <th>Address 1</th>
                            <td><?php echo htmlspecialchars($row['address1'] ?? 'Not available'); ?></td>
                        </tr>
                        <tr>
                            <th>Address 2</th>
                            <td><?php echo htmlspecialchars($row['address2'] ?? 'Not available'); ?></td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <td><?php echo htmlspecialchars($row['city'] ?? 'Not available'); ?></td>
                        </tr>
                        <tr>
                            <th>Country</th>
                            <td><?php echo htmlspecialchars($row['country'] ?? 'Not available'); ?></td>
                        </tr>
                        <tr>
                            <th>Postal Code</th>
                            <td><?php echo htmlspecialchars($row['postalCode'] ?? 'Not available'); ?></td>
                        </tr>
                    </table>
                </div>
                <br>
                <div class="address">
                    <h3>Shipping Address</h3>
                    <table class="details-table">
                        <tr>
                            <th>Address 1</th>
                            <td><?php echo htmlspecialchars($row['address1'] ?? 'Not available'); ?></td>
                        </tr>
                        <tr>
                            <th>Address 2</th>
                            <td><?php echo htmlspecialchars($row['address2'] ?? 'Not available'); ?></td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <td><?php echo htmlspecialchars($row['city'] ?? 'Not available'); ?></td>
                        </tr>
                        <tr>
                            <th>Country</th>
                            <td><?php echo htmlspecialchars($row['country'] ?? 'Not available'); ?></td>
                        </tr>
                        <tr>
                            <th>Postal Code</th>
                            <td><?php echo htmlspecialchars($row['postalCode'] ?? 'Not available'); ?></td>
                        </tr>
                    </table>
                    <a href="./editAddress.php">
    <button class="btn btn-primary">Edit Address</button>
</a>
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