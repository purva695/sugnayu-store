const menuToggle = document.querySelector(".menu-toggle");
const mainNav = document.querySelector(".main-nav");

if (menuToggle && mainNav) {
  menuToggle.addEventListener("click", () => {
    const isOpen = mainNav.classList.toggle("is-open");
    menuToggle.setAttribute("aria-expanded", String(isOpen));
    menuToggle.setAttribute(
      "aria-label",
      isOpen ? "Close navigation menu" : "Open navigation menu",
    );
  });
}

const previewForm = document.querySelector("#contactPreviewForm");
if (previewForm) {
  previewForm.addEventListener("submit", (event) => {
    event.preventDefault();
    const status = document.querySelector("#formStatus");
    if (status)
      status.textContent =
        "This is a design preview only. Message sending will be enabled after the PHP backend is connected.";
  });
}

document.addEventListener("DOMContentLoaded", function () {
    const toggle = document.getElementById("searchToggle");
    const form = document.getElementById("headerSearchForm");

    if (!toggle || !form) return;

    const searchInput = form.querySelector('input[name="q"]');

    toggle.addEventListener("click", function () {
        const isOpen = !form.hidden;

        form.hidden = isOpen;
        toggle.setAttribute("aria-expanded", String(!isOpen));

        if (!isOpen && searchInput) {
            searchInput.focus();
        }
    });

    document.addEventListener("click", function (event) {
        if (!event.target.closest(".header-search")) {
            form.hidden = true;
            toggle.setAttribute("aria-expanded", "false");
        }
    });

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            form.hidden = true;
            toggle.setAttribute("aria-expanded", "false");
        }
    });
});
