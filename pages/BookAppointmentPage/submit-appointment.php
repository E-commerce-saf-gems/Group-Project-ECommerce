<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../Login/login.php?notloggedIn=1");
    exit;
}

$customerID = $_SESSION['customer_id'];
$customerEmail = "";

include '../../database/db.php';

try {
    $sql = "SELECT email FROM customer WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customerID);
    $stmt->execute();
    $stmt->bind_result( $customerEmail);
    $stmt->fetch();
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
    <title>SAF Gems Book Appointment</title>
    <link rel="stylesheet" href="../RegisterPage/register.css" />
    <link rel="stylesheet" href="../../components/header/header.css">
    <link rel="stylesheet" href="./appointment.css" /> 
    <link rel="stylesheet" href="../../styles/common.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body id="top">
    <custom-header></custom-header>
    <main>
        <div>
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="success-message">
                    Your appointment has been booked! <br> We will contact you shortly!
                </div>
            <?php elseif (isset($_GET['success']) && $_GET['success'] == 2): ?>
                <div class="error-message">
                    An error occurred while submitting your appointment! Try again!
                </div>
            <?php endif; ?>

            <h1>Book Your Appointment Now</h1>
            <form action="process-appointment.php" method="POST">
                <label for="appointment">Appointment Type:</label>
                <select id="appointment" name="appointment">
                    <option value="online">Online</option>
                    <option value="physical">Physical</option>
                </select>

                <label for="date">Date:</label>
                <input type="date" name="date" id="date" required />


                <label for="time">Time:</label>
                <select id="time" name="time">
                </select>


                <label for="email">Email Address:</label>
                <input id="email" name="email" placeholder="Enter your Email" value="<?php echo $customerEmail; ?>" required>
                <span id="email-error" class="error-msg"></span>
                <br />

                <button type="submit">Book Appointment</button>
            </form>
        </div>
    </main>
    <script src="../../components/header/header.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="./appointment.js"></script>
</body>
</html>