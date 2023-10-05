<template>
<div class="table-responsive">
  <div class="d-flex">
    <div class="col-lg-6">
      <a href="/resident-invoice-batches">
        <h1>Lote {{ resident_invoice_batch.id }}</h1>
      </a>
    </div>
    <div class="col-lg-6 px-3 d-flex justify-content-end align-items-center">
      <input type="search" class="form-control" v-model="search" placeholder="Buscar por apartamento..." style="font-size: 1rem; height: 2rem; width: 320px;">
    </div>
  </div>
  <table class="table">
    <thead>
      <tr>
        <th>Apto.</th>
        <th>Concepto</th>
        <th>Vencido</th>
        <th>Actual</th>
        <th>Concepto</th>
        <th>Vencido</th>
        <th>Actual</th>
        <th>Concepto</th>
        <th>Vencido</th>
        <th>Actual</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="invoice in results">
        <td>{{ invoice.apto }}</td>

        <td>{{ invoice.concepto1 }}</td>
        <td>{{ invoice.vencido1 }}</td>
        <td>{{ invoice.actual1 }}</td>

        <td>{{ invoice.concepto2 }}</td>
        <td>{{ invoice.vencido2 }}</td>
        <td>{{ invoice.actual2 }}</td>

        <td>{{ invoice.concepto3 }}</td>
        <td>{{ invoice.vencido3 }}</td>
        <td>{{ invoice.actual3 }}</td>

        <td>
          <div class="btn-group">
            <a :href="`/descargar-factura/${invoice.id}`" class="btn btn-link">
              <i class="material-symbols-outlined">download</i>
            </a>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';

const props   = defineProps(['resident_invoice_batch'])
const search  = ref('')
const results = computed(()=>{
  if( search.value == '' ){ return [...props.resident_invoice_batch.resident_invoices] }
  return props.resident_invoice_batch.resident_invoices
         .filter(i => i.apto.toLowerCase().includes( search.value.toLowerCase() ))
})
onMounted(()=>{
  // results.value = [...props.resident_invoice_batch.resident_invoices]
})
</script>

<style>
th, td {
  white-space: nowrap;
}
th {
  border-bottom: 1px solid rgba(0,0,0,.097) !important;
}
</style>