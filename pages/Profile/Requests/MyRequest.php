<?php
session_start();
include('../../../database/db.php'); // Include the database connection

$customer_id = $_SESSION['customer_id'] ?? null;

if (!$customer_id) {
    // Redirect if not logged in
    header("Location: ../../Login/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Requests</title>
    
    <link rel="stylesheet" href="../../../styles/common.css">
    <link rel="stylesheet" href="./requests.css">
    <link rel="stylesheet" href="../profile.css">
    <link rel="stylesheet" href="../../../components/profileHeader/header.css">
    <link rel="stylesheet" href="../../../components/footer/footer.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body>
<custom-header></custom-header>

<div class="profile-container profile-h2">
    <div class="profile-sidebar">
        <h2>Hello</h2>
        <ul>
            <li><a href="../Details/MyDetails.php">My Details</a></li>
            <li><a href="../Bids/MyBids.php">My Bids</a></li>
            <li><a href="../Meetings/MyMeetings.php">My Meetings</a></li>
            <li><a href="../Purchases/MyPurchases.php">Purchases</a></li>
            <li><a href="../Requests/MyRequest.php" class="active">Requests</a></li>
            <li><a href="../../Login/logout.php">Signout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>My Account</h1>
        <h2>My Requests</h2>

        <?php if (isset($_GET['deleteSuccess']) && $_GET['deleteSuccess'] == 1): ?>
            <div class="success-message">Your request has been deleted!</div>
        <?php elseif (isset($_GET['deleteSuccess']) && $_GET['deleteSuccess'] == 2): ?>
            <div class="error-message">An error occurred while deleting your request! Try again!</div>
        <?php endif; ?>

        <?php if (isset($_GET['editSuccess']) && $_GET['editSuccess'] == 1): ?>
            <div class="success-message">Your request has been edited!</div>
        <?php elseif (isset($_GET['editSuccess']) && $_GET['editSuccess'] == 2): ?>
            <div class="error-message">An error occurred while editing your request! Try again!</div>
        <?php endif; ?>

        <div class="tab-content">
            <table class="sales-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Shape</th>
                        <th>Gem Type</th>
                        <th>Weight</th>
                        <th>Color</th>
                        <th>Special Requirements</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM request WHERE customer_id = ? ORDER BY request_id DESC";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $customer_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['date']) . "</td>
                                    <td>" . htmlspecialchars($row['shape']) . "</td>
                                    <td>" . htmlspecialchars($row['type']) . "</td>
                                    <td>" . htmlspecialchars($row['weight']) . "</td>
                                    <td>" . htmlspecialchars($row['color']) . "</td>
                                    <td>" . htmlspecialchars($row['requirement']) . "</td>
                                    <td>" . ($row['status'] === 'P' ? 'Pending' : htmlspecialchars($row['status'])) . "</td>
                                    <td class='actions'>";
                            if ($row['status'] === 'P') {
                                echo "<a href='./editGemRequest.php?id=" . urlencode($row['request_id']) . "' class='btn'><i class='bx bx-pencil'></i></a>
                                      <a href='#' onclick='confirmDelete(" . (int)$row['request_id'] . ")' class='btn'><i class='bx bx-trash'></i></a>";
                            }
                            echo "</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No requests found.</td></tr>";
                    }

                    $stmt->close();
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function confirmDelete(requestId) {
    const userConfirmed = confirm("Are you sure you want to delete this request?");
    if (userConfirmed) {
        window.location.href = `./deleteRequest.php?id=${requestId}`;
    }
}

setTimeout(function() {
    const successMessage = document.querySelector(".success-message");
    if (successMessage) {
        successMessage.style.display = "none";
    }
    const errorMessage = document.querySelector(".error-message");
    if (errorMessage) {
        errorMessage.style.display = "none";
    }
}, 5000);
</script>

<script src="../../../components/profileHeader/header.js"></script>
<script src="../../../components/footer/footer.js"></script>
<script src="../profile.js"></script>
<script src=""></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>
</html>
