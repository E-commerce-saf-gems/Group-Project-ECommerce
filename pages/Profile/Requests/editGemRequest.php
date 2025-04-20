<?php
include('../../../database/db.php'); // Include your database connection here

// Check if request_id is provided in the URL
if (isset($_GET['id'])) {
    $request_id = $_GET['id'];

    // Fetch the record from the database
    $sql = "SELECT * FROM request WHERE request_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $request_id);
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
                <li><a href="../Meetings/MyMeetings.php">My Meetings</a></li>
                <li><a href="../Purchases/MyPurchases.php">Purchases</a></li>
                <li><a href="../Requests/MyRequest.php">Requests</a></li>
                <li><a href="../Emails/MyEmails.html" class="active">Email Preferences</a></li>
                <li><a href="../../Login/logout.php">Signout</a></li>
            </ul>
        </div>

        <div class="main-content">
            <h1>Edit Custom Gemstone Request</h1>
            <h2>Enter Details</h2>
            <div class="tab-content">
                <form class="edit-sales-form" id="editGemsForm" action="./updateRequest.php" method="POST">
                    <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">

                    <div class="form-group">
                        <label for="gem-type">Gemstone Type</label>
                        <input type="text" id="gem-type" name="type" value="<?php echo $row['type']; ?>" placeholder="Diamond, Sapphire, Ruby" required>
                    </div>

                    <div class="form-group">
                        <label for="carat-weight">Carat Weight</label>
                        <input type="number" id="carat-weight" name="weight" value="<?php echo $row['weight']; ?>" step="0.1" min="0.1" required>
                    </div>

                    <div class="form-group">
                        <label for="shape">Shape</label>
                        <select id="shape" name="shape" required>
                            <option value="">Select a Shape</option>
                            <option value="round" <?php if ($row['shape'] === 'round') echo 'selected'; ?>>Round</option>
                            <option value="oval" <?php if ($row['shape'] === 'oval') echo 'selected'; ?>>Oval</option>
                            <option value="princess" <?php if ($row['shape'] === 'princess') echo 'selected'; ?>>Princess</option>
                            <option value="emerald" <?php if ($row['shape'] === 'emerald') echo 'selected'; ?>>Emerald</option>
                            <option value="cushion" <?php if ($row['shape'] === 'cushion') echo 'selected'; ?>>Cushion</option>
                            <option value="marquise" <?php if ($row['shape'] === 'marquise') echo 'selected'; ?>>Marquise</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="color">Color</label>
                        <input type="text" id="color" name="color" value="<?php echo $row['color']; ?>" placeholder="Aqua, Green" required>
                    </div>

                    <div class="form-group">
                        <label for="special-requirements">Special Requirements</label>
                        <textarea id="special-requirements" name="requirement" cols="100" rows="10" placeholder="Specify any additional requirements, such as clarity, size dimensions, etc."><?php echo $row['requirement']; ?></textarea>
                    </div>

                    <?php if ($row['status'] === 'P') { ?>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-save'></i> Save Changes
                            </button>
                        </div>
                    <?php } else { ?>
                        <script>
                            document.querySelectorAll('input, select, textarea').forEach(input => input.disabled = true);
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
