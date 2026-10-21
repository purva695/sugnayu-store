
/* ===== HEADER MENU TOGGLE ===== */
const menuToggle = document.querySelector(".menu-toggle");
const mainNav = document.querySelector(".main-nav");

if (menuToggle && mainNav) {
    menuToggle.addEventListener("click", function () {
        const isOpen = mainNav.classList.toggle("is-open");

        menuToggle.setAttribute("aria-expanded", String(isOpen));
        menuToggle.setAttribute(
            "aria-label",
            isOpen ? "Close navigation menu" : "Open navigation menu"
        );
    });

    // Menu link par click karne ke baad menu band ho jayega
    mainNav.querySelectorAll("a").forEach(function (link) {
        link.addEventListener("click", function () {
            mainNav.classList.remove("is-open");
            menuToggle.setAttribute("aria-expanded", "false");
        });
    });

    // Menu ke bahar click karne par menu band
    document.addEventListener("click", function (event) {
        if (
            !mainNav.contains(event.target) &&
            !menuToggle.contains(event.target)
        ) {
            mainNav.classList.remove("is-open");
            menuToggle.setAttribute("aria-expanded", "false");
        }
    });

    // Escape key se menu band
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            mainNav.classList.remove("is-open");
            menuToggle.setAttribute("aria-expanded", "false");
        }
    });
}


/* ===== CONTACT FORM PREVIEW ===== */
const previewForm = document.querySelector("#contactPreviewForm");

if (previewForm) {
    previewForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const status = document.querySelector("#formStatus");

        if (status) {
            status.textContent =
                "This is a design preview only. Message sending will be enabled after the PHP backend is connected.";
        }
    });
}


/* ===== SHOP / OUR STORY HERO SLIDER ===== */
document.addEventListener("DOMContentLoaded", function () {
    const root = document.getElementById("pageHeroSlider");

    if (!root) return;

    const slides = Array.from(root.querySelectorAll(".ref-slide"));
    const dots = Array.from(root.querySelectorAll(".ref-dots button"));
    const prev = root.querySelector(".ref-prev");
    const next = root.querySelector(".ref-next");

    if (slides.length === 0) return;

    let current = 0;
    let timer = null;

    function showSlide(index) {
        current = (index + slides.length) % slides.length;

        slides.forEach(function (slide, i) {
            slide.classList.toggle("is-active", i === current);
        });

        dots.forEach(function (dot, i) {
            dot.classList.toggle("is-active", i === current);
        });
    }

    function startAutoplay() {
        clearInterval(timer);

        timer = setInterval(function () {
            showSlide(current + 1);
        }, 3000);
    }

    if (prev) {
        prev.addEventListener("click", function () {
            showSlide(current - 1);
            startAutoplay();
        });
    }

    if (next) {
        next.addEventListener("click", function () {
            showSlide(current + 1);
            startAutoplay();
        });
    }

    dots.forEach(function (dot, index) {
        dot.addEventListener("click", function () {
            showSlide(index);
            startAutoplay();
        });
    });

    // Mouse slider par hone par autoplay pause
    root.addEventListener("mouseenter", function () {
        clearInterval(timer);
    });

    root.addEventListener("mouseleave", function () {
        startAutoplay();
    });

    showSlide(0);
    startAutoplay();
});

/* ===== MOBILE SEARCH OPEN / CLOSE ===== */
document.addEventListener("DOMContentLoaded", function () {
    const searchToggle = document.getElementById("mobileSearchToggle");
    const searchForm = document.getElementById("mobileSearchForm");

    if (!searchToggle || !searchForm) return;

    const searchInput = searchForm.querySelector('input[name="q"]');

    function closeSearch() {
        searchForm.classList.remove("mobile-search-open");
        searchToggle.setAttribute("aria-expanded", "false");
        searchToggle.setAttribute("aria-label", "Open search");
    }

    searchToggle.addEventListener("click", function () {
        const isOpen = searchForm.classList.toggle("mobile-search-open");

        searchToggle.setAttribute("aria-expanded", String(isOpen));
        searchToggle.setAttribute(
            "aria-label",
            isOpen ? "Close search" : "Open search"
        );

        if (isOpen && searchInput) {
            searchInput.focus();
        }
    });

    document.addEventListener("click", function (event) {
        if (
            !searchForm.contains(event.target) &&
            !searchToggle.contains(event.target)
        ) {
            closeSearch();
        }
    });

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            closeSearch();
        }
    });

    window.addEventListener("resize", function () {
        if (window.innerWidth > 760) {
            closeSearch();
        }
    });
});
