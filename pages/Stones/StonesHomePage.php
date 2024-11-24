<?php
include '../../database/db.php';

// Corrected SQL query syntax
$ssql = "SELECT 
            amount, 
            type, 
            origin,
            description,
            image, 
            size 
        FROM inventory
        WHERE visibility = 'show' && availability = 'available'";

$result = $conn->query($ssql);

// Check if query was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/pages/homepage/styles.css">
    <link rel="stylesheet" href="/styles/common.css">
    <link rel="stylesheet" href="/components/header/header.css">
    <link rel="stylesheet" href="/components/footer/footer.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Stones/styles.css">
    <title>Gem Product Catalog</title>
</head>

<body>
    <custom-header></custom-header>

    <div class="container">
        <!-- Sidebar for filter and sort options -->
        <aside class="sidebar">
            <h2>Filter & Sort</h2>
            <div class="filter-section">
                <h3>Sort By</h3>
                <label for="sort">Sort By:</label>
                <select id="sort">
                    <option value="best-match">Best Match</option>
                    <option value="price-low-to-high">Price: Low to High</option>
                    <option value="price-high-to-low">Price: High to Low</option>
                </select>
            </div>

            <div class="filter-section">
                <h3>Filter By</h3>
                <label for="carat-weight">Carat Weight (in carats):</label>
                <input type="range" id="carat-weight" name="carat-weight" min="1" max="10" value="5" step="0.5">
                <span id="carat-weight-display">5 carats</span><br />
                <label for="shape">Shape:</label>
                <select id="shape">
                    <option value="round">Round</option>
                    <option value="oval">Oval</option>
                    <option value="pear">Pear</option>
                    <option value="emerald">Emerald</option>
                    <option value="cushion">Cushion</option>
                </select>
                <label for="color">Color:</label>
                <select id="color">
                    <option value="blue">Blue</option>
                    <option value="yellow">Yellow</option>
                    <option value="pink">Pink</option>
                    <option value="green">Green</option>
                    <option value="clear">Clear</option>
                </select>
                <label for="origin">Origin</label>
                <select id="origin">
                    <option value="srilanka">Sri Lanka</option>
                    <option value="Canada">Canada</option>
                    <option value="Dubai">Dubai</option>
                    <option value="Africa">Africa</option>
                </select>
                <label for="price-range">Price Range:</label>
                <input type="number" id="min-price" placeholder="Min" min="0" step="50">
                <input type="number" id="max-price" placeholder="Max" min="0" step="50">
            </div>
        </aside>

        <!-- Main content area with product catalog -->
        <main class="product-catalog">
                <?php while ($row = $result->fetch_assoc()) : ?>
            <div class="shop-card">
                <div class="card-banner">
                <img 
                src="<?php echo htmlspecialchars('http://localhost/Business-Dashboard/uploads/' . $row['image']); ?>" 
                alt="<?php echo htmlspecialchars($row['type']); ?>" 
                style="width: 200px; height: 200px; object-fit: cover;">

                    <div class="card-actions">
                        <button class="action-btn btn" aria-label="bid now">
                            <ion-icon name="hammer-outline" aria-hidden="true"></ion-icon>
                        </button>
                        <button class="action-btn btn" aria-label="view more">
                            <ion-icon name="eye-outline" aria-hidden="true"></ion-icon>
                        </button>
                    </div>
                </div>
                <div class="card-content">
                    <div class="price">$<?php echo number_format($row['amount'], 2); ?></div>
                    <h3><a href="#" class="card-title"><?php echo htmlspecialchars($row['type']); ?></a></h3>
                    <p class="rating-text"><?php echo htmlspecialchars($row['description']); ?> - <?php echo $row['size']; ?> Carats</p>
                    <a href="../../pages/Stones/viewmore.php"><span class="btn btn-primary">View More</span></a>
                </div>
            </div>
        <?php endwhile; ?>

        </main>
    </div>

    <script src="/components/header/header.js"></script>
    <script src="/components/footer/footer.js"></script>
    <script src="/pages/homepage/script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <script>
        // JavaScript to display the selected carat weight value dynamically
        const caratWeightInput = document.getElementById('carat-weight');
        const caratWeightDisplay = document.getElementById('carat-weight-display');
        caratWeightInput.addEventListener('input', function () {
            caratWeightDisplay.textContent = caratWeightInput.value + " carats";
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>