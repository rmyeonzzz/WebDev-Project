// document.addEventListener("DOMContentLoaded", function() {
//     const navbar = document.querySelector(".navbar-container");
//     const scrollThreshold = 50; // Pixels to scroll before the color changes

//     function checkScroll() {
//         // window.scrollY or document.documentElement.scrollTop returns the vertical scroll position
//         if (window.scrollY > scrollThreshold) {
//             navbar.classList.add("scrolled");
//         } else {
//             navbar.classList.remove("scrolled");
//         }
//     }

//     // 1. Run once on page load to handle refreshing midway down the page
//     checkScroll();

//     // 2. Attach the function to the scroll event for dynamic changes
//     window.addEventListener("scroll", checkScroll);
// });