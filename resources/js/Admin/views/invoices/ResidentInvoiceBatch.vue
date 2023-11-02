<template>
  <div>
    <payment-uploader :resident-invoice-batch="resident_invoice_batch">
    </payment-uploader>

    <div class="table-responsive">
      <div class="d-flex aling-items-center">
        <div class="col-lg-6">
          <h1>
            Facturas del lote {{ resident_invoice_batch.id }}
          </h1>
        </div>
        <div class="col-lg-6 px-3 d-flex justify-content-end align-items-center">
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
                  :href="`/descargar-factura/${invoice.id}`"
                  class="btn btn-sm btn-link">
                  Factura
                </a>

                <a
                  v-if="payments = invoice.resident_invoice_payments.length ? invoice.resident_invoice_payments : false"
                  :href="`/pago/${payments[payments.length-1].id}`"
                  target="_blank"
                  class="btn btn-sm btn-link">
                  Recibo
                </a>

                <a
                  :href="`/descargar-factura/${invoice.id}`"
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

const props = defineProps(['resident_invoice_batch'])
const search = ref('')
const results = computed(() => {
  if (search.value == '') { return [...props.resident_invoice_batch.resident_invoices] }
  return props.resident_invoice_batch.resident_invoices
    .filter(i => i.apto.toLowerCase().includes(search.value.toLowerCase()))
})


onMounted(() => {
  // results.value = [...props.resident_invoice_batch.resident_invoices]
})
</script>

<style>
th, td {
  white-space: nowrap;
}

th {
  border-bottom: 1px solid rgba(0, 0, 0, .097) !important;
}</style>