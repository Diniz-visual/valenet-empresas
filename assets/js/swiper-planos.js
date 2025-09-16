(function () {
  'use strict';

  // helper: inicializa um swiper apontando os controles do próprio container
  function initScopedSwiper(containerSelector, options) {
    var containers = document.querySelectorAll(containerSelector);
    if (!containers.length) return [];

    return Array.prototype.map.call(containers, function (container) {
      var paginationEl = container.querySelector('.swiper-pagination');
      var nextEl = container.querySelector('.swiper-button-next');
      var prevEl = container.querySelector('.swiper-button-prev');

      var scopedOptions = Object.assign(
        {},
        options,
        {
          pagination: options.pagination
            ? Object.assign({}, options.pagination, { el: paginationEl || null })
            : undefined,
          navigation: options.navigation
            ? Object.assign({}, options.navigation, { nextEl: nextEl || null, prevEl: prevEl || null })
            : undefined
        }
      );

      // remove chaves undefined (Swiper reclama às vezes)
      if (!paginationEl) delete scopedOptions.pagination;
      if (!nextEl && !prevEl) delete scopedOptions.navigation;

      return new Swiper(container, scopedOptions);
    });
  }

  function ready(fn) {
    if (document.readyState !== 'loading') fn();
    else document.addEventListener('DOMContentLoaded', fn);
  }

  ready(function () {
    // 1) topo (swiper-home)
    initScopedSwiper('.swiper-home', {
      loop: true,
      speed: 700,
      autoplay: { delay: 4500, disableOnInteraction: false },
      effect: 'slide', // troque para 'fade' se preferir
      grabCursor: true,
      pagination: { clickable: true },
      navigation: { enabled: true },
      a11y: { enabled: true }
    });

    // 2) planos b2b (planos-b2b-swiper)
    initScopedSwiper('.planos-b2b-swiper', {
      loop: false,
      speed: 500,
      spaceBetween: 16,
      grabCursor: true,
      pagination: { clickable: true },
      // sem setas no markup? se tiver, ele pega automaticamente
      navigation: { enabled: true },
      a11y: { enabled: true },
      slidesPerView: 1,
      breakpoints: {
        576: { slidesPerView: 1.2, spaceBetween: 16 },
        768: { slidesPerView: 2,   spaceBetween: 20 },
        992: { slidesPerView: 3,   spaceBetween: 24 },
        1400:{ slidesPerView: 4,   spaceBetween: 24 }
      }
    });

    // 3) clientes parceiros (logos – carrossel contínuo)
    initScopedSwiper('.clientes-parceiros-swiper', {
      loop: true,
      speed: 4000,                 // velocidade do fluxo contínuo
      autoplay: { delay: 0, disableOnInteraction: false },
      freeMode: { enabled: true, momentum: false },
      slidesPerView: 2,
      spaceBetween: 16,
      // geralmente sem navegação/paginação para logos
      a11y: { enabled: true },
      breakpoints: {
        576: { slidesPerView: 3, spaceBetween: 16 },
        768: { slidesPerView: 4, spaceBetween: 20 },
        992: { slidesPerView: 5, spaceBetween: 24 },
        1200:{ slidesPerView: 6, spaceBetween: 24 }
      }
    });

    // 4) nossas soluções (solucoes-swiper)
    initScopedSwiper('.solucoes-swiper', {
      loop: false,
      speed: 600,
      spaceBetween: 24,
      grabCursor: true,
      pagination: { clickable: true },
      navigation: { enabled: true },
      a11y: { enabled: true },
      slidesPerView: 1,
      breakpoints: {
        576: { slidesPerView: 1.2, spaceBetween: 16 },
        768: { slidesPerView: 2,   spaceBetween: 20 },
        992: { slidesPerView: 3,   spaceBetween: 24 }
      }
    });
  });
})();
