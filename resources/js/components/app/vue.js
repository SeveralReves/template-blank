import { createApp } from 'vue'

const init = () => {

  const components = {
    ExampleComponent: () => import('../vue/ExampleComponent.vue'),
    Booking: () => import('../vue/Booking.vue'),
    BookingTable: () => import('../vue/BookingTable.vue'),
  }

  // Busca todos los nodos Blade que pidan un componente Vue
  document.querySelectorAll('[data-vue]').forEach(async (el) => {
    const name = el.getAttribute('data-vue')
    const loader = components[name]
    if (!loader) return

    // Props desde data-props (JSON)
    let props = {}
    const raw = el.getAttribute('data-props')
    if (raw) {
      try { props = JSON.parse(raw) } catch (_) {}
    }

    const Comp = (await loader()).default
    const app = createApp(Comp, props)
    app.mount(el)
  })
  
}

export default {
    init
};