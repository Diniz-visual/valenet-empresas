document.addEventListener("DOMContentLoaded", function () {
  if (typeof Swiper === "undefined") {
    console.error("Swiper não carregado para clientes & parceiros");
    return;
  }

  new Swiper(".clientes-parceiros-swiper", {
    loop: true,
    speed: 600,
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
    },
    slidesPerView: 1,   // 1 logo por vez no mobile
    spaceBetween: 12,   // espaçamento entre slides
    breakpoints: {
    320:  { slidesPerView: 1, spaceBetween: 16 },  // 👈 celulares muito pequenos
    480:  { slidesPerView: 2, spaceBetween: 18 },  // 👈 celulares médios
    576: { slidesPerView: 2, spaceBetween: 20 },
    768: { slidesPerView: 3, spaceBetween: 24 },
    992: { slidesPerView: 4, spaceBetween: 28 },
    1200:{ slidesPerView: 5, spaceBetween: 25 },
    1400: { slidesPerView: 6, spaceBetween: 28 },
    },
    pagination: {
      el: ".clientes-parceiros-swiper .swiper-pagination",
      clickable: true,
    },
    a11y: { enabled: true },
  });
});

