const init = () => {
  document.querySelectorAll('.section__faq--container[data-faq-single="true"]').forEach(function (wrap) {
      wrap.querySelectorAll('details.faq__item').forEach(function (det) {
        det.addEventListener('toggle', function () {
          const open = det.open;
          // Actualiza aria-expanded del summary
          const summary = det.querySelector('.faq__question');
          if (summary) summary.setAttribute('aria-expanded', open ? 'true' : 'false');

          if (open) {
            wrap.querySelectorAll('details.faq__item[open]').forEach(function (other) {
              if (other !== det) other.removeAttribute('open');
              const s = other.querySelector('.faq__question');
              if (s) s.setAttribute('aria-expanded', 'false');
            });
          }
        });
      });
    });
}

export default {
    init
};