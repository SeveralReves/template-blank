
import Alpine from 'alpinejs';
import AOS from 'aos';
import 'aos/dist/aos.css';

const init = () => {
  window.Alpine = Alpine;
  Alpine.start();

  AOS.init({
    offset: 120, 
    delay: 0, 
    duration: 600,
    easing: 'ease', 
    once: true, 
  });
}

export default {
    init
};