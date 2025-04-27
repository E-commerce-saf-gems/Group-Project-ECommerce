<?php
include '../../database/db.php'; 

$ssql = "
    SELECT 
        b.biddingStone_id, 
        b.startingBid, 
        b.startDate, 
        b.finishDate, 
        i.size AS size,
        i.shape AS shape,
        i.colour AS colour, 
        i.type AS type,
        i.origin AS origin,
        i.image AS image 
    FROM 
        biddingstone b
    JOIN 
        inventory i 
    ON 
        b.stone_id = i.stone_id
    WHERE 
        b.finishDate > NOW() AND b.startDate < NOW()
";

if (isset($_GET['carat-weight']) && !empty($_GET['carat-weight'])) {
    $caratWeight = (float)$_GET['carat-weight'];
    $ssql .= " AND i.size <= $caratWeight";
}

if (isset($_GET['shape']) && !empty($_GET['shape'])) {
    $shape = $conn->real_escape_string($_GET['shape']);
    $ssql .= " AND i.shape = '$shape'";
}

if (isset($_GET['color']) && !empty($_GET['color'])) {
    $color = $conn->real_escape_string($_GET['color']);
    $ssql .= " AND i.colour = '$color'";
}

if (isset($_GET['origin']) && !empty($_GET['origin'])) {
    $origin = $conn->real_escape_string($_GET['origin']);
    $ssql .= " AND i.origin = '$origin'";
}

if (isset($_GET['type']) && !empty($_GET['type'])) {
    $type = $conn->real_escape_string($_GET['type']);
    $ssql .= " AND i.type = '$type'";
}
if (isset($_GET['min-price']) && is_numeric($_GET['min-price'])) {
    $minPrice = (float)$_GET['min-price'];
    $ssql .= " AND b.startingBid >= $minPrice";
}

if (isset($_GET['max-price']) && is_numeric($_GET['max-price'])) {
    $maxPrice = (float)$_GET['max-price'];
    $ssql .= " AND b.startingBid <= $maxPrice";
}

