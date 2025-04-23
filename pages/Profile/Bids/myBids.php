<?php
session_start();
$customer_id = $_SESSION['customer_id'];

include '../../../database/db.php';

date_default_timezone_set('Asia/Kolkata');
$currentDateTime = date('Y-m-d H:i:s');

$myBidsQuery = "
    SELECT bs.*, 
           i.image, CONCAT (i.size, 'crt ' ,i.colour, '  ', i.type) as stone,
           (SELECT MAX(amount) FROM bid WHERE biddingStone_id = bs.biddingStone_id AND customer_id = $customer_id) AS myHighestBid,
           (SELECT MAX(amount) FROM bid WHERE biddingStone_id = bs.biddingStone_id) AS highestBid
    FROM biddingstone bs
    JOIN inventory i ON bs.stone_id = i.stone_id
    WHERE bs.startDate <= '$currentDateTime' 
      AND bs.finishDate > '$currentDateTime'
      AND EXISTS (
          SELECT 1 FROM bid b1 
          WHERE b1.biddingStone_id = bs.biddingStone_id 
            AND b1.customer_id = $customer_id
      )
";


$myBidsResult = $conn->query($myBidsQuery);

$liveBidsQuery = "
    SELECT bs.*, 
           i.image, CONCAT (i.size, 'crt  ' ,i.colour, '  ', i.type) as stone,
           (SELECT MAX(amount) FROM bid WHERE biddingStone_id = bs.biddingStone_id) as highestBid
    FROM biddingstone bs
    JOIN inventory i ON bs.stone_id = i.stone_id
    WHERE bs.startDate <= '$currentDateTime' AND bs.finishDate > '$currentDateTime'
      AND NOT EXISTS (
          SELECT 1 FROM bid b WHERE b.biddingStone_id = bs.biddingStone_id AND b.customer_id = $customer_id
      )
";


$liveBidsResult = $conn->query($liveBidsQuery);

$upcomingQuery = "SELECT * FROM biddingstone WHERE startDate > '$currentDateTime'";
$upcomingResult = $conn->query($upcomingQuery);

$completedQuery = "
    SELECT bs.*, 
           i.image, CONCAT (i.size, 'crt  ' ,i.colour, '  ', i.type) as stone,
           (SELECT MAX(amount) FROM bid WHERE biddingStone_id = bs.biddingStone_id) as finalValue,
           (CASE 
                WHEN (SELECT MAX(amount) FROM bid WHERE biddingStone_id = bs.biddingStone_id AND bs.customer_id = $customer_id) = (SELECT MAX(amount) FROM bid WHERE biddingStone_id = bs.biddingStone_id) 
                THEN 'Win' ELSE 'Loss' 
            END) as result
    FROM biddingstone bs
    JOIN inventory i ON bs.stone_id = i.stone_id
    INNER JOIN bid b ON bs.biddingStone_id = b.biddingStone_id
    WHERE bs.finishDate <= '$currentDateTime' AND b.customer_id = $customer_id
    GROUP BY bs.biddingStone_id
";


$completedResult = $conn->query($completedQuery);
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preload" as="image" href="../../../assets/images/logo.png">


    <title>My Bids</title>
