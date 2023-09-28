<template>
  <div class="row">
    <div class="col-lg-12">
      <extensions-nav :extension="extension" :page="'residents'"/>
    </div>
    <div class="col-md-4">
      <ResidentsForm
        :loading="loading"
        :errors="errors"
        :resident="resident"
        :extension="extension"
        @reset="resident = { ...defaultResident }"
        @storeResident="storeResident" @updateResident="updateResident" />
    </div>

    <div class="col-md-8">
      <ResidentsTable :residents="localResidents" @residentSelection="setResident" @residentDeletion="deleteResident">
      </ResidentsTable>
    </div>
  </div>
</template>
  
<script setup>
import "vue-toastification/dist/index.css";
import Vue from 'vue'
import ResidentsForm from './ResidentsForm.vue'
import ResidentsTable from './ResidentsTable.vue'
import ExtensionsNav from './../ExtensionsNav.vue'

import { onMounted, ref, watch } from 'vue'
import { createToastInterface } from "vue-toastification";
const props = defineProps(['residents', 'extension'])
watch(() => props.residents, (newVal, oldVal) => localResidents.value = [...newVal])

var toast             = null;
const loading         = ref(false)
const errors          = ref({})
const localResidents  = ref([])
const resident        = ref({})
const defaultResident = ref({
  name: null,
  age: null,
  dni: '',
  is_resident: 1,
  is_owner: 0,
  is_authorized: 0,
  disability: 0,
  card: ''
})

function setResident(res) {
  resident.value = res
}

function deleteResident(res) {
  if (!window.confirm('¿Seguro que desea eliminar el registro?')) return
  axios.post(`residents/${res.id}`, { _method: 'DELETE' })
  .then(response => {
    localResidents.value = localResidents.value.filter(r => r.id != res.id)
    toast.success('Eliminado con éxito')
  })
  .catch(error => {
    if (error.response.status == '422') {
      errors.value = error.response.data.errors ? error.response.data.errors : {}
    }
  })
}

function updateResident(data) {
  loading.value = true
  data.append('extension_id', props.extension.id)
  axios.post(`/residents/${resident.value.id}`, data)
    .then(response => {
      localResidents.value.splice(localResidents.value.indexOf(resident.value), 1, response.data.data)
      resetResident()
      toast.success('Actualizado con éxito')
    })
    .catch(error => {
      console.log(error)
      if (error.response.status == '422') {
        errors.value = error.response.data.errors ? error.response.data.errors : {}
      }
    })
    .finally(()=>loading.value = false)
}

function storeResident(data) {
  data.append('extension_id', props.extension.id)
  axios.post('residents', data)
    .then(response => {
      localResidents.value.push(response.data.data)
      resetResident()
      toast.success('Registrado con éxito')
    })
    .catch(error => console.log(error))
}

onMounted(() => {
  localResidents.value = [...props.residents]
  resetResident()
  toast = createToastInterface({ eventBus: new Vue() })
})

function resetResident() {
  resident.value = { ...defaultResident.value }
}
</script>
  
<style lang="scss" scoped>
.form-check-label {
  color: #0075ff;
}
</style>

