<template>
  <div>
    <extensions-nav :extension="extension" :page="'invoices'"></extensions-nav>
    <div class="table-responsive">
      <div class="d-flex aling-items-center">
        <div class="col-lg-6">
          <h1>
            Facturas del apartamento
          </h1>
        </div>
      </div>

      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Apto.</th>
            <th>Total</th>
            <th>Cancelado</th>
            <th>Pendiente</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="invoice in results">
            <td>{{ String(invoice.id).padStart(4, '0') }}</td>
            <td>{{ invoice.apto }}</td>
            <td>{{ invoice.total }}</td>
            <td>{{ invoice.paid }}</td>
            <td>{{ invoice.pending }}</td>
            <td>
              <div class="btn-group">
                <a
                  :href="`/descargar-factura/${invoice.id}`"
                  class="btn btn-sm btn-link">
                  Factura
                </a>

                <a
                  :href="`/descargar-factura/${invoice.id}`"
                  class="btn btn-sm btn-link">
                  Recibo
                </a>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import ExtensionsNav from './../extensions/ExtensionsNav.vue'

const props   = defineProps(['extension', 'resident_invoices'])
const search  = ref('')
const results = computed(() => {
  if (search.value == '') { return [...props.resident_invoices] }
  return props.resident_invoices
    .filter(i => i.apto.toLowerCase().includes(search.value.toLowerCase()))
})
</script>

<style>
th, td {
  white-space: nowrap;
}

th {
  border-bottom: 1px solid rgba(0, 0, 0, .097) !important;
}</style>