</head>
<body>
    <custom-header></custom-header>

    <div class="profile-container profile-h2">
        <div class="profile-sidebar">
            <h2>Hello</h2>
            <ul>
                <li><a href="../Details/MyDetails.php">My Details</a></li>
                <li><a href="../Bids/MyBids.html" class="active">My Bids</a></li>
                <li><a href="../Wishlist/MyWishlist.html">My Wishlist</a></li>
                <li><a href="../Sales/MySales.html">My Sales</a></li>
                <li><a href="../Meetings/MyMeetings.php">My Meetings</a></li>
                <li><a href="../Purchases/MyPurchases.php">Purchases</a></li>
                <li><a href="../Requests/MyRequest.php">Requests</a></li>
                <li><a href="../Emails/MyEmails.html">Email Preferences</a></li>
                <li><a href="../../Login/logout.php">Signout</a></li>
            </ul>
        </div>

        <div class="main-content">
            <h1>My Account</h1>
            <h2>My Bids</h2>
            <div class="bids-wrapper">
                <!-- My Active Bids -->
                <div class="bids-box">
                    <h3 class="mybids-text"><span class="dot blue"></span> My Ongoing Bids</h3>
                    <div class="bids-table-wrapper">
                        <table class="bids-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Stone</th>
                                    <th>Starting Bid (Rs.)</th>
                                    <th>Highest Bid (Rs.)</th>
                                    <th>My Highest Bid (Rs.)</th>
                                    <th>Actions</th>
                                    <th>Time Left</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php while($row = $myBidsResult->fetch_assoc()): ?>
                                <?php
                                    $startingBid = $row['startingBid'];
                                    $highestBid = $row['highestBid'];
                                    $myHighestBid = $row['myHighestBid'];

                                    $increaseFromStart = $highestBid - $startingBid;
                                    $diffToMyBid = $highestBid - $myHighestBid;

                                    $increaseFormatted = number_format($increaseFromStart);
                                    $differenceFormatted = number_format($diffToMyBid);
                                ?>
                                    <tr>
                                        <td><img src="http://localhost/Group-Project-ECommerce/assets/images/<?= $row['image'] ?>" alt="stone"></td>
                                        <td><?= $row['stone'] ?></td>
                                        <td><?= number_format($startingBid) ?></td>

                                        <td>
                                            <span class="bid-result none"><?= number_format($highestBid) ?></span>
                                            <span class="bid-result-difference win"><i class='bx bx-chevrons-up'></i><?= $increaseFormatted ?></span>
                                        </td>

                                        <td>
                                            <?php if ($myHighestBid < $highestBid): ?>
                                                <span class="bid-result loss"><?= number_format($myHighestBid) ?></span>
                                                <span class="bid-result-difference loss"><i class='bx bx-chevrons-down'></i><?= $differenceFormatted ?></span>
                                            <?php else: ?>
                                                <span class="bid-result win"><?= number_format($myHighestBid) ?></span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <a href="./activeBids.php?id=<?= $row['biddingStone_id'] ?>" class="bid-now-button">Details</a>
                                            <?php if ($myHighestBid < $highestBid): ?>
                                                <a href="../../bidding/bidding-itemPage.php?id=<?= $row['biddingStone_id'] ?>" class="bid-now-button">Bid Now</a>
                                            <?php endif; ?>
                                        </td>

                                        <td><?= date_diff(date_create($currentDateTime), date_create($row['finishDate']))->format('%dD %hH %iM') ?></td>
                                    </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Live Bids -->
                <div class="bids-box">
                    <h3 class="active-text"><span class="dot red"></span> Current Live Bids</h3>
                    <div class="bids-table-wrapper">
                        <table class="bids-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Stone</th>
                                    <th>Starting Bid (Rs.)</th>
                                    <th>Highest Bid (Rs.)</th>
                                    <th>Actions</th>
                                    <th>Time Left</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php while($row = $liveBidsResult->fetch_assoc()): ?>
                                <tr>
                                    <td><img src="http://localhost/Group-Project-ECommerce/assets/images/<?= $row['image'] ?>" alt="stone"></td>
                                    <td>#<?= $row['stone'] ?></td>
                                    <td><?= number_format($row['startingBid']) ?></td>
                                    <td><span class="bid-result none"><?= number_format($row['highestBid']) ?></span></td>
                                    <td>
                                        <a href="./activeBids.php?id=<?= $row['biddingStone_id'] ?>" class="bid-now-button">Details</a>
                                        <a href="../../bidding/bidding-itemPage.php?id=<?= $row['biddingStone_id'] ?>" class="bid-now-button">Bid Now</a>
                                    </td>
                                    <td><?= date_diff(date_create($currentDateTime), date_create($row['finishDate']))->format('%dD %hH %iM') ?></td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Completed Bids -->
                <div class="bids-box">
                    <h3 class="completed-text"><span class="dot green"></span>Completed Bids</h3>
                    <div class="bids-table-wrapper">
                        <table class="bids-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Stone</th>
                                    <th>End Date</th>
                                    <th>Win/Loss</th>
                                    <th>Final Value</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php while($row = $completedResult->fetch_assoc()): ?>
                                <tr>
                                    <td><img src="http://localhost/Group-Project-ECommerce/assets/images/<?= $row['image'] ?>" alt="stone"></td>
                                    <td><?= $row['stone'] ?></td>
                                    <td><?= date('M d', strtotime($row['finishDate'])) ?></td>
                                    <td>
                                        <div class="bid-result-div">
                                            <span class="bid-result <?= strtolower($row['result']) ?>"><?= $row['result'] ?></span>
                                        </div>
                                    </td>
                                    <td><?= number_format($row['finalValue']) ?></td>
                                    <td>
                                        <a href="./completedBids.php?id=<?= $row['biddingStone_id'] ?>" class="bid-now-button">Details</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <custom-footer></custom-footer>
    <script src="../../../components/profileHeader/header.js"></script>
    <script src="../../components/footer/footer.js"></script>
    <script src="./profile.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
