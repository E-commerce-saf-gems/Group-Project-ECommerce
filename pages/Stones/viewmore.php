<?php
include('../../database/db.php'); 

if (isset($_GET['id'])) {
    $stone_id = $_GET['id'];

    $sql = "SELECT * FROM inventory WHERE stone_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $stone_id);
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
    <link rel="stylesheet" href="/styles/common.css">
    <link rel="stylesheet" href="/components/header/header.css">
    <link rel="stylesheet" href="/components/footer/footer.css">
    <link rel="stylesheet" href="./styles.css">
    <title>View More Details</title>
    <style>
        /* Basic styling for the bidding detail page */
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .product-detail {
            display: flex;
            max-width: 800px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background: white;
            z-index: 1000;
        }

        .product-image {
            flex: 1;
        }

        .product-image img {
            width: 100%;
            height: auto;
        }

        .product-info {
            flex: 1;
            padding: 20px;
        }

        .product-info h2 {
            margin: 0;
            font-size: 24px;
        }

        .price {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin: 10px 0;
        }

        .details {
            margin: 10px 0;
        }

        
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #449b9b;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            margin-right: 10px;
        }

        .btn:hover {
            background-color: #367a7a;
        }

        /* Overlay for background blur effect */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 900;
        }

        .blurred {
            filter: blur(5px);
        }

        /* Bidding confirmation box */
        .bid-confirmation {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            text-align: center;
        }

        .bid-confirmation h3 {
            margin: 0 0 20px;
            font-size: 20px;
        }

        .bid-confirmation button {
            padding: 10px 20px;
            background-color: #449b9b;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin: 5px;
        }

        .bid-confirmation button:hover {
            background-color: #367a7a;
        }

            /* Container to display items in a row */
        .shop-card-container {
            display: flex;
            flex-wrap: wrap; /* Allows items to wrap onto the next line if needed */
            gap: 20px; /* Adds spacing between the cards */
            justify-content: center; /* Centers the cards in the container */
            margin-top: 20px;
        }

        .shop-card {
            width: 200px; /* Set a fixed width for each card */
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            padding: 10px;
            text-align: center;
        }

    </style>
</head>

<body>
    <custom-header></custom-header>
    <a href="../../pages/Stones/StonesHomePage.php"><button>Back</button></a>
    <div class="container">
        <div class="product-detail" id="product-detail">
            <div class="product-image">
            <?php if (!empty($row['image'])): ?>
                  <img src="http://localhost/Business-Dashboard/uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Uploaded Image";/>
              <?php endif; ?>            
            </div>
            <div class="product-info">
                <h2><?php echo $row['type'];?></h2>
                <div class="price" id="amount"><?php echo $row['origin'];?></div>
                <div class="details">
                    <p><?php echo $row['description'];?></p>
                    <p>Color: <?php echo $row['colour'];?></p>
                    <p>Shape: <?php echo $row['shape'];?></p>
                    <p>Amount: Rs. <?php echo $row['amount'];?></p>
                </div>
                <button class="btn" onclick="viewCertificate()">View Certificate</button> 
                <form action="./addToCart.php" method="POST">  
                    <input type="hidden" name="stone_id" value="<?php echo $row['stone_id'];?>">
                    <button type="submit">Add to cart</button>   
                </form>         
            </div>
        </div>
    </div>


    
    <div class="bid-confirmation" id="bid-confirmation">
        <h3>Certificate</h3>
        <?php if (!empty($row['certificate'])): ?>
                <img src="http://localhost/Business-Dashboard/uploads/<?php echo htmlspecialchars($row['certificate']); ?>" style="max-width: 300px;" />
              <?php endif; ?>        <div>
            <button onclick="closeModal()">Close</button>
            <button onclick="window.open('http://localhost/Business-Dashboard/uploads/<?php echo htmlspecialchars($row['certificate']); ?>', '_blank')">Open in New Tab</button>        </div>
    </div>
        
   
     <script>
        // Function to open the certificate view in a modal
        function viewCertificate() {
            // Show the modal and overlay
            document.getElementById('bid-confirmation').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById('bid-confirmation').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }
        
    </script>

    <script src="/components/header/header.js"></script>
    <script src="/components/footer/footer.js"></script>
</body>

</html>