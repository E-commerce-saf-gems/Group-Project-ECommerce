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
           (SELECT MAX(amount) FROM bid WHERE biddingStone_id = bs.biddingStone_id AND validity='valid') AS highestBid
    FROM biddingstone bs
    JOIN inventory inv ON bs.stone_id = inv.stone_id
    WHERE bs.biddingStone_id = $biddingStoneId AND bs.finishDate < '$currentDateTime'
";

$biddingStoneResult = $conn->query($biddingStoneQuery);
$biddingStone = $biddingStoneResult->fetch_assoc();

// Determine if the logged-in user is the winner
$isWinner = isset($biddingStone['customer_id']) && $biddingStone['customer_id'] == $customer_id;

// Get the current user's highest valid bid for this stone
$userBidQuery = "
    SELECT MAX(amount) AS userHighestBid 
    FROM bid 
    WHERE biddingStone_id = $biddingStoneId 
      AND customer_id = $customer_id 
      AND validity = 'valid'
";
$userBidResult = $conn->query($userBidQuery);
$userBid = $userBidResult->fetch_assoc();
$userHighestBid = $userBid['userHighestBid'] ?? 0;


// Get all bids for the stone
$bidsQuery = "
    SELECT b.bid_id, b.amount,b.validity, b.time, c.firstName AS bidderName, b.customer_id
    FROM bid b
    INNER JOIN customer c ON b.customer_id = c.customer_id
    WHERE b.biddingStone_id = $biddingStoneId
    ORDER BY b.time DESC
";
$bidsResult = $conn->query($bidsQuery);


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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
                <li><a href="../Meetings/MyMeetings.php">My Meetings</a></li>
                <li><a href="../Purchases/MyPurchases.php">Purchases</a></li>
                <li><a href="../Requests/MyRequest.php">Requests</a></li>
                <li><a href="../../Login/logout.php">Signout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
         <?php if($biddingStoneResult->num_rows ==0):?>
          <div class="main-content">
            <h1>My Account</h1>
            <h2>This Bid Is Currently Not Available. The Bid May Be Put Back For Re-bidding. Check Current Live Bids</h2>
          </div>
         <?php endif; ?>
         <?php if ($biddingStoneResult->num_rows >0) :?>
          <?php 
  $purchaseDeadline = date('Y-m-d H:i:s', strtotime($biddingStone['finishDate'] . ' +7 days'));
?>

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
                            <div><strong>Highest Bid:</strong> <span class="info-value bid-result win"><?= number_format($biddingStone['highestBid']) ?></span></div>
                            <div>
                              <strong>Your Current Highest:</strong>
                              <span class="info-value">
                                <?php if ($userHighestBid): ?>
                                  <?php if ($userHighestBid < $biddingStone['highestBid']): ?>
                                    <span class="bid-result loss"><?= number_format($userHighestBid) ?></span>
                                    <span class="bid-result-difference loss">
                                      <i class='bx bx-chevrons-down'></i><?= number_format($biddingStone['highestBid'] - $userHighestBid) ?>
                                    </span>
                                  <?php else: ?>
                                    <span class="bid-result win"><?= number_format($userHighestBid) ?></span>
                                  <?php endif; ?>
                                <?php else: ?>
                                  <span class="bid-result">No valid bids yet</span>
                                <?php endif; ?>
                              </span>
                            </div>
                            <div><strong>Overall Status</strong> 
                                <?php if ($isWinner): ?>
                                    <span class="info-value bid-result win">Win</span>
                                <?php else: ?>
                                    <span class="info-value bid-result loss">Loss</span>
                                <?php endif; ?>
                            </div>
                            <?php if ($isWinner && $currentDateTime < $purchaseDeadline): ?>
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
                            <th>Validity</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $previousAmount = null; 

                          while($row = $bidsResult->fetch_assoc()):
                              $currentAmount = $row['amount'];
                              $diffFromPrevious = $previousAmount !== null ? $previousAmount-$currentAmount : 0;
                              $diffFormatted = number_format($diffFromPrevious);
                          ?>
                              <tr>
                                  <td>#<?= $row['bid_id'] ?></td>
                                  <td><?= date('d M Y', strtotime($row['time'])) ?></td>
                                  <td><?= date('h:i A', strtotime($row['time'])) ?></td>
                                  <td>
                                      <span class="bid-result win"><?= number_format($currentAmount) ?></span>
                                      <?php if ($previousAmount !== null): ?>
                                          <span class="bid-result-difference <?= $diffFromPrevious >= 0 ? 'win' : 'loss' ?>">
                                              <i class='bx <?= $diffFromPrevious >= 0 ? 'bx-chevrons-up' : 'bx-chevrons-down' ?>'></i>
                                              <?= $diffFormatted ?>
                                          </span>
                                      <?php endif; ?>
                                  </td>
                                  <td><?= $row['bidderName'] ?></td>
                                  <td>
                                      <?php if($row['validity'] == 'invalid'): ?> 
                                        <span class="bid-result loss">
                                          <?= $row['validity'] ?>
                                        </span>
                                      <?php else: ?>
                                        <span class="bid-result win">
                                          <?= $row['validity'] ?>
                                        </span>
                                      <?php endif; ?>
                                  </td>
                              </tr>
                          <?php
                              $previousAmount = $currentAmount; // Update for next loop
                          endwhile;
                          ?>
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
         <?php endif ?>

    </div>

    <script src="../../../components/profileHeader/header.js"></script>
    <script src="../../components/footer/footer.js"></script>
    <script src="./profile.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
