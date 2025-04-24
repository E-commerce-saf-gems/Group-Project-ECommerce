<?php
session_start();
include '../../database/db.php';

$biddingStoneId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($biddingStoneId <= 0) {
    die("Invalid bidding item ID.");
}

$bidMessage = '';
if (isset($_POST['submit_bid']) && isset($_SESSION['customer_id'])) {
    $newBid = floatval($_POST['bid_amount']);
    $customerId = $_SESSION['customer_id'];

    if ($newBid <= 0) {
        $bidMessage = '<p style="color: red;">Please enter a valid bid amount.</p>';
    } else {
        $conn->begin_transaction();

        try {
            $lockQuery = "SELECT MAX(amount) AS highestBid FROM bid WHERE biddingStone_id = $biddingStoneId and bid.validity='valid' FOR UPDATE";
            $lockResult = $conn->query($lockQuery);
            $lockRow = $lockResult->fetch_assoc();
            $currentHighest = $lockRow['highestBid'] ? floatval($lockRow['highestBid']) : 0;

            if ($newBid > $currentHighest) {
                $insertBid = "INSERT INTO bid (biddingStone_id, customer_id, amount, time) 
                              VALUES ($biddingStoneId, $customerId, $newBid, NOW())";
                $conn->query($insertBid);

                $updateBiddingStone = "UPDATE biddingstone 
                                       SET customer_id = $customerId 
                                       WHERE biddingStone_id = $biddingStoneId";
                $conn->query($updateBiddingStone);

                $conn->commit();
                header("Location: ./bidding-itemPage.php?id=$biddingStoneId&success=1");
            } else {
                $conn->rollback();
                header("Location: ./bidding-itemPage.php?id=$biddingStoneId&success=2");
            }
        } catch (Exception $e) {
            $conn->rollback();
            $bidMessage = '<p style="color: red;">Error placing bid: ' . $conn->error . '</p>';
        }
    }
}

$query = "
    SELECT 
        i.* ,
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

$highestBidQuery = "
    SELECT MAX(amount) AS highestBid
    FROM bid
    WHERE biddingStone_id = $biddingStoneId
      AND validity = 'valid'
";
$highestBidResult = $conn->query($highestBidQuery);
$highestBidRow = $highestBidResult->fetch_assoc();
$highestBid = $highestBidRow['highestBid'] ? $highestBidRow['highestBid'] : $row['startingBid'];

$userBid = "None";
if (isset($_SESSION['customer_id'])) {
    $customerId = $_SESSION['customer_id'];
    $userBidQuery = "
        SELECT MAX(amount) AS userHighestBid
        FROM bid
        WHERE biddingStone_id = $biddingStoneId AND customer_id = $customerId
    ";
    $userBidResult = $conn->query($userBidQuery);
    $userBidRow = $userBidResult->fetch_assoc();
    if ($userBidRow['userHighestBid']) {
        $userBid = "Rs." . number_format($userBidRow['userHighestBid'], 2);
    }
}

