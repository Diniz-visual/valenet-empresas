document.addEventListener("DOMContentLoaded", function () {
  const containerSelector = ".planos-b2b-swiper";

  function initOne(container) {
    // destruir instância anterior se existir
    if (container.__swiperInstance) {
      container.__swiperInstance.destroy(true, true);
      container.__swiperInstance = null;
    }

    const swiper = new Swiper(container, {
      loop: true, // ✅ Desabilita loop para evitar repetição com sobras
      watchOverflow: true, // oculta bullets se tiver poucos slides
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true
      },
      spaceBetween: 20,
      slidesPerView: 1,

      // ❌ Removido: slidesPerGroup
      // ❌ Removido: loopFillGroupWithBlank

      pagination: {
        el: container.querySelector(".swiper-pagination"),
        clickable: true
      },

      breakpoints: {
        576:  { slidesPerView: 2, spaceBetween: 20 },
        768:  { slidesPerView: 2, spaceBetween: 20 },
        1024: { slidesPerView: 3, spaceBetween: 20 },
        1200: { slidesPerView: 4, spaceBetween: 20 }
      }
    });

    container.__swiperInstance = swiper;
  }

  const containers = document.querySelectorAll(containerSelector);
  containers.forEach(initOne);

  window.addEventListener("resize", () => containers.forEach(initOne));
  window.addEventListener("orientationchange", () => containers.forEach(initOne));
});
