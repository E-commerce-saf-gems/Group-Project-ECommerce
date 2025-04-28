<?php
include '../../database/db.php';

// Base query
$ssql = "SELECT 
            stone_id, amount, 
            type, 
            origin,
            description,
            image, 
            size,
            shape,
            colour 
        FROM inventory
        WHERE visibility = 'show' AND (availability = 'available' OR availability = 'Available')";

// Apply filters
if (isset($_GET['carat-weight']) && !empty($_GET['carat-weight'])) {
    $caratWeight = (float) $_GET['carat-weight'];
    $ssql .= " AND size <= $caratWeight";
}

if (isset($_GET['shape']) && !empty($_GET['shape'])) {
    $shape = $conn->real_escape_string($_GET['shape']);
    $ssql .= " AND shape = '$shape'";
}

if (isset($_GET['color']) && !empty($_GET['color'])) {
    $color = $conn->real_escape_string($_GET['color']);
    $ssql .= " AND colour = '$color'";
}

if (isset($_GET['origin']) && !empty($_GET['origin'])) {
    $origin = $conn->real_escape_string($_GET['origin']);
    $ssql .= " AND origin = '$origin'";
}

if (isset($_GET['min-price']) && is_numeric($_GET['min-price'])) {
    $minPrice = (float) $_GET['min-price'];
    $ssql .= " AND amount >= $minPrice";
}

if (isset($_GET['max-price']) && is_numeric($_GET['max-price'])) {
    $maxPrice = (float) $_GET['max-price'];
    $ssql .= " AND amount <= $maxPrice";
}

// Sorting
if (isset($_GET['sort']) && !empty($_GET['sort'])) {
    $sortOption = $_GET['sort'];
    if ($sortOption == 'price-low-to-high') {
        $ssql .= " ORDER BY amount ASC";
    } elseif ($sortOption == 'price-high-to-low') {
        $ssql .= " ORDER BY amount DESC";
    } else {
        $ssql .= " ORDER BY stone_id DESC"; // default or new arrival
    }
} else {
    $ssql .= " ORDER BY stone_id DESC"; // default sort
}

