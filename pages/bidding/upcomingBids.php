<?php
session_start();
include '../../database/db.php';

$biddingStoneId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($biddingStoneId <= 0) {
    die("Invalid bidding item ID.");
}

// Get stone details
$query = "
    SELECT 
        CONCAT(i.colour, ' ', i.type) AS stone_name, 
        i.image AS stone_image, 
        b.startingBid, 
        b.startDate, 
        b.finishDate
    FROM 
        biddingstone b
    JOIN 
        inventory i 
    ON 
        b.stone_id = i.stone_id
    WHERE 
        b.biddingStone_id = $biddingStoneId
";

$result = $conn->query($query);

if (!$result || $result->num_rows === 0) {
    die("Bidding details not found.");
}

$row = $result->fetch_assoc();

$startDateStr = $row['startDate'];
$now = new DateTime();
$startDate = new DateTime($row['startDate']);
$isAuctionStarted = $now >= $startDate; // Checks if auction has already started
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bidding Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../components/header/header.css">
    <link rel="stylesheet" href="../../components/footer/footer.css">
    <link rel="stylesheet" href="../../styles/common.css">
    <link rel="stylesheet" href="./itemPageStyles.css">
</head>
<body>
<custom-header></custom-header>

<div class="bid-container">
    <h1><?php echo htmlspecialchars($row['stone_name']); ?></h1>

    <div class="time-remaining">
        <h3>Time to Begin:</h3>
        <p id="countdown"><?php echo $isAuctionStarted ? 'Auction has started' : 'Calculating...'; ?></p>
    </div>

    <div class="product-info">
        <div class="product-image">
            <img src="../../assets/images/<?php echo htmlspecialchars($row['stone_image']); ?>" alt="<?php echo htmlspecialchars($row['stone_name']); ?>">
        </div>

        <div class="product-details">
            <div class="detail-row">
                <span class="label">Stone:</span>
                <span><?php echo htmlspecialchars($row['stone_name']); ?></span>
            </div>

            <div class="detail-row">
                <span class="label">Starting Bid:</span>
                <span>Rs.<?php echo number_format($row['startingBid'], 2); ?></span>
            </div>

            <div class="detail-row">
                <span class="label">Auction Start Date:</span>
                <span><?php echo $startDate->format('d M Y H:i'); ?></span>
            </div>

            <div class="detail-row">
                <span class="label">Auction End Date:</span>
                <span><?php echo (new DateTime($row['finishDate']))->format('d M Y H:i'); ?></span>
            </div>

            <?php if ($isAuctionStarted): ?>
                <p style="color: green;">The auction has already started.</p>
            <?php else: ?>
                <p style="color: red;">The auction is yet to start.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<custom-footer></custom-footer>
<script src="../../components/header/header.js"></script>
<script src="../../components/footer/footer.js"></script>

<script>
const auctionStartTime = new Date("<?php echo $startDateStr; ?>").getTime();

function updateCountdown() {
    const now = new Date().getTime();
    const timeLeft = auctionStartTime - now;

    if (timeLeft <= 0) {
        document.getElementById("countdown").innerHTML = "Auction has started";

        // Stop the countdown timer
        clearInterval(countdownTimer);
        return;
    }

    const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
    const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

    document.getElementById("countdown").innerHTML =
        days + " Days " +
        hours + " Hours " +
        minutes + " Minutes " +
        seconds + " Seconds";
}

updateCountdown();
const countdownTimer = setInterval(updateCountdown, 1000);
</script>
</body>
</html>