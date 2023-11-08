<template>
  <div>
    <extensions-nav :extension="extension" :page="'invoices'"></extensions-nav>
    <div class="table-responsive">
      <div class="d-flex aling-items-center">
        <div class="col-lg-4">
          <h1>
            Facturas del apartamento
          </h1>
        </div>
        <div class="col-lg-8 d-flex justify-content-end">
          <span class="d-flex align-items-center me-3">
            <i class="material-symbols-outlined receipt_long me-1">receipt_long</i>
            <span class="title">Recibo de caja</span>
          </span>
          <span class="d-flex align-items-center me-3">
            <i class="material-symbols-outlined receipt me-1">receipt</i>
            <span class="title">Factura</span>
          </span>
          <span class="d-flex align-items-center me-3">
            <img src="/img/pse.png" class="me-1">
            <span class="title">Pago PSE</span>
          </span>
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
                  target="_blank"
                  class="btn btn-sm btn-link">
                  <i class="material-symbols-outlined receipt_long">receipt_long</i>
                </a>

                <a
                  v-if="payments = invoice.resident_invoice_payments.length ? invoice.resident_invoice_payments : false"
                  :href="`/pago/${payments[payments.length-1].id}`"
                  class="btn btn-sm btn-link">
                  <i class="material-symbols-outlined receipt">receipt</i>
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

<style scoped>
i.material-symbols-outlined.receipt {
  color: #FF9B3F;
}
i.material-symbols-outlined.receipt_long {
  color: #1A61A3;
}
.title {
  font-size: 13px;
}
th, td {
  white-space: nowrap;
}

th {
  border-bottom: 1px solid rgba(0, 0, 0, .097) !important;
}</style>