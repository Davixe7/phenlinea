<template>
  <div class="row">
    <div class="col-md-6 mt-3 mx-auto">
      <div class="d-flex align-items-center p-3 mb-3" style="background: #D8EBC4; border-radius: 5px;">
        <img src="/img/excel.png" style="width: 30px; height: 30px;" class="me-4">
        <p class="mb-0">
          <span style="font-weight: 500;">Descargue el formato requerido</span><br>
          Cargue los datos al archivo XLSX
        </p>
        <a
          style="font-family: 'Roboto', sans-serif;"
          class="btn btn-outline-success btn-download ms-auto"
          href="/storage/formato-facturacion-residentes.xls"
          target="_blank">
          Descargar
        </a>
      </div>

      <ul class="list-group px-0">
        <template v-if="!success">
        <li class="list-group-item" style="padding-bottom: 1rem; height: 360px;">
            <h6>
              Detalles de la factura
            </h6>
            <form ref="invoiceForm" @submit.prevent="importInvoices" method="POST" enctype="multipart/form-data">
              <div class="d-flex align-items-center justify-content-between mb-3">
                <label for="#" class="mb-2">Periodo</label>
                <input type="date" v-model="invoice.periodo" class="form-control" required :disabled="batch && batch.id">
              </div>
              <div class="d-flex align-items-center justify-content-between mb-3">
                <label for="#" class="mb-2">Emisión</label>
                <input type="date" v-model="invoice.emision" class="form-control" value="" required :disabled="batch && batch.id">
              </div>
              <div class="d-flex align-items-center justify-content-between mb-3">
                <label for="#" class="mb-2">Pagar antes de</label>
                <input type="date" v-model="invoice.limite" class="form-control" required :disabled="batch && batch.id">
              </div>

              <div class="d-flex align-items-center justify-content-between mb-3">
                <label for="">URL de Pago Online</label>
                <input type="url" class="form-control" v-model="invoice.link" placeholder="https://enlacebancario.com" :disabled="batch && batch.id">
              </div>
              
              <div class="form-group">
                <label for="" class="form-label">Notas</label>
                <textarea rows="3" class="form-control" v-model="invoice.note" placeholder="Escribe alguna nota para las facturas" :disabled="batch && batch.id"></textarea>
              </div>
              
              <div class="d-flex align-items-center pt-3" style="border-top: 1px solid rgba(0,0,0,.087);">
                <input
                  v-if="!progress"
                  required
                  ref="fileInput"
                  type="file"
                  class="form-control me-3"
                />
                <div v-else class="progress me-3" style="flex: 1 0 auto;" role="progressbar" aria-label="Animated striped example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                  <div class="progress-bar progress-bar-striped progress-bar-animated" :style="{width: progress + '%'}"></div>
                </div>

                <button type="submit" class="btn btn-download bg-primary text-white" style="white-space: nowrap; width: 170px;">
                  <template v-if="uploading">
                    <div class="spinner-border spinner-border-sm me-2" role="status">
                      <span class="visually-hidden">Loading...</span>
                    </div>
                    Cargando...
                  </template>
                  <span v-else>
                    Importar facturas
                  </span>
                </button>
              </div>
            </form>
            </li>
          </template>

          <template v-else>
            <li class="list-group-item d-flex flex-column justify-content-center align-items-center" style="padding-bottom: 1rem; height: 360px;">
              <i class="material-symbols-outlined mb-3 text-success" style="font-size: 9rem;">task_alt</i>
              <div class="mb-3">{{importedInvoicesCount}} Facturas importadas con éxito</div>
              <a href="/resident-invoice-batches" class="btn btn-primary btn-download">Ver facturas</a>
            </li>
          </template>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';

const props       = defineProps(['batch'])

const invoiceForm = ref(null)
const fileInput   = ref(null)
const invoice     = ref({})

const uploading   = ref(false)
const success     = ref(false)
const progress    = ref(0)
const importedInvoicesCount = ref(0)

function loadData(){
  uploading.value = true
  let data = new FormData();
  data.append('file', fileInput.value.files[0])
  Object.keys( invoice.value ).forEach(key => data.append(key, invoice.value[key]))
  if( props.batch?.id ){
    data.append('_method', 'PUT')
  }
  return data
}

function importInvoices(){
  if( !invoiceForm.value.reportValidity() ) return
  let url = props.batch?.id
            ? `/resident-invoice-batches/${props.batch.id}`
            : '/resident-invoice-batches/import'

  axios.post(url, loadData(), {
    onUploadProgress: function (progressEvent) {
      console.log(progressEvent.loaded)
      progress.value = Math.round((progressEvent.loaded / progressEvent.total) * 100);
    }
  })
  .then(response => {
    success.value = true
    importedInvoicesCount.value = response.data.count
  })
  .catch(error=> data.log(error.response))
  .finally(() => uploading.value = false)
}

onMounted(()=>{
  if( props.batch?.id ){
    invoice.value = {...props.batch}
  }
})
</script>

<style scoped>
label {
  font-size: .9em;
  font-weight: 500;
}
h6 {
  padding: 20px;
  margin: -12px -20px 20px -20px;
  border-bottom: 1px solid lightgray;
}
.list-group-item {
  padding-bottom: 30px;
}
.btn-download {
  font-size: 13px;
  font-family: 'Roboto', sans-serif;
  padding: 0.5rem 1.5rem;
}
input.form-control:not([type=file]) {
  font-family: 'Roboto', sans-serif;
  font-size: 15px;
  width: 170px;
}
</style>