<?php
include '../../database/db.php'; // Include the database connection

// Query to fetch stones
$stonesQuery = "
    SELECT 
        stone_id, amount, 
        type, 
        origin,
        description,
        image, 
        size,
        shape,
        colour 
    FROM inventory
    WHERE visibility = 'show' AND (availability = 'available' OR availability = 'Available')
";

$stonesResult = $conn->query($stonesQuery);

if (!$stonesResult) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SAF GEMS</title>

  <link rel="shortcut icon" href="/favicon.svg" type="image/svg+xml">

  <link rel="stylesheet" href="./styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="../../components/header/header.css">

  <link rel="preload" as="image" href="/assets/images/logo.png">
  <link rel="preload" as="image" href="/assets/images/hero1.png">
  <link rel="preload" as="image" href="/assets/images/hero2.png">
  <link rel="preload" as="image" href="/assets/images/hero3.png">

</head>

<body id="top">
  <custom-header></custom-header>

  <!-- #MOBILE NAVBAR -->

  <div class="sidebar">
    <div class="mobile-navbar" data-navbar>

      <div class="wrapper">
        <a href="/pages/homepage/homepage.html" class="logo">
          <img src="/assets/images/logo.png" width="179" height="26" alt="SAF GEMS">
        </a>

        <button class="nav-close-btn" aria-label="close menu" data-nav-toggler>
          <ion-icon name="close-outline" aria-hidden="true"></ion-icon>
        </button>
      </div>

      <ul class="navbar-list">

        <li>
          <a href="/pages/homepage/homepage.html" class="navbar-link" data-nav-link>Home</a>
        </li>

        <li class="dropdown">
            <a class="navbar-link dropdown-toggle" data-nav-link>Shop</a>
            <ul class="dropdown-menu">
              <li><a href="../Stones/StonesHomePage.php" class="dropdown-item">Buy Stones</a></li>
              <li><a href="/pages/bidding/bidding.html" class="dropdown-item">Bid</a></li>
            </ul>
          </li>
          

        <li>
          <a href="/pages/aboutPage/about.html" class="navbar-link" data-nav-link>About Us</a>
        </li>

        <li>
          <a href="#contact" class="navbar-link" data-nav-link>Contact</a>
        </li>

      </ul>

    </div>

    <div class="overlay" data-nav-toggler data-overlay></div>
  </div>


  <main>
    <article>

      <!-- #HERO -->

      <section class="section hero" id="home" aria-label="hero" data-section>
        <div class="container">

          <ul class="has-scrollbar">

            <li class="scrollbar-item">
              <div class="hero-card has-bg-image" style="background-image: url('../../assets/images/hero1.png')">

                <div class="card-content">

                  <h1 class="h1 hero-title">Unveil the Brilliance<br> Within</h1>

                  <p class="hero-text"> Ethically sourced, our gemstones are crafted to illuminate every facet of your life. </p>

                  <a href="../Stones/StonesHomePage.php" class="btn btn-primary">Shop Now</a>

                </div>

              </div>
            </li>

            <li class="scrollbar-item">
              <div class="hero-card has-bg-image" style="background-image: url('../../assets/images/hero2.png')">

                <div class="card-content">

                  <h1 class="h1 hero-title">Discover Your<br>Birthstone's Beauty</h1>
                  <p class="hero-text">Each month, a unique gemstone captures the essence of those born within it. Now is your chance to own these timeless treasures.</p>
                  <a href="../bidding/bidding.html" class="btn btn-primary">Bid Now</a>
                </div>

              </div>
            </li>

            <li class="scrollbar-item">
              <div class="hero-card has-bg-image" style="background-image: url('../../assets/images/hero3.png')">

                <div class="card-content">

                  <h1 class="h1 hero-title">Craft Exquisite Jewelry<br>with Our Premium Gems</h1>
                    <p class="hero-text">Perfectly cut and polished, our gems are the cornerstone of stunning jewelry.</p>
                    <a href="../Stones/StonesHomePage.php" class="btn btn-primary">Shop Now</a>
                </div>

              </div>
            </li>

          </ul>

        </div>
      </section>

    <!-- stones -->
    <div class="stones-section">
        <div class="stones-title"><h1 class="h1">Our Stones</h1></div>
        <div class="vertical-line"></div>
        <p class="stones-description">
            Discover the beauty and uniqueness of our carefully selected gems. Each stone is handpicked for its brilliance, clarity, and color, ensuring that you receive only the finest quality. Whether you're looking for a timeless classic or a unique statement piece, our collection of gems is sure to impress.
        </p>
    </div>
    <section class="section shop" id="shop" aria-label="shop" data-section>
    <div class="container">
        <ul class="has-scrollbar">
            <?php while ($row = $stonesResult->fetch_assoc()) : ?>
                <li class="scrollbar-item">
                    <div class="shop-card">
                        <div class="card-banner img-holder" style="--width: 540; --height: 720;">
                            <img src="../../assets/images/<?php echo htmlspecialchars($row['image']); ?>" 
                                 width="540" height="720" loading="lazy" 
                                 alt="<?php echo htmlspecialchars($row['type']); ?>" class="img-cover">
                        </div>
                        <div class="card-content">
                            <div class="price">
                                <span class="span">LKR <?php echo number_format($row['amount'], 2); ?></span>
                            </div>
                            <h3><a href="#" class="card-title"><?php echo htmlspecialchars($row['type']); ?></a></h3>
                            <p class="rating-text"><?php echo htmlspecialchars($row['size']); ?> Carats</p>
                        </div>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</section>


      <?php
      // Query to fetch ongoing auctions
      $auctionsQuery = "
          SELECT 
              b.biddingStone_id, 
              b.startingBid, 
              b.currentBid, 
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

      $auctionsResult = $conn->query($auctionsQuery);

      if (!$auctionsResult) {
          die("Query failed: " . $conn->error);
      }
      ?>
    <!-- auctions -->
    <div class="stones-section">
        <div class="stones-title"><h1 class="h1">Our Auctions</h1></div>
        <div class="vertical-line"></div>
        <p class="stones-description">
            Explore our exclusive auctions featuring a curated selection of rare and exquisite gems. Each piece is available for bidding, offering you a chance to own a truly unique treasure. Whether you're seeking an investment or a personal masterpiece, our biddings provide the perfect opportunity to acquire something special.
        </p>
    </div>
    <section class="section collection" id="collection" aria-label="collection" data-section>
      <div class="container">
          <ul class="collection-list">
              <?php while ($row = $auctionsResult->fetch_assoc()) : ?>
                  <li>
                      <div class="collection-card has-before hover:shine">
                          <h2 class="h2 card-title"><?php echo htmlspecialchars($row['type']); ?></h2>
                          <p class="card-text">Starting at 
                              <span class="badge" aria-label="Starting Bid">
                                  LKR <?php echo number_format($row['startingBid'], 2); ?>
                              </span>
                          </p>
                          <a href="../bidding/bidding-itemPage.php?id=<?php echo $row['biddingStone_id']; ?>" class="btn-link">
                              <span class="span">Bid Now</span>
                              <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
                          </a>
                          <div class="has-bg-image" style="background-image: url('../../assets/images/<?php echo htmlspecialchars($row['image']); ?>')"></div>
                      </div>
                  </li>
              <?php endwhile; ?>
          </ul>
    

          <a href="../bidding/bidding.php" class="btn btn-primary view-all-btn">View All</a>

        </div>
    </section>



    
    <!-- custom request -->
    <div class="stones-section">
        <div class="stones-title"><h1 class="h1">Couldnt Find<br>What You Want?</h1></div>
        <div class="vertical-line"></div>
        <p class="stones-description">
            Discover the beauty and uniqueness of our carefully selected gems.<br>
            Each stone is handpicked for its brilliance, clarity, and color,<br>
            ensuring that you receive only the finest quality. Whether you're<br>
            looking for a timeless classic or a unique statement piece,<br>
            our collection of gems is sure to impress.
        </p>
        <a href="../customRequest/customRequest.php" class="btn btn-primary request-btn">Request Now</a>
    </div>



    <?php
      session_start();
      include '../../database/db.php';

      // Fetch the latest upcoming bid
      $query = "
          SELECT 
              CONCAT(i.colour, ' ', i.type) AS stone_name, 
              i.image AS stone_image, 
              b.startingBid, 
              b.startDate, 
              b.finishDate
          FROM 
              biddingstone b
          JOIN 
              inventory i 
          ON 
              b.stone_id = i.stone_id
          WHERE 
              b.startDate > NOW()
          ORDER BY 
              b.startDate ASC
          LIMIT 1
      ";

      $result = $conn->query($query);

      if (!$result || $result->num_rows === 0) {
          die("No upcoming bids found.");
      }

      $row = $result->fetch_assoc();

      $startDateStr = $row['startDate'];
      $now = new DateTime();
      $startDate = new DateTime($row['startDate']);
      $isAuctionStarted = $now >= $startDate; // Checks if auction has already started
      ?>

    <!-- #OFFER -->
    <section class="section offer" id="offer" aria-label="offer" data-section>
    <div class="container">
        <figure class="offer-banner">
            <img src="../../assets/images/<?php echo htmlspecialchars($row['stone_image']); ?>" 
                 width="600" height="750" loading="lazy" 
                 alt="<?php echo htmlspecialchars($row['stone_name']); ?>">
        </figure>

        <div class="offer-content">
            <p class="offer-subtitle">
                <span class="span">New Bid Starting</span>
                <span class="badge" aria-label="Starting on <?php echo $startDate->format('d M Y H:i'); ?>">
                    Starting on <?php echo $startDate->format('d M Y H:i'); ?>
                </span>
            </p>

            <h2 class="h2-large section-title"><?php echo htmlspecialchars($row['stone_name']); ?> Auction</h2>

            <p class="section-text">
                Participate in our exclusive auction for this stunning gemstone. Bidding starts at 
                Rs.<?php echo number_format($row['startingBid'], 2); ?>. Don't miss your chance to own this unique treasure.
            </p>

            <div class="countdown">
                <span class="time" aria-label="days">--</span>
                <span class="time" aria-label="hours">--</span>
                <span class="time" aria-label="minutes">--</span>
                <span class="time" aria-label="seconds">--</span>
            </div>

            <a href="../bidding/upcomingBids.php?id=<?php echo $row['biddingStone_id']; ?>" class="btn btn-primary">Join the Bid</a>
        </div>
    </div>
