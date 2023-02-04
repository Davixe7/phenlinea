<template>
  <div id="porterias">
    <div class="row">
      <div class="col-md-9">
        <div class="table-responsive">
          <div class="d-flex align-items-center">
            <h1>Porterias</h1>
            <SearchForm
              v-show="porterias && porterias.length"
              :collection="porterias"
              :attribute="'name'"
              v-model="results"
              class="ml-auto mr-1">
            </SearchForm>
          </div>
          <table class="table" v-if="results && results.length">
            <thead>
              <th>Admin</th>
              <th>Nombre</th>
              <th>Correo</th>
              <th>No. de extensiones</th>
              <th class="text-right">Opciones</th>
            </thead>
            <tbody>
              <tr v-for="porteria in results">
                <td>{{ porteria.admin ? porteria.admin.name : '' }}</td>
                <td>{{ porteria.name }}</td>
                <td>{{ porteria.email }}</td>
                <td>{{ porteria.extensions_count }}</td>
                <td class="text-right">
                  <div class="btn-group">
                    <a href="#" class="btn btn-sm btn-secondary" @click="editPorteria(porteria)">
                      <i class="material-icons">edit</i></a>
                    <a href="#" class="btn btn-sm btn-secondary" @click="deletePorteria(porteria.id)">
                      <i class="material-icons">delete</i></a>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <div v-else class="alert alert-info">No hay porterias para mostrar</div>
        </div>
      </div>
    </div>

    <div class="fab-container">
      <button
        @click="editing = false; porteriaToEdit = {}"
        type="button"
        class="btn btn-primary btn-circle"
        data-toggle="modal"
        data-target="#exampleModal">
        <i class="material-icons">add</i>
      </button>
    </div>

    <!-- Modal -->
    <div ref="PorteriasModal" class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
      aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              <span v-if="!editing">Crear</span>
              <span v-else>Actualizar</span>
              porteria
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <CreatePorteria
              :admins="admins"
              :porteria="porteriaToEdit"
              :editing="editing"
              @porteriaStored="appendPorteria"
              @porteriaUpdated="updatePorteria">
            </CreatePorteria>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import CreatePorteria from './CreatePorteria.vue'
import SearchForm from './../../../components/SearchForm.vue'

import { ref, onMounted } from 'vue'

const props = defineProps(['rows', 'admins'])

const porteriaToEdit = ref({})
const editing = ref(false)
const results = ref([])
const errors = ref({})
const porterias = ref([])
const PorteriasModal = ref(null)

function editPorteria(porteria) {
  porteriaToEdit.value = porteria
  editing.value = true
  $(PorteriasModal.value).modal('show');
}

function appendPorteria(porteria) {
  porterias.value.push(porteria)
  $(PorteriasModal.value).modal('hide')
  //$toasted.success('Porteria registrada exitosamente', { position: 'bottom-left' })
}

function updatePorteria(porteria) {
  porterias.value = porterias.value.splice(porterias.value.indexOf(porteriaToEdit), 1, porteria)
  $(PorteriasModal.value).modal('hide')
  // $toasted.success('Porteria actualizada exitosamente', { position: 'bottom-left' })
}

function deletePorteria(id) {
  if (!window.confirm('¿Seguro que quieres eliminar la porteria?')) return
  axios.delete(`/admin/porterias/${id}`)
  .then(response => porterias.value = porterias.value.filter(porteria => porteria.id != id))
  .catch(error   => errors.value = error.response.data.errors)
}

onMounted(() => porterias.value = [...props.rows]);
</script>
