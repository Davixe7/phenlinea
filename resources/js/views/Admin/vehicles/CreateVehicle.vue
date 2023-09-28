<template>
  <div class="form-section">
    <h4>
      <i class="material-symbols-outlined">directions_car</i>
      Registrar vehículo
    </h4>
    <form @submit.prevent="sendData">
        <div class="form-group">
          <label for="type">Tipo</label>
          <select class="form-control" v-model="vehicle.type">
            <option value="car">Carro</option>
            <option value="bike">Moto</option>
          </select>
        </div>

        <div class="form-group">
          <label for="plate">Placa</label>
          <input type="text" class="form-control" v-model="vehicle.plate" required>
        </div>

        <div class="form-group">
          <label for="tag">TAG</label>
          <input type="text" class="form-control" v-model="vehicle.tag">
        </div>

        <div class="form-group">
          <label for="tag">Propietario</label>
          <select class="form-control" v-model="vehicle.resident_id">
            <option v-for="resident in extension.residents" :value="resident.id">
              {{ resident.name }}
            </option>
          </select>
        </div>

        <div class="form-group d-flex justify-content-end">
          <button type="submit" class="btn btn-primary">
            <template v-if="loading">Cargando...</template>
            <template v-else>
              {{ vehicle.id ? 'Actualizar' : 'Guardar' }}
            </template>
          </button>
        </div>
      </form>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';

const emit    = defineEmits(['dataSent'])
const props   = defineProps(['vehicle', 'errors', 'extension'])
const vehicle = ref({plate: '', tag:'', type: 'car'})
const loading = ref(false)

function sendData(){
  emit('dataSent', vehicle.value)
}

watch(()=>props.vehicle, (newValue, oldValue) => vehicle.value = {...newValue})

onMounted(()=>{
  if(!props.vehicle) return
  vehicle.value = {...props.vehicle}
})
</script>