<?php
include('../../../database/db.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$meetingId = $_GET['id'];

// Fetch the meeting details
$query = "SELECT meeting_id, availableTimes_id FROM meeting WHERE meeting_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $meetingId);
$stmt->execute();
$result = $stmt->get_result();
$meeting = $result->fetch_assoc();

// Fetch available time slots (those with 'available' status)
$timeSlotsQuery = "SELECT availableTimes_id, date, time FROM availabletimes WHERE availability = 'available'";
$timeSlotsResult = $conn->query($timeSlotsQuery);
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
    <link rel="preload" as="image" href="./assets/images/logo.png">
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
                <li><a href="../Wishlist/MyWishlist.html" >My Wishlist</a></li>
                <li><a href="../Sales/MySales.html">My Sales</a></li>
                <li><a href="../Meetings/MyMeetings.html"  class="active">My Meetings</a></li>
                <li><a href="../Purchases/MyPurchases.html">Purchases</a></li>
                <li><a href="../Requests/MyRequest.php">Requests</a></li>
                <li><a href="../Emails/MyEmails.html">Email Preferences</a></li>
                <li><a href="../../Login/logout.php">Signout</a></li>
            </ul>
        </div>

        <div class="main-content">
    <h1>Edit Meeting</h1>
    <div class="tab-content">
    <form method="POST" action="updateMeeting.php">
        <input type="hidden" name="meeting_id" value="<?php echo $meeting['meeting_id']; ?>">
        <div class="form-group">
        <input type="hidden" name="currentAvailableTime_id" value="<?php echo $meeting['availableTimes_id']; ?>">

        <label for="availableTimes_id">Choose a new date and time:</label>
        <select name="availableTimes_id" required>
            <?php while ($slot = $timeSlotsResult->fetch_assoc()) : ?>
                <option value="<?php echo $slot['availableTimes_id']; ?>">
                    <?php echo $slot['date'] . ' ' . $slot['time']; ?>
                </option>
            <?php endwhile; ?>
        </select>
        <div class="form-group">
        <input type="hidden" name="currentAvailableTime_id" value="<?php echo $meeting['availableTimes_id']; ?>">

        
            </div>
            <div class="form-actions">
        <button type="submit"   class="btn btn-primary">Save Changes</button>
            </div>
            </div>
    </form>
            </div>
</body>
</html>

<?php $conn->close(); ?>
