<template>
  <div class="alert alert-info d-flex align-items-center">
    <input type="file" @change="updateFile" ref="fileInput" v-show="false">

    <template v-if="success">
      <span>
        Pagos actualizados con éxito
      </span>
      <i class="material-symbols-outlined ms-auto">done_all</i>
    </template>

    <template v-else-if="!file">
      <span>
        Cargue un archivo excel y actualice la información de los pagos
      </span>
      <button class="btn btn-primary ms-auto" @click="fileInput.click()">
        Cargar archivo
      </button>
    </template>

    <template v-else-if="file && !loading">
      <span>
        {{ file.name }} - {{ String(file.size / 1000).substring(0, 4) }}KB
      </span>
      <button class="btn btn-link ms-auto me-2" @click="resetState">
        Cancelar
      </button>
      <button class="btn btn-primary" @click="upload">
        Enviar
      </button>
    </template>

    <template v-else-if="loading">
      <span>
        {{ file.name }} - {{ String(file.size / 1000).substring(0, 4) }}KB
      </span>
      <div class="progress mx-3" style="flex: 1 0 auto;" role="progressbar">
        <div class="progress-bar progress-bar-striped progress-bar-animated" :style="{ width: progress + '%' }"></div>
      </div>
    </template>

  </div>
</template>

<script setup>
import { ref } from 'vue';
const props = defineProps(['residentInvoiceBatch'])

const fileInput = ref(null)
const file      = ref(null)
const loading   = ref(false)
const progress  = ref(0)
const success   = ref(false)

function updateFile(){
  file.value = fileInput.value.files[0]
}
function resetState(){
  fileInput.value.value = ""
  file.value = null
  success.value = false
}
function upload(){
  loading.value = true
  let data = new FormData()
  data.append('_method', 'PUT')
  data.append('file', file.value)

  axios.post(`/resident-invoice-batches/${props.residentInvoiceBatch.id}`, data, {
    onUploadProgress: function (progressEvent) {
      console.log(progressEvent.loaded)
      progress.value = Math.round((progressEvent.loaded / progressEvent.total) * 100);
    }
  })
  .then(response => {
    loading.value = false
    success.value = true
    setTimeout(()=>resetState(), 2000)
  })
  .finally(()=>loading.value = false)
}
</script>