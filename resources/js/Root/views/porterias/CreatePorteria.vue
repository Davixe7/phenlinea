<template>
  <div id="create-admin">
    <div class="form-group">
      <label for="admin_id">Administrador</label>
      <select name="admin_id" id="admin_id" class="form-control" v-model="porteria.admin_id" required>
        <option v-for="admin in admins" :value="admin.id">{{ String (admin.id).padStart(3, '0') + ' - ' +admin.name }}</option>
      </select>
    </div>

    <div class="form-group">
      <label for="name">Nombre</label>
      <input type="text" class="form-control" :class="{ 'is-invalid': errors.name }" id="name" v-model="porteria.name" required>
      <div v-if="errors.name" class="invalid-feedback">{{ errors.name[0] }}</div>
    </div>

    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" class="form-control" :class="{ 'is-invalid': errors.email }" id="email" v-model="porteria.email"
        required>
      <div v-if="errors.email" class="invalid-feedback">{{ errors.email[0] }}</div>
    </div>

    <div class="form-group">
      <label for="password">Contraseña</label>
      <input type="password" class="form-control" :class="{ 'is-invalid': errors.password }" id="password"
        v-model="porteria.password" required>
      <div v-if="errors.password" class="invalid-feedback">{{ errors.password[0] }}</div>
    </div>

    <div class="form-group text-right">
      <button v-if="!editing" class="btn btn-primary" @click="storePorteria">Enviar</button>
      <button v-else class="btn btn-primary" @click="updatePorteria">Actualizar</button>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps(['porteria', 'editing', 'admins'])
const emits = defineEmits(['porteriaStored', 'porteriaUpdated'])

const errors = ref([])

watch(props.porteria, (newPorteria, oldPorteria) => {
  if (!newPorteria) clearForm()
})

function storePorteria() {
  axios.post('/admin/porterias', {...props.porteria})
  .then(response => emits('porteriaStored', response.data.data))
  .catch(error => console.log(error))
}

function updatePorteria() {
  axios.post(`/admin/porterias/${props.porteria.id}`, {...props.porteria, '_method': 'PUT'})
  .then(response => emits('porteriaUpdated', response.data))
  .catch(error => console.log(error))
  // .catch(error   => errors.value = error.response.data.errors)
}

function clearForm() {
  errors.value = []
}
</script>
