<?php
include('../../../database/db.php'); // Include your database connection here
session_start(); // Start session to get logged-in customer data

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: ../../Login/login.php?notloggedIn=1");
    exit;
}

$customer_id = $_SESSION['customer_id']; // Get logged-in customer ID

// Check if meeting ID is provided in the URL
if (isset($_GET['id'])) {
    $meeting_id = $_GET['id'];

    // Fetch the meeting record for the logged-in customer
    $sql = "SELECT 
                m.meeting_id, 
                m.type, 
                m.status, 
                a.availableTimes_id, 
                a.date, 
                a.time 
            FROM meeting AS m
            JOIN availabletimes AS a ON m.availableTimes_id = a.availableTimes_id
            WHERE m.meeting_id = ? AND m.customer_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $meeting_id, $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "No record found or you do not have permission to edit this meeting.";
        exit;
    }
} else {
    echo "No ID specified";
    exit;
}

// Fetch all available times for the dropdown
$availableTimesQuery = "SELECT availableTimes_id, date, time, availability FROM availabletimes WHERE availability = 'available'";
$availableTimesResult = $conn->query($availableTimesQuery);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Edit Meeting</title>
</head>
<body>
    <custom-header></custom-header>
    <div class="profile-container profile-h1">
        <div class="profile-sidebar">
            <h2>Hello</h2>
            <ul>
                <li><a href="../Bids/MyBids.html">My Bids</a></li>
                <li><a href="../Wishlist/MyWishlist.html">My Wishlist</a></li>
                <li><a href="../Sales/MySales.html">My Sales</a></li>
                <li><a href="../Meetings/MyMeetings.html" class="active">My Meetings</a></li>
                <li><a href="../Purchases/MyPurchases.html">Purchases</a></li>
                <li><a href="../Requests/MyRequest.php">Requests</a></li>
                <li><a href="../Emails/MyEmails.html">Email Preferences</a></li>
                <li><a href="../../Login/logout.php">Signout</a></li>
            </ul>
        </div>

        <div class="main-content">
            <h1>Edit Meeting</h1>
            <h2>Enter Details</h2>
            <div class="tab-content">
                <form class="edit-sales-form" id="editMeetingForm" action="./updateMeeting.php" method="POST">
                    <input type="hidden" name="meeting_id" value="<?php echo $row['meeting_id']; ?>">

                    <div class="form-group">
                        <label for="appointment">Appointment Type:</label>
                        <select id="appointment" name="appointment" required>
                            <option value="online" <?php if ($row['type'] === 'on') echo 'selected'; ?>>Online</option>
                            <option value="physical" <?php if ($row['type'] === 'ph') echo 'selected'; ?>>Physical</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="availableTimes">Available Time:</label>
                        <select id="availableTimes" name="availableTimes_id" required>
                            <option value="">Select a date and time</option>
                            <?php
                            while ($timeRow = $availableTimesResult->fetch_assoc()) {
                                $selected = $timeRow['availableTimes_id'] == $row['availableTimes_id'] ? 'selected' : '';
                                echo "<option value='" . $timeRow['availableTimes_id'] . "' $selected>" 
                                     . $timeRow['date'] . " " . $timeRow['time'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <?php if ($row['status'] === 'P') { ?>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-save'></i> Save Changes
                            </button>
                        </div>
                    <?php } else { ?>
                        <div class="error-message">
                            Only pending meetings can be edited.
                        </div>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>

    <script src="../../../components/profileHeader/header.js"></script>
    <script src="../../../components/footer/footer.js"></script>
</body>
</html>
