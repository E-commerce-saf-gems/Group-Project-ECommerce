<?php
include('../../../database/db.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure `id` parameter is passed
if (!isset($_GET['id'])) {
    die('Meeting ID not provided.');
}

$meetingId = $_GET['id'];

// Fetch the meeting details
$query = "SELECT meeting_id, availableTimes_id FROM meeting WHERE meeting_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $meetingId);
$stmt->execute();
$result = $stmt->get_result();
$meeting = $result->fetch_assoc();

// If no meeting is found, show an error and exit
if (!$meeting) {
    die('Meeting not found.');
}

// Fetch the booked date and time for the current availableTimes_id
$bookedTimeQuery = "SELECT date, time FROM availabletimes WHERE availableTimes_id = ?";
$bookedStmt = $conn->prepare($bookedTimeQuery);
$bookedStmt->bind_param("i", $meeting['availableTimes_id']);
$bookedStmt->execute();
$bookedResult = $bookedStmt->get_result();
$bookedTime = $bookedResult->fetch_assoc();

// Fetch available time slots (those with 'available' status)
$timeSlotsQuery = "SELECT availableTimes_id, date, time FROM availabletimes WHERE availability = 'available'";
$timeSlotsResult = $conn->query($timeSlotsQuery);

// Close the statement for meeting details
$stmt->close();
$bookedStmt->close();
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
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
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
            <li><a href="../Meetings/MyMeetings.php" class="active">My Meetings</a></li>
            <li><a href="../Purchases/MyPurchases.php">Purchases</a></li>
            <li><a href="../Requests/MyRequest.php">Requests</a></li>
            <li><a href="../Emails/MyEmails.html">Email Preferences</a></li>
            <li><a href="../../Login/logout.php">Signout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Edit Meeting</h1>
        <h2>Enter Details</h2>
        <div class="tab-content">
            <!-- Display the current booked date and time -->
            <div class="booked-info">
                <p><strong>Currently Booked:</strong> 
                    <?php 
                    echo htmlspecialchars($bookedTime['date'] . ' ' . $bookedTime['time']); 
                    ?>
                </p>
            </div>

            <!-- Form to edit meeting -->
            <form method="POST" action="updateMeeting.php">
                <input type="hidden" name="meeting_id" value="<?php echo htmlspecialchars($meeting['meeting_id']); ?>">
                <input type="hidden" name="currentAvailableTime_id" value="<?php echo htmlspecialchars($meeting['availableTimes_id']); ?>">

                <div class="form-group">
                    <label for="availableTimes_id">Choose a new date and time:</label>
                    <select name="availableTimes_id" required>
                        <?php while ($slot = $timeSlotsResult->fetch_assoc()) : ?>
                            <option value="<?php echo htmlspecialchars($slot['availableTimes_id']); ?>" 
                                <?php echo $slot['availableTimes_id'] == $meeting['availableTimes_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($slot['date'] . ' ' . $slot['time']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Close database connection
$conn->close();
?>
</body>
</html>
