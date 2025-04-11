<?php
include '../../database/db.php';

// Base query
$ssql = "SELECT * FROM inventory WHERE visibility = 'show' AND availability = 'available'";

// Apply filters
if (isset($_GET['carat-weight']) && !empty($_GET['carat-weight'])) {
    $caratWeight = (float)$_GET['carat-weight'];
    $ssql .= " AND size <= $caratWeight";
}

if (isset($_GET['shape']) && !empty($_GET['shape'])) {
    $shape = $conn->real_escape_string($_GET['shape']);
    $ssql .= " AND shape = '$shape'";
}

if (isset($_GET['colour']) && !empty($_GET['colour'])) {
    $colour = $conn->real_escape_string($_GET['colour']);
    $ssql .= " AND colour = '$colour'";
}

if (isset($_GET['origin']) && !empty($_GET['origin'])) {
    $origin = $conn->real_escape_string($_GET['origin']);
    $ssql .= " AND origin = '$origin'";
}

if (isset($_GET['min-price']) && is_numeric($_GET['min-price'])) {
    $minPrice = (float)$_GET['min-price'];
    $ssql .= " AND amount >= $minPrice";
}

if (isset($_GET['max-price']) && is_numeric($_GET['max-price'])) {
    $maxPrice = (float)$_GET['max-price'];
    $ssql .= " AND amount <= $maxPrice";
}

// Apply sorting
if (isset($_GET['sort']) && !empty($_GET['sort'])) {
    $sortOption = $_GET['sort'];
    if ($sortOption == 'price-low-to-high') {
        $ssql .= " ORDER BY amount ASC";
    } elseif ($sortOption == 'price-high-to-low') {
        $ssql .= " ORDER BY amount DESC";
    } elseif ($sortOption == 'new-arrival') {
        $ssql .= " ORDER BY stone_id DESC"; // Assuming `created_at` is a column for new arrivals
    }
}

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
   <!-- filepath: c:\xampp\htdocs\Group-Project-ECommerce\pages\Stones\StonesHomePage.php -->
<form method="GET" action="">
    <div class="filter-section">
        <h3>Sort By</h3>
        <label for="sort">Sort By:</label>
        <select id="sort" name="sort">
            <option value="new-arrival" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'new-arrival') ? 'selected' : ''; ?>>New Arrival</option>
            <option value="price-low-to-high" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price-low-to-high') ? 'selected' : ''; ?>>Price: Low to High</option>
            <option value="price-high-to-low" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price-high-to-low') ? 'selected' : ''; ?>>Price: High to Low</option>
        </select>
    </div>

    <div class="filter-section">
        <h3>Filter By</h3>
        <label for="carat-weight">Carat Weight (in carats):</label>
        <input type="range" id="carat-weight" name="carat-weight" min="1" max="10" value="<?php echo isset($_GET['carat-weight']) ? htmlspecialchars($_GET['carat-weight']) : '5'; ?>" step="0.5">
        <span id="carat-weight-display"><?php echo isset($_GET['carat-weight']) ? htmlspecialchars($_GET['carat-weight']) : '5'; ?> carats</span><br />

        <label for="shape">Shape:</label>
        <select id="shape" name="shape">
            <option value="" <?php echo (!isset($_GET['shape']) || $_GET['shape'] == '') ? 'selected' : ''; ?>>Any</option>
            <option value="round" <?php echo (isset($_GET['shape']) && $_GET['shape'] == 'round') ? 'selected' : ''; ?>>Round</option>
            <option value="oval" <?php echo (isset($_GET['shape']) && $_GET['shape'] == 'oval') ? 'selected' : ''; ?>>Oval</option>
            <option value="pear" <?php echo (isset($_GET['shape']) && $_GET['shape'] == 'pear') ? 'selected' : ''; ?>>Pear</option>
            <option value="emerald" <?php echo (isset($_GET['shape']) && $_GET['shape'] == 'emerald') ? 'selected' : ''; ?>>Emerald</option>
            <option value="cushion" <?php echo (isset($_GET['shape']) && $_GET['shape'] == 'cushion') ? 'selected' : ''; ?>>Cushion</option>
        </select>

        <label for="colour">Colour:</label>
        <select id="colour" name="colour">
            <option value="" <?php echo (!isset($_GET['colour']) || $_GET['colour'] == '') ? 'selected' : ''; ?>>Any</option>
            <option value="blue" <?php echo (isset($_GET['colour']) && $_GET['colour'] == 'blue') ? 'selected' : ''; ?>>Blue</option>
            <option value="yellow" <?php echo (isset($_GET['colour']) && $_GET['colour'] == 'yellow') ? 'selected' : ''; ?>>Yellow</option>
            <option value="pink" <?php echo (isset($_GET['colour']) && $_GET['colour'] == 'pink') ? 'selected' : ''; ?>>Pink</option>
            <option value="green" <?php echo (isset($_GET['colour']) && $_GET['colour'] == 'green') ? 'selected' : ''; ?>>Green</option>
            <option value="clear" <?php echo (isset($_GET['colour']) && $_GET['colour'] == 'clear') ? 'selected' : ''; ?>>Clear</option>
        </select>

        <label for="origin">Origin</label>
        <select id="origin" name="origin">
            <option value="" <?php echo (!isset($_GET['origin']) || $_GET['origin'] == '') ? 'selected' : ''; ?>>Any</option>
            <option value="Sri Lanka" <?php echo (isset($_GET['origin']) && $_GET['origin'] == 'srilanka') ? 'selected' : ''; ?>>Sri Lanka</option>
            <option value="Canada" <?php echo (isset($_GET['origin']) && $_GET['origin'] == 'Canada') ? 'selected' : ''; ?>>Canada</option>
            <option value="Dubai" <?php echo (isset($_GET['origin']) && $_GET['origin'] == 'Dubai') ? 'selected' : ''; ?>>Dubai</option>
            <option value="Africa" <?php echo (isset($_GET['origin']) && $_GET['origin'] == 'Africa') ? 'selected' : ''; ?>>Africa</option>
        </select>

        <label for="price-range">Price Range:</label>
        <input type="number" id="min-price" name="min-price" placeholder="Min" min="0" step="50" value="<?php echo isset($_GET['min-price']) ? htmlspecialchars($_GET['min-price']) : ''; ?>">
        <input type="number" id="max-price" name="max-price" placeholder="Max" min="0" step="50" value="<?php echo isset($_GET['max-price']) ? htmlspecialchars($_GET['max-price']) : ''; ?>">
    </div>
    <button type="submit">Apply Filters</button>
</form>
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
                    <div class="price"><?php echo number_format($row['size'], 2); ?> carats</div> <!-- new -->
                    <p class="rating-text"><?php echo htmlspecialchars($row['description']); ?> - <?php echo $row['origin']; ?> </p>
                    <?php
                    echo "<a href='./viewmore.php?id=" . $row['stone_id'] . "' class='btn btn-primary'>";
                    ?> View More</a>

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