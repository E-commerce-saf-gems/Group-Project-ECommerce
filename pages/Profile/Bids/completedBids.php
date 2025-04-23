<?php
session_start();
$customer_id = $_SESSION['customer_id'];

include '../../../database/db.php';

// Get the current date and time
date_default_timezone_set('Asia/Kolkata');
$currentDateTime = date('Y-m-d H:i:s');

// Get the specific biddingstone details (completed one)
$biddingStoneId = $_GET['id'];

$biddingStoneQuery = "
    SELECT bs.*,
            inv.type, inv.colour, inv.shape, inv.origin, inv.description, inv.size, inv.image,
           (SELECT MAX(amount) FROM bid WHERE biddingStone_id = bs.biddingStone_id) AS highestBid
    FROM biddingstone bs
    JOIN inventory inv ON bs.stone_id = inv.stone_id
    WHERE bs.biddingStone_id = $biddingStoneId AND bs.finishDate < '$currentDateTime'
";

$biddingStoneResult = $conn->query($biddingStoneQuery);
$biddingStone = $biddingStoneResult->fetch_assoc();

// Determine if the logged-in user is the winner
$isWinner = isset($biddingStone['customer_id']) && $biddingStone['customer_id'] == $customer_id;


// Get all bids for the stone
$bidsQuery = "
    SELECT b.bid_id, b.amount, b.time, c.firstName AS bidderName, b.customer_id
    FROM bid b
    INNER JOIN customer c ON b.customer_id = c.customer_id
    WHERE b.biddingStone_id = $biddingStoneId
    ORDER BY b.time DESC
";
$bidsResult = $conn->query($bidsQuery);

$purchaseDeadline = date('Y-m-d H:i:s', strtotime($biddingStone['finishDate'] . ' +7 days'));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../styles/common.css">
    <link rel="stylesheet" href="../profile.css">
    <link rel="stylesheet" href="./bids.css">
    <link rel="stylesheet" href="../../../components/profileHeader/header.css">
    <link rel="stylesheet" href="../../../components/footer/footer.css">
    <link rel="stylesheet" href="./bidsSection.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" />
    <link rel="preload" as="image" href="../../../assets/images/logo.png">
    <title>Completed Bid</title>
</head>
<body>
    <custom-header></custom-header>

    <div class="profile-container profile-h2">
        <div class="profile-sidebar">
            <h2>Hello</h2>
            <ul>
                <li><a href="../Details/MyDetails.php">My Details</a></li>
                <li><a href="../Bids/MyBids.php" class="active">My Bids</a></li>
                <li><a href="../Wishlist/MyWishlist.html">My Wishlist</a></li>
                <li><a href="../Sales/MySales.html">My Sales</a></li>
                <li><a href="../Meetings/MyMeetings.php">My Meetings</a></li>
                <li><a href="../Purchases/MyPurchases.php">Purchases</a></li>
                <li><a href="../Requests/MyRequest.php">Requests</a></li>
                <li><a href="../Emails/MyEmails.html">Email Preferences</a></li>
                <li><a href="../../Login/logout.php">Signout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h1>My Account</h1>
            <h2>Completed Bids</h2>
            <div class="bid-details-container">
                <div class="bid-details-box">
                    <div class="bid-header">
                        <div class="bid-title">
                            <h2>Bid No #<?= $biddingStone['biddingStone_id'] ?></h2>
                        </div>
                        <div class="bid-status">
                            <span class="dot dot-completed"></span>
                            <span class="live-text-completed">Completed</span>
                        </div>
                    </div>

                    <div class="bid-info-box">
                    <div class="bid-image-section">
                            <img id="mainImage" src="http://localhost/Group-Project-ECommerce/assets/images/<?= $biddingStone['image'] ?>" alt="stone" class="main-image">
                        </div>

                        <div class="bid-info">
                            <div><strong>Details:</strong> <span class="info-value">
                              <?=
                                $biddingStone['type'] .' - ' .
                                $biddingStone['size'] .'crt -' .
                                $biddingStone['colour'] . ' - ' .
                                $biddingStone['shape'] . ' - ' .
                                $biddingStone['origin']
                              ?></span></div>
                            <div><strong>Description:</strong> <span class="info-value"><?= $biddingStone['description'] ?></span></div>
                            <div><strong>Starting Bid:</strong> <span class="info-value"><?= number_format($biddingStone['startingBid']) ?></span></div>
                            <div><strong>Start Date:</strong> <span class="info-value"><?= $biddingStone['startDate'] ?></span></div>
                            <div><strong>End Date:</strong> <span class="info-value"><?= $biddingStone['finishDate'] ?></span></div>
                        </div>

                        <div class="bid-info">
                            <div><strong>Highest Bid:</strong> <span class="info-value"><?= number_format($biddingStone['highestBid']) ?></span></div>
                            <div><strong>Overall Status</strong> 
                                <?php if ($isWinner): ?>
                                    <span class="info-value bid-result win">Win</span>
                                <?php else: ?>
                                    <span class="info-value bid-result loss">Loss</span>
                                <?php endif; ?>
                            </div>
                            <?php if ($isWinner): ?>
                                <div class="countdown-timer">
                                      <strong>Time left to purchase:</strong> 
                                      <span id="countdown"></span>
                                  </div>
                                <div>
                                    <a href="../../Stones/addToCart.php?stone_id=<?= $biddingStone['stone_id'] ?>" class="bid-now-button">Add To Cart</a>
                                </div>
                                <p>
                                    Please note: You must complete the purchase within <strong>7 days</strong>
                                    of winning the bid. If you fail to do so, the stone will be <strong>revoked</strong>.
                                    Repeated failure (twice) to complete purchases will result in <strong>disqualification from future bids</strong>.
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="bid-cycle-container">
                    <table class="bids-table">
                        <thead>
                          <tr>
                            <th>Bid ID</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Value</th>
                            <th>Bidder Name</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $bidsResult->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?= $row['bid_id'] ?></td>
                                    <td><?= date('d M Y', strtotime($row['time'])) ?></td>
                                    <td><?= date('h:i A', strtotime($row['time'])) ?></td>
                                    <td><?= number_format($row['amount']) ?></td>
                                    <td><?= $row['bidderName'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../../../components/profileHeader/header.js"></script>
    <script src="../../components/footer/footer.js"></script>
    <script src="./profile.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
