<template>
  <div id="create-admin" v-if="admin">
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
      <label for="picture">
        Foto de Perfil
        <span v-if="admin && admin.picture">
          <a :href="'/' + admin.picture" target="_blank">
            Ver actual
          </a>
        </span>
      </label>
      <input type="file" class="form-control" name="picture" ref="pictureInput" @change="loadPicture">
    </div>

    <div class="form-group text-right">
      <button v-if="!editing" class="btn btn-primary" @click="storeAdmin">Enviar</button>
      <button v-else class="btn btn-primary" @click="updateAdmin">Actualizar</button>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps(['admin', 'editing'])
const errors = ref({})
const new_picture = ref(null)
const pictureInput = ref(null)

const emits = defineEmits(['adminStored', 'adminUpdated'])

watch(props.admin, (newAdmin, oldAdmin) => {
  if (!newAdmin.id) clearForm()
})

function loadPicture() {
  new_picture.value = pictureInput.value.files[0];
}

function storeAdmin() {
  axios.post('/admin/admins', loadData())
  .then(response => emits('adminStored', response.data.data))
  .catch(error => errors.value = error.response.data.errors)
}

function updateAdmin() {
  let data = loadData();
  data.append('_method', 'PUT');
  axios.post('/admin/admins/' + props.admin.id, data)
  .then(response => emits('adminUpdated', response.data.data))
  .catch(error   => console.log(error))
}

function loadData() {
  let data = new FormData();
  Object.keys(props.admin).forEach(key => data.append(key, props.admin[key]))
  data.append('picture', new_picture.value)
  return data;
}

function clearForm() {
  errors.value = []
  pictureInput.value = ''
}
</script>

