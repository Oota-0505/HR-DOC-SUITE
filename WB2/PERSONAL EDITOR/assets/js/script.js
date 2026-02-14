jQuery(function ($) {
  $(function () {
    $(".js-hamburger").click(function () {
      $(this).toggleClass("is-open");
      $(".js-drawer").toggleClass("is-open");
    });

    $(".js-drawer a[href]").on("click", function () {
      $(".js-hamburger").removeClass("is-open");
      $(".js-drawer").removeClass("is-open");
    });

    $(window).on("resize", function () {
      if (window.matchMedia("(min-width: 768px)").matches) {
        $(".js-hamburger").removeClass("is-open");
        $(".js-drawer").removeClass("is-open");
      }
    });

    // Scroll Animation (Intersection Observer)
    if ("IntersectionObserver" in window) {
      var observerOptions = {
        root: null,
        rootMargin: "0px 0px -35% 0px",
        threshold: 0.2
      };

      var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            observer.unobserve(entry.target);
          }
        });
      }, observerOptions);

      var viewportHeight = window.innerHeight;
      document.querySelectorAll(".js-animate, .js-animate-stagger").forEach(function (el) {
        var rect = el.getBoundingClientRect();
        if (rect.top < viewportHeight * 1.2) {
          el.classList.add("is-visible");
        } else {
          observer.observe(el);
        }
      });
    } else {
      document.querySelectorAll(".js-animate, .js-animate-stagger").forEach(function (el) {
        el.classList.add("is-visible");
      });
    }
  });
});
