<template>
  <div id="create-user" v-if="user">
    <div class="form-group">
      <label for="name">Nombre</label>
      <input type="text" class="form-control" id="name" v-model="user.name">
    </div>

    <div class="row">
      <div class="form-group col">
        <label for="email">Usuario</label>
        <input type="email" class="form-control" :class="{ 'is-invalid': errors.email }" id="email"
          v-model="user.email">
        <div v-if="errors.email" class="invalid-feedback">{{ errors.email[0] }}</div>
      </div>

      <div class="form-group col">
        <label for="password">Contraseña</label>
        <input type="password" class="form-control" :class="{ 'is-invalid': errors.password }" id="password"
          v-model="user.password">
        <div v-if="errors.password" class="invalid-feedback">{{ errors.password[0] }}</div>
      </div>
    </div>

    <div class="form-group text-right">
      <button v-if="!editing" class="btn btn-primary" @click="storeUser">Enviar</button>
      <button v-else class="btn btn-primary" @click="updateUser">Actualizar</button>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps(['user', 'editing'])
const errors = ref({})

const emits = defineEmits(['userStored', 'userUpdated'])

watch(props.user, (newUser, oldUser) => {
  if (!newUser.id) clearForm()
})

function storeUser() {
  axios.post('/admin/users', loadData())
    .then(response => emits('userStored', response.data.data))
    .catch(error => errors.value = error.response.data.errors)
}

function updateUser() {
  let data = loadData();
  data.append('_method', 'PUT');
  axios.post('/admin/users/' + props.user.id, data)
    .then(response => emits('userUpdated', response.data.data))
    .catch(error => {
      if( error.response.status == '403' ) alert('No tiene permisos para realizar esta acción')
    })
}

function loadData() {
  let data = new FormData();
  Object.keys(props.user).forEach(key => data.append(key, props.user[key]))
  return data;
}

function clearForm() {
  errors.value = []
}
</script>

