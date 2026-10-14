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