if (isset($_GET['sort']) && !empty($_GET['sort'])) {
    $sortOption = $_GET['sort'];
    if ($sortOption == 'price-low-to-high') {
        $ssql .= " ORDER BY b.startingBid ASC";
    } elseif ($sortOption == 'price-high-to-low') {
        $ssql .= " ORDER BY b.startingBid DESC";
    } else {
        $ssql .= " ORDER BY b.startDate DESC"; 
    }
} else {
    $ssql .= " ORDER BY b.startDate DESC"; 
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
    <link rel="stylesheet" href="../homepage/styles.css">
    <link rel="stylesheet" href="../../styles/common.css">
    <link rel="stylesheet" href="../../components/header/header.css">
    <link rel="stylesheet" href="../../components/footer/footer.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./styles.css">
    <title>Bidding Page</title>
</head>

<body>
    <custom-header></custom-header>

    <div class="container">
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
        <span id="carat-weight-display"><?= isset($_GET['carat-weight']) ? htmlspecialchars($_GET['carat-weight']) : 5; ?> carats</span><br>

        <label for="shape">Shape:</label>
        <select id="shape" name="shape" onchange="document.getElementById('filter-form').submit();">
            <option value="">Any</option>
            <option value="round" <?= (isset($_GET['shape']) && $_GET['shape'] == 'round') ? 'selected' : ''; ?>>Round</option>
            <option value="oval" <?= (isset($_GET['shape']) && $_GET['shape'] == 'oval') ? 'selected' : ''; ?>>Oval</option>
            <option value="pear" <?= (isset($_GET['shape']) && $_GET['shape'] == 'pear') ? 'selected' : ''; ?>>Pear</option>
            <option value="emerald" <?= (isset($_GET['shape']) && $_GET['shape'] == 'emerald') ? 'selected' : ''; ?>>Emerald</option>
            <option value="cushion" <?= (isset($_GET['shape']) && $_GET['shape'] == 'cushion') ? 'selected' : ''; ?>>Cushion</option>
        </select>

        <label for="color">Color:</label>
        <select id="color" name="color" onchange="document.getElementById('filter-form').submit();">
            <option value="">Any</option>
            <option value="blue" <?= (isset($_GET['color']) && $_GET['color'] == 'blue') ? 'selected' : ''; ?>>Blue</option>
            <option value="yellow" <?= (isset($_GET['color']) && $_GET['color'] == 'yellow') ? 'selected' : ''; ?>>Yellow</option>
            <option value="pink" <?= (isset($_GET['color']) && $_GET['color'] == 'pink') ? 'selected' : ''; ?>>Pink</option>
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

    <div class="filter-section">
        <button type="button" onclick="window.location.href='bidding.php'" class="btn btn-secondary">Reset Filters</button>
    </div>
        
</form>
</aside>

<main class="content">
    <h1 style="margin-left: 20px;">Upcoming Bids</h1>
    <div class="slider-container">
        <div class="slider" id="slider">
            <?php
            $upcomingBidsQuery = "
                SELECT 
                    b.biddingStone_id, 
                    b.startingBid, 
                    b.startDate, 
                    b.finishDate, 
                    i.colour AS colour, 
                    i.type AS type,
                    i.origin AS origin,
                    i.image AS image 
                FROM 
                    biddingstone b
                JOIN 
                    inventory i 
                ON 
                    b.stone_id = i.stone_id
                WHERE 
                    b.startDate > NOW()
                ORDER BY 
                    b.finishDate ASC
            ";
            $upcomingBidsResult = $conn->query($upcomingBidsQuery);

            if (!$upcomingBidsResult) {
                die("Query failed: " . $conn->error);
            }

            while ($row = $upcomingBidsResult->fetch_assoc()) :
            ?>
                <div class="slide">
                    <img src="../../assets/images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['type']); ?>" class="gem_img">
                    <h3><?php echo htmlspecialchars($row['colour']); ?></h3>
                    <h3><?php echo htmlspecialchars($row['type']); ?></h3>
                    <h4><?php echo htmlspecialchars($row['origin']); ?></h4>
                    <p>Starting Bid: Rs.<?php echo number_format($row['startingBid'], 2); ?></p>
                    <span>
                        <?php
                        $finishDate = new DateTime($row['finishDate']);
                        $now = new DateTime();
                        $interval = $now->diff($finishDate);
                        echo $interval->format('%d Days Left');
                        ?>
                    </span>
                    <a href="./upcomingBids.php?id=<?php echo $row['biddingStone_id']; ?>" class="btn btn-primary">More Details</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>


<h1 style="margin-left: 20px;">Available Bids</h1>
    <main class="product-catalog">

    <?php while ($row = $result->fetch_assoc()) : ?>
        <div class="shop-card">
            <div class="card-banner">
                <img 
                src="<?php echo htmlspecialchars('http://localhost/Group-Project-ECommerce/assets/images/' . $row['image']); ?>" 
                alt="<?php echo htmlspecialchars($row['type']); ?>" 
                style="width: 200px; height: 200px; object-fit: cover;">
            </div>
            <div class="card-content">
                <h3><?php echo htmlspecialchars($row['colour']); ?></h3>
                <h3><?php echo htmlspecialchars($row['type']); ?></h3>
                <h4><?php echo htmlspecialchars($row['origin']); ?></h4>
                <div class="days-left">
                    <ion-icon name="timer-outline" aria-hidden="true"></ion-icon>
                    <span>
                        <?php
                        $finishDate = new DateTime($row['finishDate']);
                        $now = new DateTime();
                        $interval = $now->diff($finishDate);
                        echo $interval->format('%d Days Left');
                        ?>
                    </span>
                </div>
                <a href="./bidding-itemPage.php?id=<?php echo $row['biddingStone_id']; ?>" class="btn btn-primary">Bid Now</a>
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

        if (caratWeightInput && caratWeightDisplay) {
            caratWeightInput.addEventListener('input', () => {
                caratWeightDisplay.textContent = caratWeightInput.value + ' carats';
            });
        }
    
        const slider = document.getElementById('slider');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        let index = 0;

        const moveSlider = (direction) => {
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;
        const visibleSlides = 3; 

    if (direction === 'next') {
        index = (index + 1) % totalSlides;
    } else if (direction === 'prev') {
        index = (index - 1 + totalSlides) % totalSlides;
    }

    const offset = index * (100 / visibleSlides);
    slider.style.transform = `translateX(-${offset}%)`; 
};

if (nextBtn && prevBtn) {
    nextBtn.addEventListener('click', () => moveSlider('next'));
    prevBtn.addEventListener('click', () => moveSlider('prev'));
}
    
        const caratWeightInput = document.getElementById('carat-weight');
        const caratWeightDisplay = document.getElementById('carat-weight-display');

        caratWeightInput.addEventListener('input', () => {
            caratWeightDisplay.textContent = caratWeightInput.value + ' carats';
        });

        </script>
</body>

</html>