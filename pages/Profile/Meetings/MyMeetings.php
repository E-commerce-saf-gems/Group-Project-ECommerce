<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../styles/common.css">
    <link rel="stylesheet" href="../profile.css">
    <link rel="stylesheet" href="./meeting.css">
    <link rel="stylesheet" href="../../../components/header/header.css">
    <link rel="stylesheet" href="../../../components/footer/footer.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preload" as="image" href="../../../assets/images/logo.png">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <title>My Meetings</title>
</head>
<body>
    <custom-header></custom-header>
    <div class="profile-container profile-h1">
        <div class="profile-sidebar">
            <h2>Hello</h2>
            <ul>
                <li><a href="../MyDetails.html">My Details</a></li>
                <li><a href="../MyBids.html">My Bids</a></li>
                <li><a href="../MyWishlist.html">My Wishlist</a></li>
                <li><a href="../MySales.html">My Sales</a></li>
                <li><a href="../MyMeetings.php" class="active">My Meetings</a></li>
                <li><a href="../MyPurchases.html">Purchases</a></li>
                <li><a href="../MyRequest.php">Requests</a></li>
                <li><a href="../MyEmails.html">Email Preferences</a></li>
                <li><a href="../../Login/logout.php">Signout</a></li>
            </ul>
        </div>

     <div class="main-content">
        <h1>My Account</h1>
        <h2>My Meetings</h2>


        <?php if (isset($_GET['deleteSuccess']) && $_GET['deleteSuccess'] == 1): ?>
            <div class="success-message">
                Your meeting has been deleted!
            </div>
            <?php elseif(isset($_GET['deleteSuccess']) && $_GET['deletesuccess'] == 2) : ?>
                <div class="error-message">
                    An error occurred while deleting your meeting! Try again!
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['editSuccess']) && $_GET['editSuccess'] == 1): ?>
            <div class="success-message">
                Your meeting has been edited!
            </div>
            <?php elseif(isset($_GET['editSuccess']) && $_GET['editsuccess'] == 2) : ?>
                <div class="error-message">
                    An error occurred while editing your meeting! Try again!
                </div>
            <?php endif; ?>



            <div class="tab-content">
                    <table class="sales-table">
                        <thead>
                            <tr>
                                <th>Appointment Type</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
include('../../../database/db.php'); // Include the database connection
session_start(); // Start the session to access session variables

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../../Login/login.php?notloggedIn=1");
    exit;
}

$customerID = $_SESSION['customer_id']; // Get the logged-in customer's ID from the session

// Fetch meetings specific to the logged-in customer
$sql = "SELECT 
            m.meeting_id, 
            m.type, 
            a.date, 
            a.time, 
            c.email AS email, 
            m.status
        FROM meeting AS m
        JOIN customer AS c ON m.customer_id = c.customer_id
        JOIN availabletimes AS a ON a.availableTimes_id = m.availableTimes_id
        WHERE m.customer_id = ?";

$stmt = $conn->prepare($sql); // Prepare the SQL query
$stmt->bind_param("i", $customerID); // Bind the customer ID to the query
$stmt->execute();
$result = $stmt->get_result(); // Execute the query and get the result set

if ($result->num_rows > 0) {
    // Output each row of data
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['type']) . "</td>
                <td>" . htmlspecialchars($row['date']) . "</td>
                <td>" . htmlspecialchars($row['time']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['status']) . "</td>
                <td class='actions'>";
                        
                if ($row['status'] === 'P') { 
                    // Status is Pending
                    echo "
                        <a href='./editMeeting.php?id=" . $row['meeting_id'] . "' class='btn'>
                            <i class='bx bx-pencil'></i> Edit
                        </a>
                        <a href='#' onclick='requestDelete(" . $row['meeting_id'] . ")' class='btn'>
                            <i class='bx bx-trash'></i> Request to Delete
                        </a>";
                } elseif ($row['status'] === 'A') { 
                    // Status is Approved
                    echo "
                        <a href='#' onclick='requestEdit(" . $row['meeting_id'] . ")' class='btn'>
                            <i class='bx bx-pencil'></i> Request to Edit
                        </a>
                        <a href='#' onclick='deleteMeeting.php(" . $row['meeting_id'] . ")' class='btn'>
                            <i class='bx bx-trash'></i> Request to Delete
                        </a>";
                } elseif ($row['status'] === 'C') { 
                    // Status is Complete
                    // No buttons
                }                            
                echo  "</td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No meetings found.</td></tr>";
}

$stmt->close(); // Close the statement
$conn->close(); // Close the database connection
?>

                        </tbody>
                    </table>
                </div>
        </div>

            </div>
    </div>
</div>

<script>
    function requestDelete(meetingId) {
        if (confirm("Are you sure you want to request to delete this meeting?")) {
            // Send an AJAX request to update the status to 'RD'
            fetch(`updateMeetingStatus.php?id=${meetingId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Successfully updated, now you can update the row in the table
                        alert('Meeting status updated to "Request to Delete"');
                        location.reload(); // Reload the page to reflect the status change
                    } else {
                        alert('Failed to update the status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the meeting status');
                });
        }
    }
</script>



    
    <script src="../../../components/header/header.js"></script>
    <script src="../../../components/footer/footer.js"></script>
    <script src="../profile.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    
</body>
</html>