<?php
include '../../database/db.php'; // Include the database connection

// Get the biddingStone_id from the query parameter
$biddingStoneId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the bidding and stone details
$query = "
    SELECT 
        CONCAT(i.colour, ' ', i.type) AS stone_name, 
        i.image AS stone_image, 
        b.startingBid, 
        b.currentBid, 
        b.startDate, 
        b.finishDate, 
        b.cycle_no_completed, 
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Existing Styles */
        .bidding-container {
            width: 100%;
            max-width: 600px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 0 auto;
        }

        .product-header {
            background-color: #449f9f;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .product-header h2 {
            margin: 0;
            font-size: 18px;
        }

        .product-content {
            display: flex;
            padding: 15px;
        }

        .product-image {
            flex: 1;
            margin-right: 15px;
        }

        .product-image img {
            max-width: 100%;
            border-radius: 8px;
        }

        .product-details {
            flex: 2;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 10px;
        }

        .detail-item .label {
            color: #666;
        }

        .detail-item .value {
            font-weight: bold;
        }

        .cycle-info {
            background-color: #f0f0f0;
            padding: 15px;
            text-align: center;
        }

        .cycle-timer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .cycle-timer-icon {
            color: #449f9f;
            font-size: 24px;
        }

        .bid-now-btn {
            background-color: #449f9f;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .bid-now-btn:hover {
            background-color: #367a7a;
        }

        /* Popup Styles */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .popup-content {
            background: white;
            padding: 20px;
            border-radius: 12px;
            width: 400px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .popup-content h3 {
            margin-top: 0;
        }

        .popup-content input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        .popup-buttons {
            display: flex;
            justify-content: space-between;
        }

        .popup-buttons button {
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .popup-buttons .confirm-btn {
            background-color: #449f9f;
            color: white;
        }

        .popup-buttons .cancel-btn {
            background-color: #ccc;
        }
            /* Popup Buttons Styling */
    .popup-increment-buttons {
        display: flex;
        justify-content: space-between;
        margin: 10px 0;
    }

    .increment-btn {
        flex: 1;
        margin: 0 5px;
        padding: 10px 15px;
        border: none;
        border-radius: 6px;
        background-color: #449f9f;
        color: white;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        text-align: center;
    }

    .increment-btn:hover {
        background-color: #367a7a;
    }
    </style>
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
                    // Example: Assume each cycle lasts 1 hour
                    $cycleTimeLeft = new DateTime('+1 hour');
                    $interval = $now->diff($cycleTimeLeft);
                    echo $interval->format('%h:%i:%s');
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Popup Structure -->
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

    const biddingStoneId = <?php echo $biddingStoneId; ?>; // Get the biddingStoneId from PHP
    const bidIncrement = 500; // Minimum increment

// Open the popup
bidNowBtn.addEventListener("click", () => {
    popupOverlay.style.display = "flex";
    const currentBid = parseFloat(currentBidEl.textContent.replace(/[^0-9.-]+/g, ""));
    bidAmountInput.value = Math.round(currentBid); // Set the input field to the current highest bid
});
// Close the popup
cancelBidBtn.addEventListener("click", () => {
    popupOverlay.style.display = "none";
});

// Increment bid amount by 1000
increment100Btn.addEventListener("click", () => {
    bidAmountInput.value = Math.round(parseFloat(bidAmountInput.value) + 1000);
});

// Increment bid amount by 500
increment50Btn.addEventListener("click", () => {
    bidAmountInput.value = Math.round(parseFloat(bidAmountInput.value) + 500);
});

// Confirm the bid
confirmBidBtn.addEventListener("click", () => {
    const bidAmount = Math.round(parseFloat(bidAmountInput.value)); // Round to avoid floating-point issues
    const currentBid = parseFloat(currentBidEl.textContent.replace(/[^0-9.-]+/g, "")); // Remove Rs. symbol

    if (bidAmount < currentBid + bidIncrement) {
        alert(`Your bid must be at least Rs.${(currentBid + bidIncrement)}`);
        return;
    }

    // Send the new bid to the server
    fetch('./updateBid.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `biddingStoneId=${biddingStoneId}&newBid=${bidAmount}`,
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                // Update the current bid on the webpage
                currentBidEl.textContent = `Rs.${bidAmount}`;
                userBidEl.textContent = `Rs.${bidAmount}`;
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
    <script src="../../components/header/header.js"></script>
    <script src="../../components/footer/footer.js"></script>
</body>
</html>
