<?php
include('../../../database/db.php'); // Include your database connection here

// Check if request_id is provided in the URL
if (isset($_GET['id'])) {
    $meeting_id = $_GET['id'];

    // Fetch the record from the database
    $sql = "SELECT * FROM meeting WHERE meeting_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $meeting_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "No record found";
        exit;
    }
} else {
    echo "No ID specified";
    exit;
}
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
    <title>Edit Request Gems</title>
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
            <h2>Enter Details</h2>
            <div class="tab-content">
                <form class="edit-sales-form" id="editGemsForm" action="./updateMeeting.php" method="POST">
                    <input type="hidden" name="meeting_id" value="<?php echo $meeting_id; ?>">


                    <div class="form-group">
                    <label for="appointment">Appintment Type :</label>
                    <select id="appointment" name="appointment">
                    <option value="">Select a Appointment Type</option>
              <option value="online" <?php if ($row['type'] === 'online') echo 'selected'; ?>>Online</option>
              <option value="physical" <?php if ($row['type'] === 'physical') echo 'selected'; ?>>Physical</option>
            </select>
            </div>

                   

                    <div class="form-group">
                        <label for="date">date</label>
                        <input type="date" id="date" name="date" value="<?php echo $row['date']; ?> required>
                    </div>

                     <div class="form-group">
                     <label for="time">Time:</label>
                    
            <select id="time" name="time" required>
            <option value="">Select a time</option>
                <option value="14:30" <?php if ($row['time'] === '14:30') echo 'selected'; ?>>14:30</option>
                <option value="15:30" <?php if ($row['time'] === '15:30') echo 'selected'; ?>>15:30</option>
                <option value="16:30" <?php if ($row['time'] === '16:30') echo 'selected'; ?>>16:30</option>
                <option value="17:30" <?php if ($row['time'] === '17:30') echo 'selected'; ?>>17:30</option>
                <option value="18:30" <?php if ($row['time'] === '18:30') echo 'selected'; ?>>18:30</option>
                <option value="19:30" <?php if ($row['time'] === '19:30') echo 'selected'; ?>>19:30</option>
                <option value="20:30" <?php if ($row['time'] === '20:30') echo 'selected'; ?>>20:30</option>
</select>
                    </div>


                    <?php if ($row['status'] === 'p') { ?>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-save'></i> Save Changes
                            </button>
                        </div>
                    <?php } else { ?>
                        <script>
                            document.querySelectorAll('input, select').forEach(input => input.disabled = true);
                        </script>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>

    <script src="../profile.js"></script>
    <script src="../../../components/profileHeader/header.js"></script>
    <script src="../../../components/footer/footer.js"></script>
    
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
