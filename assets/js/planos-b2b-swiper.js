document.addEventListener("DOMContentLoaded", function () {
  const containerSelector = ".planos-b2b-swiper";
  const MAX_PER_VIEW = 4; // maior slidesPerView configurado

  function ensureMinSlides(container) {
    const wrapper = container.querySelector(".swiper-wrapper");
    if (!wrapper) return;

    // evita clonar de novo em cada resize
    if (wrapper.querySelector('[data-clone="1"]')) return;

    const originals = Array.from(wrapper.querySelectorAll(".swiper-slide"));
    const origCount = originals.length;

    // duplica até ter pelo menos o dobro do necessário
    if (origCount > 0 && origCount < MAX_PER_VIEW) {
      const target = MAX_PER_VIEW * 2;
      let i = 0;
      while (wrapper.querySelectorAll(".swiper-slide").length < target) {
        const clone = originals[i % origCount].cloneNode(true);
        clone.setAttribute("data-clone", "1");
        wrapper.appendChild(clone);
        i++;
      }
    }
  }

  function initOne(container) {
    ensureMinSlides(container);

    if (container.__swiperInstance) {
      container.__swiperInstance.destroy(true, true);
      container.__swiperInstance = null;
    }

    const swiper = new Swiper(container, {
      loop: true,                // loop sempre ativo
      watchOverflow: false,      // força loop mesmo se tiver pouco
      loopFillGroupWithBlank: false,
      loopAdditionalSlides: 8,

      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true
      },

      speed: 600,
      grabCursor: true,

      slidesPerView: 1,
      slidesPerGroup: 1,         // sempre de 1 em 1
      spaceBetween: 20,

      pagination: {
        el: container.querySelector(".swiper-pagination"),
        clickable: true
      },

      breakpoints: {
        576:  { slidesPerView: 2, slidesPerGroup: 1, spaceBetween: 20 },
        768:  { slidesPerView: 2, slidesPerGroup: 1, spaceBetween: 20 },
        1024: { slidesPerView: 3, slidesPerGroup: 1, spaceBetween: 20 },
        1200: { slidesPerView: 4, slidesPerGroup: 1, spaceBetween: 20 }
      }
    });

    container.__swiperInstance = swiper;
  }

  const containers = document.querySelectorAll(containerSelector);
  containers.forEach(initOne);

  const reinit = () => containers.forEach(initOne);
  window.addEventListener("resize", reinit);
  window.addEventListener("orientationchange", reinit);
});
