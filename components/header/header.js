class Header extends HTMLElement {
  async connectedCallback() {
    // Step 1: Fetch session data from the backend
    let isLoggedIn = false; // Default to not logged in
    try {
      const response = await fetch("../../pages/Login/sessionData.php"); // Replace with the correct path
      const sessionData = await response.json();
      isLoggedIn = sessionData.loggedIn;
    } catch (error) {
      console.error("Failed to fetch session data:", error);
    }

    // Step 2: Define the HTML structure for logged-in and not logged-in states
    const loggedInMenu = `
      <ul class="dropdown-menu">
        <li><a href="../Profile/Details/MyDetails.php" class="dropdown-item">Profile</a></li>
        <li><a href="#" class="dropdown-item" id="logout">Logout</a></li>
      </ul>
    `;

    const loggedOutMenu = `
      <ul class="dropdown-menu">
        <li><a href="../Login/login.php" class="dropdown-item">Login</a></li>
        <li><a href="../RegisterPage/register.html" class="dropdown-item">Register</a></li>
      </ul>
    `;

    // Step 3: Dynamically set the header based on login state
    this.innerHTML = `
      <header class="header">
        <div class="alert">
          <div class="container">
            <a href="../bidding/bidding-itemPage.php">
              <p class="alert-text">Biddings Now Going On <u>Bid Now</u></p>
            </a>
          </div>
        </div>

        <div class="header-top" data-header>
          <div class="container">
            <button class="nav-open-btn" aria-label="open menu" data-nav-toggler>
              <span class="line line-1"></span>
              <span class="line line-2"></span>
              <span class="line line-3"></span>
            </button>

        

            <a href="../homepage/homepage.php" class="logo">
              <img src="../../assets/images/logo.png" width="179" height="26" alt="SAF GEMS">
            </a>

            <div class="header-actions">
              <div class="dropdown">
                <button class="header-action-btn dropdown-toggle" aria-label="user" data-nav-link>
                  <ion-icon name="person-outline" aria-hidden="true"></ion-icon>
                </button>
                ${isLoggedIn ? loggedInMenu : loggedOutMenu}
              </div>

              <a href="../cart/cart.php">
                <button class="header-action-btn" aria-label="cart item">
                  <data class="btn-text" value="0"></data>
                  <ion-icon name="bag-handle-outline" aria-hidden="true"></ion-icon>
                  <span class="btn-badge">3</span>
                </button>
              </a>
            </div>

            <nav class="navbar">
              <ul class="navbar-list">
                <li><a href="../homepage/homepage.php" class="navbar-link has-after">Home</a></li>
                <li class="dropdown">
                  <a class="navbar-link dropdown-toggle" data-nav-link>Shop</a>
                  <ul class="dropdown-menu">
                    <li><a href="../Stones/StonesHomePage.php" class="dropdown-item">Buy Stones</a></li>
                    <li><a href="../bidding/bidding.php" class="dropdown-item">Bid</a></li>
                  </ul>
                </li>
                <li><a href="../aboutPage/about.html" class="navbar-link has-after">About Us</a></li>
                <li><a href="../contactUs/Contact.html" class="navbar-link has-after">Contact</a></li>
              </ul>
            </nav>
          </div>
        </div>
      </header>
    `;

    // Step 4: Add logout functionality
    if (isLoggedIn) {
      const logoutButton = document.getElementById("logout");
      if (logoutButton) {
        logoutButton.addEventListener("click", async function () {
          try {
            const logoutResponse = await fetch("../Login/logout.php", { method: "POST" });
            if (logoutResponse.ok) {
              location.reload(); // Reload to update header state
            }
          } catch (error) {
            console.error("Logout failed:", error);
          }
        });
      }
    }
  }
}

customElements.define("custom-header", Header);
