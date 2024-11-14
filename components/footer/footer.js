class Footer extends HTMLElement {
  connectedCallback() {
      this.innerHTML = `
          <footer class="footer">
              <div class="footer-content">
                  <a href="#" class="footer-logo">
                      <img src="../../assets/images/logo-grey.png" width="80" height="80" alt="Company Logo">
                  </a>
                  <ul class="social-links">
                      <li>
                          <a href="#" class="social-link" aria-label="Facebook">
                              <ion-icon name="logo-facebook"></ion-icon>
                          </a>
                      </li>
                      <li>
                          <a href="#" class="social-link" aria-label="Instagram">
                              <ion-icon name="logo-instagram"></ion-icon>
                          </a>
                      </li>
                  </ul>
              </div>
          </footer>
      `;
  }
}

customElements.define('custom-footer', Footer);
