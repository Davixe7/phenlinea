<template>
  <div id="users">

    <div class="table-responsive">
      <div class="d-flex align-items-center">
        <h1 style="flex: 1;">Super Usuarios</h1>
        <div class="d-flex py-3 px-3" style="flex: 1;">
          <SearchForm v-if="users && users.length" v-model="results" :collection="rows" :attribute="'name'">
          </SearchForm>
        </div>
      </div>
      <table class="table" v-if="results && results.length">
        <thead>
          <th>Nombre</th>
          <th>Email</th>
          <th class="text-right">
            Opciones
          </th>
        </thead>
        <tbody>
          <tr v-for="user in results">
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td class="text-right">
              <button class="btn btn-xs btn-link" @click="editUser(user)">
                <i class="material-icons">edit</i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="!results || !results.length" class="alert alert-info">
      No hay usuarios para mostrar
    </div>

    <div class="fab-container">
      <button color="primary" data-toggle="modal" data-target="#userEditModal"
        @click="() => { editing = false; userToEdit = {}; }">
        <i class="material-icons">add</i>
      </button>
    </div>

    <!-- Modal -->
    <div ref="UsersModal" class="modal fade" id="userEditModal" tabindex="-1" role="dialog"
      aria-labelledby="userEditModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="userEditModalLabel">
              <span v-if="!editing">Crear</span>
              <span v-else>Actualizar</span>
              Usuario
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <CreateUser :user="userToEdit" :editing="editing" @userStored="appendUser" @userUpdated="updateUser">
            </CreateUser>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import CreateUser from './CreateUser.vue'
import SearchForm from './../../../components/SearchForm.vue'
import { ref, onMounted } from 'vue'

const props = defineProps(['users'])

const userToEdit = ref({})
const editing = ref(false)
const results = ref([])
const errors  = ref({})
const rows    = ref([])

const UsersModal = ref(null)

function appendUser(user) {
  rows.value.push(user)
  $(UsersModal.value).modal('hide')
  toasted.success('Usuario registrado exitosamente')
}

function editUser(user) {
  userToEdit.value = user
  editing.value = true
  $(UsersModal.value).modal('show');
}

function updateUser(user) {
  rows.value.splice(rows.value.indexOf( userToEdit.value ), 1, user)
  $(UsersModal.value).modal('hide')
}

function deleteUser(id) {
  if (!window.confirm('¿Seguro que quieres eliminar al usuario?')) return
  axios.delete(`/admin/users/${id}`)
    .then(() => users.value = users.filter(user => user.id != id))
    .catch(error => {
      console.log(error)
      if( error.response.status == '403'){ alert('No tiene permisos para ejecutar esta acción') }
    })
}

onMounted(() => rows.value = [...props.users])
</script>

<style>
.modal-title {
  font-size: 1.1em;
}
</style>