$result = $conn->query($ssql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="..//homepage/styles.css">
    <link rel="stylesheet" href="../../styles/common.css">
    <link rel="stylesheet" href="../../components/header/header.css">
    <link rel="stylesheet" href="../../components/footer/footer.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="./homeStyles.css">
    <title>Gem Product Catalog</title>
</head>

<body>
    <custom-header></custom-header>

    <div class="container">
        <!-- Sidebar for filter and sort options -->
        <aside class="sidebar">
            <h2>Filter & Sort</h2>
            <form method="GET" id="filter-form">
                <div class="filter-section">
                    <h3>Sort By</h3>
                    <label for="sort">Sort By:</label>
                    <select id="sort" name="sort" onchange="document.getElementById('filter-form').submit();">
                        <option value="new-arrival" <?= (isset($_GET['sort']) && $_GET['sort'] == 'new-arrival') ? 'selected' : ''; ?>>Recently Added</option>
                        <option value="price-low-to-high" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price-low-to-high') ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price-high-to-low" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price-high-to-low') ? 'selected' : ''; ?>>Price: High to Low</option>
                    </select>
                </div>

                <div class="filter-section">
                    <h3>Filter By</h3>

                    <label for="carat-weight">Carat Weight:</label>
                    <input type="range" id="carat-weight" name="carat-weight" min="1" max="10" step="0.5"
                        value="<?= isset($_GET['carat-weight']) ? htmlspecialchars($_GET['carat-weight']) : 5; ?>"
                        onchange="document.getElementById('filter-form').submit();">
                    <span
                        id="carat-weight-display"><?= isset($_GET['carat-weight']) ? htmlspecialchars($_GET['carat-weight']) : 5; ?>
                        carats</span><br>

                    <label for="shape">Shape:</label>
                    <select id="shape" name="shape" onchange="document.getElementById('filter-form').submit();">
                        <option value="">Any</option>
                        <option value="round" <?= (isset($_GET['shape']) && $_GET['shape'] == 'round') ? 'selected' : ''; ?>>Round</option>
                        <option value="oval" <?= (isset($_GET['shape']) && $_GET['shape'] == 'oval') ? 'selected' : ''; ?>>
                            Oval</option>
                        <option value="pear" <?= (isset($_GET['shape']) && $_GET['shape'] == 'pear') ? 'selected' : ''; ?>>
                            Pear</option>
                        <option value="emerald" <?= (isset($_GET['shape']) && $_GET['shape'] == 'emerald') ? 'selected' : ''; ?>>Emerald</option>
                        <option value="cushion" <?= (isset($_GET['shape']) && $_GET['shape'] == 'cushion') ? 'selected' : ''; ?>>Cushion</option>
                    </select>

                    <label for="color">Color:</label>
                    <select id="color" name="color" onchange="document.getElementById('filter-form').submit();">
                        <option value="">Any</option>
                        <option value="blue" <?= (isset($_GET['color']) && $_GET['color'] == 'blue') ? 'selected' : ''; ?>>
                            Blue</option>
                        <option value="yellow" <?= (isset($_GET['color']) && $_GET['color'] == 'yellow') ? 'selected' : ''; ?>>Yellow</option>
                        <option value="pink" <?= (isset($_GET['color']) && $_GET['color'] == 'pink') ? 'selected' : ''; ?>>
                            Pink</option>
                        <option value="green" <?= (isset($_GET['color']) && $_GET['color'] == 'green') ? 'selected' : ''; ?>>Green</option>
                        <option value="clear" <?= (isset($_GET['color']) && $_GET['color'] == 'clear') ? 'selected' : ''; ?>>Clear</option>
                    </select>

                    <label for="origin">Origin:</label>
                    <select id="origin" name="origin" onchange="document.getElementById('filter-form').submit();">
                        <option value="">Any</option>
                        <option value="srilanka" <?= (isset($_GET['origin']) && $_GET['origin'] == 'srilanka') ? 'selected' : ''; ?>>Sri Lanka</option>
                        <option value="Canada" <?= (isset($_GET['origin']) && $_GET['origin'] == 'Canada') ? 'selected' : ''; ?>>Canada</option>
                        <option value="Dubai" <?= (isset($_GET['origin']) && $_GET['origin'] == 'Dubai') ? 'selected' : ''; ?>>Dubai</option>
                        <option value="Africa" <?= (isset($_GET['origin']) && $_GET['origin'] == 'Africa') ? 'selected' : ''; ?>>Africa</option>
                    </select>

                    <label for="min-price">Price Range:</label>
                    <input type="number" id="min-price" name="min-price" min="0" placeholder="Min" step="50"
                        value="<?= isset($_GET['min-price']) ? htmlspecialchars($_GET['min-price']) : ''; ?>"
                        onchange="document.getElementById('filter-form').submit();">
                    <input type="number" id="max-price" name="max-price" min="0" placeholder="Max" step="50"
                        value="<?= isset($_GET['max-price']) ? htmlspecialchars($_GET['max-price']) : ''; ?>"
                        onchange="document.getElementById('filter-form').submit();">
                </div>
            </form>
        </aside>

        <!-- Main content area with product catalog -->
        <main class="product-catalog">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="shop-card">
                    <div class="card-banner">
                        <img src="<?php echo htmlspecialchars('http://localhost/Group-Project-ECommerce/assets/images/' . $row['image']); ?>"
                            alt="<?php echo htmlspecialchars($row['type']); ?>"
                            style="width: 200px; height: 200px; object-fit: cover;">
                    </div>
                    <div class="card-content">
                        <h3><a href="#" class="card-title"><?php echo htmlspecialchars($row['type']); ?></a></h3>
                        <h3><a href="#" class="card-title"><?php echo htmlspecialchars($row['colour']); ?></a></h3>
                        <div class="price">Rs. <?php echo number_format($row['amount']); ?></div>
                        <?php
                        echo "<a href='./viewmore2.php?id=" . $row['stone_id'] . "' class='btn btn-primary'>";
                        ?>View More</span></a>
                    </div>
                </div>
            <?php endwhile; ?>

        </main>
    </div>

    <script src="../../components/header/header.js"></script>
    <script src="../../components/footer/footer.js"></script>
    <script src="../homepage/script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <script>
        const caratWeightInput = document.getElementById('carat-weight');
        const caratWeightDisplay = document.getElementById('carat-weight-display');

        caratWeightInput.addEventListener('input', () => {
            caratWeightDisplay.textContent = caratWeightInput.value + ' carats';
        });

    </script>
</body>

</html>