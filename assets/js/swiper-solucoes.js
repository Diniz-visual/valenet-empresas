document.addEventListener("DOMContentLoaded", function () {
  const containerSelector = ".solucoes-swiper";

  function initOne(container) {
    // destruir instância anterior se existir
    if (container.__swiperInstance) {
      container.__swiperInstance.destroy(true, true);
      container.__swiperInstance = null;
    }

    const slidesCount = container.querySelectorAll(".swiper-slide").length;

    const swiper = new Swiper(container, {
      loop: true,
      watchOverflow: true,
      autoplay: {
        delay: 4000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true
      },
      spaceBetween: 20,
      slidesPerView: 1,

      pagination: {
        el: container.querySelector(".swiper-pagination"),
        clickable: true
      },

      navigation: {
        nextEl: container.querySelector(".swiper-button-next"),
        prevEl: container.querySelector(".swiper-button-prev")
      },

      breakpoints: {
        576:  { slidesPerView: 2, spaceBetween: 20 },
        768:  { slidesPerView: 2, spaceBetween: 20 },
        1024: { slidesPerView: 3, spaceBetween: 20 },
        1200: { slidesPerView: 4, spaceBetween: 20 }
      },

      a11y: { enabled: true },
      keyboard: { enabled: true, onlyInViewport: true }
    });

    // 🔎 esconder bullets no desktop se tiver exatamente 4 slides
    const paginationEl = container.querySelector(".swiper-pagination");
    if (paginationEl) {
      const checkPagination = () => {
        const currentSlidesPerView = swiper.params.slidesPerView;
        const activeBp = swiper.currentBreakpoint;

        // pega config do breakpoint ativo
        const bpConfig = swiper.params.breakpoints[activeBp];
        const perView = bpConfig ? bpConfig.slidesPerView : currentSlidesPerView;

        if (window.innerWidth >= 1200 && slidesCount <= perView) {
          paginationEl.style.display = "none";
        } else {
          paginationEl.style.display = "";
        }
      };

      // checa agora e a cada resize
      checkPagination();
      window.addEventListener("resize", checkPagination);
    }

    container.__swiperInstance = swiper;
  }

  const containers = document.querySelectorAll(containerSelector);
  containers.forEach(initOne);

  window.addEventListener("resize", () => containers.forEach(initOne));
  window.addEventListener("orientationchange", () => containers.forEach(initOne));
});
