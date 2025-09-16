(function () {
  'use strict';

  // Lê e limpa o data-target (ex.: "1.200" -> 1200)
  function readTarget(el) {
    var raw = (el.getAttribute('data-target') || '').toString().trim();
    var clean = raw.replace(/[^\d]/g, ''); // só dígitos
    return parseInt(clean, 10) || 0;
  }

  // Abreviação pt-BR
  function formatPtBrCompact(n) {
    var abs = Math.abs(n);
    if (abs >= 1e6) {
      var baseM = n / 1e6;
      var txtM = (Number.isInteger(baseM) ? baseM.toFixed(0) : baseM.toFixed(1)).replace('.', ',');
      return txtM + ' mi';
    }
    if (abs >= 1e3) {
      var baseK = n / 1e3;
      var txtK = (Number.isInteger(baseK) ? baseK.toFixed(0) : baseK.toFixed(1)).replace('.', ',');
      return txtK + ' mil';
    }
    return String(n);
  }

  function composeBody(n, compact) {
    return compact ? formatPtBrCompact(n) : n.toLocaleString('pt-BR'); // 1200 -> "1.200"
  }

  function animateCount(el, target, compact, duration, suffix, prefix) {
    if (el.__animating) return; // evita sobreposição
    el.__animating = true;

    var start = performance.now();

    function tick(now) {
      var t = Math.min(1, (now - start) / duration);
      var eased = 1 - Math.pow(1 - t, 3); // easeOutCubic
      var current = Math.round(target * eased);

      var body = composeBody(current, compact);
      el.textContent = (prefix || '') + body + (suffix || '');

      if (t < 1) {
        requestAnimationFrame(tick);
      } else {
        el.textContent = (prefix || '') + composeBody(target, compact) + (suffix || '');
        el.__animating = false;
      }
    }

    requestAnimationFrame(tick);
  }

  function initCounters() {
    var nodes = document.querySelectorAll('.stat-value[data-target]');
    if (!nodes.length) return;

    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;

        var el = entry.target;
        var target  = readTarget(el);
        var compact = el.getAttribute('data-compact') === 'false';
        var suffix  = el.getAttribute('data-suffix')  || '';
        var prefix  = el.getAttribute('data-prefix')  || ''; // agora opcional

        // Reinicia SEMPRE ao entrar na viewport
        animateCount(el, target, compact, 1400, suffix, prefix);
      });
    }, { threshold: 0.35 });

    nodes.forEach(function (el) { io.observe(el); });
  }

  if (document.readyState !== 'loading') initCounters();
  else document.addEventListener('DOMContentLoaded', initCounters);
})();
