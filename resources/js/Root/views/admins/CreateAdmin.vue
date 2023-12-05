<template>
  <div id="create-admin" v-if="admin">
    <div class="form-group">
      <label for="name">ID de la comunidad</label>
      <input type="tel" class="form-control" id="device_community_id" v-model="admin.device_community_id">
    </div>
    <div class="form-group">
      <label for="device_building_id">ID de Edificio</label>
      <input type="tel" class="form-control" id="device_building_id" v-model="admin.device_building_id">
    </div>
    <div class="form-group">
        <label for="device_serial_number">Serial Dispositivo</label>
        <input type="tel" class="form-control" id="device_serial_number" v-model="admin.device_serial_number">
      </div>
    <div class="form-group">
      <label for="name">Plazo validez visitas</label>
      <select name="visits_lifespan" id="visits_lifespan" class="form-control" v-model="admin.visits_lifespan">
        <option value="24">24</option>
        <option value="48">48</option>
      </select>
    </div>
    <div class="form-group">
      <label for="name">ID Grupo Whatsapp</label>
      <input type="text" class="form-control" id="whatsapp_group_id" v-model="admin.whatsapp_group_id">
    </div>
    <div class="form-group">
      <label for="name">URL Grupo Whatsapp</label>
      <input type="url" class="form-control" id="whatsapp_group_url" v-model="admin.whatsapp_group_url">
    </div>
    <div class="form-group">
      <label for="name">QR Grupo Whatsapp</label>
      <input type="file" class="form-control" id="input_whatsapp_qr" ref="inputWhatsappQr">
    </div>
    <div class="form-group">
      <label for="name">Nombre</label>
      <input type="text" class="form-control" id="name" v-model="admin.name">
    </div>
    <div class="row">
      <div class="form-group col">
        <label for="nit">NIT</label>
        <input type="text" class="form-control" :class="{ 'is-invalid': errors.nit }" id="nit" v-model="admin.nit">
        <div v-if="errors.nit" class="invalid-feedback">{{ errors.nit[0] }}</div>
      </div>
      <div class="form-group col">
        <label for="email">Email</label>
        <input type="email" class="form-control" :class="{ 'is-invalid': errors.contact_email }" id="contact_email"
          v-model="admin.contact_email">
        <div v-if="errors.contact_email" class="invalid-feedback">{{ errors.contact_email[0] }}</div>
      </div>
    </div>
    <div class="form-group">
      <label for="address">Dirección</label>
      <input type="text" class="form-control" id="address" v-model="admin.address">
    </div>

    <div class="row">
      <div class="form-group col">
        <label for="phone">Celular</label>
        <input type="tel" class="form-control" :class="{ 'is-invalid': errors.phone }" id="phone" v-model="admin.phone">
        <div v-if="errors.phone" class="invalid-feedback">{{ errors.phone[0] }}</div>
      </div>

      <div class="form-group col">
        <label for="phone_2">Celular 2</label>
        <input type="tel" class="form-control" :class="{ 'is-invalid': errors.phone_2 }" id="phone_2"
          v-model="admin.phone_2">
        <div v-if="errors.phone_2" class="invalid-feedback">{{ errors.phone_2[0] }}</div>
      </div>
    </div>

    <div class="row">
      <div class="form-group col">
        <label for="email">Usuario</label>
        <input type="email" class="form-control" :class="{ 'is-invalid': errors.email }" id="email"
          v-model="admin.email">
        <div v-if="errors.email" class="invalid-feedback">{{ errors.email[0] }}</div>
      </div>

      <div class="form-group col">
        <label for="password">Contraseña</label>
        <input type="password" class="form-control" :class="{ 'is-invalid': errors.password }" id="password"
          v-model="admin.password">
        <div v-if="errors.password" class="invalid-feedback">{{ errors.password[0] }}</div>
      </div>
    </div>

    <div v-if="editing" class="form-group">
      <label for="status">Status</label>
      <select v-model="admin.status" id="status" class="form-control">
        <option :value.number="1">Solvente</option>
        <option :value.number="0">Pendiente</option>
      </select>
    </div>

    <div class="form-group">
      <label for="#">Logo</label>
      <input type="file" ref="pictureInput" class="form-control">
    </div>

    <div class="form-group text-right">
      <button v-if="!editing" class="btn btn-primary" @click="storeAdmin">Enviar</button>
      <button v-else class="btn btn-primary" @click="updateAdmin">Actualizar</button>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props        = defineProps(['admin', 'editing'])
const errors       = ref({})
const pictureInput = ref(null)

const emits = defineEmits(['adminStored', 'adminUpdated'])

watch(props.admin, (newAdmin, oldAdmin) => {
  if (!newAdmin.id) clearForm()
})

function storeAdmin() {
  axios.post('/admin/admins', loadData())
  .then(response => emits('adminStored', response.data.data))
  .catch(error   => {
      if(error.response.status = '422'){
          errors.value = error.response.data.errors
          return
      }
      alert('Error al actualizar')
  })
}

function updateAdmin() {
  let data = loadData();
  data.append('_method', 'PUT');
  axios.post('/admin/admins/' + props.admin.id, data)
  .then(response => emits('adminUpdated', response.data.data))
  .catch(error   => {
      if(error.response.status = '422'){
          errors.value = error.response.data.errors
          return
      }
      alert('Error al actualizar')
  })
}

function loadData() {
  let data = new FormData();

  let file = document.querySelector('#input_whatsapp_qr').files[0]
  if( file ){ data.append('whatsapp_qr', file)}

  let picture = pictureInput.value.files[0]
  if( picture ){ data.append('picture', picture)}

  Object.keys(props.admin).forEach(key => {
    let nullSafeValue = props.admin[key] == null ? '' : props.admin[key]
    data.append(key, nullSafeValue)
  })
  return data;
}

function clearForm() {
  errors.value = []
  document.querySelector('#input_whatsapp_qr').value = ''
}
</script>

