<?php
include '../../database/db.php'; // Include the database connection
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

        
        <!-- Sidebar for filter and sort options -->
        <aside class="sidebar">
            <h2>Filter & Sort</h2>

            <div class="filter-section">
                <h3>Sort By</h3>
                <label for="sort">Sort By:</label>
                <select id="sort">
                    <option value="best-match">Recently Added</option>
                    <option value="price-low-to-high">Price: Low to High</option>
                    <option value="price-high-to-low">Price: High to Low</option>
                </select>
            </div>

            <div class="filter-section">
                <h3>Filter By</h3>

                <!-- Filter by Carat Weight -->
                <label for="carat-weight">Carat Weight (in carats):</label>
                <input type="range" id="carat-weight" name="carat-weight" min="1" max="10" value="5" step="0.5">
                <span id="carat-weight-display">5 carats</span><br />

                <!-- Filter by Shape -->
                <label for="shape">Shape:</label>
                <select id="shape">
                    <option value="all">All</option>
                    <option value="round">Round</option>
                    <option value="oval">Oval</option>
                    <option value="pear">Pear</option>
                    <option value="emerald">Emerald</option>
                    <option value="cushion">Cushion</option>
                </select>

                <!-- Filter by Color -->
                <label for="color">Color:</label>
                <select id="color">
                    <option value="all">All</option>
                    <option value="blue">Blue</option>
                    <option value="yellow">Yellow</option>
                    <option value="pink">Pink</option>
                    <option value="green">Green</option>
                    <option value="clear">Clear</option>
                </select>

                <!-- Filter by Type -->
                <label for="type">Type:</label>
                <select id="type">
                    <option value="all">All</option>
                    <option value="ruby">Ruby</option>
                    <option value="diamond">Diamond</option>
                    <option value="sapphire">Sapphire</option>
                    
                </select>

                <!-- Filter by Origin-->
                <label for="origin">Origin:</label>
                <select id="origin">
                    <option value="all">All</option>
                    <option value="srilanka">Sri Lanka</option>
                    <option value="madagascar">Madagascar</option>
                </select>

                <!-- Filter by Clarity -->
                <label for="clarity">Clarity:</label>
                <select id="clarity">
                    <option value="all">All</option>
                    <option value="flawless">Flawless</option>
                    <option value="vs1">VS1</option>
                    <option value="vs2">VS2</option>
                    <option value="si1">SI1</option>
                    <option value="si2">SI2</option>
                </select>

                <!-- Filter by Price Range -->
                 <div class="ranger" style="border: 2px solid teal; margin-top: 20px; border-radius: 20px; padding:10px ;">
                    <label for="price-range" >Price Range:</label>
                    <input type="number" id="min-price" placeholder="Min:0" min="0" step="50">
                    <input type="number" id="max-price" placeholder="Max:0" min="0" step="50">
                </div>

                <button id=":filter-button" class="filter-button"> Filter </button>
            </div>
        </aside>

        

         <!-- Upcoming Bids Section -->
         <main class="content">
            <h1 style="margin-left: 20px;">Upcoming Bids</h1>
            <div class="slider-container">
                <div class="slider" id="slider">
                    <?php
                    // Fetch upcoming bids data
                    $query = "
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
                            b.finishDate > NOW() -- Only show upcoming bids
                        ORDER BY 
                            b.finishDate ASC
                    ";
                    $result = $conn->query($query);

                    if (!$result) {
                        die("Query failed: " . $conn->error);
                    }

                    // Loop through the results and display each upcoming bid
                    while ($row = $result->fetch_assoc()) :
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
                            <button>Bid Now!</button>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

             <!-- Main Product Catalog -->
        <main class="product-catalog">
           <!-- <div> <h1 style="margin-left: 20px;">Main Product Catalog</h1><div> -->
            <div class="catalog-container">
            <?php
            // Fetch main product catalog data
            $query = "
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
                ORDER BY 
                    b.startDate DESC
            ";
            $result = $conn->query($query);

            if (!$result) {
                die("Query failed: " . $conn->error);
            }

            // Loop through the results and display each product
            while ($row = $result->fetch_assoc()) :
            ?>

            <main class="product-catalog">
                <div class="shop-card">
                    <div class="card-banner">
                        <img src="../../assets/images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['type']); ?>" />
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
                        <h3><?php echo htmlspecialchars($row['colour']); ?></h3>
                        <h3><?php echo htmlspecialchars($row['type']); ?></h3>
                        <h4><?php echo htmlspecialchars($row['origin']); ?></h4>
                    <div class="price">Starting Bid: Rs.<?php echo number_format($row['startingBid'], 2); ?></div>
                    <h3><a href="#" class="card-title"><?php echo htmlspecialchars($row['type']); ?></a></h3>
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
        </div>
    </main>

    </div>

    <script src="../../components/header/header.js"></script>
    <script src="../../components/footer/footer.js"></script>
    <script src="../homepage/script.js"></script>
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

    <script>
        const slider = document.getElementById('slider');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        let index = 0;

        const moveSlider = (direction) => {
            const slides = document.querySelectorAll('.slide');
            const totalSlides = slides.length;
            const visibleSlides = 3; // Adjust based on how many slides fit in view

            if (direction === 'next') {
                index = (index + 1) % totalSlides;
            } else if (direction === 'prev') {
                index = (index - 1 + totalSlides) % totalSlides;
            }

            const offset = index * (100 / visibleSlides);
            slider.style.transform = translateX(-${offset}%);
        };

        nextBtn.addEventListener('click', () => moveSlider('next'));
        prevBtn.addEventListener('click', () => moveSlider('prev'));
    </script>
</body>

</html>