<template>
  <div>
    <div class="card">
      <div class="card__header">
          <h3 class="card__header--title">
              Usuarios
          </h3>
          <div class="card__header--button">
            <button type="button" class="button__primary" @click="openCreate">Nuevo</button>
          </div>
      </div>
      <div class="card__body" style="display:flex; gap: 10px; align-items:center; flex-wrap: wrap;">
        <input
          v-model.trim="filters.q"
          class="field__input"
          style="max-width: 260px;"
          placeholder="Buscar por título..."
          @keyup.enter="applyFilters"
        />

        <select v-model="filters.status" class="field__input" style="max-width: 200px;">
          <option value="">Todos</option>
          <option value="draft">Borrador</option>
          <option value="active">Activa</option>
          <option value="paused">Pausada</option>
          <option value="finished">Finalizada</option>
        </select>

        <select v-model.number="filters.per_page" class="field__input" style="max-width: 140px;">
          <option :value="5">5</option>
          <option :value="10">10</option>
          <option :value="20">20</option>
          <option :value="50">50</option>
        </select>
        <button class="button__secondary" @click="applyFilters">Filtrar</button>
        <button class="button__secondary" @click="resetFilters">Limpiar</button>
      </div>
    </div>

    <!-- Listado -->
    <div class="card" style="margin-top: 14px" v-if="rafflesList.length">
      <div class="card__body">
        <div
          v-for="r in rafflesList"
          :key="r.id"
          style="display:flex; gap: 10px; align-items:center; justify-content:space-between; padding: 10px 0; border-bottom: 1px solid #eee;"
        >
          <div>
            <strong>{{ r.title }}</strong>
            <div style="font-size: 12px; opacity: .75;">
              {{ r.slug }} · {{ r.status }} · {{ r.ticket_price }} {{ r.currency }} · Tickets vendidos: {{ r.tickets_sold }}/{{ r.total_tickets }}
            </div>
          </div>

          <div style="display:flex; gap:8px; align-items:center;">
            <button class="button__secondary" @click="openEdit(r)">Editar</button>
            <button class="button__secondary" @click="openOrders(r)">Órdenes</button>
            <button class="button__secondary" @click="openSoldTickets(r)">Vendidos</button>
            <button class="button__secondary" @click="openWinner(r)">Ganador</button>
          </div>

        </div>
      </div>
    </div>

    <div class="card" style="margin-top: 14px" v-else>
      <div class="card__body" style="opacity:.7;">No hay datos para mostrar.</div>
    </div>

    <!-- Paginación -->
    <div class="pagination" v-if="pagination.last_page > 1">
      <button class="button__secondary" :disabled="pagination.current_page <= 1" @click="goToPage(pagination.current_page - 1)">
        Anterior
      </button>

      <button
        v-for="p in pagesToShow"
        :key="p"
        class="button__secondary"
        :class="{ 'is-active': p === pagination.current_page }"
        @click="goToPage(p)"
      >
        {{ p }}
      </button>

      <button class="button__secondary" :disabled="pagination.current_page >= pagination.last_page" @click="goToPage(pagination.current_page + 1)">
        Siguiente
      </button>

      <div class="pagination__meta">
        Página {{ pagination.current_page }} de {{ pagination.last_page }} · Total: {{ pagination.total }}
      </div>
    </div>

    <BaseModal
      v-model="openCreateModal"
      :title="isEdit ? 'Editar Usuario' : 'Nueva Usuario'"
      @close="resetForm"
    >
      <form @submit.prevent="save" class="table-form">
        <!-- Título -->
        <div class="field">
          <label class="field__label">Título *</label>
          <input
            v-model.trim="form.title"
            type="text"
            class="field__input"
            placeholder="Ej: Rifa solidaria Enero 2026"
            @input="handleTitleInput"
          />
          <small v-if="errors.title" class="field__error">{{ errors.title }}</small>
        </div>

        <!-- Slug -->
        <div class="field">
          <label class="field__label">Slug *</label>
          <input
            v-model.trim="form.slug"
            type="text"
            class="field__input"
            placeholder="ej: rifa-solidaria-enero-2026"
          />
          <small v-if="errors.slug" class="field__error">{{ errors.slug }}</small>
        </div>


        <!-- Precio + Moneda -->
        <div class="grid-2">
          <div class="field">
            <label class="field__label">Precio por ticket</label>
            <input
              v-model="form.ticket_price"
              type="number"
              step="0.01"
              min="0"
              class="field__input"
              placeholder="Ej: 5.00"
            />
            <small v-if="errors.ticket_price" class="field__error">{{ errors.ticket_price }}</small>
          </div>

          <div class="field">
            <label class="field__label">Moneda *</label>
            <select v-model="form.currency" class="field__input">
              <option disabled value="">Selecciona</option>
              <option value="USD">USD</option>
              <option value="VES">VES</option>
            </select>
            <small v-if="errors.currency" class="field__error">{{ errors.currency }}</small>
          </div>
        </div>

        <!-- Total tickets + Fecha sorteo -->
        <div class="grid-2">
          <div class="field">
            <label class="field__label">Total de tickets *</label>
            <input
              v-model="form.total_tickets"
              type="number"
              min="1"
              class="field__input"
              placeholder="Ej: 1000"
            />
            <small v-if="errors.total_tickets" class="field__error">{{ errors.total_tickets }}</small>
          </div>

          <div class="field">
            <label class="field__label">Fecha del sorteo</label>
            <input
              v-model="form.draw_at"
              type="date"
              class="field__input"
            />
          </div>
        </div>
        <div class="grid-2">
          <!-- Status -->
          <div class="field">
            <label class="field__label">Estatus *</label>
            <select v-model="form.status" class="field__input">
              <option value="draft">Borrador</option>
              <option value="active">Activa</option>
              <option value="paused">Pausada</option>
              <option value="closed">Cerrada</option>
              <option value="drawn">Extendida</option>
              <option value="cancelled">Descartada</option>
            </select>
          </div>

          <div class="field">
            <label class="field__label">Tipo *</label>
            <select v-model="form.assignment_type" class="field__input">
              <option value="manual">Manual</option>
              <option value="auto">Automatica</option>
            </select>
          </div>


        </div>

        <div class="field">
          <label class="field__label">Cover</label>
          <input type="file" accept="image/*" @change="onCoverChange" />

          <div v-if="preview.cover" style="margin-top:10px;">
            <img :src="preview.cover" style="max-width:100%; border-radius:10px;" />
          </div>
        </div>

        <div class="field">
          <label class="field__label">Galería</label>
          <input type="file" accept="image/*" multiple @change="onGalleryChange" />

          <div v-if="preview.gallery.length" style="display:flex; gap:10px; flex-wrap:wrap; margin-top:10px;">
            <img v-for="(src, i) in preview.gallery" :key="i" :src="src"
              style="width:90px; height:90px; object-fit:cover; border-radius:10px;"
            />
          </div>

          <div v-if="gallerySaved.length" style="display:flex; gap:10px; flex-wrap:wrap; margin-top:10px;">
            <div v-for="img in gallerySaved" :key="img.id">
              <img :src="img.image_url" style="width:90px; height:90px; object-fit:cover; border-radius:10px;" />
              <button type="button" class="button__secondary" @click="deleteGalleryImage(img)" style="width:100%; margin-top:6px;">
                Quitar
              </button>
            </div>
          </div>
        </div>

      </form>

      <template #footer>
        <button class="button__secondary" @click="openCreateModal = false; resetForm()">
          Cancelar
        </button>

        <button class="button__primary" @click="save">
          {{ isEdit ? 'Actualizar' : 'Guardar' }}
        </button>
      </template>
    </BaseModal>

    <BaseModal v-model="openOrdersModal" title="Órdenes de la rifa">
      <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
        <input v-model.trim="ordersFilters.q" class="field__input" style="max-width:260px;" placeholder="Buscar (code/email/id)" />
        <select v-model="ordersFilters.status" class="field__input" style="max-width:200px;">
          <option value="">Todos</option>
          <option value="reserved">reserved</option>
          <option value="pending_payment">pending_payment</option>
          <option value="under_review">under_review</option>
          <option value="approved">approved</option>
          <option value="rejected">rejected</option>
          <option value="expired">expired</option>
        </select>
        <button class="button__secondary" @click="fetchOrders(1)">Filtrar</button>
      </div>

      <div style="margin-top:12px;" v-if="orders.length">
        <div v-for="o in orders" :key="o.id" style="padding:10px 0; border-bottom:1px solid #eee;">
          <div style="display:flex; justify-content:space-between; gap:10px; align-items:center;">
            <div>
              <strong>#{{ o.order_code || o.id }}</strong>
              <div style="font-size:12px; opacity:.75;">
                {{ orderStatus(o.status) }} · qty: {{ o.qty }} · {{ o.amount_total }} {{ o.currency }}
              </div>
              <div style="font-size:12px; opacity:.75;" v-if="o.customer_snapshot?.email">
                {{ o.customer_snapshot.email }} · {{ o.customer_snapshot.full_name }}
              </div>
              <div v-if="o.payment_reference" style="font-size:12px; opacity:.75;">
                Referencia: {{ o.payment_reference ? o.payment_reference : 'No' }}
              </div>
              <div v-if="o.paid_at" style="font-size:12px; opacity:.75;">
                Método: {{ paymentMethod(o.payment_method) }}
              </div>
              <div v-if="o.paid_at" style="font-size:12px; opacity:.75;">
                Pagado el: {{ o.paid_at.slice(0,10) }}
              </div>
              <div style="font-size:12px; opacity:.75;">
                Creado el: {{ o.created_at.slice(0,10) }}
              </div>
            </div>

            <div style="display:flex; gap:8px;">
              <button
                class="button__secondary"
                v-if="o.status === 'under_review'"
                @click="approveOrder(o)"
              >Aprobar</button>

              <button
                class="button__secondary"
                v-if="['under_review','reserved','pending_payment'].includes(o.status)"
                @click="rejectOrder(o)"
              >Rechazar</button>

              <a
                v-if="o.payment_proof_url"
                :href="o.payment_proof_url"
                target="_blank"
                class="button__secondary"
              >Ver comprobante</a>
            </div>
          </div>
        </div>
      </div>

      <div v-else style="opacity:.7; padding:10px 0;">No hay órdenes.</div>

      <template #footer>
        <button class="button__secondary" @click="openOrdersModal = false">Cerrar</button>
      </template>
    </BaseModal>

    <BaseModal v-model="openTicketsModal" title="Tickets vendidos">
      <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
        <select v-model="ticketsFilters.status" class="field__input" style="max-width:200px;">
          <option value="sold">Vendidos</option>
          <option value="reserved">Reservados</option>
          <option value="available">Disponibles</option>
        </select>
        <button class="button__secondary" @click="fetchTickets(1)">Cargar</button>
      </div>

      <div style="margin-top:12px; display:flex; gap:8px; flex-wrap:wrap;">
        <div
          v-for="t in ticketsSold"
          :key="t.id"
          style="padding:6px 10px; border:1px solid #eee; border-radius:10px;"
        >
          #{{ t.number }}
        </div>
      </div>

      <template #footer>
        <button class="button__secondary" @click="openTicketsModal = false">Cerrar</button>
      </template>
    </BaseModal>

    <BaseModal v-model="openWinnerModal" title="Ganador">
      <div v-if="selectedRaffle?.winner_ticket_number" style="padding:10px; border:1px solid #eee; border-radius:10px;">
        Ganador actual: <strong>{{ winnerSnapshot }}</strong>
      </div>
      <div v-else style="opacity:.7;">Aún no hay ganador.</div>

      <div style="margin-top:12px;">
        <label class="field__label">Asignar manual (numero vendido)</label>
        <input v-model.number="winnerTicketId" type="number" class="field__input" placeholder="Ej: 123" />
        <small style="opacity:.7;">Tip: abre “Vendidos” para ver los tickets.</small>

        <div style="display:flex; gap:10px; margin-top:10px; flex-wrap:wrap;">
          <button class="button__secondary" @click="setWinnerManual" :disabled="!winnerTicketId">
            Asignar manual
          </button>
          <button class="button__primary" @click="drawWinnerRandom">
            Sortear aleatorio
          </button>
        </div>
      </div>

      <template #footer>
        <button class="button__secondary" @click="openWinnerModal = false">Cerrar</button>
      </template>
    </BaseModal>

  </div>
