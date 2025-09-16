document.addEventListener("DOMContentLoaded", function () {
  var sliders = document.querySelectorAll(".swiper-home");
  if (!sliders.length || typeof Swiper === "undefined") return;

  sliders.forEach(function (el) {
    var slides = el.querySelectorAll(".swiper-slide");

    // Esconde setas e paginação se só tiver 1 slide
    if (slides.length <= 1) {
      var nextBtn = el.querySelector(".swiper-button-next");
      var prevBtn = el.querySelector(".swiper-button-prev");
      var pagination = el.querySelector(".swiper-pagination");

      if (nextBtn) nextBtn.style.display = "none";
      if (prevBtn) prevBtn.style.display = "none";
      if (pagination) pagination.style.display = "none";
      return; // nem inicializa o swiper
    }

    // Inicializa o swiper se tiver mais de 1 slide
    new Swiper(el, {
      loop: true,
      slidesPerView: 1,
      spaceBetween: 20, // 👈 GAP ENTRE SLIDES (px)
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      pagination: {
        el: el.querySelector(".swiper-pagination"),
        clickable: true,
      },
      navigation: {
        nextEl: el.querySelector(".swiper-button-next"),
        prevEl: el.querySelector(".swiper-button-prev"),
      },
      speed: 1000,
    });
  });
});
