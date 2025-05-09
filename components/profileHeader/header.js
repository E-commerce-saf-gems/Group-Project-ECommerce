class Header extends HTMLElement {
  connectedCallback() {
// JavaScript to dynamically update the profile dropdown
const isLoggedIn = localStorage.getItem("loggedInUser");

this.innerHTML = `
  <header class="header">
    <div class="alert">
      <div class="container">
        <p class="alert-text">Biddings Now Going On <u>Bid Now</u></p>
      </div>
    </div>

    <div class="header-top" data-header>
      <div class="container">
        <button class="nav-open-btn" aria-label="open menu" data-nav-toggler>
          <span class="line line-1"></span>
          <span class="line line-2"></span>
          <span class="line line-3"></span>
        </button>

        <div class="input-wrapper">
          <input type="search" name="search" placeholder="Search product" class="search-field">
          <button class="search-submit" aria-label="search">
            <ion-icon name="search-outline" aria-hidden="true"></ion-icon>
          </button>
        </div>

        <a href="../../../assets/images/logo.png" class="logo">
          <img src="../../../assets/images/logo.png" width="179" height="26" alt="SAF GEMS">
        </a>

        <div class="header-actions">
          <div class="dropdown">
            <button class="header-action-btn dropdown-toggle" aria-label="user" data-nav-link>
              <ion-icon name="person-outline" aria-hidden="true"></ion-icon>
            </button>
            <ul class="dropdown-menu">
                    <li><a href="../../Profile/Details/MyDetails.html" class="dropdown-item">Profile</a></li>
                    <li><a href="../../Login/logout.php" class="dropdown-item" id="logout">Logout</a></li>
            </ul>
          </div>

          <button class="header-action-btn" aria-label="favourite item">
            <ion-icon name="star-outline" aria-hidden="true"></ion-icon>
            <span class="btn-badge">0</span>
          </button>

          <a href="../../cart/cart.php">
            <button class="header-action-btn" aria-label="cart item">
              <data class="btn-text" value="0"></data>
              <ion-icon name="bag-handle-outline" aria-hidden="true"></ion-icon>
              <span class="btn-badge">3</span>
            </button>                
          </a>
        </div> 

        <nav class="navbar">
          <ul class="navbar-list">
            <li><a href="../../homepage/homepage.html" class="navbar-link has-after">Home</a></li>
            <li class="dropdown">
              <a href="#shop" class="navbar-link dropdown-toggle" data-nav-link>Shop</a>
              <ul class="dropdown-menu">
                <li><a href="../../Stones/StonesHomePage.php" class="dropdown-item">Buy Stones</a></li>
                <li><a href="../../bidding/bidding.html" class="dropdown-item">Bid</a></li>
              </ul>
            </li>
            <li><a href="../../aboutPage/about.html" class="navbar-link has-after">About Us</a></li>
            <li><a href="#" class="navbar-link has-after">Contact</a></li>
          </ul>
        </nav>
      </div>
    </div>
  </header>
`;

  }
}

customElements.define('custom-header', Header);
