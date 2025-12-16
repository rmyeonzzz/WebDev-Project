// document.addEventListener("DOMContentLoaded", function() {
//     // --- Navbar Scroll Effect Logic ---
//     const navbar = document.querySelector(".navbar-container");
//     const scrollThreshold = 50; // Pixels to scroll before the color changes

//     function checkScroll() {
//         // Apply a class to change the navbar appearance (e.g., set a solid color)
//         if (window.scrollY > scrollThreshold) {
//             navbar.classList.add("scrolled");
//         } else {
//             // Remove the class to revert to the initial transparent state
//             navbar.classList.remove("scrolled");
//         }
//     }

//     // Run once on page load and attach to the scroll event
//     checkScroll();
//     window.addEventListener("scroll", checkScroll);
// });