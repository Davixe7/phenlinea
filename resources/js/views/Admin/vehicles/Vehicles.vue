<template>
  <div class="row">
    <div class="col-lg-12">
      <extensions-nav :extension="extension" :page="'vehicles'"/>
    </div>

    <div class="col-lg-4">
      <create-vehicle
        :vehicle="vehicle"
        :extension="extension"
        @dataSent="saveVehicle"
      />
    </div>
    <div class="col-lg-8">
      <div class="form-section">
        <h4>
          <i class="material-symbols-outlined">directions_car</i>
          Vehículos del apartamento
        </h4>
        <table class="table">
          <thead>
            <th>
              Tipo
            </th>
            <th>
              Placa
            </th>
            <th>
              Tag
            </th>
            <th>
              Acciones
            </th>
          </thead>
          <tbody>
            <tr v-for="v in vehicles">
              <td>
                <i class="material-symbols-outlined me-2">
                  {{ v.type == 'car' ? 'directions_car' : 'two_wheeler' }}  
                </i>
                {{ v.type == 'car' ? 'Carro' : 'Moto' }}
              </td>
              <td>
                <div class="badge bg-warning text-dark">
                  {{ v.plate }}
                </div>
              </td>
              <td>{{ v?.tag }}</td>
              <td>
                <button class="btn btn-link btn-link-primary me-3" @click="vehicle = v">
                  Editar
                </button>
                <button class="btn btn-link btn-link-primary" @click="deleteVehicle(vehicles.indexOf(v))">
                  Eliminar
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import Vue from 'vue'
import { onMounted, ref } from 'vue'

import ExtensionsNav from './../ExtensionsNav.vue'
import CreateVehicle from './CreateVehicle.vue';
import { createToastInterface } from "vue-toastification";

var toast = null;
const props = defineProps(['vehicles', 'extension'])

const vehicles = ref([])
const loading = ref(false)
const vehicle = ref({ plate: '', tag: '', type: 'car' })

function saveVehicle(vehicle) {
  vehicle.id ? updateVehicle(vehicle) : storeVehicle(vehicle)
}

function storeVehicle(newVehicle) {
  console.log(newVehicle)
  axios.post(`/extensions/${props.extension.id}/vehicles`, { ...newVehicle, extension_id: props.extension.id })
    .then(response => {
      vehicles.value.unshift(response.data)
      vehicle.value = { plate: '', tag: '', type: 'car' }
      toast.success('Registrado con éxito')
    })
    .catch(error => console.log(error.response))
    .finally(() => loading.value = false)
}

function updateVehicle(newVehicle) {
  axios.post(`/extensions/${props.extension.id}/vehicles/${newVehicle.id}`, { ...newVehicle, extension_id: props.extension.id, _method: 'PUT' })
    .then(response => {
      vehicles.value.splice(vehicles.value.indexOf(vehicle.value), 1, response.data)
      vehicle.value = { plate: '', tag: '', type: 'car' }
      toast.success('Actualizado con éxito')
    })
    .catch(error => console.log(error.response))
    .finally(() => loading.value = false)
}

function deleteVehicle(index) {
  if (!window.confirm('Confirma la eliminación del vehículo?')) return
  axios.post(`/vehicles/${vehicles.value[index].id}`, { _method: 'DELETE' })
    .then(response => { vehicles.value.splice(index, 1); toast.success('Eliminado con éxito') })
    .catch(error => console.log(error.response))
    .finally(() => loading.value = false)
}

onMounted(() => {
  toast = createToastInterface({ eventBus: new Vue() })
  if (!props.vehicles || !props.vehicles.length) return
  vehicles.value = [...props.vehicles]
})
</script>