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
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles/common.css">
    <link rel="stylesheet" href="../../components/header/header.css">
    <link rel="stylesheet" href="../../components/footer/footer.css">
    <link rel="stylesheet" href="./contact.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <title>Product</title>
    <style>
        /* Back button */
        .back-btn {
          margin-bottom: 20px;
          padding: 10px 15px;
          background-color: #449b9b;
          color: white;
          border: none;
          border-radius: 5px;
          cursor: pointer;
          transition: background-color 0.3s ease;
        }
    
        .back-btn:hover {
          background-color: #367a7a;
        }
    
        /* Product Detail Layout */
        .product-detail-container {
          display: flex;
          flex-wrap: wrap;
          max-width: 1000px;
          margin: auto;
          gap: 20px;
          padding: 20px;
          background: white;
          border-radius: 12px;
          box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }
    
        .product-images {
          display: flex;
          gap: 10px;
          flex: 1;
          min-width: 300px;
        }
    
        .thumbnails {
          display: flex;
          flex-direction: column;
          gap: 10px;
        }
    
        .thumbnails img {
          width: 60px;
          height: 60px;
          object-fit: cover;
          border: 1px solid #ccc;
          border-radius: 6px;
          cursor: pointer;
        }
    
        .main-image img {
          width: 250px;
          height: 250px;
          object-fit: cover;
          border-radius: 10px;
        }
    
        .product-info {
          flex: 1;
          min-width: 300px;
        }
    
        .product-info h2 {
          margin: 0 0 10px;
          font-size: 24px;
          color: #333;
        }
    
        .product-info .price {
          font-size: 20px;
          color: #449b9b;
          margin-left: 10px;
        }
    
        .details {
          margin-bottom: 20px;
          font-weight: 500;
        }
    
        .btn {
          display: inline-block;
          padding: 10px 20px;
          background-color: #449b9b;
          color: white;
          border: none;
          border-radius: 5px;
          cursor: pointer;
          margin-bottom: 20px;
          transition: background-color 0.3s ease;
        }
    
        .btn:hover {
          background-color: #367a7a;
        }
  
    
        /* Shop Card Section */
        .section-heading {
          text-align: center;
          margin: 50px 0 20px;
          font-size: 24px;
          color: #333;
        }
    
        .shop-card-container {
          display: flex;
          justify-content: center;
          flex-wrap: wrap;
          gap: 20px;
          padding: 0 20px 40px;
        }
    
        .shop-card {
          width: 200px;
          border: 1px solid #ddd;
          border-radius: 10px;
          overflow: hidden;
          box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
          background-color: #fff;
          text-align: center;
          padding: 10px;
        }
    
        .shop-card img {
          width: 100%;
          height: 180px;
          object-fit: cover;
          border-bottom: 1px solid #eee;
        }
    
        .shop-card h4 {
          margin: 10px 0 5px;
          font-size: 16px;
          font-weight: 600;
          color: #333;
        }
    
        .shop-card .price {
          color: #449b9b;
          font-weight: bold;
        }
    
        /* Responsive */
        @media (max-width: 768px) {
          .product-detail-container {
            flex-direction: column;
            align-items: center;
          }
    
          .main-image img {
            width: 100%;
            height: auto;
          }
    
          .thumbnails {
            flex-direction: row;
            justify-content: center;
          }
    
          .thumbnails img {
            width: 50px;
            height: 50px;
          }
    
          .product-images {
            flex-direction: column;
            align-items: center;
          }
        }
      </style>
</head>
    <custom-header></custom-header>
    <a href="../../pages/Stones/StonesHomePage.php">
      </a>
    
      <div class="product-detail-container">
        <div class="product-images">
          <div class="thumbnails">
            <?php if (!empty($row['image'])): ?>
              <img src="http://localhost/Group-Project-ECommerce/assets/images/<?php echo htmlspecialchars($row['certificate']); ?>" alt="Thumbnail 1" />
            <?php endif; ?>
          </div>
          <div class="main-image">
            <?php if (!empty($row['image'])): ?>
              <img src="http://localhost/Group-Project-ECommerce/assets/images/<?php echo htmlspecialchars($row['image']); ?>" alt="Main Image" />
            <?php endif; ?>
          </div>
        </div>
    
        <div class="product-info">
          <h2><?php echo htmlspecialchars($row['type']); ?> 
          <span class="price">Rs. <?php echo htmlspecialchars($row['amount']); ?></span>
          </h2>
          <p class="details">
            Color: <?php echo htmlspecialchars($row['colour']); ?> | 
            Shape: <?php echo htmlspecialchars($row['shape']); ?> | 
            Origin: <?php echo htmlspecialchars($row['origin']); ?>
          </p>
    
          <form action="./addToCart.php" method="POST">
            <input type="hidden" name="stone_id" value="<?php echo htmlspecialchars($row['stone_id']); ?>" />
            <button class="btn" type="submit">Add To Cart</button>
          </form>
    
          <div class="description">
            <h3>Description</h3>
            <p><?php echo htmlspecialchars($row['description']); ?></p>
          </div>
        </div>
      </div>
    
      <h2 class="section-heading"><?php echo htmlspecialchars($row['type']); ?> Suggestions</h2>
      <div class="shop-card-container">
        <?php for ($i = 0; $i < 4; $i++): ?>
          <div class="shop-card">
            <img src="http://localhost/Business-Dashboard/uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Product" />
            <h4><?php echo htmlspecialchars($row['type']); ?></h4>
            <p class="price">Rs. <?php echo htmlspecialchars($row['amount']); ?></p>
          </div>
        <?php endfor; ?>
      </div>
    
      <div class="bid-confirmation" id="bid-confirmation" style="display:none;">
        <h3>Certificate</h3>
        <?php if (!empty($row['certificate'])): ?>
          <img src="http://localhost/Business-Dashboard/uploads/<?php echo htmlspecialchars($row['certificate']); ?>" style="max-width: 300px;" />
          <div>
            <button onclick="closeModal()">Close</button>
            <button onclick="window.open('http://localhost/Business-Dashboard/uploads/<?php echo htmlspecialchars($row['certificate']); ?>', '_blank')">Open in New Tab</button>
          </div>
        <?php endif; ?>
      </div>
    
      <custom-footer></custom-footer>
      <script>
        function viewCertificate() {
          document.getElementById('bid-confirmation').style.display = 'block';
        }
        function closeModal() {
          document.getElementById('bid-confirmation').style.display = 'none';
        }
      </script>
      <script src="../../components/header/header.js"></script>
      <script src="/components/footer/footer.js"></script>
      <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
      <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