$finishDateStr = $row['finishDate'];
$now = new DateTime();
$finishDate = new DateTime($row['finishDate']);
$isAuctionEnded = $now >= $finishDate;
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
<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="success-message">
                    Your Bid Was Placed Successfully
                    <u><a href="../Profile/Bids/MyBids.php">View My Bids</a></u>
                </div>
            <?php elseif (isset($_GET['success']) && $_GET['success'] == 2): ?>
                <div class="error-message">
                    Bid must be higher than the current highest bid.Refresh the page to see the current highest bid
                </div>
            <?php endif; ?>

    <h1><?php echo htmlspecialchars($row['stone_name']); ?></h1>

    <div class="time-remaining">
        <h3>Time Remaining:</h3>
        <p id="countdown"><?php echo $isAuctionEnded ? 'Auction has ended' : 'Calculating...'; ?></p>
    </div>

    <?php echo $bidMessage; ?>

    <div class="product-info">
    <div class="product-image">
        <img src="../../assets/images/<?php echo htmlspecialchars($row['stone_image']); ?>" alt="<?php echo htmlspecialchars($row['stone_name']); ?>">
    </div>

    <div class="product-details">
        <div class="auction-details">
            <h3>Auction Details</h3>
            <table>
                <tr>
                    <th>Starting Bid</th>
                    <td>Rs. <?php echo number_format($row['startingBid'], 2); ?></td>
                </tr>
                <tr>
                    <th>Start Date</th>
                    <td><?php echo date('F j, Y', strtotime($row['startDate'])); ?></td>
                </tr>
                <tr>
                    <th>End Date</th>
                    <td><?php echo date('F j, Y', strtotime($row['finishDate'])); ?></td>
                </tr>
            </table>
        </div>

        <div class="bidding-info">
            <h3>Bidding Information</h3>
            <table>
                <tr>
                    <th>Current Highest Bid</th>
                    <td>Rs. <?php echo number_format($highestBid, 2); ?></td>
                </tr>
                <tr>
                    <th>Your Highest Bid</th>
                    <td><?php echo $userBid; ?></td>
                </tr>
            </table>
        </div>

        <?php if (isset($_SESSION['customer_id']) && !$isAuctionEnded): ?>
            <div class="bid-form-wrapper">
                <div class="bid-form">
                    <h3>Place Your Bid</h3>
                    <form method="post" action="">
                        <div class="bid-input-container">
                            <input type="number" id="bid_amount" name="bid_amount" placeholder="Enter your bid amount"
                                min="<?php echo $highestBid + 1000; ?>" value="<?php echo $highestBid + 1000; ?>" required>
                            <div class="increment-buttons">
                                <button type="button" class="increment-btn" onclick="incrementBid(1000)">+Rs. 1,000</button>
                                <button type="button" class="increment-btn" onclick="incrementBid(10000)">+Rs. 10,000</button>
                            </div>
                        </div>
                        <div class="current-bid-info">
                            <span>Current highest bid: Rs. <?php echo number_format($highestBid, 2); ?></span>
                        </div>
                        <button type="submit" name="submit_bid" class="submit-bid-btn">Place Bid</button>
                    </form>
                </div>
            </div>

            <div class="stone-details">
            <h3>Stone Details</h3>
            <table>
                <tr>
                    <th>Stone: </th>
                    <td><?php echo htmlspecialchars($row['stone_name']); ?></td>
                </tr>
                <tr>
                    <th>Size: </th>
                    <td><?php echo htmlspecialchars($row['size']); ?></td>
                </tr>
                <tr>
                    <th>Shape: </th>
                    <td><?php echo htmlspecialchars($row['shape']); ?></td>
                </tr>
                <tr>
                    <th>Origin: </th>
                    <td><?php echo htmlspecialchars($row['origin']); ?></td>
                </tr>
                <tr>
                    <th>Description:   </th>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                </tr>
            </table>
        </div>
        <?php elseif (!isset($_SESSION['customer_id'])): ?>
            <p class="error-success">Please log in to place a bid.</p>
        <?php elseif ($isAuctionEnded): ?>
            <p class="error-message">This auction has ended.</p>
        <?php endif; ?>
    </div>
</div>

</div>

<custom-footer></custom-footer>
<script src="../../components/header/header.js"></script>
<script src="../../components/footer/footer.js"></script>

<script>
function incrementBid(amount) {
    const bidInput = document.getElementById('bid_amount');
    const currentHighestBid = <?php echo $highestBid; ?>;
    let currentValue = bidInput.value ? parseFloat(bidInput.value) : currentHighestBid + 1000;
    let newValue = currentValue <= currentHighestBid ? currentHighestBid + amount : currentValue + amount;
    bidInput.value = newValue;
    if (parseFloat(bidInput.value) <= HighestBidcurrent) {
        bidInput.value = currentHighestBid + 1000;
    }
}

const auctionEndTime = new Date("<?php echo $finishDateStr; ?>").getTime();

function updateCountdown() {
    const now = new Date().getTime();
    const timeLeft = auctionEndTime - now;

    if (timeLeft <= 0) {
        document.getElementById("countdown").innerHTML = "Auction has ended";
        
        const bidFormWrapper = document.querySelector(".bid-form-wrapper");
        if (bidFormWrapper) {
            bidFormWrapper.innerHTML = "<p style='color: red;'>This auction has ended.</p>";
        }

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

setTimeout(function() {
        const message = document.querySelector(".success-message");
        if (message) {
            message.style.display = "none";
        }
    }, 5000);
</script>
</body>
</html>
