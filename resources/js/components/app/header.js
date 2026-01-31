import Headroom from "headroom.js";

const init = () => {
  const navbar = document.querySelector(".header");
  // construct an instance of Headroom, passing the element
  if (!navbar) {
    console.warn("No se encontró el elemento de navegación para Headroom.");
  }else{
    var headroom  = new Headroom(navbar, {
    });
    // initialise
    headroom.init();
  }
}

export default {
    init
};
