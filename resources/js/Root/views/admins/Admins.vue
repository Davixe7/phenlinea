<template>
  <div id="admins">

    <div class="table-responsive">
      <div class="d-flex align-items-center">
        <h1 style="flex: 1;">Administradores</h1>
        <div class="d-flex py-3 px-3" style="flex: 1;">
          <SearchForm
            v-if="admins && admins.length"
            v-model="results"
            :collection="rows"
            :attribute="'name'">
          </SearchForm>
        </div>
      </div>
      <table class="table" v-if="results && results.length">
        <thead>
          <th>Nombre</th>
          <th>NIT</th>
          <th>Celular</th>
          <th>Celular 2</th>
          <th>Correo</th>
          <th>Status</th>
          <th>WA-ID</th>
          <th class="text-right">
            Opciones
          </th>
        </thead>
        <tbody>
          <tr v-for="admin in results">
            <td>{{ admin.name }}</td>
            <td>{{ admin.nit }}</td>
            <td>{{ admin.phone }}</td>
            <td>{{ admin.phone_2 }}</td>
            <td>{{ admin.email }}</td>
            <td>{{ admin.status }}</td>
            <td>
              <div class="d-flex">
                <input class="form-control form-control-sm" v-model="admin.whatsapp_group_id">
                <button class="btn btn-primary btn-sm" @click="updateWhatsappGroupId(admin)">
                  {{ loading ? 'cargando' : 'guardar' }}
                </button>
              </div>
            </td>

            <td class="text-right">
              <div class="btn-group">
                <a :href="`/admin/admins/${admin.id}/export`" class="btn btn-xs btn-link">
                  e
                </a>
                <a :href="`/admin/admins/${admin.id}/edit-permissions`" class="btn btn-xs btn-link">
                  <i class="material-symbols-outlined">lock</i>
                </a>
                <button class="btn btn-xs btn-link" @click="editAdmin(admin)"><i
                    class="material-symbols-outlined">edit</i></button>
                <button class="btn btn-xs btn-link" @click="deleteAdmin(admin.id)"><i
                    class="material-symbols-outlined">delete</i></button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-if="!results || !results.length" class="alert alert-info">
      No hay administradores para mostrar
    </div>

    <div class="fab-container">
      <button
        color="primary"
        data-toggle="modal"
        data-target="#adminEditModal"
        @click="() => { editing = false; adminToEdit = {}; }">
        <i class="material-symbols-outlined">add</i>
      </button>
    </div>

    <!-- Modal -->
    <div ref="AdminsModal" class="modal fade" id="adminEditModal" tabindex="-1" role="dialog"
      aria-labelledby="adminEditModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="adminEditModalLabel">
              <span v-if="!editing">Crear</span>
              <span v-else>Actualizar</span>
              administrador
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <CreateAdmin
              :admin="adminToEdit"
              :editing="editing"
              @adminStored="appendAdmin"
              @adminUpdated="updateAdmin">
          </CreateAdmin>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div ref="PaymentsModal" class="modal fade" id="paymentsModal" tabindex="-1" role="dialog"
      aria-labelledby="paymentsModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 id="paymentsModalLabel">Historial de Pagos</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <Payments v-if="paymentToEdit" :admin="paymentToEdit">
            </Payments>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import CreateAdmin from './CreateAdmin.vue'
import Payments from './Payments.vue'
import SearchForm from './../../../components/SearchForm.vue'
import { ref, onMounted } from 'vue'

const props = defineProps(['admins'])

const adminToEdit = ref({})
const paymentToEdit = ref(null)
const editing = ref(false)
const results = ref([])
const errors = ref({})
const rows   = ref([])
const loading = ref(false)

const AdminsModal   = ref(null)
const PaymentsModal = ref(null)

function appendAdmin(admin) {
  rows.value.push(admin)
  results.value = [...rows.value]
  $(AdminsModal.value).modal('hide')
}

function editAdmin(admin) {
  adminToEdit.value = admin
  editing.value = true
  $(AdminsModal.value).modal('show');
}

function editPayment(admin) {
  paymentToEdit.value = admin
  editing.value = true
  $(PaymentsModal.value).modal('show');
}

function updateAdmin(admin) {
  rows.value.splice( rows.value.indexOf( adminToEdit ), 1, admin )
  $(AdminsModal.value).modal('hide')
}

function deleteAdmin(id) {
  if (!window.confirm('¿Seguro que quieres eliminar al administrador?')) return
  axios.delete(`/admin/admins/${id}`)
  .then(()     => {
    props.admins = props.admins.filter(admin => admin.id != id)
  })
  .catch(error => errors.value = error.response.data.errors)
}

function updateWhatsappGroupId(admin){
  axios.post(`/admin/admins/${admin.id}`, {...admin, _method: 'PUT'})
  .then(response => {
    console.log( 'Admin whatsapp group id updated' );
  })
  .catch(error=>{
    console.log(error.response)
  })
}

onMounted(() => {
  rows.value    = [...props.admins]
  results.value = [...props.admins]
})
</script>

<style>
.modal-title {
  font-size: 1.1em;
}
</style>
