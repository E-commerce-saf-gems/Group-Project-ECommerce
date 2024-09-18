document.addEventListener("DOMContentLoaded", function() {
  const navTogglers = document.querySelectorAll("[data-nav-toggler]");
  const navbar = document.querySelector("[data-navbar]");

  navTogglers.forEach((toggler) => {
    toggler.addEventListener("click", function() {
      navbar.classList.toggle("active");
    });
  });

  const navbarLinks = document.querySelectorAll("[data-nav-link]");
  navbarLinks.forEach((link) => {
    link.addEventListener("click", function() {
      navbar.classList.remove("active");
    });
  });
});