</template>

<script>
import axios from 'axios'
import Swal from 'sweetalert2' 
import BaseModal from '../Common/BaseModal.vue'

export default {
  name: 'RafflesIndex',
  components: { BaseModal },
  props: {
    raffles: { type: Array, default: () => [] },
  },
  data() {
    return {
      openCreateModal: false,
      isEdit: false,
      editingId: null,
      autoSlug: true,

      form: this.emptyForm(),
      errors: {},

      rafflesList: [],
      loadingList: false,
      pagination: {
        current_page: 1,
        last_page: 1,
        per_page: 10,
        total: 0,
      },

      filters: {
        q: '',
        status: '',
        per_page: 10,
      },

      files: { cover: null, gallery: [] },
      preview: { cover: null, gallery: [] },
      gallerySaved: [],
      selectedRaffle: null,
      // Orders modal
      openOrdersModal: false,
      orders: [],
      ordersPagination: { current_page: 1, last_page: 1, per_page: 10, total: 0 },
      ordersFilters: { status: '', q: '', per_page: 10 },
      // Tickets sold modal
      openTicketsModal: false,
      ticketsSold: [],
      ticketsPagination: { current_page: 1, last_page: 1, per_page: 50, total: 0 },
      ticketsFilters: { status: 'sold', per_page: 50 },
      // Winner modal
      openWinnerModal: false,
      winnerLoading: false,
      winnerTicketId: null,
    }
  },
  computed: {
    pagesToShow() {
      // simple: máximo 7 páginas visibles alrededor de la actual
      const total = this.pagination.last_page || 1
      const current = this.pagination.current_page || 1
      const delta = 3

      const start = Math.max(1, current - delta)
      const end = Math.min(total, current + delta)

      const pages = []
      for (let i = start; i <= end; i++) pages.push(i)
      return pages
    },
    winnerSnapshot() {
      const winner = JSON.parse(this.selectedRaffle?.winner_snapshot) || null
      return winner?.full_name + ' ' + winner?.phone + ' ' + winner?.email || 'N/A'
    },
    orderStatus() {
      return (status) => {
        const map = {
          reserved: 'Reservado',
          pending_payment: 'Pendiente de pago',
          under_review: 'En revisión',
          approved: 'Aprobado',
          rejected: 'Rechazado',
          expired: 'Expirado',
        }
        return map[status] || status
      }
    },
    paymentMethod() {
      return (method) => {
        const map = {
          pago_movil: 'Pago móvil',
          efectivo: 'Efectivo',
          binance: 'Binance',
          transferencia: 'Transferencia',
        }
        return map[method] || method
      }
    },
  },

  mounted() {
    this.fetchData(1)
  },
  methods: {
    async fetchData(page = 1) {
      this.loadingList = true
      try {
        const { data } = await axios.get('/api/users', {
          params: {
            page,
            per_page: this.filters.per_page,
            q: this.filters.q || undefined,
            status: this.filters.status || undefined,
          },
        })

        // Laravel: { data: paginator }
        const paginator = data?.data

        this.rafflesList = paginator?.data ?? []
        this.pagination = {
          current_page: paginator?.current_page ?? page,
          last_page: paginator?.last_page ?? 1,
          per_page: paginator?.per_page ?? this.filters.per_page,
          total: paginator?.total ?? 0,
        }
      } catch (error) {
        console.error(error)
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: error.response?.data?.message || 'No se pudo cargar la lista.',
          customClass: { container: 'swal-high-z' },
        })
      } finally {
        this.loadingList = false
      }
    },
    goToPage(page) {
      if (page < 1 || page > this.pagination.last_page) return
      this.fetchRaffles(page)
    },

    applyFilters() {
      // al filtrar vuelves a página 1
      this.fetchRaffles(1)
    },

    resetFilters() {
      this.filters.q = ''
      this.filters.status = ''
      this.filters.per_page = 10
      this.fetchRaffles(1)
    },
    emptyForm() {
      return {
        title: '',
        slug: '',
        description: '',
        terms: '',
        ticket_price: '',
        currency: 'USD',
        assignment_type: 'manual',
        total_tickets: '',
        draw_at: '',
        status: 'draft',
      }
    },

    openCreate() {
      this.isEdit = false
      this.editingId = null
      this.autoSlug = true
      this.errors = {}
      this.form = this.emptyForm()
      this.openCreateModal = true
    },

    async openEdit(raffle) {
      this.isEdit = true
      this.editingId = raffle.id
      this.autoSlug = false
      this.errors = {}

      // Mapea lo que venga del backend (ajusta si tus keys cambian)
      this.form = {
        title: raffle.title ?? '',
        slug: raffle.slug ?? '',
        description: raffle.description ?? '',
        terms: raffle.terms ?? '',
        ticket_price: raffle.ticket_price ?? '',
        currency: raffle.currency ?? 'USD',
        assignment_type: raffle.assignment_type ?? 'manual',
        total_tickets: raffle.total_tickets ?? '',
        draw_at: raffle.draw_at ? String(raffle.draw_at).slice(0, 10) : '',
        status: raffle.status ?? 'draft',
      }

      this.preview.cover = raffle.cover_image_url || null
      // this.gallerySaved = raffle.images || []
      this.preview.gallery = []
      this.files.gallery = []
      this.files.cover = null

      const res = await axios.get(`/api/raffles/${raffle.id}/gallery`)
      this.gallerySaved = res.data?.data || []

      this.openCreateModal = true
    },

    resetForm() {
      // Se ejecuta al cerrar por overlay/esc también
      this.errors = {}
      this.form = this.emptyForm()
      this.isEdit = false
      this.editingId = null
      this.files = { cover: null, gallery: [] }
      this.preview = { cover: null, gallery: [] }
      this.gallerySaved = []
    },

    handleTitleInput() {
      // Si es crear, o si el slug estaba vacío y autoSlug está activo
      if (!this.autoSlug) return
      if (!this.form.slug) {
        this.form.slug = this.slugify(this.form.title)
      } else {
        // si quieres que SIEMPRE actualice mientras escribes:
        this.form.slug = this.slugify(this.form.title)
      }
    },

    slugify(str) {
      return String(str || '')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
    },

    validate() {
      const e = {}

      if (!this.form.title) e.title = 'El título es obligatorio.'
      if (!this.form.slug) e.slug = 'El slug es obligatorio.'
      if (!this.form.description) e.description = 'La descripción es obligatoria.'
      if (!this.form.terms) e.terms = 'Los términos son obligatorios.'
      if (!this.form.currency) e.currency = 'La moneda es obligatoria.'

      const total = Number(this.form.total_tickets)
      if (!this.form.total_tickets) e.total_tickets = 'El total de tickets es obligatorio.'
      else if (!Number.isFinite(total) || total < 1) e.total_tickets = 'Debe ser un número mayor o igual a 1.'

      if (this.form.ticket_price !== '' && this.form.ticket_price !== null) {
        const price = Number(this.form.ticket_price)
        if (!Number.isFinite(price) || price < 0) e.ticket_price = 'El precio debe ser un número válido (>= 0).'
      }

      this.errors = e
      return Object.keys(e).length === 0
    },

    async save() {
      if (!this.validate()) return

      const payload = { ...this.form }

      const wasEdit = this.isEdit
      const raffleId = wasEdit ? this.editingId : null

      try {
        let id = raffleId

        if (wasEdit) {
          await axios.put(`/api/raffles/${raffleId}`, payload)
        } else {
          const res = await axios.post('/api/raffles', payload)
          id = res.data?.data?.id
        }

        await this.uploadCoverIfAny(id)
        await this.uploadGalleryIfAny(id)

        Swal.fire({
          icon: 'success',
          title: 'Éxito',
          text: wasEdit ? 'Rifa actualizada con éxito.' : 'Rifa creada con éxito.',
          timer: 2000,
          showConfirmButton: false,
          customClass: { container: 'swal-high-z' },
        })

        this.openCreateModal = false
        this.form = this.emptyForm()
        this.isEdit = false
        this.editingId = null
        this.errors = {}

        this.fetchRaffles(this.pagination?.current_page || 1)

      } catch (error) {
        console.error(error)

        // ✅ si backend manda 422 con errors, mapea al form
        if (error.response?.status === 422 && error.response?.data?.errors) {
          const backendErrors = error.response.data.errors
          const e = {}
          Object.keys(backendErrors).forEach((k) => (e[k] = backendErrors[k]?.[0]))
          this.errors = e
        }

        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: error.response?.data?.message || 'No se pudo guardar la rifa.',
          customClass: { container: 'swal-high-z' },
        })
      }
    },
    async uploadCoverIfAny(raffleId) {
      if (!raffleId) return
      if (!this.files?.cover) return
      await this.uploadCover(raffleId)
    },
    async uploadGalleryIfAny(raffleId) {
      if (!raffleId) return
      if (!this.files?.gallery?.length) return
      await this.uploadGallery(raffleId)
    },
    onCoverChange(e) {
      const file = e.target.files?.[0]
      if (!file) return
      this.files.cover = file
      this.preview.cover = URL.createObjectURL(file)
    },

    onGalleryChange(e) {
      const files = Array.from(e.target.files || [])
      this.files.gallery = files
      this.preview.gallery = files.map(f => URL.createObjectURL(f))
    },

    async uploadCover(raffleId) {
      if (!this.files.cover) return

      const fd = new FormData()
      fd.append('image', this.files.cover)

      const { data } = await axios.post(`/api/raffles/${raffleId}/cover`, fd, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })

      // Actualiza preview con lo guardado
      this.preview.cover = data?.data?.cover_image_url || this.preview.cover
      this.files.cover = null
    },

    async uploadGallery(raffleId) {
      if (!this.files.gallery?.length) return

      const fd = new FormData()
      this.files.gallery.forEach((f) => fd.append('images[]', f))

      const { data } = await axios.post(`/api/raffles/${raffleId}/gallery`, fd, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })

      // agrega a gallerySaved y limpia previews
      this.gallerySaved = [...this.gallerySaved, ...(data?.data || [])]
      this.files.gallery = []
      this.preview.gallery = []
    },

    async deleteGalleryImage(img) {
      await axios.delete(`/api/raffles/${this.editingId}/gallery/${img.id}`)
      this.gallerySaved = this.gallerySaved.filter(i => i.id !== img.id)
    },
    async openOrders(raffle) {
      this.selectedRaffle = raffle
      this.openOrdersModal = true
      await this.fetchOrders(1)
    },

    async fetchOrders(page = 1) {
      const rid = this.selectedRaffle?.id
      if (!rid) return

      const { data } = await axios.get(`/api/admin/raffles/${rid}/orders`, {
        params: {
          page,
          per_page: this.ordersFilters.per_page,
          q: this.ordersFilters.q || undefined,
          status: this.ordersFilters.status || undefined,
        }
      })

      const paginator = data?.data
      this.orders = paginator?.data || []
      this.ordersPagination = {
        current_page: paginator?.current_page || 1,
        last_page: paginator?.last_page || 1,
        per_page: paginator?.per_page || this.ordersFilters.per_page,
        total: paginator?.total || 0,
      }
    },
    async approveOrder(order) {
      const res = await Swal.fire({
        icon: 'question',
        title: 'Aprobar orden',
        text: `¿Aprobar la orden ${order.order_code || order.id}?`,
        showCancelButton: true,
        confirmButtonText: 'Sí, aprobar',
        cancelButtonText: 'Cancelar',
        customClass: { container: 'swal-high-z' },
      })
      if (!res.isConfirmed) return

      await axios.post(`/api/admin/orders/${order.id}/approve`)
      Swal.fire({ icon:'success', title:'Listo', text:'Orden aprobada.', timer:1500, showConfirmButton:false, customClass:{container:'swal-high-z'} })
      await this.fetchOrders(this.ordersPagination.current_page)
      await this.fetchRaffles(this.pagination.current_page)
    },

    async rejectOrder(order) {
      const res = await Swal.fire({
        title: 'Rechazar orden',
        input: 'text',
        inputLabel: 'Motivo (opcional)',
        showCancelButton: true,
        confirmButtonText: 'Rechazar',
        cancelButtonText: 'Cancelar',
        customClass: { container: 'swal-high-z' },
      })
      if (!res.isConfirmed) return

      await axios.post(`/api/admin/orders/${order.id}/reject`, { reason: res.value || null })
      Swal.fire({ icon:'success', title:'Listo', text:'Orden rechazada.', timer:1500, showConfirmButton:false, customClass:{container:'swal-high-z'} })
      await this.fetchOrders(this.ordersPagination.current_page)
      await this.fetchRaffles(this.pagination.current_page)
    },
    async openSoldTickets(raffle) {
      this.selectedRaffle = raffle
      this.openTicketsModal = true
      this.ticketsFilters.status = 'sold'
      await this.fetchTickets(1)
    },

    async fetchTickets(page = 1) {
      const rid = this.selectedRaffle?.id
      if (!rid) return

      const { data } = await axios.get(`/api/admin/raffles/${rid}/tickets`, {
        params: {
          page,
          per_page: this.ticketsFilters.per_page,
          status: this.ticketsFilters.status || undefined,
        }
      })

      const paginator = data?.data
      this.ticketsSold = paginator?.data || []
      this.ticketsPagination = {
        current_page: paginator?.current_page || 1,
        last_page: paginator?.last_page || 1,
        per_page: paginator?.per_page || this.ticketsFilters.per_page,
        total: paginator?.total || 0,
      }
    },
    async openWinner(raffle) {
      this.selectedRaffle = raffle
      this.winnerTicketId = raffle.winner_ticket_number || null
      this.openWinnerModal = true
    },

    async drawWinnerRandom() {
      const rid = this.selectedRaffle?.id
      if (!rid) return

      const res = await Swal.fire({
        icon: 'question',
        title: 'Sortear ganador',
        text: 'Se escogerá un ticket aleatorio entre los vendidos. ¿Continuar?',
        showCancelButton: true,
        confirmButtonText: 'Sí, sortear',
        cancelButtonText: 'Cancelar',
        customClass: { container: 'swal-high-z' },
      })
      if (!res.isConfirmed) return

      const { data } = await axios.post(`/api/admin/raffles/${rid}/draw`)
      Swal.fire({ icon:'success', title:'Ganador seleccionado', text:data?.message || 'Sorteo listo.', customClass:{container:'swal-high-z'} })

      await this.fetchRaffles(this.pagination.current_page)
      this.selectedRaffle = this.rafflesList.find(x => x.id === rid) || this.selectedRaffle
        setTimeout(() => {
        this.selectedRaffle = this.rafflesList.find(x => x.id === rid) || this.selectedRaffle
      }, 200);

    },

    async setWinnerManual() {
      const rid = this.selectedRaffle?.id
      if (!rid || !this.winnerTicketId) return
      try {
        await axios.post(`/api/admin/raffles/${rid}/winner`, { ticket_number: this.winnerTicketId })
        Swal.fire({ icon:'success', title:'Listo', text:'Ganador asignado.', timer:1500, showConfirmButton:false, customClass:{container:'swal-high-z'} })
      } catch (error) {
        console.error(error)
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: error.response?.data?.message || 'No se pudo asignar el ganador.',
          customClass: { container: 'swal-high-z' },
        })
        return
      }

      await this.fetchRaffles(this.pagination.current_page)
      setTimeout(() => {
        this.selectedRaffle = this.rafflesList.find(x => x.id === rid) || this.selectedRaffle
      }, 200);

    },

  },
}
</script>

<style scoped>
</style>
