<template>
  <div class="form-section">
    <h4 class="d-flex align-items-center">
      <i class="material-symbols-outlined me-3">key_vertical</i>
      Control de Acceso
    </h4>

    <div class="mb-2 ps-3">
      {{ resident.name }}
    </div>

    <div v-if="loading" class="alert alert-dark bg-dark text-white">
      <div class="spinner-border spinner-border-sm text-light me-3" role="status">
      </div>
      Cargando dispositivos y puntos de acceso
    </div>

    <table class="table" v-if="!loading && devices && devices.length">
      <thead>
        <th>Punto de acceso</th>
        <th>Autorizado</th>
      </thead>
      <tbody>
        <ResidentDevice
        v-for="device in devices"
        :resident="resident"
        :key="device.devSn"
        :sn="device.devSn"
        :name="device.devName"
        :auth="device.auth">
        </ResidentDevice>
      </tbody>
    </table>
  </div>
</template>
  
<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import ResidentDevice from './ResidentDevice.vue';

const props = defineProps(['resident'])

watch(() => props.resident, (newValue, oldValue) => {
  if( !newValue.id ) { return }
  resident.value = { ...newValue }
  fetchHouseholdDevices()
})

const loading     = ref(true)
const errors      = ref({})
const resident    = ref({})
const devices     = ref([])

function fetchHouseholdDevices(){
  loading.value = true
  axios.get(`/residents/${props.resident.id}/deviceslist`)
  .then(response=>{
    devices.value = response.data
  })
  .finally(()=>loading.value = false)
}

onMounted(()=>{
  if( resident.value.id ){
    fetchHouseholdDevices()
  }
})
</script>
  
<style lang="scss" scoped>
.form-check-label {
  color: #0075ff;
}
label {
  font-size: .9rem;
}
.form-group {
  margin-bottom: .5rem;
}
.form-group .form-control {
  border: 1px solid #000;
}
</style>

