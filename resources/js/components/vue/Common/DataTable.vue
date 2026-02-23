<template>
  <div class="datatable">
    <table class="table">
      <thead>
        <tr>
          <th v-for="column in columns" :key="column.field">{{ column.label }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="loading">
          <td :colspan="columns.length" class="text-center">
            Cargando datos...
          </td>
        </tr>
        
        <template v-if="!loading && data && data.length > 0">
          <tr v-for="(row, index) in data" :key="index">
            <td v-for="column in columns" :key="column.field">
              <template v-if="column.formatter">
                {{ column.formatter(row[column.field]) }}
              </template>
              <template v-else-if="column.field == 'actions'">
                <div class="table__actions">
                  <button class="button__primary button--small" @click="$emit('edit', row)">
                    <span class="material-symbols-outlined">edit</span>
                  </button>
                  <button class="button__danger button--small" @click="$emit('delete', row)">
                    <span class="material-symbols-outlined">delete</span>
                  </button>
                </div>
              </template>
              <template v-else>
                {{ row[column.field] }}
              </template>
            </td>
          </tr>
        </template>
        <tr v-else-if="!loading && (!data || data.length === 0)">
          <td :colspan="columns.length" class="text-center">
            No hay datos disponibles.
          </td>
        </tr>
      </tbody>
      <tfoot v-if="pagination?.last_page > 1">
        <tr>
          <td :colspan="columns.length">
            <div class="pagination" v-if="pagination?.last_page > 1">
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
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
</template>

<script>
export default { 
  props:{
    columns: {
      type: Array,
      required: true
    },
    data: {
      type: Array,
      required: true
    },
    pagination: {
      type: Object,
      required: false,
      default: null
    },
    loading: {
      type: Boolean,
      required: false,
      default: false
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
  },
  methods: {
    goToPage(page) {
      if (page >= 1 && page <= this.pagination.last_page) {
        this.$emit('page-change', page)
      }
    }
  }

}
</script>

<style>
</style>