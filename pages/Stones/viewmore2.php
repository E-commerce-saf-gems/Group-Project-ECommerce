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
  <link rel="stylesheet" href="./styles.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <title>Product</title>
</head>
<custom-header></custom-header>
<a href="../../pages/Stones/StonesHomePage.php">
</a>

<div class="product-detail-container">
  <div class="product-images">
    <div class="thumbnails">
      <?php if (!empty($row['image'])): ?>
        <img
          src="http://localhost/Group-Project-ECommerce/assets/images/<?php echo htmlspecialchars($row['certificate']); ?>"
          alt="Thumbnail 1" onclick="viewCertificate()" />
      <?php endif; ?>
    </div>
    <div class="main-image">
      <?php if (!empty($row['image'])): ?>
        <img src="http://localhost/Group-Project-ECommerce/assets/images/<?php echo htmlspecialchars($row['image']); ?>"
          alt="Main Image" />
      <?php endif; ?>
    </div>
  </div>

  <div class="product-info">
    <h2><?php echo htmlspecialchars($row['type']); ?>
      <span class="price">Rs. <?php echo htmlspecialchars($row['amount']); ?></span>
    </h2>
    <p class="details">
      Color: <?php echo htmlspecialchars($row['colour']); ?> |
      Type: <?php echo htmlspecialchars($row['type']); ?> |
      Shape: <?php echo htmlspecialchars($row['shape']); ?> |
      Origin: <?php echo htmlspecialchars($row['origin']); ?>
    </p>
    <button class="btn" onclick="viewCertificate()">View Certificate</button>

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


<h2 class="section-heading">More Stones</h2>
<?php
$suggestionsSql = "SELECT * FROM inventory ORDER BY stone_id DESC LIMIT 4";
$suggestionsResult = $conn->query($suggestionsSql);

if ($suggestionsResult && $suggestionsResult->num_rows > 0):
  ?>
  <div class="shop-card-container">
    <?php while ($suggestion = $suggestionsResult->fetch_assoc()): ?>
      <div class="shop-card">
      <img
  src="http://localhost/Group-Project-ECommerce/assets/images/<?php echo htmlspecialchars($suggestion['image']); ?>"
  alt="Product"
  style="cursor: pointer;"
  onclick="window.location.href='../../pages/Stones/viewmore2.php?id=<?php echo $suggestion['stone_id']; ?>';" />

        <h4><?php echo htmlspecialchars($suggestion['type']); ?></h4>
        <p class="price">Rs. <?php echo htmlspecialchars($suggestion['amount']); ?></p>
      </div>
    <?php endwhile; ?>
  </div>
<?php else: ?>
  <p style="text-align: center;">No suggestions found.</p>
<?php endif; ?>


<!-- Certificate Modal -->
<div class="bid-confirmation" id="bid-confirmation"
  style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:white; padding:20px; border-radius:10px; z-index:1001;">
  <h3>Certificate</h3>
  <?php if (!empty($row['certificate'])): ?>
    <img src="http://localhost/Business-Dashboard/uploads/<?php echo htmlspecialchars($row['certificate']); ?>"
      style="max-width: 300px;" />
    <div style="margin-top: 10px;">
      <button onclick="closeModal()">Close</button>
      <button
        onclick="window.open('http://localhost/Business-Dashboard/uploads/<?php echo htmlspecialchars($row['certificate']); ?>', '_blank')">Open
        in New Tab</button>
    </div>
  <?php endif; ?>
</div>


<custom-footer></custom-footer>
<script>
  function viewCertificate() {
    document.getElementById('bid-confirmation').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
  }

  function closeModal() {
    document.getElementById('bid-confirmation').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
  }
</script>

<script src="../../components/header/header.js"></script>
<script src="/components/footer/footer.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>