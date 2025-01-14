<?php
include('../../../database/db.php'); // Include the database connection

// SQL query to fetch meeting details along with date and time from availabletimes
$sql = "SELECT 
            meeting.meeting_id, 
            meeting.type, 
            availabletimes.date AS date, 
            availabletimes.time AS time, 
            customer.email AS email, 
            meeting.status
        FROM meeting
        JOIN customer ON meeting.customer_id = customer.customer_id
        JOIN availabletimes ON meeting.availableTimes_id = availabletimes.availableTimes_id";
$result = $conn->query($sql);
?>

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
    <title>My Meetings</title>
</head>
<body>
    <custom-header></custom-header>
    <div class="profile-container profile-h1">
        <div class="profile-sidebar">
            <h2>Hello</h2>
            <ul>
                <li><a href="../MyDetails.php">My Details</a></li>
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
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Map status codes to full words
                                $statusMap = [
                                    'P' => 'Pending Request Meeting',
                                    'A' => 'Meeting Approved',
                                    'C' => 'Meeting Completed',
                                    'R' => 'Pending Request to Delete'
                                ];
                                $statusFullWord = $statusMap[$row['status']] ?? 'Unknown'; // Default to 'Unknown' if status is unrecognized

                                echo "<tr>
                                        <td>{$row['type']}</td>
                                        <td>{$row['date']}</td>
                                        <td>{$row['time']}</td>
                                        <td>{$row['email']}</td>
                                        <td>{$statusFullWord}</td>
                                        <td class='actions'>";
                                // Handle actions based on meeting status
                                if ($row['status'] === 'P') {
                                    // Edit and Delete options for pending meetings
                                    echo "<a href='./editMeeting.php?id={$row['meeting_id']}' class='btn'><i class='bx bx-pencil'></i> Edit</a>";
                                } elseif ($row['status'] === 'A') {
                                    // Request to delete for approved meetings
                                    echo "<a href='./requestDelete.php?id={$row['meeting_id']}' class='btn'><i class='bx bx-x'></i> Request Delete</a>";
                                }
                                echo "</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No meetings found.</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(meetingId) {
            const userConfirmed = confirm("Are you sure you want to delete this meeting?");
            if (userConfirmed) {
                window.location.href = `./deleteMeeting.php?id=${meetingId}`;
            }
        }
    </script>
</body>
</html>