</section>


    <!-- timeline -->
    <section class="timeline-section">
      <div class="container">
    
        <h2 class="timeline-title">Our Gemstone Journey</h2>
    
        <div class="timeline">
    
          <div class="timeline-item">
            <div class="timeline-icon">
              <span>1</span>
            </div>
            <div class="timeline-content">
              <h3 class="timeline-step">Get the Stone from Mines</h3>
              <p>Our journey begins deep within the Earth's crust, where we carefully extract the raw gemstones from the mines.</p>
            </div>
          </div>
    
          <div class="timeline-item">
            <div class="timeline-icon">
              <span>2</span>
            </div>
            <div class="timeline-content">
              <h3 class="timeline-step">Cut and Polish</h3>
              <p>Our expert gem cutters shape and polish the stones to bring out their natural brilliance and beauty.</p>
            </div>
          </div>
    
          <div class="timeline-item">
            <div class="timeline-icon">
              <span>3</span>
            </div>
            <div class="timeline-content">
              <h3 class="timeline-step">Certify</h3>
              <p>Each gemstone is rigorously tested and certified for its authenticity and quality.</p>
            </div>
          </div>
    
          <div class="timeline-item">
            <div class="timeline-icon">
              <span>4</span>
            </div>
            <div class="timeline-content">
              <h3 class="timeline-step">Put It Up for Sale</h3>
              <p>Finally, our gemstones are showcased for sale, ready to find their new home.</p>
            </div>
          </div>
    
        </div>
    
      </div>
    </section>

        <!-- #FEATURE -->

        <section class="section feature" aria-label="feature" data-section>
          <div class="container">
      
              <h2 class="h2-large section-title">Why Choose Us?</h2>
      
              <ul class="flex-list">
      
                  <li class="flex-item">
                      <div class="feature-card">
      
                          <img src="../../assets/images/certification.png" width="204" height="236" loading="lazy" alt="Guaranteed Authenticity"
                              class="card-icon">
      
                          <h3 class="h3 card-title">Guaranteed Authenticity</h3>
      
                          <p class="card-text">
                              Every Saf Gem is certified for its authenticity, ensuring that you receive only genuine and natural gemstones of the highest quality.
                          </p>
      
                      </div>
                  </li>
      
                  <li class="flex-item">
                      <div class="feature-card">
      
                          <img src="../../assets/images/ethicallySourced.jpg" width="204" height="236" loading="lazy"
                              alt="Ethically Sourced" class="card-icon">
      
                          <h3 class="h3 card-title">Ethically Sourced</h3>
      
                          <p class="card-text">
                              Our gems are sourced from mines and suppliers who follow ethical practices, guaranteeing that our gems are conflict-free and responsibly obtained.
                          </p>
      
                      </div>
                  </li>
      
                  <li class="flex-item">
                      <div class="feature-card">
      
                          <img src="../../assets/images/cutting.jpg" width="204" height="236" loading="lazy"
                              alt="Exquisite Craftsmanship" class="card-icon">
      
                          <h3 class="h3 card-title">Exquisite Craftsmanship</h3>
      
                          <p class="card-text">
                              Our gems are crafted by skilled artisans, ensuring that each piece reflects unparalleled precision and beauty, making every Saf Gem a work of art.
                          </p>
      
                      </div>
                  </li>
      
              </ul>
      
          </div>
      </section>
  </main>



  <!-- footer -->

  <footer class="footer" data-section>
    <div class="container">
      <div class="footer-bottom">
        <div class="wrapper">
          <ul class="social-list">
            <li>
              <a href="www.facebook.com" class="social-link">
                <ion-icon name="logo-facebook"></ion-icon>
              </a>
            </li>
            <li>
              <a href="www.instagram.com" class="social-link">
                <ion-icon name="logo-instagram"></ion-icon>
              </a>
            </li>
          </ul>
        </div>
        <a href="#" class="logo">
          <img src="../../assets/images/logo-grey.png" width="50" height="50" loading="lazy" alt="Gemstone Elegance">
        </a>
        <img src="../../assets/images/pay.png" width="200" height="28" alt="available all payment method">
      </div>
    </div>
  </footer>
  

  <a href="#top" class="back-top-btn" aria-label="back to top" data-back-top-btn>
    <ion-icon name="arrow-up" aria-hidden="true"></ion-icon>
  </a>

  <script src="./script.js" defer></script>
  <script src="../../components/header/header.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

  <script>
    const auctionStartTime = new Date("<?php echo $startDateStr; ?>").getTime();

    function updateCountdown() {
        const now = new Date().getTime();
        const timeLeft = auctionStartTime - now;

        if (timeLeft <= 0) {
            document.querySelector(".countdown").innerHTML = "Auction has started";
            return;
        }

        const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
        const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

        document.querySelector(".countdown").innerHTML = `
            <span class="time" aria-label="days">${days}</span>
            <span class="time" aria-label="hours">${hours}</span>
            <span class="time" aria-label="minutes">${minutes}</span>
            <span class="time" aria-label="seconds">${seconds}</span>
        `;
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);
    </script>

</body>

</html>