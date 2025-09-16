document.addEventListener('DOMContentLoaded', function () {
  const sectionSelector = '.solucoes-swiper-section';
  const MAX_PER_VIEW = 4; // maior slidesPerView nos breakpoints

  function ensureMinSlides(container) {
    const wrapper = container.querySelector('.swiper-wrapper');
    if (!wrapper) return;

    const clonedAlready = wrapper.querySelector('.swiper-slide[data-clone="1"]');
    if (clonedAlready) return; // evita clonar novamente ao reinit

    const originals = Array.from(wrapper.querySelectorAll('.swiper-slide'));
    const origCount = originals.length;

    // Se tiver menos que o máximo exibido no desktop, duplicamos até ter o suficiente
    if (origCount > 0 && origCount < MAX_PER_VIEW) {
      // alvo = o dobro do necessário p/ loop ficar suave
      const target = MAX_PER_VIEW * 2;
      let i = 0;
      while (wrapper.querySelectorAll('.swiper-slide').length < target) {
        const clone = originals[i % origCount].cloneNode(true);
        clone.setAttribute('data-clone', '1');
        wrapper.appendChild(clone);
        i++;
      }
    }
  }

  function initOne(section) {
    const container  = section.querySelector('.solucoes-swiper');
    const pagination = section.querySelector('.swiper-pagination');
    const nextEl     = container?.querySelector('.swiper-button-next');
    const prevEl     = container?.querySelector('.swiper-button-prev');

    if (!container) return;

    // 1) Garante quantidade mínima de slides para loop ficar perfeito
    ensureMinSlides(container);

    // 2) Conta os slides (já com possíveis clones)
    const slidesCount = container.querySelectorAll('.swiper-wrapper .swiper-slide').length;

    // 3) Destrói instância anterior
    if (container.__swiperInstance) {
      container.__swiperInstance.destroy(true, true);
      container.__swiperInstance = null;
    }

    // 4) Inicia o Swiper
    const swiper = new Swiper(container, {
      loop: true,                       // loop sempre ativo
      loopFillGroupWithBlank: false,    // sem "fantasmas"
      loopAdditionalSlides: 8,          // margem extra p/ reciclar com suavidade
      speed: 600,
      grabCursor: true,

      autoplay: {
        delay: 4000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      },

      pagination: {
        el: pagination,
        clickable: true,
        dynamicBullets: true,
      },

      navigation: {
        nextEl: nextEl,
        prevEl: prevEl,
      },

      // anda de 1 em 1 sempre, para não sobrar grupo incompleto
      slidesPerView: 1,
      slidesPerGroup: 1,
      spaceBetween: 20,

      breakpoints: {
        576:  { slidesPerView: 2, slidesPerGroup: 1, spaceBetween: 20 },
        768:  { slidesPerView: 3, slidesPerGroup: 1, spaceBetween: 20 },
        1024: { slidesPerView: 3, slidesPerGroup: 1, spaceBetween: 20 },
        1200: { slidesPerView: 4, slidesPerGroup: 1, spaceBetween: 20 },
      },

      a11y: { enabled: true },
      keyboard: { enabled: true, onlyInViewport: true },
      // Se tiver só 1 slide original, ainda assim vai rodar pois clonamos acima
      allowTouchMove: slidesCount > 1
    });

    container.__swiperInstance = swiper;
  }

  const sections = document.querySelectorAll(sectionSelector);
  sections.forEach(initOne);

  // Reinit ao redimensionar/orientação — clones são feitos só uma vez
  const reinit = () => sections.forEach(initOne);
  window.addEventListener('resize', reinit);
  window.addEventListener('orientationchange', reinit);
});
