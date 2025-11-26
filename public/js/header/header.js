// Header Sticky Enhancement
(function () {
  "use strict";

  const header = document.querySelector(".header");
  let lastScrollTop = 0;
  let ticking = false;

  function updateHeader(scrollPos) {
    if (scrollPos > 50) {
      header.classList.add("scrolled");
    } else {
      header.classList.remove("scrolled");
    }
  }

  function onScroll() {
    lastScrollTop = window.pageYOffset || document.documentElement.scrollTop;

    if (!ticking) {
      window.requestAnimationFrame(function () {
        updateHeader(lastScrollTop);
        ticking = false;
      });
      ticking = true;
    }
  }

  // Attach scroll listener
  window.addEventListener("scroll", onScroll, { passive: true });

  // Initial check
  updateHeader(window.pageYOffset || document.documentElement.scrollTop);
})();
