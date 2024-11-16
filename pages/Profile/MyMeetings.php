<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles/common.css">
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="../../components/header/header.css">
    <link rel="stylesheet" href="../../components/footer/footer.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preload" as="image" href="../../assets/images/logo.png">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <title>My Meetings</title>
</head>
<body>
    <custom-header></custom-header>
    <div class="profile-container profile-h1">
        <div class="profile-sidebar">
            <h2>Hello</h2>
            <ul>
                <li><a href="MyDetails.html">My Details</a></li>
                <li><a href="MyBids.html">My Bids</a></li>
                <li><a href="MyWishlist.html">My Wishlist</a></li>
                <li><a href="MySales.html">My Sales</a></li>
                <li><a href="MyMeetings.php" class="active">My Meetings</a></li>
                <li><a href="MyPurchases.html">Purchases</a></li>
                <li><a href="MyRequest.php">Requests</a></li>
                <li><a href="MyEmails.html">Email Preferences</a></li>
                <li><a href="#">Signout</a></li>
            </ul>
        </div>

     <div class="main-content">
        <h1>My Account</h1>
        <h2>My Meetings</h2>
            <div class="tab-content">
                    <table class="sales-table">
                        <thead>
                            <tr>
                                <th>Appointment Type</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include('../../database/db.php'); // Include the database connection
    
                            // SQL query to fetch the requests data from the database
                            $sql = "SELECT * FROM meeting"; // Replace with your actual table name
                            $result = $conn->query($sql);
    
                            if ($result->num_rows > 0) {
                                // Output each row of data
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>" . $row['type'] . "</td>
                                            <td>" . $row['date'] . "</td>
                                            <td>" . $row['time'] . "</td>
                                            <td>" . $row['email'] . "</td>
                                            <td class='actions'>
                                                <a href='./viewMeetingRequest.html?id=" . $row['meeting_id'] . "' class='btn'><i class='bx bx-eye'></i></a>
                                                <a href='./deleteMeetingRequest.php?id=" . $row['meeting_id'] . "' class='btn'><i class='bx bx-trash'></i></a>
                                            </td>
                                        </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No requests found.</td></tr>";
                            }
    
                            $conn->close(); // Close the database connection
                            ?>
                        </tbody>
                    </table>
                </div>
        </div>

            </div>
    </div>
</div>

    
    <script src="../../components/header/header.js"></script>
    <script src="../../components/footer/footer.js"></script>
    <script src="profile.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    
</body>
</html>