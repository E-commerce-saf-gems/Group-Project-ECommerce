class Footer extends HTMLElement {
    connectedCallback() {
      this.innerHTML = `
        <footer class="footer" data-section>
          <div class="container">
            <div class="footer-top">
              <ul class="footer-list">
                <li>
                  <p class="footer-list-title">Company</p>
                </li>
                <li>
                  <p class="footer-list-text">
                    Find a location nearest you. See <a href="#" class="link">Our Stores</a>
                  </p>
                </li>
                <li>
                  <p class="footer-list-text bold">011 276 4147</p>
                </li>
                <li>
                  <p class="footer-list-text">www.safgems.com</p>
                </li>
              </ul>
              <ul class="footer-list">
                <li>
                  <p class="footer-list-title">Gemstone Collections</p>
                </li>
                <li>
                  <a href="#" class="footer-link">New Arrivals</a>
                </li>
                <li>
                  <a href="#" class="footer-link">Best Sellers</a>
                </li>
              </ul>
              <ul class="footer-list">
                <li>
                  <p class="footer-list-title">Customer Service</p>
                </li>
                <li>
                  <a href="#" class="footer-link">Start a Return</a>
                </li>
                <li>
                  <a href="#" class="footer-link">Contact Us</a>
                </li>
                <li>
                  <a href="#" class="footer-link">Shipping FAQ</a>
                </li>
                <li>
                  <a href="#" class="footer-link">Terms & Conditions</a>
                </li>
                <li>
                  <a href="#" class="footer-link">Privacy Policy</a>
                </li>
              </ul>
              <div class="footer-list">
                <p class="newsletter-title">Stay Sparkling!</p>
                <p class="newsletter-text">
                  Enter your email below to be the first to know about our new gemstone collections and exclusive offers.
                </p>
                <form action="" class="newsletter-form">
                  <input type="email" name="email_address" placeholder="Enter your email address" required class="email-field">
                  <button type="submit" class="btn btn-primary">Subscribe</button>
                </form>
              </div>
            </div>
            <div class="footer-bottom">
              <div class="wrapper">
                <ul class="social-list">
                  <li>
                    <a href="#" class="social-link">
                      <ion-icon name="logo-twitter"></ion-icon>
                    </a>
                  </li>
                  <li>
                    <a href="#" class="social-link">
                      <ion-icon name="logo-facebook"></ion-icon>
                    </a>
                  </li>
                  <li>
                    <a href="#" class="social-link">
                      <ion-icon name="logo-instagram"></ion-icon>
                    </a>
                  </li>
                  <li>
                    <a href="#" class="social-link">
                      <ion-icon name="logo-pinterest"></ion-icon>
                    </a>
                  </li>
                </ul>
              </div>
              <a href="#" class="logo">
                <img src="/assets/images/logo.png" width="179" height="26" loading="lazy" alt="Gemstone Elegance">
              </a>
              <img src="/assets/images/pay.png" width="200" height="28" alt="available all payment methods">
            </div>
          </div>
        </footer>
  
        <a href="#top" class="back-top-btn" aria-label="back to top" data-back-top-btn>
          <ion-icon name="arrow-up" aria-hidden="true"></ion-icon>
        </a>
      `;
    }
  }
  
  customElements.define('custom-footer', Footer);
  