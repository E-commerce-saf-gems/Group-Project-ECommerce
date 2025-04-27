<?php
include '../../database/db.php';

$biddingStoneId = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = "
    SELECT 
        CONCAT(i.colour, ' ', i.type) AS stone_name, 
        i.image AS stone_image, 
        b.startingBid, 
        b.startDate, 
        b.finishDate, 
        b.no_of_Cycles 
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bidding Details</title>
    <link rel="stylesheet" href="../../components/header/header.css">
    <link rel="stylesheet" href="../../components/footer/footer.css">
    <link rel="stylesheet" href="../../styles/common.css">
    <link rel="stylesheet" href="./itemPageStyles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <custom-header></custom-header>
    <div class="bidding-container">
        <div class="product-header">
            <h2>Total Time Left</h2>
            <div class="total-time">
                <?php
                $finishDate = new DateTime($row['finishDate']);
                $now = new DateTime();
                $interval = $now->diff($finishDate);
                echo $interval->format('%d Days %h Hours %i Minutes');
                ?>
            </div>
        </div>

        <div class="product-content">
            <div class="product-image">
                <img src="../../assets/images/<?php echo htmlspecialchars($row['stone_image']); ?>" alt="<?php echo htmlspecialchars($row['stone_name']); ?>">
            </div>
            <div class="product-details">
                <div class="detail-item">
                    <span class="label">Stone Details</span>
                    <span class="value" id="stone-name" style="color: #449f9f;"><?php echo htmlspecialchars($row['stone_name']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="label">Starting Bid</span>
                    <span class="value" id="starting-bid">Rs.<?php echo number_format($row['startingBid'], 2); ?></span>
                </div>
                <div class="detail-item">
                    <span class="label">Current Highest Bid</span>
                    <span class="value" id="current-bid">Rs.<?php echo number_format($row['currentBid'], 2); ?></span>
                </div>
                <div class="detail-item">
                    <span class="label">Your Bid</span>
                    <span class="value" id="user-bid">None</span>
                </div>
                <div class="detail-item">
                    <span class="label">Cycle Number</span>
                    <span class="value" id="cycle-number"><?php echo htmlspecialchars($row['cycle_no_completed'] + 1); ?></span>
                </div>
                <div class="detail-item">
                    <span class="label">Total Cycles</span>
                    <span class="value"><?php echo htmlspecialchars($row['no_of_Cycles']); ?></span>
                </div>
                <button class="bid-now-btn" id="bid-now-btn">
                    <i class="fas fa-gavel"></i> Bid Now
                </button>
            </div>
        </div>

        <div class="cycle-info">
            <div class="cycle-timer">
                <div>
                    <i class="fas fa-sync cycle-timer-icon"></i> Cycle Time Left
                </div>
                <div class="timer-display">
                    <?php
                    $cycleTimeLeft = new DateTime('+1 hour');
                    $interval = $now->diff($cycleTimeLeft);
                    echo $interval->format('%h:%i:%s');
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="popup-overlay" id="popup-overlay">
        <div class="popup-content">
            <h3>Place Your Bid</h3>
            <input type="number" id="bid-amount" placeholder="Enter your bid amount">
            <div class="popup-increment-buttons">
                <button class="increment-btn" id="increment-100">+1000</button>
                <button class="increment-btn" id="increment-50">+500</button>
            </div>
            <div class="popup-buttons">
                <button class="confirm-btn" id="confirm-bid">Confirm</button>
                <button class="cancel-btn" id="cancel-bid">Cancel</button>
            </div>
        </div>
    </div>
    <custom-footer></custom-footer>
    <script>
    const bidNowBtn = document.getElementById("bid-now-btn");
    const popupOverlay = document.getElementById("popup-overlay");
    const confirmBidBtn = document.getElementById("confirm-bid");
    const cancelBidBtn = document.getElementById("cancel-bid");
    const bidAmountInput = document.getElementById("bid-amount");
    const increment100Btn = document.getElementById("increment-100");
    const increment50Btn = document.getElementById("increment-50");

    const startingBidEl = document.getElementById("starting-bid");
    const currentBidEl = document.getElementById("current-bid");
    const userBidEl = document.getElementById("user-bid");

    const biddingStoneId = <?php echo $biddingStoneId; ?>;
    const bidIncrement = 500; 

bidNowBtn.addEventListener("click", () => {
    popupOverlay.style.display = "flex";
    const currentBid = parseFloat(currentBidEl.textContent.replace(/[^0-9.-]+/g, ""));
    bidAmountInput.value = Math.round(currentBid); 
});
cancelBidBtn.addEventListener("click", () => {
    popupOverlay.style.display = "none";
});

increment100Btn.addEventListener("click", () => {
    bidAmountInput.value = Math.round(parseFloat(bidAmountInput.value) + 1000);
});

increment50Btn.addEventListener("click", () => {
    bidAmountInput.value = Math.round(parseFloat(bidAmountInput.value) + 500);
});

confirmBidBtn.addEventListener("click", () => {
    const bidAmount = Math.round(parseFloat(bidAmountInput.value));
    const currentBid = parseFloat(currentBidEl.textContent.replace(/[^0-9.-]+/g, "")); 

    if (bidAmount < currentBid + bidIncrement) {
        alert(Your bid must be at least Rs.${(currentBid + bidIncrement)});
        return;
    }

    fetch('./updateBid.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: biddingStoneId=${biddingStoneId}&newBid=${bidAmount},
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                currentBidEl.textContent = Rs.${bidAmount};
                userBidEl.textContent = Rs.${bidAmount};
                popupOverlay.style.display = "none";
            } else {
                alert('Failed to place the bid. Please try again.');
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
});
</script>
<script src="./script.js"></script>
    <script src="../../components/header/header.js"></script>
    <script src="../../components/footer/footer.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>