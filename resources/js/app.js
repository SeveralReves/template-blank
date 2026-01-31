import './bootstrap';

import header from './components/app/header.js';
import animate from './components/app/animate.js';
import vue from './components/app/vue.js';
import slick from './components/app/slick.js';  
import UI from './components/app/UI.js';


document.addEventListener('DOMContentLoaded', () => {

  header.init();
  animate.init();
  vue.init();
  slick.init();
  UI.init();

});
