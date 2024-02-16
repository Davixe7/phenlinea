<template>
  <div>
    <payment-uploader
      @paymentsUpdated="data => batch.resident_invoices = data"
      :batch="batch">
    </payment-uploader>

    <div class="table-responsive">
      <div class="d-flex aling-items-center">
        <div class="col-lg-4">
          <h1>
            Facturas del lote {{ batch.id }}
          </h1>
        </div>
        <div class="col-lg-8 px-3 d-flex justify-content-end align-items-center">
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
          <input
            type="search"
            class="form-control"
            v-model="search"
            placeholder="Buscar por apartamento..."
            style="font-size: 1rem; height: 2.5rem; width: 320px;">
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
                  v-if="payments = invoice.resident_invoice_payments.length ? invoice.resident_invoice_payments : false"
                  :href="`/pago/${payments[payments.length-1].id}`"
                  target="_blank"
                  class="btn btn-sm btn-link">
                  <i class="material-symbols-outlined receipt_long">receipt_long</i>
                </a>

                <a
                  :href="`/descargar-factura/${invoice.id}`"
                  class="btn btn-sm btn-link">
                  <i class="material-symbols-outlined receipt">receipt</i>
                </a>

                <a
                  :href="`/extensions/${invoice.extension_id}/balance`"
                  target="_blank"
                  class="btn btn-sm btn-link">
                  Edo. Cta
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
import { computed, onMounted, ref } from 'vue';
import PaymentUploader from './PaymentUploader.vue';

const props   = defineProps(['batch'])
const search  = ref('')
const results = computed(() => {
  if (search.value == '') { return [...props.batch.resident_invoices] }
  return props.batch.resident_invoices
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
}
.btn-group > * {
  display: flex;
  align-items: center;
  line-height: 1;
}
</style>