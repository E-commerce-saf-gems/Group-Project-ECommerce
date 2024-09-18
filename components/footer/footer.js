// Footer JavaScript

document.addEventListener("DOMContentLoaded", () => {
    // Dynamically set the current year in the copyright section
    const currentYear = new Date().getFullYear();
    const copyright = document.querySelector(".copyright");
    copyright.textContent = `© ${currentYear} Safgems`;
  
    // Newsletter form submission handler
    const newsletterForm = document.getElementById("newsletter-form");
  
    newsletterForm.addEventListener("submit", (event) => {
      event.preventDefault();
      const emailField = document.querySelector(".email-field");
      const email = emailField.value.trim();
  
      if (validateEmail(email)) {
        alert("Thank you for subscribing!");
        emailField.value = ""; // Clear input after successful submission
      } else {
        alert("Please enter a valid email address.");
      }
    });
  
    // Validate email format
    function validateEmail(email) {
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailPattern.test(email);
    }
  });

  /**
 * header sticky & back top btn active
 */

const header = document.querySelector("[data-header]");
const backTopBtn = document.querySelector("[data-back-top-btn]");

const headerActive = function () {
  if (window.scrollY > 150) {
    header.classList.add("active");
    backTopBtn.classList.add("active");
  } else {
    header.classList.remove("active");
    backTopBtn.classList.remove("active");
  }
}

addEventOnElem(window, "scroll", headerActive);

let lastScrolledPos = 0;

const headerSticky = function () {
  if (lastScrolledPos >= window.scrollY) {
    header.classList.remove("header-hide");
  } else {
    header.classList.add("header-hide");
  }

  lastScrolledPos = window.scrollY;
}

addEventOnElem(window, "scroll", headerSticky);