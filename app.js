document.addEventListener("DOMContentLoaded", function () {
  var menuToggle = document.querySelector("[data-menu-toggle]");

  if (menuToggle) {
    menuToggle.addEventListener("click", function () {
      var isOpen = document.body.classList.toggle("menu-open");
      menuToggle.setAttribute("aria-expanded", String(isOpen));
    });
  }

  window.addEventListener("resize", function () {
    if (window.innerWidth > 860) {
      document.body.classList.remove("menu-open");
      if (menuToggle) {
        menuToggle.setAttribute("aria-expanded", "false");
      }
    }
  });
});
