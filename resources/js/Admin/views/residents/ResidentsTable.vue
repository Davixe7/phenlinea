<template>
  <div class="form-section mb-0" style="padding-bottom: 7px;">
    <h4>
      <i class="material-symbols-outlined">supervisor_account</i> Núcleo Familiar
    </h4>
    <div v-if="residents && residents.length" class="table-responsive">
      <table class="table">
        <thead>
          <!-- <th>Foto</th> -->
          <th>Foto</th>
          <th>ID</th>
          <th>Nombre</th>
          <th>Edad</th>
          <th>Documento</th>
          <th>Tarjeta</th>
          <th>P</th>
          <th>R</th>
          <th>A</th>
          <th>D</th>
          <th class="text-right">Opciones</th>
        </thead>
        <tbody>
          <tr v-for="resident in residents">
            <td>
              <a v-if="resident.picture" :href="resident.picture" target="_blank">
                <img :src="resident.picture" width="40px" height="40px" style="border-radius: 50%;">
              </a>
              <div v-else style="width: 40px; height: 40px; border-radius: 50%"></div>
            </td>
            <td>{{ resident.id }}</td>
            <td>{{ resident.name }}</td>
            <td>{{ resident.age }}</td>
            <td>{{ resident.dni }}</td>
            <td>{{ resident.card }}</td>
            <td>
              <i v-if="resident.is_owner" class="material-symbols-outlined">done</i>
            </td>
            <td>
              <i v-if="resident.is_resident" class="material-symbols-outlined">done</i>
            </td>
            <td>
              <i v-if="resident.is_authorized" class="material-symbols-outlined">done</i>
            </td>
            <td>
              <i v-if="resident.disability" class="material-symbols-outlined">done</i>
            </td>
            <td class="d-flex align-items-center justify-content-end">
              <a
                v-if="user.device_community_id"
                href="#"
                @click.prevent="$emit('residentAuth', resident)" class="me-2">
                <i class="material-symbols-outlined">key_vertical</i>
              </a>
              <a href="#" @click.prevent="$emit('residentEdit', resident)" class="me-2">
                <i class="material-symbols-outlined">edit</i>
              </a>
              <a href="#" @click.prevent="$emit('residentDeletion', resident)">
                <i class="material-symbols-outlined">close</i>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-else class="alert alert-info">
      <i class="material-symbols-outlined">error_outline</i>
      No hay registros de residente para mostrar
    </div>
  </div>
</template>
  
<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';

const props = defineProps(['residents'])
const user = ref({})

onMounted(()=>{
  axios.get('/user').then(response=>{
    user.value = response.data;
    console.log('La respuesta es:' + response.data)
  });
})
</script>

