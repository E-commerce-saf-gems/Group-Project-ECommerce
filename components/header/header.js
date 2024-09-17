class Header extends HTMLElement {
    connectedCallback() {
      this.innerHTML = 
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
                  <input type="search" name="search" placeholder="Search product" class="search-field"></input>
        
                  <button class="search-submit" aria-label="search">
                    <ion-icon name="search-outline" aria-hidden="true"></ion-icon>
                  </button>
                </div>
        
                <a href="/pages/homepage/homepage.html" class="logo">
                  <img src="/assets/images/logo.png" width="179" height="26" alt="SAF GEMS"></img>
                </a>
        
                <div class="header-actions">
        
                  <button class="header-action-btn" aria-label="user">
                    <ion-icon name="person-outline" aria-hidden="true" ></ion-icon>
                  </button>
        
                  <button class="header-action-btn" aria-label="favourite item">
                    <ion-icon name="star-outline" aria-hidden="true" ></ion-icon>
        
                    <span class="btn-badge">0</span>
                  </button>
        
                  <button class="header-action-btn" aria-label="cart item">
                    <data class="btn-text" value="0"></data>
        
                    <ion-icon name="bag-handle-outline" aria-hidden="true" ></ion-icon>
        
                    <span class="btn-badge">3</span>
                  </button>
                </div> 
                <nav class="navbar">
                    <ul class="navbar-list">
                      <li>
                        <a href="./index.html" class="navbar-link has-after">Home</a>
                      </li>
                      <li class="dropdown">
                          <a href="#shop" class="navbar-link dropdown-toggle" data-nav-link>Shop</a>
                          <ul class="dropdown-menu">
                            <li><a href="#buy-stones" class="dropdown-item">Buy Stones</a></li>
                            <li><a href="#bid" class="dropdown-item">Bid</a></li>
                          </ul>
                        </li>
                      <li>
                        <a href="#about" class="navbar-link has-after">About Us</a>
                      </li>
                      <li>
                        <a href="#contact" class="navbar-link has-after">Contact</a>
                      </li>
          
                    </ul>
                  </nav>   
              </div>
            </div>
        
          </header>
      ;
    }
}
  
customElements.define('custom-header', Header);

const addEventOnElem = function (elem, type, callback) {
  if (elem.length > 1) {
    for (let i = 0; i < elem.length; i++) {
      elem[i].addEventListener(type, callback);
    }
  } else {
    elem.addEventListener(type, callback);
  }
}

const navTogglers = document.querySelectorAll("[data-nav-toggler]");
const navbar = document.querySelector("[data-navbar]");
const navbarLinks = document.querySelectorAll("[data-nav-link]");
const overlay = document.querySelector("[data-overlay]");

const toggleNavbar = function () {
  navbar.classList.toggle("active");
  overlay.classList.toggle("active");
}

addEventOnElem(navTogglers, "click", toggleNavbar);

const closeNavbar = function () {
  navbar.classList.remove("active");
  overlay.classList.remove("active");
}

addEventOnElem(navbarLinks, "click", closeNavbar);
